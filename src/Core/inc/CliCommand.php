<?php

abstract class CliCommand {

    abstract public function getName(): string;

    public function getDescription(): string {
        return '';
    }

    protected function writeInfo(string $message): void {
        fwrite(STDOUT, $message . "\n");
        fflush(STDOUT);
    }

    protected function writeStatus(string $label, string $message, float $startedAt): void {
        $line = sprintf("\r[%s] %s %s", $this->formatElapsed($startedAt), $label, $this->shortenStatus($message));
        fwrite(STDOUT, $line);
        fflush(STDOUT);
    }

    protected function finishStatus(string $label, string $message, float $startedAt): void {
        $line = sprintf("\r[%s] %s %s", $this->formatElapsed($startedAt), $label, $message);
        fwrite(STDOUT, $line . "\n");
        fflush(STDOUT);
    }

    /**
     * @return array{stdout:string,stderr:string,exit_code:int|null,timed_out:bool,duration:float}
     */
    protected function runStreamingCommand(string $label, string $command, int $timeoutInSeconds): array {
        $startedAt = microtime(true);

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = @proc_open($command, $descriptors, $pipes);
        if(!is_resource($process)) {
            $this->finishStatus($label, 'failed to start subprocess', $startedAt);
            return [
                'stdout' => '',
                'stderr' => '',
                'exit_code' => 1,
                'timed_out' => false,
                'duration' => microtime(true) - $startedAt,
            ];
        }

        fclose($pipes[0]);
        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);

        $stdout = '';
        $stderr = '';
        $lastOutput = 'running';
        $timedOut = false;

        while (true) {
            $status = proc_get_status($process);
            $running = $status['running'];

            $read = [];
            if(!feof($pipes[1])) {
                $read[] = $pipes[1];
            }
            if(!feof($pipes[2])) {
                $read[] = $pipes[2];
            }

            if(count($read) > 0) {
                $write = null;
                $except = null;
                @stream_select($read, $write, $except, 0, 200000);

                foreach ($read as $stream) {
                    $chunk = stream_get_contents($stream);
                    if(!is_string($chunk) || $chunk === '') {
                        continue;
                    }

                    if($stream === $pipes[1]) {
                        $stdout .= $chunk;
                    } else {
                        $stderr .= $chunk;
                    }

                    $lastOutput = $this->extractLastOutput($chunk, $lastOutput);
                    $this->writeStatus($label, $lastOutput, $startedAt);
                }
            } else {
                $this->writeStatus($label, $lastOutput, $startedAt);
                usleep(200000);
            }

            if((microtime(true) - $startedAt) >= $timeoutInSeconds) {
                $timedOut = true;
                proc_terminate($process);
                $lastOutput = 'timed out';
                break;
            }

            if(!$running) {
                break;
            }
        }

        foreach ([1, 2] as $pipeIndex) {
            $tail = stream_get_contents($pipes[$pipeIndex]);
            if(is_string($tail) && $tail !== '') {
                if($pipeIndex === 1) {
                    $stdout .= $tail;
                } else {
                    $stderr .= $tail;
                }
                $lastOutput = $this->extractLastOutput($tail, $lastOutput);
            }
            fclose($pipes[$pipeIndex]);
        }

        $exitCode = proc_close($process);
        if(!is_int($exitCode) || $exitCode < 0) {
            $exitCode = $timedOut ? 124 : ($status['exitcode'] ?? 1);
        }

        $finalMessage = $timedOut
            ? 'timed out'
            : sprintf('finished with exit code %d; last output: %s', $exitCode, $lastOutput);
        $this->finishStatus($label, $finalMessage, $startedAt);

        return [
            'stdout' => $stdout,
            'stderr' => $stderr,
            'exit_code' => $exitCode,
            'timed_out' => $timedOut,
            'duration' => microtime(true) - $startedAt,
        ];
    }

    protected function formatElapsed(float $startedAt): string {
        return sprintf('%0.1fs', microtime(true) - $startedAt);
    }

    private function extractLastOutput(string $chunk, string $fallback): string {
        $normalized = trim(str_replace("\r", "\n", $chunk));
        if($normalized === '') {
            return $fallback;
        }

        $lines = preg_split('/\n+/', $normalized);
        if(!is_array($lines) || count($lines) === 0) {
            return $fallback;
        }

        return (string)$lines[count($lines) - 1];
    }

    private function shortenStatus(string $message): string {
        $message = trim($message);
        if(strlen($message) <= 140) {
            return $message;
        }

        return substr($message, 0, 137) . '...';
    }

    /**
     * @param string[] $args
     */
    abstract public function run(array $args): int;
}

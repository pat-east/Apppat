<?php

include_once('CliCommand.php');

class CliCommandManager {

    /** @var array<string, CliCommand> */
    private array $commands = [];

    public function init(): void {
        foreach (Helper::GetDerivingClasses('CliCommand') as $commandClass) {
            /** @var CliCommand $command */
            $command = new $commandClass();
            $this->commands[$command->getName()] = $command;
        }

        ksort($this->commands);
    }

    /**
     * @param string[] $argv
     */
    public function run(array $argv): int {
        $commandName = $argv[1] ?? '';
        if ($commandName === '' || in_array($commandName, ['help', '--help', '-h'])) {
            $this->printUsage();
            return $commandName === '' ? 1 : 0;
        }

        if (!isset($this->commands[$commandName])) {
            fwrite(STDERR, sprintf("Unknown CLI command [%s].\n", $commandName));
            $this->printUsage();
            return 1;
        }

        $args = array_slice($argv, 2);
        return $this->commands[$commandName]->run($args);
    }

    private function printUsage(): void {
        fwrite(STDOUT, "Usage: php cli.php <command> [args...]\n");
        fwrite(STDOUT, "Available commands:\n");

        foreach ($this->commands as $command) {
            fwrite(STDOUT, sprintf("  %-24s %s\n", $command->getName(), $command->getDescription()));
        }
    }
}

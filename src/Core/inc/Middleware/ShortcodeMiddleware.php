<?php

class ShortcodeMiddleware extends OutputMiddleware {

    const MaxShortcodeProcessingDepth = 10;

    public function run(Route $route, string $stdout = ''): OutputMiddlewareResult {

        $processed = $this->process($stdout);

        return new OutputMiddlewareResult(middlewareResultStatus::Success, $stdout, $processed);
    }

    private function process($stdout, $maxDepth = self::MaxShortcodeProcessingDepth) {
        $shortcodePattern = '~\[(?P<name>[a-zA-Z_][\w-]*)(?P<attrs>(?:\s+[a-zA-Z_][\w-]*=(?:"[^"]*"|\'[^\']*\'|[^\s\]]+))*)\s*\](?:(?P<content>.*?)\[/\k<name>\])?~su';
        $attrPattern = '~([a-zA-Z_][\w-]*)=(?:"([^"]*)"|\'([^\']*)\'|([^\s"\']+))~u';
        $processedStdout = $stdout;
        $depth = 0;
        do {
            $preProcessedStdout = $processedStdout;
            $processedStdout = preg_replace_callback($shortcodePattern, function($m) use ($attrPattern) {
                $attrs = [];
                if (!empty($m['attrs'])) {
                    if (preg_match_all($attrPattern, $m['attrs'], $am, PREG_SET_ORDER)) {
                        foreach ($am as $a) {
                            $attrs[$a[1]] = $a[2] !== '' ? $a[2] : ($a[3] !== '' ? $a[3] : $a[4]);
                        }
                    }
                }

                $name = $m['name'];
                $content = $m['content'] ?? '';

                $shortcode = ShortcodeFactory::Create($name, $attrs, $content);
                return $shortcode !== null ? $shortcode->process() : $m[0];
            }, $processedStdout);

            if($processedStdout == $preProcessedStdout) {
                break;
            }

        } while(++$depth < $maxDepth);
        return $processedStdout;
    }
}
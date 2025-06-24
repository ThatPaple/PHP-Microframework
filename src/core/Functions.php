<?php
namespace App\Core;

class Functions
{
    public static function puke(...$vars): void
    {
        // if no args passed, dump debug backtrace or info
        if (empty($vars)) {
            // Dump caller info + backtrace
            $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $caller = $bt[1] ?? null;

            $file = $caller['file'] ?? 'unknown file';
            $line = $caller['line'] ?? 'unknown line';

            echo "<pre style='background:#222;color:#eee;padding:15px;border-radius:5px;font-family: monospace;font-size:14px;'>";
            if ($vars) {
                echo "<strong style='color:#f88;'>PUKE called at {$file}:{$line} (no variables passed)</strong>\n\n";
            }

            echo "==== HTTP CONTEXT ====\n\n";

            echo "--- \$_SESSION ---\n";
            if (session_status() === PHP_SESSION_ACTIVE) {
                echo htmlspecialchars(print_r($_SESSION, true)) . "\n\n";
            } else {
                echo "Session not started.\n\n";
            }

            echo "--- \$_COOKIE ---\n";
            echo htmlspecialchars(print_r($_COOKIE, true)) . "\n\n";

            echo "--- \$_GET ---\n";
            echo htmlspecialchars(print_r($_GET, true)) . "\n\n";

            echo "--- \$_POST ---\n";
            echo htmlspecialchars(print_r($_POST, true)) . "\n\n";

            echo "--- \$_FILES ---\n";
            echo htmlspecialchars(print_r($_FILES, true)) . "\n\n";

            echo "--- \$_SERVER (selected keys) ---\n";
            $serverKeys = ['REQUEST_METHOD', 'REQUEST_URI', 'HTTP_USER_AGENT', 'REMOTE_ADDR', 'HTTP_HOST'];
            $serverSubset = [];
            foreach ($serverKeys as $key) {
                $serverSubset[$key] = $_SERVER[$key] ?? 'N/A';
            }
            echo htmlspecialchars(print_r($serverSubset, true)) . "\n\n";

            if (!empty($vars)) {
                foreach ($vars as $i => $var) {
                    echo "Variable #{$i}:\n";

                    if (is_callable($var)) {
                        try {
                            $result = $var();
                            echo "Callable executed, returned:\n";
                            echo htmlspecialchars(print_r($result, true)) . "\n\n";
                        } catch (\Throwable $e) {
                            echo "Callable threw exception:\n";
                            echo htmlspecialchars($e->getMessage()) . "\n\n";
                        }
                    } else {
                        echo htmlspecialchars(print_r($var, true)) . "\n\n";
                    }
                }
            }

            echo "Backtrace:\n";
            foreach ($bt as $i => $frame) {
                $file = $frame['file'] ?? '[internal]';
                $line = $frame['line'] ?? '';
                $func = $frame['function'] ?? '';
                echo "#{$i} {$file}({$line}): {$func}()\n";
            }
            echo '</pre>';
            return;
        }

        // Dump all passed variables
        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        $file = $bt['file'] ?? 'unknown file';
        $line = $bt['line'] ?? 'unknown line';

        echo "<pre style='background:#222;color:#eee;padding:15px;border-radius:5px;font-family: monospace;font-size:14px;'>";
        echo "<strong style='color:#f88;'>PUKE called at {$file}:{$line}</strong>\n\n";

        foreach ($vars as $i => $var) {
            echo "Variable #{$i}:\n";
            echo htmlspecialchars(print_r($var, true)) . "\n\n";
        }

        echo '== End dump ==</pre>';
    }
}

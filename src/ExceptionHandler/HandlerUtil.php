<?php

namespace DegenerateZan\Utils\ExceptionHandler;

abstract class HandlerUtil
{

    /**
     * Whether to disable colors or not, can be useful for shells that don't support color-coded output.
     *
     * @var bool
     */
    protected static $noColor = false;

    /**
     * Colors for formatting the output.
     *
     * @var array
     */
    protected static $colors = [
        "cyan" => "\033[1;36m",
        "red" => "\033[1;31m",
        "yellow" => "\033[1;33m",
        "reset" => "\033[0m",
        "bg_red" => "\033[1;41m",
    ];
    
    /**
     * Strips ANSI escape codes from a string.
     *
     * @param string $string The string to strip.
     * @return string The stripped string.
     */
    protected static function stripAnsi(string $string): string
    {
        return str_replace(array_values(self::$colors), '', $string);
    }

            /**
     * Retrieves the namespace based on the line of code.
     *
     * @param string $file The file path where the error occurred.
     * @param int    $line The line number where the error occurred.
     * @return string The namespace extracted from the error line, or an empty string if not found.
     */
    protected static function getNamespace(string $file, int $line): string
    {
        // Read the file contents
        $fileContents = file_get_contents($file);

        // Find the line that contains the error
        $lines = explode("\n", $fileContents);
        $errorLine = $lines[$line - 1];

        // Extract the namespace from the error line
        $namespace = '';

        // Check if the error line contains a namespace declaration
        if (preg_match('/namespace\s+(.*?);/', $errorLine, $matches)) {
            $namespace = $matches[1];
        }

        return $namespace;
    }


    /**
     * Retrieves the maximum length among an array of strings after stripping ANSI escape codes.
     *
     * @param array $output The array of strings.
     * @return int The maximum length.
     */
    protected static function getMaxLen(array $output): int
    {
        $len = 0;
        foreach ($output as $line) {
            $lineLen = strlen(self::stripAnsi($line));
            if ($lineLen > $len) {
                $len = $lineLen;
            }
        }
        return $len;
    }

    /**
     * Dumps the stack trace of an exception to a file.
     *
     * @param string $stackTrace    The exception instance.
     * @param string $fileLocation  The file location to dump the stack trace.
     * @return void
     */
    protected static function dumpStackTrace(string $stackTrace, string $fileLocation): void
    {
        file_put_contents($fileLocation, $stackTrace);
    }


}
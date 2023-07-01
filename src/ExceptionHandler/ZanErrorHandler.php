<?php

namespace DegenerateZan\Utils\ExceptionHandler;


/**
 * ErrorHandler class for handling PHP errors and displaying eye candy error messages.
 *
 *  if you want to use it don't forget to define the handler
 * ```
 * set_exception_handler([\DegenerateZan\Utils\ExceptionHandler\ZanErrorHandler, "handle"]);
 * ```
 * 
 */
class ZanErrorHandler extends HandlerUtil
{

    /**
     * Whether to disable colors or not, can be useful for shells that don't support color-coded output.
     *
     * @var bool
     */
    protected static $noColor = false;

    /**
     * Whether to fill the last borders or not.
     * Some people like it, some people don't.
     *
     * @var bool
     */
    protected static $fillBorders = false;



    /**
     * Handles the PHP error and displays the error message.
     *
     * @param int    $severity The severity level of the error.
     * @param string $message  The error message.
     * @param string $file     The file where the error occurred.
     * @param int    $line     The line number where the error occurred.
     * @return void
     */
    public static function handle($errno, $errstr, $errfile, $errline)
    {
        printf($errstr . PHP_EOL);
        $errorType = self::getSeverityName($errno);
        $borderAmount = 78;
        $indentationAmount = 2;

        $border = str_repeat('─', $borderAmount);
        $indentation = str_repeat(' ', $indentationAmount);
    
        $output = [];
        $output[] = '';
        $output[] = self::getColor("cyan") . '┌' . $border . '┐' . self::getColor("reset");
        $output[] = self::getColor("cyan") . '│' . $indentation . self::getColor("red") . 'ERROR' . self::getColor("reset") . '    ';
        $output[] = self::getColor("cyan") . '├' . $border . '┤' . self::getColor("reset");
        $output[] = self::getColor("cyan") . '│' . $indentation . self::getColor("yellow") . 'Type: ' . self::getColor("reset") . $errorType;
        $output[] = self::getColor("cyan") . '│' . $indentation . self::getColor("yellow") . 'Message: ' . self::getColor("reset") . $errstr;
        $output[] = self::getColor("cyan") . '│' . $indentation . self::getColor("yellow") . 'File: ' . self::getColor("reset") . $errfile;
        $output[] = self::getColor("cyan") . '│' . $indentation . self::getColor("yellow") . 'Line: ' . self::getColor("reset") . $errline;
        $output[] = self::getColor("cyan") . '└' . $border . '┘' . self::getColor("reset");
    
        if ((!empty($namespace))){
            // Retrieve the Namespace used within the Scope area of the code
            $output[] = PHP_EOL .self::getColor("reset") . $indentation . self::getColor("bg_red") . " " . $namespace . " " . self::getColor("reset") . PHP_EOL;
        }
    
        $output[] = self::getHighlightedCode($errfile, $errline);
    
        // Fill borders
        if (self::$fillBorders) {
            $maxlen = self::getMaxLen($output);

            $magicNumber = $borderAmount + $borderAmount + $indentationAmount; // don't ask me how did i pulled this out
            
            foreach ($output as $index => &$content) {
                $missingChars = $maxlen - strlen(self::stripAnsi($content)) - $magicNumber;
                if ($missingChars < 0) {
                    continue;
                }
                $output[$index] = $content . str_repeat(" ", $missingChars) . self::getColor("cyan") . '│'. self::getColor("reset");
            }
        }
    
        $output = implode(PHP_EOL, $output);
    
        fwrite(STDERR,$output);
    }


    /**
     * Returns the name of the error severity level.
     *
     * @param int $severity The severity level of the error.
     * @return string The name of the severity level.
     */
    protected static function getSeverityName(int $severity): string
    {
        $severityNames = [
            E_ERROR             => 'E_ERROR',
            E_WARNING           => 'E_WARNING',
            E_PARSE             => 'E_PARSE',
            E_NOTICE            => 'E_NOTICE',
            E_CORE_ERROR        => 'E_CORE_ERROR',
            E_CORE_WARNING      => 'E_CORE_WARNING',
            E_COMPILE_ERROR     => 'E_COMPILE_ERROR',
            E_COMPILE_WARNING   => 'E_COMPILE_WARNING',
            E_USER_ERROR        => 'E_USER_ERROR',
            E_USER_WARNING      => 'E_USER_WARNING',
            E_USER_NOTICE       => 'E_USER_NOTICE',
            E_STRICT            => 'E_STRICT',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
            E_DEPRECATED        => 'E_DEPRECATED',
            E_USER_DEPRECATED   => 'E_USER_DEPRECATED',
            E_ALL               => 'E_ALL',
        ];

        return $severityNames[$severity] ?? 'UNKNOWN';
    }



    /**
     * Retrieves the highlighted code around the error line.
     *
     * @param string $errorFile The file path.
     * @param int    $line      The line number.
     * @param int    $padding   The number of lines to include before and after the error line.
     * @return string The highlighted code output.
     */
    protected static function getHighlightedCode(string $errorFile, int $line, int $padding = 5): string
    {
        $lines = file($errorFile);
        $start = max($line - $padding, 0);
        $end = min($line + $padding, count($lines) - 1);

        $codeOutput = "Code:\n";
        for ($i = $start; $i <= $end; $i++) {
            $lineNumber = $i + 1;
            $close = ($lineNumber == $line) ? self::getColor("reset") : "";
            $opener = ($lineNumber == $line) ? self::getColor("red") : "";
            $codeOutput .= sprintf("%-4d", $lineNumber) . $opener . " | " . rtrim($lines[$i]) . $close . PHP_EOL;
        }

        return $codeOutput;
    }


        /**
     * Returns the ANSI escape sequence for the specified color.
     *
     * @param string $color The color name.
     * @return string The ANSI escape sequence for the color.
     */
    protected static function getColor(string $color): string
    {
        if (self::$noColor) {
            return '';
        }

        $colors = self::$colors;
        return isset($colors[$color]) ? $colors[$color] : '';
    }





}




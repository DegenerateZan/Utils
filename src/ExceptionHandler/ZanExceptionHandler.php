<?php

namespace DegenerateZan\Utils\ExceptionHandler;

use Throwable;

/**
 * ExceptionHandler class for handling exceptions and displaying eye candy error messages.
 * 
 * if you want to use it don't forget to define the handler
 * ```
 * set_exception_handler([\DegenerateZan\Utils\ExceptionHandler\ZanExceptionHandler, "handle"]);
 * ```
 * 
 * Warning if you dont define this, it'll auto define it
 * ```
 *  define("DUMP_STACK_TRACE", true);
 *  define("STACK_TRACE_OUTPUT", "log/StackTraceDump.log");
 * ```
 * 
 */
class ZanExceptionHandler extends HandlerUtil
{

    /**
     * Whether to disable colors or not, can be useful for shells that don't support color coded output.
     *
     * @var bool
     */
    protected static $noColor = false;

    /**
     * Whether to fill the last borders or not.
     * some people like it, some people don't
     *
     * @var bool
     */
    protected static $fillBorders = false;



    /**
     * Handles the exception and displays the error message.
     *
     * @param $exception The exception object to handle.
     * @param int $padding The number of lines to include before and after the error line.
     * @return void
     */
    public static function handle(Throwable $exception, int $padding = 5): void
    {
        if (!defined("DUMP_STACK_TRACE")){
            define("DUMP_STACK_TRACE", true);
        }
        if (!defined("STACK_TRACE_OUTPUT")){
            define("STACK_TRACE_OUTPUT", "log/StackTraceDump.log");
        }


        printf($exception->__toString() . PHP_EOL );
        $errorType = get_class($exception);
        $tracesAsString = $exception->getTraceAsString();
        $errorFile = $exception->getFile();
        $errorLine = $exception->getLine();

        $borderAmount = 70; // warning bug can occured when the value is less than 53
        $indentationAmount = 2;

        $border = str_repeat('─', $borderAmount);
        $indentation = str_repeat(' ', $indentationAmount);

        $namespace = self::getNamespace($errorFile, $errorLine);

        $output[] = self::getColor("cyan") . '┌' . $border . '┐' . self::getColor("reset");
        $output[] = self::getColor("cyan") . '│' . $indentation . self::getColor("red") . 'EXCEPTION' . self::getColor("reset") . ' ';
        $output[] = self::getColor("cyan") . '├' . $border . '┤' . self::getColor("reset");
        $output[] = self::getColor("cyan") . '│' . $indentation . self::getColor("yellow") . 'Type: ' . self::getColor("reset") . get_class($exception);
        $output[] = self::getColor("cyan") . '│' . $indentation . self::getColor("yellow") . 'Message: ' . self::getColor("reset") . $exception->getMessage();
        $output[] = self::getColor("cyan") . '│' . $indentation . self::getColor("yellow") . 'File: ' . self::getColor("reset") . $exception->getFile();
        $output[] = self::getColor("cyan") . '│' . $indentation . self::getColor("yellow") . 'Line: ' . self::getColor("reset") . $exception->getLine();
        if (DUMP_STACK_TRACE){
            $output[] = self::getColor("cyan") . '│' . $indentation . self::getColor("yellow") . 'Stack Trace Dump Location: ' . self::getColor("reset") . STACK_TRACE_FILE_LOC;
        }

        $output[] = self::getColor("cyan") . '└' . $border . '┘' . self::getColor("reset");

        if (!empty($namespace)) {
            // Retrieve the Namespace used within the Scope area of the code
            $output[] = PHP_EOL . self::getColor("reset") . $indentation . self::getColor("bg_red") . " " . $namespace . " " . self::getColor("reset") . PHP_EOL;
        }

        // Get the lines of code around the error line
        $output[] = self::getHighlightedCode($errorFile, $exception->getLine(), $padding);


        // Again, some people like it, some people don't
        if (self::$fillBorders) {
            // Get the longest string length among the array
            $maxlen = self::getMaxLen($output);

            // Value? straight from my ass
            $magicNumber = $borderAmount + $borderAmount + $indentationAmount; // don't ask me how did i pulled this out

            foreach ($output as $index => &$content) {
                $missingChars = $maxlen - strlen(self::stripAnsi($content)) - 1 - $magicNumber;
                var_dump($content);
                echo PHP_EOL . "index no $index parsed str : " . strlen(self::stripAnsi($content)) .  "  raw str (non parsed) : " . strlen($content) .PHP_EOL;
                if ($missingChars < 0) {
                    continue;
                }
                $output[$index] = $content . str_repeat(" ", ($missingChars)) . self::getColor("cyan") . '│'. self::getColor("reset");
            }
        }

        // Print or log the error output
        $output = implode(PHP_EOL, $output);
        fwrite(STDERR,$output);



        if (!DUMP_STACK_TRACE){
            return;
        }
        
        self::dumpStackTrace($tracesAsString, STACK_TRACE_OUTPUT);

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

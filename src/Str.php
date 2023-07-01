<?php

namespace DegenerateZan\Utils;

use Exception;

/**
 * Specialized utilty for String
 */
class Str{

    /**
     * echo with an PHP_EOL at the end of string
     *
     * @param  string $string
     * @return void
     */
    public static function echoNewLine(string $string){
        echo $string . PHP_EOL;
    }
    
    /**
     * check wether string has number
     *
     * @param  string $string
     * @return bool
     */
    public static function ifStringHasNum(string $string){
        //preg_match_all('!\d+!', $string, $matches);
        return (preg_match('#[0-9]#',$string)) ? true : false;

    }
    
    /**
     * to cut the string from offset 0 up to offset of the selected string, and will return the remaining string
     * 
     * example : cut_selected("damn this bar is crazy", "damn this bar") : "is crazy"
     *
     * @param  string $string
     * @param  string $selected
     * @return void
     */
    public static function cutSelected(string $string, string $selected) {
        $selected_length = strlen($selected);
        $max_length = strlen($string);
        $firstoffset = strpos($string, $selected) + $selected_length;
        $hasil=substr($string, $firstoffset);
        return $hasil;
        
    }
    
    /**
     * to get string between 2 specified part of the string
     *
     * @param  string $string
     * @param  string $start
     * @param  string $end
     * @return void
     */
    public static function getStrBetween($string, $start, $end){
        if (is_null($start) || is_null($end)) return $string;
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }


    public static function var_dumps_new_lines(...$data){
        $i = 1;
        foreach($data as $d){
            echo "Debug Variable Dump number $i\n";
            var_dump($d);
            $i++;
        }
    }

    public static function newlines($string){
        echo str_repeat("\n", 3);
        echo $string;
        echo str_repeat("\n", 3);
    }

}

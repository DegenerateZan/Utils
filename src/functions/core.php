<?php

namespace DegenerateZan\Utils\function\core;
    


    /**
     * it'll dump the whole object into a json
     */
    function json_dump_to_cache(object $object,string $filename){
        $json = json_encode($object, JSON_PRETTY_PRINT);

        file_put_contents($filename, $json);

    }
    function return_var_dump(...$args): string
    {
        ob_start();
        try {
            var_dump(...$args);
            return ob_get_clean();
        } catch (\Throwable $ex) {
            // PHP8 ArgumentCountError for 0 arguments, probably..
            // in php<8 this was just a warning
            ob_end_clean();
            throw $ex;
        }
    }



    function return_output_callback($callback): string
    {
        ob_start();
        try {
            $callback();
            return ob_get_clean();
        } catch (\Throwable $ex) {
            // PHP8 ArgumentCountError for 0 arguments, probably..
            // in php<8 this was just a warning
            ob_end_clean();
            throw $ex;
        }
    }


    function unset_array_merge($arr, $key){
        unset($arr[$key]);
        return array_merge($arr);
    
    }

    function array_to_concat($arr, $needle = ", "){
        if (!is_array($arr)) return $arr;
        $string = implode(", ", $arr);

        return $string;


    }

    function get_longest_string_in_array(array $arr){
        $lengths = array_map('strlen', $arr);

        $max_len = max($lengths);
        
        $longest_strings = array_filter($arr,function($value) use ($max_len){
            return strlen($value) === $max_len;
        });

        return $longest_strings;
    }

    function endKey($array){
        end($array);
        return key($array);
    }
    

    function convert_const_name_to_camelcase($const_name){
        $const_name = strtolower($const_name);
        if(str_contains($const_name, "_")){
            $i = 0;
            $str_count = substr_count($const_name, "_");
            for($i = 0; $i <= $str_count; $i++){
                $pos = strpos($const_name,"_", $i += 1);
                $cammel_case = strtoupper($const_name[$pos + 1]);
                $const_name[$pos] = "?"; // it'll unset the '_' from defined offset // i dont have a choice to use the easy route with '?'
                $const_name[$pos + 1] = $cammel_case;
            }
            //$const_name = str_replace("_", "", $const_name);
            return str_replace("?", "", $const_name);
        }
        return $const_name;

    }

    function colorLog($str, $type = 'i'){
        switch ($type) {
            case 'e': //error
                return "\033[31m$str \033[0m";
            break;
            case 's': //success
                return "\033[32m$str \033[0m";
            break;
            case 'w': //warning
                return "\033[33m$str \033[0m";
            break;  
            case 'i': //info
                return "\033[36m$str \033[0m";
            break;      
            default:
            # code...
            break;
        }
    }

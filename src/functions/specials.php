<?php


if (!function_exists("convert_type_target_dir")){
    function convert_type_target_dir($loc, $type_target, bool $git_bash_mode = false){

        if ($type_target == "auto") $type_target = (DIRECTORY_SEPARATOR == "/") ? "unix" : "win";
        
        $result = preg_split('~[\\\\/]~', $loc);
        if($git_bash_mode){
            $result[0] = strtolower($result[0]);
            $result[0] = "/" . str_replace(":", "", $result[0]);
        }
    
        if($type_target == "unix"){
    
            return implode("/", $result);
        }   
        
        if($type_target == "win"){
            return implode("\\", $result);
        }
    
    }
}

if (!function_exists("get_namespaces")){
    function get_namespaces(){
        $namespaces=array();
        foreach(get_declared_classes() as $name) {
            if(preg_match_all("@[^\\\]+(?=\\\)@iU", $name, $matches)) {
                $matches = $matches[0];
                $parent =&$namespaces;
                while(count($matches)) {
                    $match = array_shift($matches);
                    if(!isset($parent[$match]) && count($matches))
                        $parent[$match] = array();
                    $parent =&$parent[$match];

                }
            }
        }
    return $namespaces;
    }
}
    function dd(...$vars){
        foreach($vars as $var){
            var_dump($var) . PHP_EOL;

        }
        die;
    }

    function to_json_object($var): object{
        return json_decode(json_encode($var));
    }
function is_valid_php_code_or_throw( $code ) {

        $old = ini_set('display_errors', 1);
        try {
                token_get_all("<?php\n$code", TOKEN_PARSE);
        }
        catch ( Throwable $ex ) {
                $error = $ex->getMessage();
                $line = $ex->getLine() - 1;
                throw new Exception("PARSE ERROR on line $line:\n\n$error");
        }
        finally {
                ini_set('display_errors', $old);
        }
}

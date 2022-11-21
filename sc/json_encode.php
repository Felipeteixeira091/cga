<?php
mb_internal_encoding("iso-8859-1");
mb_http_output("iso-8859-1"); 
ob_start("mb_output_handler");   
header("Content-Type: text/html; charset=ISO-8859-1",true);

class JsonEncodePAcentos{

    #Coverte todo o array para utf8 de forma recursiva.
    private static function utf8_converter($array)
    { #Método obtido no site: http://nazcalabs.com/blog/convert-php-array-to-utf8-recursively/
        array_walk_recursive($array, function(&$item, $key){
            if(!mb_detect_encoding($item, 'utf-8', true)){
                    $item = utf8_encode($item);
            }
        });

        return $array;
    }


    public static function converter($arrayJson){       
        $arrayJson = self::utf8_converter($arrayJson);      
        $var = json_encode($arrayJson, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return utf8_decode($var);
    }

}
<?php

class Utils
{
    public static function jsonResponse($responseData, int $code = 200) :void {
        http_response_code($code);
        echo json_encode($responseData, JSON_UNESCAPED_UNICODE);
        
        exit;
    }
}

?>
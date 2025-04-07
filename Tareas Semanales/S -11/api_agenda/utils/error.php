<?php 
    class CustomError{
        public static function throwError($message, $code){
            http_response_code($code);
            echo json_encode(array("error" => array("message" => $message, "code" => $code)));
            exit();
        }
    }
?>

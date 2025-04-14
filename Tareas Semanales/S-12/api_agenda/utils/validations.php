<?php
    class Validator {
        public static function validateContactName($name) {
            if (empty($name)) {
                return false;
            }
            
            if (strlen($name) < 2 || strlen($name) > 100) {
                return false;
            }
            
            return true;
        }
        
        public static function validatePhone($phone) {
            if (empty($phone)) {
                return false;
            }
            
            
            if (!preg_match("/^[0-9]{1,15}$/", $phone)) {
                return false;
            }
            return true;
        }
        
        public static function validateId($id) {
            if (empty($id) || !is_numeric($id) || $id <= 0) {
                return false;
            }
            
            return true;
        }
        
        public static function validateSearchTerm($term) {
            if (empty($term) || strlen($term) < 2) {
                return false;
            }
            
            return true;
        }
    }
?>

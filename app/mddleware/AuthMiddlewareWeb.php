<?php

class AuthMiddlewareWeb
{
    public static function isLogin() 
    {
        if(isset($_SESSION['token'])) {
            return true;
        } 
        
        return false;
    }

    public static function isAdmin() 
    {
        if(self::isLogin() && $_SESSION['token']['is_admin'] == 1) {
            return true;
        } 
        
        return false;
    }

    public static function canEditProfile($userId) 
    {
        if(self::isLogin() && $_SESSION['token']['id'] == $userId || self::isAdmin()) {
            return true;
        } 
        
        return false;
    }

        public static function canEditProduct($productId) 
    {
        if(self::isLogin() && self::isAdmin()) {
            return true;
        } 
        
        return false;
    }
} 

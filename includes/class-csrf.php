<?php

class CSRF
{
    //generate random token
    public static function generateToken($prefix='')
    {
        if(!isset($_SESSION[$prefix.'_csrf_token']))
        {
            $_SESSION[$prefix.'_csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    //verify - make sure it match with our form's token
    public static function verifyToken($formToken,$prefix='')
    {
        // var_dump( $formToken );
        // var_dump( $_SESSION[ $prefix . '_csrf_token' ] );
        if(isset($_SESSION[$prefix.'_csrf_token'])&&$formToken===$_SESSION[$prefix.'_csrf_token']){
            return true;
        }
        return false;
    }

    //retrieve token (if exist)
    public static function getToken($prefix='')
    {
            if(isset($_SESSION[$prefix.'_csrf_token']))
            {
                return $_SESSION[$prefix.'_csrf_token'];
            }
        return false;
    }

    //unset/remove token
    public static function removeToken($prefix='')
    {
        if(isset($_SESSION[$prefix.'_csrf_token']))
        {
            unset($_SESSION[$prefix.'_csrf_token']);
        }
    }
}
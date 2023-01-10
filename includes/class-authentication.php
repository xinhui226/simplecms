<?php

class Authentication{

    public function __construct()
    {

    }

    /*
    *To login user
    */
    public static function login($email,$password)
    {
        DB::connect()->select(
            'SELECT * FROM users WHERE email= :email',
            [
                'email'=>$email
            ]
        );
    }

    /*
    *To signup user
    */
    public static function signup($name,$email,$password)
    {

       return DB::connect()->insert(
            'INSERT INTO users (name,email,password) VALUES(:name,:email,:password)',
            [
            'name'=>$name,
            'email'=>$email,
            'password'=>password_hash($password,PASSWORD_DEFAULT)
            ]
        );

    }

    
    /*
    *To logout user
    */
    public static function logout()
    {
        unset($_SESSION['user']);
    }

    /**
     * check if user is logged in or not
     */
    public static function isLoggedIn()
    {
        return isset($_SESSION['user']);
    }

    /**
     * assign user's session
     */
    public static function setSession($user_id)
    {
        //load user data form database based on the id $user_id provided
        $user = DB::connect()->select(
            'SELECT * FROM users WHERE id = :id',
            [
                'id'=>$user_id
            ]
        );
        
        //assign it to the session data $_SESSION['user']
        $_SESSION['user'] =[ 
            'id'=>$user['id'],
            'name'=>$user['name'],
            'email'=>$user['email']
        ];
    }

}
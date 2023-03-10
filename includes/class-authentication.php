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
        $user_id=false;
        $user = DB::connect()->select(
            'SELECT * FROM users WHERE email= :email',
            [
                'email'=>$email
            ]
        );

        //if user is valid then return $user array
        if($user){
            //verify password
            if(password_verify($password,$user['password']))
            {
                $user_id = $user['id'];
            }
        }

        //make sure return user id in database
        return $user_id;
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
            'email'=>$user['email'],
            'role'=>$user['role']
        ];
    }

    /**
     * Retrieve user's role form $_SESSION data
     */
    public static function getRole()
    {
            if(self::isLoggedIn()){
            return $_SESSION['user']['role'];}

        return false;
    }
    
    /**
     * Check if the current user is admin
     */
    public static function isAdmin()
    {
        return self::getRole() == 'admin';
    }

    /**
     * Check if the current user is editor
     */
    public static function isEditor()
    {
        return self::getRole() =='editor';
    }

    /**
     * Check if the current user is normal user
     */
    public static function isUser()
    {
        return self::getRole()=='user';
    }


    /**
     * to control user's access
     * $role can be 'admin' , 'editor' , or 'user'
     */
    public static function whoCanAccess($role)
    {
            if(self::isLoggedIn())
            {
                switch($role){
                    case 'admin' :
                        return self::isAdmin();
                    case 'editor' :
                        return self::isEditor()||self::isAdmin();
                    case 'user' :
                        return self::isUser()||self::isEditor()||self::isAdmin();
                } //end - switch
            }
        
        //if no condition met, we'll return false
        return false;
    }
}
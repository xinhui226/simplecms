<?php

class User{

    /**
     * Retrieve all user form database
     */
    public static function getAllUsers()
    {
       return DB::connect()->select(
            'SELECT * FROM users ORDER BY id DESC',
            [],
            true
        );
    }

    /**
     * Retrieve user data by id
     */
    public static function getUserById($id)
    {
        return DB::connect()->select(
            'SELECT * FROM users WHERE id = :id',
            [
                'id'=>$id
            ]
            );
    }

    /**
     * update user data
     */
    public static function update($id,$name,$email,$role,$password=null)
    {
        //setup params
        $params = [
            'id'=>$id,
            'name'=>$name,
            'email'=>$email,
            'role'=>$role
        ];

        //if password is not null
        if($password)
        {
            $params['password'] = password_hash($password,PASSWORD_DEFAULT);
        }

        //update user data into database
        return DB::connect()->update(
            'UPDATE users SET name=:name, email=:email, '. ($password?'password=:password, ':'') .'role=:role WHERE id=:id',
            $params
        );

    }
}
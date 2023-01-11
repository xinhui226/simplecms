<?php

//static class
class FormValidation{

    /**
     * make sure email is unique
     */
    public static function checkEmailUniqueness($email)
    {
        $user = DB::connect()->select(
            'SELECT * FROM users WHERE email = :email',
            [
                'email'=>$email
            ]
            );
        
        //if user is already exist
        if($user)
        {
            return 'Email is already exist.';
        }

        return false;
    }

    /**
     * do all the validation function
     */
    public static function validate($data,$rules=[])
    {
        $error = false;

        //do all the validation function checking
        foreach($rules as $key => $condition)
        {
            switch($condition)
            {
                //make sure all fields are filled
                case 'required':
                    if(empty($data[$key]))
                    {
                        $error .= 'The field "'. $key . '" is empty<br>';
                    };
                    break;
                case 'is_password_match' :
                    if($data['password']!=$data['confirm_password'])
                    {
                        $error .= 'Password do not match<br>';
                    }
                    break;
                case 'email_check' :
                    if(empty($data[$key]))
                    {
                        $error .= 'The field "'. $key . '" is empty<br>';
                    }elseif(!filter_var($data[$key],FILTER_VALIDATE_EMAIL))
                    {
                        $error .='Email format is invalid<br>';
                    }
                    break;
            } //end - foreach
        }

        return $error;
    }
}
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
                case 'password_check' :
                    if(empty($data[$key]))
                    {
                        $error .= 'The field "'. $key . '" is empty<br>';
                    }elseif(strlen($data[$key])<8)
                    {
                        $error.='Password should be at least 8 characters<br>';
                    }
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
                //make sure csrf token is match
                case 'login_form_csrf_token' :
                    //$data[$key] = $_POST['csrf_token]
                    if( !CSRF::verifyToken($data[$key],'login_form'))
                    {
                        die('Nice Try');
                    }
                    break;
                case 'signup_form_csrf_token' :
                    if(!CSRF::verifyToken($data[$key],'signup_form'))
                    {
                        die('Nice Try');
                    }
                    break;
                case 'edit_user_form_csrf_token' :
                    if(!CSRF::verifyToken($data[$key],'edit_user_form'))
                    {
                        die('Nice Try');
                    }
                    break;
                case 'add_user_form_csrf_token' :
                    if(!CSRF::verifyToken($data[$key],'add_user_form'))
                    {
                        die('Nice Try');
                    }
                    break;
                case 'delete_user_form_csrf_token' :
                    if(!CSRF::verifyToken($data[$key],'delete_user_form'))
                    {
                        die('Nice Try');
                    }
                    break;
                case 'edit_post_form_csrf_token' :
                    if(!CSRF::verifyToken($data[$key],'edit_post_form'))
                    {
                        die('Nice Try');
                    }
                    break;
                case 'add_post_form_csrf_token' :
                    if(!CSRF::verifyToken($data[$key],'add_post_form'))
                    {
                        die('Nice Try');
                    }
                    break;
                case 'delete_post_form_csrf_token' :
                    if(!CSRF::verifyToken($data[$key],'delete_post_form'))
                    {
                        die('Nice Try');
                    }
                    break;
                
            } //end - foreach
        }

        return $error;
    }
}
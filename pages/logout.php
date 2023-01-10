<?php

//make sure if user is logged in
if( Authentication::isLoggedIn()){
    //then only trigger logout function
    Authentication::logout();
}

header('Location: /login');
exit;
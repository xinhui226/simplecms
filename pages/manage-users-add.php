<?php

if(!Authentication::whoCanAccess('admin'))
 {
  header('Location: /dashboard');
  exit;
 }

 //step 1
 CSRF::generateToken('add_user_form');

 //step 2
 if($_SERVER['REQUEST_METHOD']=="POST"){

  //step 3
  $rules = [
    'name'=>'required',
    'email'=>'email_check',
    'role'=>'required',
    'password'=>'password_check',
    'confirm_password'=> 'is_password_match',
    'csrf_token' =>'add_user_form_csrf_token'
  ];

  $error= FormValidation::validate(
    $_POST,
    $rules
  );

  if(FormValidation::checkEmailUniqueness($_POST['email'])){
    $error.=FormValidation::checkEmailUniqueness($_POST['email']);
  }

  if(!$error){

    //step 4
    User::add(
      $_POST['name'],
      $_POST['email'],
      $_POST['role'],
      $_POST['password']
    );

    //step 5
    CSRF::removeToken('add_user_form');

    //step 6
    header('Location: /manage-users');
    exit;
  }
 }
 
require dirname(__DIR__)."/parts/header.php";

?>
    <div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Add New User</h1>
      </div>
      <div class="card mb-2 p-4">
        <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
        <?php require dirname(__DIR__)."/parts/error_box.php"?>
          <div class="mb-3">
            <div class="row">
              <div class="col">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name"/>
              </div>
              <div class="col">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"/>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row">
              <div class="col">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password"/>
              </div>
              <div class="col">
                <label for="confirm-password" class="form-label"
                  >Confirm Password</label
                >
                <input
                  type="password"
                  class="form-control"
                  id="confirm-password"
                name="confirm_password"/>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-control" id="role" name="role">
              <option selected value="">Select an option</option>
              <option value="user">User</option>
              <option value="editor">Editor</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Add</button>
            <input type="hidden" name="csrf_token" value="<?=CSRF::getToken('add_user_form')?>">
          </div>
        </form>
      </div>
      <div class="text-center">
        <a href="/manage-users" class="btn btn-link btn-sm"
          ><i class="bi bi-arrow-left"></i> Back to Users</a
        >
      </div>
    </div>

    <?php

require dirname(__DIR__)."/parts/footer.php";

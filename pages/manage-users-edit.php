<?php

if(!Authentication::whoCanAccess('admin'))
 {
  header('Location: /dashboard');
  exit;
 }

//get user's data
$user = User::getUserById($_GET['id']);

//step 1:set csrf token
CSRF::generateToken('edit_user_form');

//step 2:make sure post request
if($_SERVER['REQUEST_METHOD']=='POST')
{

  //step 3:do error check

  //do a check if password & comfirmpassword filled or not
  $is_password_changed =
  (
  isset($_POST['password'])&&!empty($_POST['password'])||
  isset($_POST['confirm_password'])&&!empty($_POST['comfirm_password'])
  ? true : false
  );

    //if both password & confirm password are empty, skip error checking for both fields
    $rules = [
      'name'=>'required',
      'email'=>'email_check',
      'role'=>'required',
      'csrf_token' =>'edit_user_form_csrf_token'
    ];

    //if password is updated
    if($is_password_changed){
      $rules['password']='password_check';
      $rules['confirm_password']= 'is_password_match';
    }

    $error = FormValidation::validate(
      $_POST,
      $rules
    );

    //if email changed, make sure it didn't exist
    //we check for email changes
    if($user['email']!==$_POST['email']){
      //do error check to make sure no data exist
      $error.=FormValidation::checkEmailUniqueness($_POST['email']);
    }//end - $user['email] != $_POST['email]

    if(!$error)
    {
        //step 4:update user
        User::update(
          $user['id'],
          $_POST['name'],
          $_POST['email'],
          $_POST['role'],
          //password update if available
          ($is_password_changed? $_POST['password']:null)
        );

        //step 5:remove csrf token
        CSRF::removeToken('edit_user_form');

        //step 6:redirect to manage-users page
        header('Location: /manage-users');
        exit;
    }//end- !$error

}//end - if request-method is post

 
require dirname(__DIR__)."/parts/header.php";

?>
    <div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Edit User</h1>
      </div>
      <div class="card mb-2 p-4">
        <?php require dirname(__DIR__)."/parts/error_box.php";?>
        <form action="<?= $_SERVER['REQUEST_URI']?>" method="POST">
          <div class="mb-3">
            <div class="row">
              <div class="col">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?=$user['name']?>"/>
              </div>
              <div class="col">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?=$user['email']?>"/>
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
              <option value="">Select an option</option>
              <option value="user" <?=($user['role']=='user' ? 'selected' : '') ?>>User</option>
              <option value="editor" <?=($user['role']=='editor' ? 'selected' : '') ?>>Editor</option>
              <option value="admin" <?=($user['role']=='admin' ? 'selected' : '') ?>>Admin</option>
            </select>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Update</button>
            <input type="hidden" name="csrf_token" value="<?=CSRF::getToken('edit_user_form')?>">
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

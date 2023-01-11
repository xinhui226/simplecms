<?php

 //set CSRF token

 //make sure user is not login or else redirect to dashboard page
 if(Authentication::isLoggedIn()){
  header ('Location: /dashboard');
  exit;
}

//process login form
if($_SERVER['REQUEST_METHOD']=='POST'){

  $email = $_POST['email'];
  $password = $_POST['password'];

  //step 1:do error check
  $error = FormValidation::validate(
    $_POST,
    [
    'email'=>'required', //email is key, required is condition
    'password'=>'required'
   ]
  );

  //make sure the $error is false (no error inside)
  if(!$error)
  {

 //step 2:login the user
  $user_id=Authentication::login($email,$password);
  //if $user_id is false means either email or password is incorrect

  if(!$user_id){
    //triger error message
    $error = "Email or password is incorrect";
  }else{
    //$user_id is valid (id in database)
    var_dump($user_id);
    //step 3:set user to session data $_SESSION['user']
    Authentication::setSession($user_id);

    //step 4:remove CSRF token & redirect user to dashboard page
        //4.1: remove CSRF token

        //4.2: redirect to dashboard
        header('Location: /dashboard');
        exit;

  } //end - !$user_id

 
  } //end - !$error


} //end - POST method


require dirname(__DIR__)."/parts/header.php";

?>
    <div class="container my-5 mx-auto" style="max-width: 500px;">
      <h1 class="h1 mb-4 text-center">Login</h1>

      <div class="card p-4">
        <?php require dirname(__DIR__).'/parts/error_box.php'; ?>
        <form method="POST" action="<?=$_SERVER['REQUEST_URI']?>">
          <div class="mb-2">
            <label for="email" class="visually-hidden">Email</label>
            <input
              type="text"
              class="form-control"
              name="email"
              id="email"
              placeholder="email@example.com"
            />
          </div>
          <div class="mb-2">
            <label for="password" class="visually-hidden">Password</label>
            <input
              type="password"
              class="form-control"
              name="password"
              id="password"
              placeholder="Password"
            />
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Login</button>
          </div>
        </form>
      </div>

      <!-- links -->
      <div
        class="d-flex justify-content-between align-items-center gap-3 mx-auto pt-3"
      >
        <a href="/" class="text-decoration-none small"
          ><i class="bi bi-arrow-left-circle"></i> Go back</a
        >
        <a href="/signup" class="text-decoration-none small"
          >Don't have an account? Sign up here
          <i class="bi bi-arrow-right-circle"></i
        ></a>
      </div>
    </div>

    <?php

require dirname(__DIR__)."/parts/footer.php";

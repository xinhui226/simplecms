<?php

//only admin can access
if(!Authentication::whoCanAccess('admin'))
 {
  header('Location: /dashboard');
  exit;
 }

 //step 1
 CSRF::generateToken('delete_user_form');

 if($_SERVER['REQUEST_METHOD']=='POST'){

  $error=FormValidation::validate(
    $_POST,
      [
      'userid'=>'required',
      'csrf_token'=>'delete_user_form_csrf_token'
      ]
  );

  if(!$error)
  {
    User::delete(
      $_POST['userid']
    );

    CSRF::removeToken('delete_user_form');

    header('Location: /manage-users');
    exit;
  }
 }
 
require dirname(__DIR__)."/parts/header.php";

?>
    <div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Manage Users</h1>
        <div class="text-end">
          <a href="/manage-users-add" class="btn btn-primary btn-sm"
            >Add New User</a
          >
        </div>
      </div>
      <?php require dirname(__DIR__)."/parts/error_box.php" ?>
      <div class="card mb-2 p-4">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">No.</th>
              <th scope="col">ID</th>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">Role</th>
              <th scope="col" class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach(User::getAllUsers() as $index=>$user) :?>
            <tr>
              <th><?=$index+1?></th>
              <th scope="row"><?=$user['id']?></th>
              <td><?=$user['name']?></td>
              <td><?=$user['email']?></td>
              <td>
                <?php if($user['role']=='admin') : ?>
                <span class="badge bg-primary"><?=ucwords($user['role'])?></span>
                <?php elseif($user['role']=='editor') : ?>
                <span class="badge bg-info"><?=ucwords($user['role'])?></span>
                <?php elseif($user['role']=='user') : ?>
                <span class="badge bg-secondary"><?=ucwords($user['role'])?></span>
                <?php endif; ?>
              </td>
              <td class="text-end">
                <?php if($user['id']!=$_SESSION['user']['id'] && $user['role']!='admin'): ?>
                <div class="buttons d-flex">
                  <a
                    href="/manage-users-edit?id=<?=$user['id']?>"
                    class="btn btn-success btn-sm me-2"
                    ><i class="bi bi-pencil"></i
                  ></a>
                  <!--delete button start-->
                  <!-- Button trigger modal -->
                  <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#user-<?=$user['id']?>">
                    <i class="bi bi-trash"></i>
                  </button>
                  <!-- Modal -->
                  <div class="modal fade" id="user-<?=$user['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5" id="exampleModalLabel">Delete User "<?=$user['name']?>"</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body text-start">
                            <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
                              Are you sure to delete user "<?=$user['name']?>" ?
                              <input type="hidden" name="userid" value="<?=$user['id']?>">
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary">Delete</button>
                              <input type="hidden" name="csrf_token" value="<?=CSRF::getToken('delete_user_form')?>">
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  <!--delete button end-->
                </div>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="text-center">
        <a href="/dashboard" class="btn btn-link btn-sm"
          ><i class="bi bi-arrow-left"></i> Back to Dashboard</a
        >
      </div>
    </div>

    <?php

require dirname(__DIR__)."/parts/footer.php";

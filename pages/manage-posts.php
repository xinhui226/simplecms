<?php

if(!Authentication::whoCanAccess('user'))
 {
  header('Location: /login');
  exit;
 }
 
 CSRF::generateToken('delete_post_form');

 if($_SERVER['REQUEST_METHOD']=="POST"){

  $error = FormValidation::validate(
    $_POST,
    [
      'postid'=>'required',
      'csrf_token'=>'delete_post_form_csrf_token'
      ]
  );

  if(!$error){
    Post::delete(
      $_POST['postid']
    );

    CSRF::removeToken('delete_post_form');

    header('Location: /manage-posts');
    exit;
  }//end -if (!$error)
  

 }// end - $_SERVER['REQEUST_METHOD']

require dirname(__DIR__)."/parts/header.php";

?>
    <div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Manage Posts</h1>
        <div class="text-end">
          <a href="/manage-posts-add" class="btn btn-primary btn-sm"
            >Add New Post</a
          >
        </div>
      </div>
      <?php require dirname(__DIR__)."/parts/error_box.php" ?>
      <div class="card mb-2 p-4">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col" style="width: 40%;">Title</th>
              <th scope="col">Status</th>
              <th scope="col" class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach(Post::getAllPosts() as $post): ?>
              <?php if(Authentication::whoCanAccess('editor')||$post['user_id']==$_SESSION['user']['id']) : ?>
            <tr>
              <th scope="row"><?=$post['id']?></th>
              <td><?=$post['title']?></td>
              <td><span class="badge <?=($post['status']=="pending"?'bg-warning':'bg-success')?>"><?=($post['status']=="pending"?'Pending Review':'Publish')?></span></td>
              <td class="text-end">
                <div class="buttons">
                  <a
                    href="/post?id=<?=$post['id']?>"
                    target="_blank"
                    class="btn btn-primary btn-sm me-2 <?=($post['status']=="pending"?'disabled':'')?>"
                    ><i class="bi bi-eye"></i
                  ></a>
                  <a
                    href="/manage-posts-edit?id=<?=$post['id']?>"
                    class="btn btn-secondary btn-sm me-2"
                    ><i class="bi bi-pencil"></i
                  ></a>
                  <!--delete button start-->
                  <!-- Button trigger modal -->
                  <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#post-<?=$post['id']?>">
                    <i class="bi bi-trash"></i>
                  </button>
                  <!-- Modal -->
                  <div class="modal fade" id="post-<?=$post['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Post "<?=$post['title']?>"</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body text-start">
                            <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
                             <h6>Are you sure to delete post "<?=$post['title']?>" ?</h6> 
                             <h6>Content :</h6>
                             <p><?=$post['content']?></p>
                              <input type="hidden" name="postid" value="<?=$post['id']?>">
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary">Delete</button>
                              <input type="hidden" name="csrf_token" value="<?=CSRF::getToken('delete_post_form')?>">
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  <!--delete button end-->
                </div>
              </td>
            </tr>
            <?php endif; ?>
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

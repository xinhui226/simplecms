<?php

require dirname(__DIR__)."/parts/header.php";

?>
    <div class="container mx-auto my-5" style="max-width: 500px;">
      <h1 class="h1 mb-4 text-center">My Blog</h1>
      <?php foreach(Post::getAllPosts() as $post) : ?>
        <?php if($post['status']=='publish') :?>
      <div class="card mb-2">
        <div class="card-body">
          <h5 class="card-title"><?=$post['title']?></h5>
          <p class="card-text"><?=$post['content']?></p>
          <div class="text-end">
            <a href="/post?id=<?=$post['id']?>" class="btn btn-primary btn-sm">Read More</a>
          </div>
        </div>
      </div>
      <?php endif; ?>
      <?php endforeach; ?>
      </div>

      <div class="mt-4 d-flex justify-content-center gap-3">
        <?php if(Authentication::isLoggedIn()) : ?>
        <a href="/dashboard" class="btn btn-link btn-sm">Dashboard</a>
        <a href="/logout" class="btn btn-link btn-sm">Logout</a>
        <?php else : ?>
        <a href="/login" class="btn btn-link btn-sm">Login</a>
        <a href="/signup" class="btn btn-link btn-sm">Sign Up</a>
        <?php endif; ?>
      </div>
    </div>

    <?php

require dirname(__DIR__)."/parts/footer.php";

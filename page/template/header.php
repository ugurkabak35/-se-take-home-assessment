<?php 
if(isset($_SESSION["_access_token"])){
    $menu_buttons = '<a class="nav-link" href="?page=basket">Sepet</a>
    <a class="nav-link" href="?page=logout">Çıkış Yap</a>';
}else{
    $menu_buttons  = '<a class="nav-link" href="?page=login">Giriş Yap</a>';
}

?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="?page=index">Ideasoft</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="?page=index">Anasayfa</a>
        </li>
      </ul>
      <form class="d-flex">
            <?= $menu_buttons ?>
      </form>
    </div>
  </div>
</nav>

<?php
$data = getProducts($db);
$products = "";
for($i=0;$i<count($data);$i++){
  $products .= '<div class="col-sm-3">
  <div class="card">
    <img src="'.$data[$i]["image"].'" class="card-img-top" alt="...">
    <div class="card-body">
      <h5 class="card-title">'.$data[$i]["price"].'₺</h5>
      <p class="card-text">'.$data[$i]["name"].'</p>
      <a href="#" onclick="addBasket(\''.$data[$i]["id"].'\')" class="btn btn-primary">Sepete Ekle</a>
    </div>
  </div>
</div>
';
}


?>

<div class="container">
  <div class="row">
    <?= $products ?>
  </div>
</div>
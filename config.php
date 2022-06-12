<?php 
require('db.php');

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

function response($data, $httpStatusCode = 200, $message = "İşlem Gerçekleştirildi")
{
    return ["message" => $message, "data" => $data, "status" => $httpStatusCode];
}

function prear($data)
{
    echo("<pre>");
    print_r($data);
    echo("</pre>");
}


function getProducts($db){
    $stmt = $db->query("SELECT * FROM products");
    while ($row = $stmt->fetch()) {
        $data[] = [
            "id" => $row["id"],
            "category_id" => $row["category_id"],
            "name" => $row["name"],
            "stock" => $row["stock"],
            "price" => $row["price"],
            "image" => $row["image"]
        ];        
    }
    return $data;
}

// ürün detayları fonksiyonu
function getProductsDetails($db,$product_id){
    $stmt = $db->query("SELECT * FROM products where id={$product_id}");
    while ($row = $stmt->fetch()) {
        $data[] = [
            "id" => $row["id"],
            "category_id" => $row["category_id"],
            "name" => $row["name"],
            "stock" => $row["stock"],
            "price" => $row["price"],
            "image" => $row["image"]
        ];        
    }
    return $data;
}

// Kategori detayları fonksiyonu
function getCategoryDetails($db,$category_id){
    $stmt = $db->query("SELECT * FROM categories where id={$category_id}");
    while ($row = $stmt->fetch()) {
        $data[] = [
            "id" => $row["id"],
            "name" => $row["name"],
            "add_time" => $row["add_time"],
            "status" => $row["status"]
        ];        
    }
    return $data;
}

// fiyat formatlama fonksiyonu
function price_format($price)
{
    $new_price = number_format($price, 2, ',', '.');
    return $new_price;
}

// şifreleme
function passwordConvertor($password){
    return md5(sha1(md5($password)));
}

// Kullanıcının aktif sepetini bulma fonksiyonu
function GetUserActiveBasket($db,$user_token="OL7c31YGKUgDtyv3")
{
    $sql=$db->query("SELECT * from user_basket where user_token='{$user_token}' and status='0'")->fetch(PDO::FETCH_ASSOC);
     return $sql["id"];

}

// Sepetteki ürünler fonksiyonu
function GetBasketItems($db,$user_token="OL7c31YGKUgDtyv3")
{
    $ActiveBasketId=GetUserActiveBasket($db,$user_token);

    $sql=$db->query("SELECT * from basket_products where user_token='{$user_token}' and active_basket_id='{$ActiveBasketId}' group by product_id ");
    while ($row = $sql->fetch()) {
        $data[] = [
            "id" => $row["id"],
            "user_token" => $row["user_token"],
            "total_basket_product_price" => UserBasketProductPrice($db,$row["product_id"],$ActiveBasketId),
            "product_details" => getProductsDetails($db,$row["product_id"]),
            "product_count" => UserBasketProductCount($db,$row["product_id"],$ActiveBasketId),
            "category_detail" => getCategoryDetails($db,$row["category_id"]),
            "price" => $row["price"],
            "add_time" => $row["add_time"],
            "status"=>$row["status"],
            "basket_id" => $ActiveBasketId,
            "product_id"=> $row["product_id"]
        ];        
    }
    return $data;
}

// Sepet ürün sayısı(stok kontrolü için)
function UserBasketProductCount($db,$product_id,$basket_id)
{
    $sql=$db->query("SELECT count(*) as product_count from basket_products where product_id='{$product_id}' and active_basket_id='{$basket_id}' and status='1'")->fetch(PDO::FETCH_ASSOC);
    
    return $sql["product_count"];
}

// İndirim için kategoriye ait ürün sayısı
function UserBasketCategoryCount($db,$category_id,$basket_id)
{
    $sql=$db->query("SELECT count(*) as category_count from basket_products where category_id='{$category_id}' and active_basket_id='{$basket_id}' and status='1'")->fetch(PDO::FETCH_ASSOC);
    
    return $sql["category_count"];
}

// Sepet ürün fiyatı
function UserBasketProductPrice($db,$product_id,$basket_id)
{
    $sql=$db->query("SELECT sum(price) as product_price from basket_products where product_id='{$product_id}' and active_basket_id='{$basket_id}' and status='1'")->fetch(PDO::FETCH_ASSOC);
    
    return $sql["product_price"];
}

// Kullanıcı toplam sepet tutarı
function UserBasketTotalPrice($db,$user_token="OL7c31YGKUgDtyv3")
{
    $ActiveBasket_Id=GetUserActiveBasket($db,$user_token);

    $sql=$db->query("SELECT sum(price) as total_price from basket_products where  active_basket_id='{$ActiveBasket_Id}' and status='1'")->fetch(PDO::FETCH_ASSOC);
    
    return discount($db,$user_token);
}

// Sepette indirimleri uygulama
function AddBasketDiscount($db,$user_token="OL7c31YGKUgDtyv3",$price)
{
    $ActiveBasket_Id=GetUserActiveBasket($db,$user_token);
    $sql=$db->query("SELECT * from user_basket where status='1'  and id='{$ActiveBasket_Id}'")->fetch(PDO::FETCH_ASSOC);
    $discount_total=$sql["discount_total"];
    $new_price=($discount_total+$price);
    $update = $db->query("UPDATE user_basket SET discount_total='{$new_price}' WHERE id = '{$ActiveBasket_Id}' ");    
}   

// Sepet toplam indirim tutarı
function BasketDiscount($db,$user_token="OL7c31YGKUgDtyv3")
{
    $ActiveBasket_Id=GetUserActiveBasket($db,$user_token);
    $sql=$db->query("SELECT * from user_basket where status='1'  and id='{$ActiveBasket_Id}'")->fetch(PDO::FETCH_ASSOC);
    $discount_total=$sql["discount_total"];

    return $discount_total;
}

// İndirim hesaplama
function discount($db,$user_token="OL7c31YGKUgDtyv3")
{
    $yuzde_kac_indirim=10;
    $ActiveBasket_Id=GetUserActiveBasket($db,$user_token);  
    $total_price=(UserBasketTotalPrice($db,$user_token)-BasketDiscount($db,$user_token="OL7c31YGKUgDtyv3"));
    if($total_price>=1000)
    {
           $indirim_tutari=($total_price/100)*$yuzde_kac_indirim;
           $indirimli_toplam=$total_price-$indirim_tutari;

           AddBasketDiscount($db,$user_token,$indirim_tutari);         

           $ID_2_product_count=UserBasketCategoryCount($db,2,$ActiveBasket_Id);
           $ID_1_product_count=UserBasketCategoryCount($db,1,$ActiveBasket_Id);
        
        //Mod Kullanılabilir
        if($ID_2_product_count==6)
        {
            $sql=$db->query("SELECT * from basket_products where status='1'  and active_basket_id='{$ActiveBasket_Id}' and category_id=2 limit 1")->fetch(PDO::FETCH_ASSOC);
            $product_id=$sql["id"];
            $price=$sql["price"];
            AddBasketDiscount($db,$user_token,$price);         
            $update = $db->query("UPDATE basket_products SET price='0' WHERE id = '{$product_id}' ");    
        }

        if($ID_1_product_count>=2)
        {
            $sql=$db->query("SELECT * from basket_products where status='1'  and active_basket_id='{$ActiveBasket_Id}' and category_id=1 order by price desc limit 1")->fetch(PDO::FETCH_ASSOC);
            $product_id=$sql["id"];
            $current_price=$sql["price"];
            $new_price=($current_price-(($total_price/100)*20));
            AddBasketDiscount($db,$user_token,(($total_price/100)*20));
            $update = $db->query("UPDATE basket_products SET price='{$new_price}' WHERE id = '{$product_id}' ");    
        }

        $sql=$db->query("SELECT sum(price) as total_price from basket_products where  active_basket_id='{$ActiveBasket_Id}' and status='1'")->fetch(PDO::FETCH_ASSOC);
    
        return $sql["total_price"];
    

    }
    else
    {
        //Mod Kullanılabilir
        if($ID_2_product_count==6)
        {
            $sql=$db->query("SELECT * from basket_products where status='1'  and active_basket_id='{$ActiveBasket_Id}' and category_id=2 limit 1")->fetch(PDO::FETCH_ASSOC);
            $product_id=$sql["id"];
            $price=$sql["price"];
            AddBasketDiscount($db,$user_token,$price);   
            $update = $db->query("UPDATE basket_products SET price='0' WHERE id = '{$product_id}' ");    
        }

        if($ID_1_product_count>=2)
        {
            $sql=$db->query("SELECT * from basket_products where status='1'  and active_basket_id='{$ActiveBasket_Id}' and category_id=1 order by price desc limit 1")->fetch(PDO::FETCH_ASSOC);
            $product_id=$sql["id"];
            $current_price=$sql["price"];
            $new_price=($total_price/100)*20;
            AddBasketDiscount($db,$user_token,(($total_price/100)*20));
            $update = $db->query("UPDATE basket_products SET price='{$new_price}' WHERE id = '{$product_id}' ");    
        }

        $sql=$db->query("SELECT sum(price) as total_price from basket_products where  active_basket_id='{$ActiveBasket_Id}' and status='1'")->fetch(PDO::FETCH_ASSOC);
    
        return $sql["total_price"];

    }

}



// Login
if(isset($_POST["login_action"])){
    $email = $_POST["email"];
    $password = passwordConvertor($_POST["password"]);
    $query  = $db->query("SELECT * FROM users WHERE email='{$email}' AND password='{$password}'")->fetch(PDO::FETCH_ASSOC);

    if ( $query["user_token"] != "" ){
            $_SESSION['_access_token'] = $query["user_token"];
            
            header( "refresh:1;url=?page=index" );
            $msg = '<div class="alert alert-success" role="alert">
            Giriş başarılı. Yönlendiriliyorsunuz.
          </div>';           
        
    }else{
        $msg = '<div class="alert alert-danger" role="alert">
            Email veya kullanıcı adı hatalı.
          </div>';
    }
}




?>

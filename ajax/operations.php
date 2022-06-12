<?php 
require_once("../config.php");
?>
<?php 

//Functions Start

    function isHaveStock($product_id,$db){
        $sql = $db->query("SELECT * FROM products WHERE id = '{$product_id}'")->fetch(PDO::FETCH_ASSOC);
        $stock = $sql["stock"];
        $get_order = $db->query("select count(product_id) as total from basket_products where product_id = '{$product_id}'")->fetch(PDO::FETCH_ASSOC);
        $count = $get_order["total"];
        if($stock > $count){
            return true;
        }else{
            return false;
        }
    }

    function checkUserBasket($user_token,$db){
        $date_time = date('Y-m-d H:i:s');
        $query = $db->query("SELECT * FROM user_basket WHERE user_token = '{$user_token}'")->fetch(PDO::FETCH_ASSOC);
        if($query){
            $listingMap[] = [
                "status" => "success",
                "basket_id" => $query["id"]
            ];
        }else{
            $add_query = $db->prepare("insert into user_basket set user_token = :user_token, add_time = :add_time, status = :status");
            $add = $add_query->execute(array("user_token" => $user_token,"add_time" => $date_time,"status" => 0));
            if($add){
                $get_basket = $db->query("SELECT * FROM user_basket WHERE user_token = '{$user_token}'")->fetch(PDO::FETCH_ASSOC);
                $listingMap[] = [
                    "status" => "success",
                    "basket_id" => $get_basket["id"]
                ];
            }else{
                $listingMap[] = [
                    "status" => "error",
                    "message" => "İşlem sırasında bir hata oluştu. Lütfen daha sonra tekrar deneyin"
                ];
            }
        }
        return $listingMap;
    }

    function productInfo($product_id,$db){
        $sql = $db->query("select * from products where id = '{$product_id}'")->fetch(PDO::FETCH_ASSOC);
        $listingMap[] = [
            "status" => "success",
            "price" => $sql["price"],
            "category_id" => $sql["category_id"]
        ];
        return $listingMap;
    }

    function addBasketProduct($basket_id,$user_token,$product_id,$db){
        if(isHaveStock($product_id,$db) == true){
            $date_time = date('Y-m-d H:i:s');
            $add_Basket = $db->prepare("insert into basket_products set active_basket_id = :basket_id, user_token = :user_token, product_id = :product_id,category_id = :category_id, price = :price,
            add_time = :add_time, status= :status");
            $add = $add_Basket->execute(array(
                "basket_id" => $basket_id,
                "user_token" => $user_token,
                "product_id" => $product_id,
                "category_id" => productInfo($product_id,$db)[0]["category_id"],
                "price" => productInfo($product_id,$db)[0]["price"],
                "add_time" => $date_time,
                "status" => 1
            ));
            if($add){
                $listingMap[] = [
                    "status" => "success",
                    "message" => "Ürün sepetinize başarılı bir şekilde eklendi"
                ];
            }else{
                $listingMap[] = [
                    "status" => "error",
                    "message" => "İşlem sırasında bir hata oluştu. Lütfen daha sonra tekrar deneyiniz"
                ];
            }
        }else{
            $listingMap[] = [
                "status" => "error",
                "message" => "Eklemeye çalıştığınız ürün tükendi"
            ];
        }
        discount($db,$user_token);
        return $listingMap;
    }

    function addBasket($user_token,$product_id,$db)
    {
        $basket = checkUserBasket($user_token,$db);
        if($basket[0]["status"] == "success"){
            $basket_id = $basket[0]["basket_id"];
            discount($db,$user_token);
            return addBasketProduct($basket_id,$user_token,$product_id,$db);
        }else{
            $listingMap[] = [
                "status" => $basket[0]["status"],
                "message" => $basket[0]["message"]
            ];
        }
        return $listingMap;
    }
//Functions End


if($_POST["action"] == "addToBasket"){
    $user_token = $_POST["user_token"];
    $product_id = $_POST["product_id"];
    echo json_encode(response(addBasket($user_token,$product_id,$db)));
}

if($_POST["action"] == "new_total_basket_price"){
    $user_token = $_POST["user_token"];
    echo json_encode(response(UserBasketTotalPrice($db,$user_token)));
}

if($_POST["action"] == "increaseBasketProduct"){
    $user_token = $_POST["user_token"];
    $product_id = $_POST["product_id"];
    $current_count = $_POST["current_count"];
    $basket_id = $_POST["basket_id"];
    $add_basket = addBasketProduct($basket_id,$user_token,$product_id,$db);
    if($add_basket[0]["status"] == "success"){
        $new_count = $current_count + 1;
        $listingMap[] = [
            "status" => "success",
            "new_count" => $new_count,
            "new_price" => UserBasketTotalPrice($db,$user_token),
            "total_product_price" => getProductTotalPrice($db,$new_count,$product_id)
        ];
    }else{
        $listingMap[] = [
            "status" => "error",
            "message" => $add_basket[0]["message"]
        ];
    }
    discount($db,$user_token);
    echo json_encode(response($listingMap));
}

function getProductTotalPrice($db,$count,$product_id){
    $sql=$db->query("SELECT * from products where id='{$product_id}'")->fetch(PDO::FETCH_ASSOC);
    $price =  $sql["price"];
    return $count * $price;
}

if($_POST["action"] == "decreaseBasketProduct"){
    $user_token = $_POST["user_token"];
    $product_id = $_POST["product_id"];
    $current_count = $_POST["current_count"];
    $basket_id = $_POST["basket_id"];

    $delete = $db->query("DELETE FROM basket_products WHERE active_basket_id = '{$basket_id}' AND product_id = '{$product_id}' ORDER BY id DESC LIMIT 1");

    if ($delete) {
        $new_count = $current_count - 1;
        $listingMap[] = [
            "status" => "success",
            "new_count" => $new_count,
            "new_price" => UserBasketTotalPrice($db,$user_token),
            "total_product_price" => getProductTotalPrice($db,$new_count,$product_id)
        ];
    } else {
        $listingMap[] = [
            "status" => "error",
            "message" => "Ürün sepetten çıkarıldı"
        ];
    }
    discount($db,$user_token);
    echo json_encode(response($listingMap));

}


if($_POST["action"]=="change_product_count")
{
    $user_token = $_POST["user_token"];
    $product_id = $_POST["product_id"];
    $product_count = $_POST["product_count"];

    $ActiveBasket_Id=GetUserActiveBasket($db,$user_token);

    $active_count=UserBasketProductCount($db,$product_id,$ActiveBasket_Id);    
  
    if($product_count>$active_count)
    {      
        for($i=0;$i<($product_count-$active_count);$i++)
        {  
            addBasket($user_token,$product_id,$db);
        }
    }
    else if($product_count<$active_count)
    {
        for($i=0;$i<($active_count-$product_count);$i++)
        {       
            $sql=$db->query("SELECT * from basket_products where user_token='{$user_token}' and status='1'  and active_basket_id='{$ActiveBasket_Id}' and product_id={$product_id}  limit 1")->fetch(PDO::FETCH_ASSOC);
            $new_id=$sql["id"];
            $update = $db->query("UPDATE basket_products SET status='0' WHERE id = '{$new_id}' ");            
        }       
       
    }
    else
    {
        null;
    }    
}
?>
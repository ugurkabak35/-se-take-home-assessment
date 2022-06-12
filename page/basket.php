<?php
$GetBasketItems=GetBasketItems($db,$_SESSION["_access_token"]);
$UserBasketTotalPrice=UserBasketTotalPrice($db,$_SESSION["_access_token"]);
$BasketDiscount=BasketDiscount($db,$_SESSION["_access_token"]);
$BasketProduct="";
for($i=0;$i<count($GetBasketItems);$i++)
{
    $BasketProduct.='
    <tr id="basket_product_'.$GetBasketItems[$i]["id"].'">
        <td>
            <figure class="itemside align-items-center">
                <div class="aside"><img src="https://i.imgur.com/1eq5kmC.png" class="img-sm"></div>
                <figcaption class="info"> <a href="#" class="title text-dark" data-abc="true">'.$GetBasketItems[$i]["product_details"][0]["name"].'</a>
                    <p class="text-muted small">Kategori:'.$GetBasketItems[$i]["category_detail"][0]["name"].'</p>
                </figcaption>
            </figure>
        </td>
        <td>
        <div class="d-flex">
            <span class="basket_btn" onclick="decreaseProduct(\''.$GetBasketItems[$i]["product_id"].'\',\''.$GetBasketItems[$i]["basket_id"].'\')"><i class="fas fa-minus"></i></span>
            <input type="text" readonly style="width: 75%;" class="form-control" id="product_count_'.$GetBasketItems[$i]["product_id"].'"  value="'.$GetBasketItems[$i]["product_count"].'"> 
            <span class="basket_btn" onclick="increaseProduct(\''.$GetBasketItems[$i]["product_id"].'\',\''.$GetBasketItems[$i]["basket_id"].'\')"><i class="fas fa-plus"></i></span>
        </div>
        </td>
        <td>
            <div class="price-wrap"> <span class="price" id="product_total_price_'.$GetBasketItems[$i]["product_id"].'">'.$GetBasketItems[$i]["total_basket_product_price"].' ₺</span> </div>
        </td>
    </tr> ';
}   
?>
<style>
    .basket_btn
    {
        width: 40px;
        height: 40px;
        padding: 3px 6px;
        column-fill: balance;
        margin-top: 5px;
        cursor: pointer;
    }
</style>


<div class="container-fluid">
        <div class="row">
            <aside class="col-lg-9">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-borderless table-shopping-cart">
                            <thead class="text-muted">
                                <tr class="small text-uppercase">
                                    <th scope="col">Ürün</th>
                                    <th scope="col" width="120">Adet</th>
                                    <th scope="col" width="120">Fiyat</th>
                                    <th scope="col" class="text-right d-none d-md-block" width="200"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?= $BasketProduct ?>                     
                            </tbody>
                        </table>
                    </div>
                </div>
            </aside>
            <aside class="col-lg-3">               
                <div class="card">
                    <div class="card-body">
                        <dl class="dlist-align">
                            <dt>Toplam Tutar:</dt>
                            <dd class="text-right ml-3"><?= $UserBasketTotalPrice+$BasketDiscount ?> </dd>
                        </dl>
                        <dl class="dlist-align">
                            <dt>İndirim:</dt>
                            <dd class="text-right text-danger ml-3"><?= $BasketDiscount ?></dd>
                        </dl>
                        <dl class="dlist-align">
                            <dt>Genel Toplam:</dt>
                            <dd class="text-right text-dark b ml-3"><strong id="total_price"><?= $UserBasketTotalPrice ?> ₺</strong></dd>
                        </dl>
                        <hr> <a href="#" class="btn btn-out btn-primary btn-square btn-main" > Satın Al </a> <a href="?page=index" class="btn btn-out btn-success btn-square btn-main mt-2" >Alışverişe Devam Et</a>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <script>
        const change_product_count =($items)=>
        {
            alert($('#product_count_'+$items).val());
            /*
            jQuery.ajax({
                url: $baseUrl,
                type: "POST",
                dataType: "JSON",
                data: {
                    action: "change_product_count",
                    user_token: $('input[name="user_token"]').val(),
                    product_id:$items,
                    product_count: $('#product_count_'+$items).val(),
                },
                success: function(response) {
                    jQuery.each(response.data, function(index, value) {
                       
                    });

                },
                complete: function(response) {
                    new_total_price();
                },
            });
            */
        }

        const new_total_price =()=>
        {
            jQuery.ajax({
                url: $baseUrl,
                type: "POST",
                dataType: "JSON",
                data: {
                    action: "new_total_basket_price",
                    user_token: $('input[name="user_token"]').val(),
                },
                success: function(response) {
                    $('#total_price').html(response.data);
                },
                complete: function(response) {
                    
                },
            });
        }
    </script>
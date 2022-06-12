var $baseUrl = "ajax/operations.php";

function isLogined() {
    var $user_token = $('input[name="user_token"]').val();
    if ($user_token != null && $user_token != "" && $user_token != undefined) {
        return true;
    } else {
        return false;
    }
}

function pleaseLogin() {
    swal("Dikkat!", "Devam etmek için lütfen giriş yapın", "error");
}

function errorToast($message) {
    swal("Hata!", $message, "error");
}

function successToast($message) {
    swal("Tebrikler!", $message, "success");
}

function addBasket($product_id) {
    if (isLogined() == true) {
        jQuery.ajax({
            url: $baseUrl,
            type: "POST",
            dataType: "JSON",
            data: {
                action: "addToBasket",
                user_token: $('input[name="user_token"]').val(),
                product_id: $product_id
            },
            success: function(response) {
                jQuery.each(response.data, function(index, value) {
                    if (value.status == "success") {
                        successToast(value.message);
                    } else {
                        errorToast(value.message);
                    }
                });

            },
            complete: function(response) {},
        });
    } else {
        pleaseLogin();
    }

}

function increaseProduct($product_id, $basket_id) {
    var $current_count = $('#product_count_' + $product_id).val();
    if (isLogined() == true) {
        jQuery.ajax({
            url: $baseUrl,
            type: "POST",
            dataType: "JSON",
            data: {
                action: "increaseBasketProduct",
                user_token: $('input[name="user_token"]').val(),
                product_id: $product_id,
                current_count: $current_count,
                basket_id: $basket_id
            },
            success: function(response) {
                jQuery.each(response.data, function(index, value) {
                    if (value.status == "success") {
                        $('#product_count_' + $product_id).val(value.new_count);
                        $('#product_total_price_' + $product_id).html(value.total_product_price + " ₺");
                        //successToast(value.message);
                    } else {
                        errorToast(value.message);
                    }
                });

            },
            complete: function(response) {},
        });
    } else {
        pleaseLogin();
    }
}


function decreaseProduct($product_id, $basket_id) {
    var $current_count = $('#product_count_' + $product_id).val();
    if (isLogined() == true) {
        jQuery.ajax({
            url: $baseUrl,
            type: "POST",
            dataType: "JSON",
            data: {
                action: "decreaseBasketProduct",
                user_token: $('input[name="user_token"]').val(),
                product_id: $product_id,
                current_count: $current_count,
                basket_id: $basket_id
            },
            success: function(response) {
                jQuery.each(response.data, function(index, value) {
                    if (value.status == "success") {
                        if (value.new_count == 0) {
                            $('#basket_product_' + $product_id).remove();
                        }
                        $('#product_total_price_' + $product_id).html(value.total_product_price + " ₺");
                        $('#product_count_' + $product_id).val(value.new_count);
                        //successToast(value.message);
                    } else {
                        errorToast(value.message);
                    }
                });

            },
            complete: function(response) {},
        });
    } else {
        pleaseLogin();
    }
}
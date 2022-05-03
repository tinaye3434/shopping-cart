<?php

session_start();

require_once("php/createDb.php");
require_once("php/component.php");

$db = new createDb("productDb","producttb");

if(isset($_POST['remove'])){
    if($_GET['action'] == 'remove'){
        foreach($_SESSION['cart'] as $key => $value){
            if($value["product_id"] == $_GET['id']){
                unset($_SESSION['cart'][$key]);
                echo "<script> alert('Product has been removed')</script>";
                echo "<script>window.location ='cart.php'</script>";
            }
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>

    <!-- css from bootsrap  -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<!-- custom css styles  -->
<link rel="stylesheet" href="style.css">

<!-- font awesome  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body class="bg-light">

<?php

require_once('php/header.php');


?>

<div class="container-fluid">
    <div class="row px-5">
        <div class="col-md-7">
            <div class="shopping-cart">
                <h6>My Cart</h6>
                <hr>

                <?php

                $total= 0;
                if(isset($_SESSION['cart'])){
                    $product_id = array_column($_SESSION['cart'], 'product_id');

                    $result = $db->getData();
                    while($row = mysqli_fetch_assoc($result)){
                        foreach ($product_id as $id) {
                            if($row['id'] == $id){
                                cartElement($row['product_image'], $row['product_name'], $row['product_price'],$row['id']);
                                $total = $total + (int)$row['product_price'];
                            }
                        }
                    }
                }else{
                    echo "<h5>Cart is Empty</h5>";
                }

                ?>
                
            </div>
        </div>
        <div class="col-md-4 offset-md-1 border rounded mt-5 bg-white h-25">
            <div class="pt-4">
                <h6>PRICE DETAILS</h6>
                <hr>
                <div class="row price-details">
                    <div class="col-md-6">
                        <?php
                        if(isset($_SESSION['cart'])){
                            $count = count($_SESSION['cart']);
                            echo "<h6>Price($count items)</h6>";
                        }else{
                            echo "<h6>Price(0 items)</h6>";
                        }

                        ?>
                        <h6>Delivery Charges</h6>
                        <hr>
                        <h6>Amount Payable</h6>
                    </div>
                    <div class="col-md-6">
                        <h6><?php echo  $total; ?></h6>
                        <h6 class="text-success">FREE</h6>
                        <hr>
                        <h6>$<?php
                                echo $total;
                            ?>
                        </h6>
                    </div>
                        
                </div>
                <div id="paypal-payment-button"> 

                </div> 
            </div>

        </div>
    </div>
</div>







<!-- paypal javascript -->
    <!-- Replace "test" with your own sandbox Business account app client ID -->
    <script src="https://www.paypal.com/sdk/js?client-id=ARMrY1RPrxE2jz6Chg8kvrb-XYimC18NjZIptAAcdlmt1_VVqzfnq6_eWesU52iw-AZ4muQFvSaTP9v4&disable-funding=credit,card&currency=USD"></script>
    <script>
        paypal.Buttons({
    style:{
        color: 'blue',
        shape: 'pill'
    },
    createOrder: function (data,actions) {
        return actions.order.create({
            purchase_units:[{
                amount: {
                    value:<?php echo $total ?>
                }
            }]
        });
    },
    onApprove: function(data,actions){
        return actions.order.capture().then(function(details) {
            console.log(details)
            window.location.replace("http://localhost/question5/success.php")
        });
    },
    onCancel: function(data) {
        window.location.replace("http://localhost/question5/onCancel.php")
    }
}).render("#paypal-payment-button");
    </script>

<!-- bootstrap javascript  -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
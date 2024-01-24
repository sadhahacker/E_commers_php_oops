<?php
$id = $_GET['id'];
require '../includes/database.php';
require '../includes/redirect.php';
$data = new database();
$connection = $data->connect();
$sql = "INSERT INTO orders (customer_id, product_id, product, price,quantity,order_date)
SELECT customer_id, product_id, product, price, quantity,NOW()
FROM cart WHERE cart.customer_id=".$id;
$connection->query($sql);
$sqlamount = "UPDATE orders yt SET yt.total_amount = (yt.quantity * yt.price)";
 $connection->query($sqlamount);
$sqldelete = "DELETE FROM `cart` WHERE `cart`.`customer_id` =".$id;
 $connection->query($sqldelete);
header('Location: cart.php?message=buy');
?>
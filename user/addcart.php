<?php
require '../includes/database.php';
require '../includes/redirect.php';
session_start();
$data = new database();
$connection = $data->connect();
$sql = "SELECT * FROM products where id = ".$_GET['id'];
$result = $connection->query($sql);
$row = $result->fetch_assoc();
$cartadd = "INSERT INTO cart (product, product_id, customer_id, price) VALUES (?,?,?,?);";
$statement = $connection->prepare($cartadd);
$statement->bind_param('siii', $row['productname'],$row['id'],$_SESSION['id'],$row['price'] );
$statement->execute();
header("Location: user.php?message=addedcart");
?>  
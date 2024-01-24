<?php
require '../includes/database.php';
require '../includes/redirect.php';
session_start();
if($_GET['quan']>=0){
    $data = new database();
    $connection = $data->connect();
    $sql = "UPDATE `cart` SET `quantity` = ?,`total_price` = price * quantity WHERE `cart`.`id` =?";
    $statement = $connection->prepare($sql);
    $statement->bind_param('ii', $_GET['quan'], $_GET['id']);
    $statement->execute();
    redirect("cart");
}
else{
    redirect("cart");
}
?>
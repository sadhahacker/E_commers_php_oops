<?php
require '../includes/database.php';
require '../includes/redirect.php';
$data = new database();
$connection = $data->connect();
$id = $_GET['id'];
$sql = "DELETE FROM products WHERE `products`.`id` = ?;";
$statement = $connection->prepare($sql);
$statement->bind_param('i', $id);
$statement->execute();
header('Location: listproduct.php?message=success');
?>
<?php
require '../includes/database.php';
require '../includes/redirect.php';
session_start();
$data = new database();
$connection = $data->connect();
$sql = "DELETE FROM cart WHERE cart.id = ".$_GET['id'];
$connection->query($sql);
header('Location: cart.php?message=success');
?>
<?php
include 'db.php';
$cart_id = $_POST['cart_id'];
$conn->query("DELETE FROM cart WHERE id = $cart_id");
header("Location: cart.php");
?>

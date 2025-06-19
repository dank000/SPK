<?php
include 'db.php';
$cart_id = $_POST['cart_id'];
$quantity = max(1, intval($_POST['quantity']));
$conn->query("UPDATE cart SET quantity = $quantity WHERE id = $cart_id");
header("Location: cart.php");
?>

<?php
include '../MAIN-PAGE/db.php';
$cart_id = $_POST['cart_id'];
$conn->query("DELETE FROM cart WHERE id = $cart_id");
header("Location: ../MAIN-PAGE/cart.php");
?>

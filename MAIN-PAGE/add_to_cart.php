<?php
include '../MAIN-PAGE/db.php';
$product_id = $_POST['product_id'];
// Cek apakah produk sudah ada di keranjang
$res = $conn->query("SELECT * FROM cart WHERE product_id = $product_id");
if ($res->num_rows > 0) {
  $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE product_id = $product_id");
} else {
  $conn->query("INSERT INTO cart (product_id, quantity) VALUES ($product_id, 1)");
}
header("Location: cart.php");
?>

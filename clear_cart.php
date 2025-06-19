<?php
include 'db.php';
$conn->query("DELETE FROM cart");
header("Location: cart.php");
?>

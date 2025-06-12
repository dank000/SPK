<?php
include '../MAIN-PAGE/db.php';
$conn->query("DELETE FROM cart");
header("Location: ../MAIN-PAGE/cart.php");
?>

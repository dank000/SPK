<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Cart</title>
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
  <nav class="navbar">
    <div class="navbar-center">
      <span class="navbar-title">Your Cart</span>
      <a href="index.php">Kembali</a>
    </div>
  </nav>

  <div class="cart">
    <h2>Keranjang Anda</h2>
    <?php
    $result = $conn->query("
      SELECT cart.id AS cart_id, products.name, products.image, products.price, cart.quantity
      FROM cart
      JOIN products ON cart.product_id = products.id
    ");
    $total = 0;
    while ($row = $result->fetch_assoc()):
      $subtotal = $row['price'] * $row['quantity'];
      $total += $subtotal;
    ?>
      <div class="cart-item">
        <img src="<?= $row['image'] ?>" alt="<?= $row['name'] ?>" />
        <div>
          <h4><?= $row['name'] ?></h4>
          <h5>Rp <?= number_format($row['price'], 0, ',', '.') ?></h5>
          <form action="remove_item.php" method="POST">
            <input type="hidden" name="cart_id" value="<?= $row['cart_id'] ?>">
            <button class="remove-item">Hapus</button>
          </form>
        </div>
        <div>
          <form action="update_cart.php" method="POST">
            <input type="hidden" name="cart_id" value="<?= $row['cart_id'] ?>">
            <input type="number" name="quantity" value="<?= $row['quantity'] ?>" min="1" style="width: 60px">
            <button>Update</button>
          </form>
        </div>
      </div>
    <?php endwhile; ?>
    <div class="cart-footer">
      <h3>Total: Rp <?= number_format($total, 0, ',', '.') ?></h3>
      <form action="clear_cart.php" method="POST">
        <button class="clear-cart banner-btn">Kosongkan Keranjang</button>
      </form>
    </div>
  </div>
</body>
</html>

<?php
include 'db.php';

// Query ambil data
$sql = "SELECT c.id AS cart_id, p.id AS product_id, p.name, p.image, p.price, c.quantity
        FROM cart c
        JOIN products p ON c.product_id = p.id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Cart</title>
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
  <nav class="navbar"><a href="index.php">← Kembali</a></nav>
  <h2>Keranjang Anda</h2>

  <?php if ($result && $result->num_rows > 0): ?>
    <?php
      $total = 0;
      while ($row = $result->fetch_assoc()):
        $subtotal = $row['price'] * $row['quantity'];
        $total += $subtotal;
    ?>
      <div class="cart-item">
        <img src="<?= htmlspecialchars($row['image']) ?>" width="80">
        <strong><?= htmlspecialchars($row['name']) ?></strong><br>
        Harga: Rp <?= number_format($row['price'],0,',','.') ?> ×
        <?= $row['quantity'] ?> = Rp <?= number_format($subtotal,0,',','.') ?>
        <form method="POST" action="remove_item.php">
          <input type="hidden" name="cart_id" value="<?= $row['cart_id'] ?>">
          <button type="submit">Hapus</button>
        </form>
        <form method="POST" action="update_cart.php">
          <input type="hidden" name="cart_id" value="<?= $row['cart_id'] ?>">
          <input type="number" name="quantity" value="<?= $row['quantity'] ?>" min="1">
          <button type="submit">Update</button>
        </form>
      </div>
    <?php endwhile; ?>

    <hr>
    <h3>Total: Rp <?= number_format($total,0,',','.') ?></h3>
    <form method="POST" action="clear_cart.php">
      <button type="submit">Kosongkan Keranjang</button>
    </form>

  <?php else: ?>
    <p>Keranjang kosong.</p>
  <?php endif; ?>
</body>
</html>

<?php
require 'db.php';

// hitung total item untuk badge di navbar
$totalItemsRow = $conn
  ->query("SELECT COALESCE(SUM(quantity),0) AS total FROM cart")
  ->fetch_assoc();
$totalItems = $totalItemsRow['total'];

// ambil data keranjang
$sql = "
  SELECT
    c.product_id,
    c.quantity,
    p.nama   AS nama,
    p.harga  AS harga,
    p.image  AS image
  FROM cart c
  JOIN products p ON c.product_id = p.id
";
$res = $conn->query($sql);

// siapkan array item & total harga
$totalPrice = 0;
$items = [];
if ($res->num_rows) {
  while ($r = $res->fetch_assoc()) {
    $subtotal = $r['harga'] * $r['quantity'];
    $totalPrice += $subtotal;
    $items[] = $r;
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Keranjang Belanja</title>
  <link rel="stylesheet" href="styles.css" />
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <!-- Inline CSS untuk merapikan tampilan cart -->
  <style>
    :root {
      --grey-light: #ececec;
    }
    .cart-container {
      max-width: 1170px;
      margin: 2rem auto;
      padding: 0 1.5rem;
    }
    .cart-header {
      background: var(--mainGrey2);
      padding: 1rem 0;
      text-align: center;
      font-size: 1.8rem;
      font-weight: bold;
      color: var(--mainBlack);
      border-bottom: 1px solid var(--grey-light);
    }
    .cart-badge {
      position: absolute;
      top: 1rem;
      right: 1.5rem;
      background: var(--primaryColor);
      color: var(--mainWhite);
      width: 2rem;
      height: 2rem;
      line-height: 2rem;
      text-align: center;
      border-radius: 50%;
      font-size: 0.9rem;
    }
    .cart-items-list {
      margin-top: 2rem;
    }
    .cart-item {
      display: flex;
      align-items: center;
      padding: 1rem 0;
      border-bottom: 1px solid var(--grey-light);
    }
    .cart-item img {
      width: 80px;
      height: auto;
      margin-right: 1.5rem;
      border-radius: 8px;
      background: #fff;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .cart-details {
      flex: 1;
    }
    .cart-details h4 {
      margin: 0 0 0.25rem;
      font-size: 1rem;
      text-transform: capitalize;
    }
    .cart-details h5 {
      margin: 0 0 0.25rem;
      color: var(--primaryColor);
      font-size: 0.9rem;
    }
    .cart-details p {
      margin: 0;
      font-size: 0.9rem;
    }
    .cart-actions {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.5rem;
    }
    .cart-actions .remove-item {
      background: transparent;
      color: var(--mainBlack);
      border: none;
      cursor: pointer;
      font-size: 1.1rem;
    }
    .cart-actions .remove-item:hover {
      color: #f44336;
    }
    .cart-footer {
      text-align: center;
      padding: 2rem 0;
    }
    .cart-footer h3 {
      margin-bottom: 1rem;
      font-size: 1.2rem;
    }
    .cart-footer .btn-group {
      display: flex;
      justify-content: center;
      gap: 1rem;
      flex-wrap: wrap;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <div class="navbar-center">
      <h1 class="navbar-title"></h1>
      <div class="cart-btn">
        <i class="fas fa-shopping-cart nav-icon"></i>
        <span class="cart-badge"><?= $totalItems ?></span>
      </div>
    </div>
  </nav>

  <!-- Kontainer Keranjang -->
  <div class="cart-container">
    <div class="cart-header">Keranjang Anda</div>

    <?php if (empty($items)): ?>
      <p style="text-align:center; padding:2rem 0;">
        Keranjang Anda masih kosong. <a href="index.php">Lanjut Belanja</a>
      </p>
    <?php else: ?>
      <div class="cart-items-list">
        <?php foreach ($items as $item): ?>
          <div class="cart-item">
            <img 
              src="<?= htmlspecialchars($item['image']) ?>" 
              alt="<?= htmlspecialchars($item['nama']) ?>" 
            />
            <div class="cart-details">
              <h4><?= htmlspecialchars($item['nama']) ?></h4>
              <h5>Rp <?= number_format($item['harga'], 0, ',', '.') ?></h5>
              <p>Jumlah: <?= $item['quantity'] ?></p>
            </div>
            <div class="cart-actions">
              <form method="post" action="cart_action.php">
                <input type="hidden" name="action"     value="remove">
                <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                <button type="submit" class="remove-item" title="Hapus Produk">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="cart-footer">
        <h3>Total Belanja: Rp <?= number_format($totalPrice, 0, ',', '.') ?></h3>
        <div class="btn-group">
          <form method="post" action="cart_action.php">
            <input type="hidden" name="action" value="clear">
            <button type="submit" class="clear-cart-btn">Kosongkan Keranjang</button>
          </form>
          <a href="index.php" class="banner-btn">Lanjut Belanja</a>
        </div>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>

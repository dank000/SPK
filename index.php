<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Phone Store</title>
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
  <nav class="navbar">
    <div class="navbar-center">
      <span class="navbar-title">Phone Store</span>
      <div class="cart-btn">
        <a href="cart.php">
          <i class="fas fa-cart-plus"></i> <span class="cart-items">Lihat Keranjang</span>
        </a>
      </div>
    </div>
  </nav>

  <section class="products">
    <div class="section-title"><h2>produk kami</h2></div>
    <div class="products-center">
      <?php
      $result = $conn->query("SELECT * FROM products");
      while ($row = $result->fetch_assoc()):
      ?>
        <div class="product-item">
          <article class="product">
            <div class="img-container">
              <img src="<?= $row['image'] ?>" alt="<?= $row['name'] ?>" class="product-img">
              <form method="POST" action="add_to_cart.php">
                <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                <button class="add-to-cart bag-btn">Tambah</button>
              </form>
            </div>
            <h3><?= $row['name'] ?></h3>
            <h4>Rp <?= number_format($row['price'], 0, ',', '.') ?></h4>
          </article>
        </div>
      <?php endwhile; ?>
    </div>
  </section>
</body>
</html>

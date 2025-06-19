<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("Location: login.php");
  exit;
}
?>

<?php include '../db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin - Produk</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <h2>Manajemen Produk</h2>
  <a href="tambah.php">➕ Tambah Produk</a> | <a href="spk.php">🔍 Perhitungan SPK</a>
  <a href="spk_ahp.php">SPK AHP</a> | 
    <a href="spk_wp.php">SPK WP</a> | 
    <a href="spk_saw.php">SPK SAW</a>

  <table border="1" cellpadding="10" cellspacing="0">
    <tr>
      <th>Nama</th><th>Harga</th><th>Bestseller</th><th>Aksi</th>
    </tr>
    <?php
    $res = $conn->query("SELECT * FROM products");
    while ($row = $res->fetch_assoc()):
    ?>
      <tr>
        <td><?= $row['name'] ?></td>
        <td>Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
        <td><?= $row['bestseller'] ? '✅' : '❌' ?></td>
        <td>
          <a href="edit.php?id=<?= $row['id'] ?>">✏️ Edit</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>

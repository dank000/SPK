<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("Location: login.php");
  exit;
}
?>

<?php include '../db.php'; ?>
<?php
$id = $_GET['id'];
$res = $conn->query("SELECT * FROM products WHERE id = $id");
$row = $res->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $price = $_POST['price'];
  $desc = $_POST['description'];
  $image = $_POST['image'];
  $bestseller = isset($_POST['bestseller']) ? 1 : 0;

  $conn->query("UPDATE products SET name='$name', price=$price, description='$desc', image='$image', bestseller=$bestseller WHERE id = $id");
  header("Location: index.php");
}
?>

<h2>Edit Produk</h2>
<form method="POST">
  Nama: <input type="text" name="name" value="<?= $row['name'] ?>"><br>
  Harga: <input type="number" name="price" value="<?= $row['price'] ?>"><br>
  Deskripsi: <textarea name="description"><?= $row['description'] ?></textarea><br>
  Gambar (path): <input type="text" name="image" value="<?= $row['image'] ?>"><br>
  Bestseller: <input type="checkbox" name="bestseller" <?= $row['bestseller'] ? "checked" : "" ?>><br>
  <button type="submit">Update</button>
</form>

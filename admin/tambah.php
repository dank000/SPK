<?php include '../db.php'; ?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $price = $_POST['price'];
  $desc = $_POST['description'];
  $image = $_POST['image'];
  $bestseller = isset($_POST['bestseller']) ? 1 : 0;

  $stmt = $conn->prepare("INSERT INTO products (name, price, description, image, bestseller) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sissi", $name, $price, $desc, $image, $bestseller);
  $stmt->execute();
  header("Location: index.php");
}
?>

<h2>Tambah Produk</h2>
<form method="POST">
  Nama: <input type="text" name="name"><br>
  Harga: <input type="number" name="price"><br>
  Deskripsi: <textarea name="description"></textarea><br>
  Gambar (path): <input type="text" name="image"><br>
  Bestseller: <input type="checkbox" name="bestseller"><br>
  <button type="submit">Simpan</button>
</form>

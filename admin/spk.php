<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("Location: login.php");
  exit;
}
?>

<?php
include '../db.php';

$data = [];
$res = $conn->query("SELECT * FROM products");
while ($row = $res->fetch_assoc()) {
  $data[] = [
    'name' => $row['name'],
    'price' => $row['price'],
    'bestseller' => $row['bestseller'],
  ];
}

// Normalisasi + Pembobotan
$harga = array_column($data, 'price');
$bestseller = array_column($data, 'bestseller');
$harga_max = max($harga);
$bestseller_max = max($bestseller);

$bobot = ['harga' => 0.7, 'bestseller' => 0.3]; // Bobot kriteria

foreach ($data as &$d) {
  $d['n_harga'] = ($harga_max - $d['price']) / $harga_max; // Harga cost
  $d['n_bestseller'] = $d['bestseller'] / $bestseller_max;
  $d['nilai'] = ($d['n_harga'] * $bobot['harga']) + ($d['n_bestseller'] * $bobot['bestseller']);
}
usort($data, fn($a, $b) => $b['nilai'] <=> $a['nilai']);
?>

<h2>Hasil Perhitungan SPK (TOPSIS Sederhana)</h2>
<table border="1" cellpadding="10">
  <tr>
    <th>Nama</th><th>Harga</th><th>Bestseller</th><th>Skor</th>
  </tr>
  <?php foreach ($data as $d): ?>
    <tr>
      <td><?= $d['name'] ?></td>
      <td>Rp <?= number_format($d['price'], 0, ',', '.') ?></td>
      <td><?= $d['bestseller'] ? 'Ya' : 'Tidak' ?></td>
      <td><?= round($d['nilai'], 4) ?></td>
    </tr>
  <?php endforeach; ?>
</table>

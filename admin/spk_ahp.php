<?php include 'auth.php'; include '../db.php';

$data = $conn->query("SELECT * FROM products");
$products = [];
while ($row = $data->fetch_assoc()) $products[] = $row;

$kriteria = ['harga', 'bestseller'];
$matriks = [
  'harga' => ['harga' => 1, 'bestseller' => 1/3],
  'bestseller' => ['harga' => 3, 'bestseller' => 1]
];

$bobot = [];
foreach ($kriteria as $k1) {
  $total = array_sum(array_column($matriks, $k1));
  foreach ($kriteria as $k2) {
    $normal[$k2][$k1] = $matriks[$k2][$k1] / $total;
  }
}

foreach ($normal as $k => $col) {
  $bobot[$k] = array_sum($col) / count($col);
}

foreach ($products as &$p) {
  $harga_norm = 1 - ($p['price'] / max(array_column($products, 'price')));
  $p['score'] = $harga_norm * $bobot['harga'] + $p['bestseller'] * $bobot['bestseller'];
}
usort($products, fn($a, $b) => $b['score'] <=> $a['score']);
?>

<h2>SPK AHP</h2>
<table border="1" cellpadding="5">
<tr><th>Nama</th><th>Skor</th></tr>
<?php foreach ($products as $p): ?>
<tr><td><?= $p['name'] ?></td><td><?= round($p['score'], 4) ?></td></tr>
<?php endforeach; ?>
</table>

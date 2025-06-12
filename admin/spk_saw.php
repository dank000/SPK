<?php include 'auth.php'; include '../MAIN-PAGE/db.php';

$data = $conn->query("SELECT * FROM products");
$products = [];
foreach ($data as $row) $products[] = $row;

$harga = array_column($products, 'price');
$max_harga = max($harga);

foreach ($products as &$p) {
  $c1 = 1 - ($p['price'] / $max_harga); // cost
  $c2 = $p['bestseller'];               // benefit
  $p['score'] = ($c1 * 0.6) + ($c2 * 0.4);
}
usort($products, fn($a, $b) => $b['score'] <=> $a['score']);
?>

<h2>SPK SAW</h2>
<table border="1" cellpadding="5">
<tr><th>Nama</th><th>Skor</th></tr>
<?php foreach ($products as $p): ?>
<tr><td><?= $p['name'] ?></td><td><?= round($p['score'], 4) ?></td></tr>
<?php endforeach; ?>
</table>

<?php include 'auth.php'; include '../db.php';

$data = $conn->query("SELECT * FROM products");
$products = [];
$max_price = 0;
while ($row = $data->fetch_assoc()) {
  $products[] = $row;
  $max_price = max($max_price, $row['price']);
}

$bobot = ['harga' => 0.6, 'bestseller' => 0.4];
$total_s = 0;

foreach ($products as &$p) {
  $c1 = 1 / $p['price']; // cost
  $c2 = $p['bestseller']; // benefit
  $p['s'] = pow($c1, $bobot['harga']) * pow($c2 ?: 0.1, $bobot['bestseller']);
  $total_s += $p['s'];
}

foreach ($products as &$p) {
  $p['score'] = $p['s'] / $total_s;
}
usort($products, fn($a, $b) => $b['score'] <=> $a['score']);
?>

<h2>SPK Weighted Product</h2>
<table border="1" cellpadding="5">
<tr><th>Nama</th><th>Skor</th></tr>
<?php foreach ($products as $p): ?>
<tr><td><?= $p['name'] ?></td><td><?= round($p['score'], 4) ?></td></tr>
<?php endforeach; ?>
</table>

<?php
// cart_action.php
require 'db.php';

/**
 * Hindari open-redirect dengan hanya mengizinkan karakter alfanumerik, slash, dot, underscore
 */
function clean_redirect(string $url): string {
    return preg_replace('/[^a-zA-Z0-9_\.\/]/', '', $url);
}

// Ambil parameter
$action   = $_GET['action']            ?? '';
$pid      = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
$redirect = isset($_GET['redirect'])   ? clean_redirect($_GET['redirect']) : 'cart.php';

switch ($action) {
    case 'add':
        if ($pid > 0) {
            // sudah ada di keranjang?
            $chk = $conn->query("SELECT * FROM cart WHERE product_id = {$pid}");
            if ($chk && $chk->num_rows) {
                $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE product_id = {$pid}");
            } else {
                $conn->query("INSERT INTO cart (product_id, quantity) VALUES ({$pid}, 1)");
            }
        }
        break;

    case 'increment':
        if ($pid > 0) {
            $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE product_id = {$pid}");
        }
        break;

    case 'decrement':
        if ($pid > 0) {
            $conn->query("UPDATE cart SET quantity = quantity - 1 WHERE product_id = {$pid}");
            $conn->query("DELETE FROM cart WHERE product_id = {$pid} AND quantity <= 0");
        }
        break;

    case 'remove':
        if ($pid > 0) {
            $conn->query("DELETE FROM cart WHERE product_id = {$pid}");
        }
        break;

    case 'clear':
        $conn->query("DELETE FROM cart");
        break;

    default:
        header('HTTP/1.1 400 Bad Request');
        echo 'Invalid cart action';
        exit;
}

// Redirect kembali ke halaman asal
header('Location: ' . $redirect);
exit;

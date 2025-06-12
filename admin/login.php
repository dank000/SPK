<?php
session_start();
include '../MAIN-PAGE/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $user = $_POST['username'];
  $pass = md5($_POST['password']);

  $result = $conn->query("SELECT * FROM users WHERE username='$user' AND password='$pass'");
  if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $_SESSION['login'] = true;
    $_SESSION['role'] = $data['role'];
    $_SESSION['user'] = $data['username'];
    header("Location: index.php");
  } else {
    echo "<p style='color:red'>Login gagal</p>";
  }
}
?>

<h2>Login Admin</h2>
<form method="POST">
  Username: <input name="username"><br>
  Password: <input type="password" name="password"><br>
  <button type="submit">Login</button>
</form>

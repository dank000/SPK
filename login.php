<?php
session_start();
include 'db.php'; // koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            header("Location: user_home.php");
            exit();
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
  <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: url('img/universitas.png') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-box {
            width: 400px;
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }
        h2 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
            padding-right: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #aaa;
            border-radius: 5px;
            font-size: 14px;
        }
        .remember {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .bottom-link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        .bottom-link a {
            color: #007BFF;
            text-decoration: none;
        }
        .bottom-link a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            text-align: center;
            font-size: 14px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
  <div class="modal">
    <div class="modal-content">
      <h2>Login</h2>
      <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
      <form method="post">
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit" class="banner-btn">Login</button>
      </form>
      <br>
      <a href="register.php">Belum punya akun? Daftar di sini</a>
    </div>
  </div>
</body>
</html>

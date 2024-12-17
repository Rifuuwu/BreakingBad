<?php
session_start();
include '../includes/db.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password' AND role = 'buyer'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: homepage.php');
        exit();
    } else {
        echo "<script>alert('Username atau password salah');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Login</title>
    <link rel="stylesheet" href="../assets/css/asset.css">
</head>
<body>
    <div class="main">
        <div class="judul">
            <h1>Buyer Login</h1>
            <h2>What do you want to buy today?</h2>
        </div>
        <div class="form-2">
            <form action="buyerLogin.php" method="post">
                <div class="input">
                    <div class="input-box">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" required>
                    </div>
                    <div class="input-box">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" required>
                    </div>
                </div>
                <div class="btn-1">
                    <button type="submit">Login</button>
                </div>
            </form>
            <div class="btn-2">
                <button onclick="window.location.href='sellerLogin.php'">Login as Seller</button>
                <button onclick="window.location.href='register.php'">Register</button>
            </div>
        </div>
    </div>
</body>
</html>
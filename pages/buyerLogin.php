<?php
    include '../includes/db.php';
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MethMart - Buyer Login</title>
</head>
<body>
<div class="judul">
        <h1>Buyer Login</h1>
        <h2>what do you want to sell today?</h2>
    </div>
    <div class="form-1">
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
<?php 
    if(isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password = md5($password);
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result) > 0){
            $user = mysqli_fetch_assoc($result);
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header('Location: homepage.php');
        } else {
            echo 'Username atau password salah';
        }
    }
?>
</body>
</html>
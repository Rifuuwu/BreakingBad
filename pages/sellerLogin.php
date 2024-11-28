<?php
    include '../includes/db.php';
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MethMart - Seller Login</title>
</head>
<body>
    <h2>Seller Login</h2>
    <form action="sellerLogin.php" method="post">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Login</button>
    </form>

<?php 
    if(isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result) > 0){
            $user = mysqli_fetch_assoc($result);
            if($user['role'] != 'seller'){
                echo 'Anda bukan seller';
            } else {
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header('Location: dashboard.php');
            }
        }else{
            echo 'Username atau password salah';
        }
    }
?>
</body>
</html>
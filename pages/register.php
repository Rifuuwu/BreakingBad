<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MethMart - Register</title>
</head>
<body>
    <h1>Registrasi disini</h1>

    <form action="register.php" method="post">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
        <label for="role">Role</label>
        <select name="role" id="role">
            <option value="buyer">Buyer</option>
            <option value="seller">Seller</option>
        </select>
        <button type="submit">Register</button>
    </form>
    <button onclick="window.location.href='sellerLogin.php'">Masuk Sebagai Seller</button>
    <button onclick="window.location.href='buyerLogin.php'">Masuk Sebagai Buyer</button>
        <?php
            include '../includes/db.php';
            session_start();
            if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['role'])){
                $username = $_POST['username'];
                $password = $_POST['password'];
                $password = md5($password);
                $role = $_POST['role'];
                $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
                if(mysqli_query($conn, $query)){
                    $user_id = mysqli_insert_id($conn);
                    if($role=='seller'){
                        $squery = "INSERT INTO `sellers` (`seller_id`, `reputation`, `wanted`) VALUES ('$user_id', '0', '0')";
                        mysqli_query($conn, $squery);
                        echo 'Registrasi berhasil';
                    }
                } else {
                    echo 'Registrasi gagal';
                }
            }
        ?>
</body>
</html>
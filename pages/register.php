<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MethMart - Register</title>
</head>
<body>
    <div class="judul">
            <h1>Create New Account</h1>
            <h2>What do you want to be called?</h2>
    </div>
    <div class="form-1">
        <form action="register.php" method="post">
            <div class="input">
                <div class="input-box">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="input-box">
                    <label for="username">Password</label>
                    <input type="text" name="password" id="password" required>
                </div>
                <div class="input-box">
                    <label for="username">Confirm Password</label>
                    <input type="text" name="password" id="password" required>
               </div>
            </div>
            <div class="select">
                <label for="username">Role</label>
                <select name="role" id="role" placeholder="Role" required>
                    <option value="buyer">Buyer</option>
                    <option value="seller">Seller</option>
                </select>
            </div>
            <div class="btn-1">
                <button type="submit">Create New Account</button>
            </div>
        </form>
        <div class="btn-2">
            <button type="submit">Buyer Login</button>
            <button type="submit">Seller Login</button>
        </div>
    </div>
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
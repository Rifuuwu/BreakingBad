<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MethMart - Register</title>
    <link rel="stylesheet" href="../assets/css/asset.css">
</head>
<body>
    <div class="main">
        <div class="judul">
                <h1>Create New Account</h1>
                <h2>What do you want to be called?</h2>
        </div>
        <div class="form-2">
            <form action="register.php" method="post">
                <div class="input">
                    <div class="input-box">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" required>
                    </div>
                    <div class="input-box">
                        <label for="username">Password</label>
                        <input type="password" name="password" id="password" required>
                    </div>
                    <div class="input-box">
                        <label for="username">Confirm Password</label>
                        <input type="password" name="cpassword" id="cpassword" required>
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
                <button onclick="window.location.href='buyerLogin.php'">Buyer Login</button>
                <button onclick="window.location.href='sellerLogin.php'">Seller Login</button>
            </div>
        </div>
    </div>
        <?php
            include '../includes/db.php';
            session_start();
            if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['role']) && isset($_POST['cpassword'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $cpassword = $_POST['cpassword'];
                $role = $_POST['role'];

                // Check if password and confirm password match
                if ($password !== $cpassword) {
                    echo "<script>alert('Password tidak sama dengan confirm password')</script>";
                } else {
                    // Hash the password
                    $hashed_password = md5($password);

                    // Insert user into the users table
                    $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed_password', '$role')";
                    if (mysqli_query($conn, $query)) {
                        $user_id = mysqli_insert_id($conn);

                        if ($role == 'seller') {
                            // Insert seller details into the sellers table
                            $squery = "INSERT INTO sellers (seller_id, reputation, wanted) VALUES ('$user_id', '0', '0')";
                            mysqli_query($conn, $squery);
                            echo '<script>alert("Registrasi berhasil sebagai seller")</script>';
                        } elseif ($role == 'buyer') {
                            // Update user balance for buyers
                            $squery = "UPDATE users SET balance = '1000' WHERE user_id = $user_id";
                            mysqli_query($conn, $squery);
                            echo '<script>alert("Registrasi berhasil sebagai buyer")</script>';
                        }
                    } else {
                        echo '<script>alert("Registrasi gagal")</script>';
                    }
                }
            }
        ?>
</body>
</html>
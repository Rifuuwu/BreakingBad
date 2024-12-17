<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

// Include database connection
include('../includes/db.php');

// Fetch user details
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

function chooseImage($category) {
    // Daftar mapping kategori ke filename image
    $imageMap = [
        'Blue Meth' => 'blueMeth.jpeg',
        'Crystal Meth' => 'crystalMeth.jpeg',
        'Mexican Cartel Meth' => 'mexicanMeth.jpeg',
        'Street Meth' => 'streetMeth.jpeg',
        'Experimental Batch' => 'experimentMeth.jpeg',
    ];

    // Mengembalikan image filename jika kategori ada, jika tidak default image
    return isset($imageMap[$category]) ? $imageMap[$category] : 'defaultImage.jpeg';
}

// Check if the user is a seller
if ($user['role'] !== 'seller') {
    echo "<script>alert('Access denied. Only sellers can access this page.'); window.location.href = 'login.php';</script>";
    exit();
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = $_POST['username'];
    $new_password = $_POST['password'];
    $hashed_password = md5($new_password);

    $update_query = "UPDATE users SET username = '$new_username', password = '$hashed_password' WHERE user_id = $user_id";
    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Profile updated successfully');</script>";
        // Update session username
        $_SESSION['username'] = $new_username;
    } else {
        echo "<script>alert('Error updating profile: " . mysqli_error($conn) . "');</script>";
    }
}

// Fetch user products
$query = "SELECT * FROM products WHERE seller_id = $user_id";
$products = mysqli_query($conn, $query);

if (!$products) {
    echo "Error fetching products: " . mysqli_error($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Profile</title>
    <link rel="stylesheet" href="../assets/css/asset.css">
</head>
<body>
    <?php include('../includes/header.php'); ?>
    <main>
        <div class="profile">
            <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?></h1>
            <p>Role: <?php echo htmlspecialchars($user['role']); ?></p>
            <p>Balance: <?php echo htmlspecialchars($user['balance']);?></p>
            <h2>Update Profile</h2>
            <form action="sellerProfile.php" method="post">
                <div class="input-box">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="input-box">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="btn-1">
                    <button type="submit" class="btn-1">Update Profile</button>
                </div>
            </form>
            <h2>Your Products</h2>
            <div class="seller-products">
                <div class="product-list">
                    <?php while ($product = mysqli_fetch_assoc($products)): ?>
                        <div class="product-card">
                            <?php
                                $image_filename = chooseImage($product['category']);
                            ?>
                            <img src="../assets/image/<?php echo $image_filename; ?>" alt="<?php echo $product['name']; ?>" class="product-image">
                            <h3><?php echo $product['name']; ?></h3>
                            <p>Harga: $<?= htmlspecialchars($product['price']) ?></p>
                            <p>Stok: <?= htmlspecialchars($product['stock']) ?></p>
                            <p>Kategori: <?= htmlspecialchars($product['category']) ?></p>
                            <p>Kemurnian: <?= htmlspecialchars($product['purity']) ?></p>
                            <button class="tombol" onclick="window.location.href='updateProduct.php?product_id=<?= $product['product_id'] ?>'">Update</button>
                            <button class="tombol" onclick="window.location.href='deleteProduct.php?id=<?= $product['product_id'] ?>'">Delete</button>
                        </div>
                    <?php endwhile; ?>
                </div>
                <br><br>
            </div>
        </div>
    </main>
    <?php include('../includes/footer.php'); ?>
</body>
</html>
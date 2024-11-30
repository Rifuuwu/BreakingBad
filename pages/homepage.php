<?php
    include '../includes/db.php';
    session_start();

    if (!isset($_SESSION['role'])) {
        header('Location: login.php');
        exit();
    }

    $role = $_SESSION['role']; 
    $query = "SELECT * FROM products"; 
    $result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MethMart - Home</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <h1>Welcome to MethMart!</h1>

    <?php if ($role === 'seller'): ?>
        <button style="position: fixed; bottom: 20px; right: 20px;" onclick="window.location.href='addProduct.php'">
            Tambah Produk
        </button>
    <?php endif; ?>

    <div class="product-list">
        <?php while ($product = mysqli_fetch_assoc($result)): ?>
            <div class="product-card">
                <h2><?= htmlspecialchars($product['name']) ?></h2>
                <p>Harga: <?= htmlspecialchars($product['price']) ?></p>
                <p>Stok: <?= htmlspecialchars($product['stock']) ?></p>
                <p>Kategori: <?= htmlspecialchars($product['category']) ?></p>
                <p>Kemurnian: <?= htmlspecialchars($product['purity']) ?></p>
                

                <?php if ($role === 'buyer'): ?>
                    <button onclick="window.location.href='productDetail.php?id=<?= $product['product_id'] ?>'">Beli</button>
                <?php endif; ?>


                <?php if ($role === 'seller'): ?>
                    <p style="color: red;">(Anda adalah penjual, fitur beli tidak tersedia)</p>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>

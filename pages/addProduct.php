<?php
    include '../includes/db.php';
    session_start();

    // Cek apakah user adalah seller
    if ($_SESSION['role'] !== 'seller') {
        header('Location: homepage.php'); // Redirect jika bukan seller
        exit();
    }

    $username = $_SESSION['username'];

    // Ambil user_id seller berdasarkan username
    $query = "SELECT user_id FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    $seller_id = $user['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $desc = $_POST['desc'];
        $stock = $_POST['stock'];
        $category = $_POST['category'];
        $purity = $_POST['purity'];

        $query = "INSERT INTO products (seller_id, name, price, p_desc, stock, category, purity) 
                VALUES ('$seller_id', '$name', '$price', '$desc', '$stock', '$category', '$purity')";
        if (mysqli_query($conn, $query)) {
            echo "Produk berhasil ditambahkan!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
</head>
<body>
    <h1>Tambah Produk Baru</h1>
    <form method="POST" action="">
        <label for="name">Nama Produk:</label>
        <input type="text" name="name" id="name" required>
        <label for="price">Harga:</label>
        <input type="number" name="price" id="price" required>
        <label for="desc">Deskripsi:</label>
        <textarea name="desc" id="desc" required></textarea>
        <label for="stock">Stok:</label>
        <input type="number" name="stock" id="stock" required>
        <label for="category">Kategori</label>
        <select name="category" id="category" required>
            <option value="Blue Meth">Blue Meth</option>
            <option value="Crystal Meth">Crystal Meth</option>
            <option value="Mexican Cartel Meth">Mexican Cartel Meth</option>
            <option value="Street Meth">Street Meth</option>
            <option value="Experimental Batch">Experimental Batch</option>
        </select>
        <br>

        <label for="purity">Kemurnian</label>
        <select name="purity" id="purity" required>
            <option value="70">70%</option>
            <option value="85">85%</option>
            <option value="92">92%</option>
            <option value="96">96%</option>
            <option value="99">99%</option>
        </select>
        <br>
        <button type="submit">Tambahkan</button>
    </form>

    <button onclick="window.location.href='homepage.php';">Kembali ke Homepage</button>
</body>
</html>

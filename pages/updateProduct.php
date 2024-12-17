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

    // Ambil product_id dari parameter URL
    $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

    // Fetch product details
    $query = "SELECT * FROM products WHERE product_id = $product_id AND seller_id = $seller_id";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);

    if (!$product) {
        echo "<script>alert('Product not found or you do not have permission to edit this product.'); window.location.href = 'homepage.php';</script>";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $desc = $_POST['desc'];
        $stock = $_POST['stock'];
        $category = $_POST['category'];
        $purity = $_POST['purity'];

        $query = "UPDATE products SET name = ?, price = ?, p_desc = ?, stock = ?, category = ?, purity = ? WHERE product_id = ? AND seller_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sdisssii', $name, $price, $desc, $stock, $category, $purity, $product_id, $seller_id);

        if ($stmt->execute()) {
            echo "<script>alert('Product updated successfully'); window.location.href = 'sellerProfile.php';</script>";
        } else {
            echo "<script>alert('Error updating product: " . $conn->error . "');</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link rel="stylesheet" href="../assets/css/asset.css">
</head>
<body>
    <?php include('../includes/header.php'); ?>
    <main>
        <div class="update">
            <h1>Update Product</h1>
            <form class="form-2" action="updateProduct.php?product_id=<?php echo $product_id; ?>" method="post">
                <div class="input-box">
                    <label for="name">Product Name</label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>
                <div class="input-box">
                    <label for="price">Price</label>
                    <input type="number" step="0.01" name="price" id="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                </div>
                <div class="input-box">
                    <label for="desc">Description</label>
                    <textarea name="desc" id="desc" required><?php echo htmlspecialchars($product['p_desc']); ?></textarea>
                </div>
                <div class="input-box">
                    <label for="stock">Stock</label>
                    <input type="number" name="stock" id="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required>
                </div>
                <div class="input-box">
                    <label for="category">Category</label>
                    <div class="select">
                        <select name="category" id="category" required>
                            <option value="Blue Meth">Blue Meth</option>
                            <option value="Crystal Meth">Crystal Meth</option>
                            <option value="Mexican Cartel Meth">Mexican Cartel Meth</option>
                            <option value="Street Meth">Street Meth</option>
                            <option value="Experimental Batch">Experimental Batch</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="input-box">
                    <label for="purity">Purity</label>
                    <div class="select">
                        <select name="purity" id="purity" required>
                            <option value="70">70%</option>
                            <option value="85">85%</option>
                            <option value="92">92%</option>
                            <option value="96">96%</option>
                            <option value="99">99%</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="btn-1">
                    <button type="submit">Update Product</button>
                </div>
            </form>
            <div class="btn-1">
            <button onclick="window.location.href='homepage.php';">Kembali ke Homepage</button>
            </div>
        </div>
    </main>
    <?php include('../includes/footer.php'); ?>
</body>
</html>

<?php
    include '../includes/db.php';
    include '../includes/header.php';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['role'])) {
        header('Location: login.php');
        exit();
    }

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


    $role = $_SESSION['role']; 

    // Get the current page number from the query string, default to 1 if not set
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $items_per_page = 8;
    $offset = ($page - 1) * $items_per_page;

    // Fetch the total number of products
    $total_items_query = "SELECT COUNT(*) as count FROM products";
    $total_items_result = $conn->query($total_items_query);
    $total_items = $total_items_result->fetch_assoc()['count'];
    $total_pages = ceil($total_items / $items_per_page);

    // Fetch the products for the current page
    $query = "SELECT p.*, u.username as seller_name 
              FROM products p 
              JOIN users u ON p.seller_id = u.user_id 
              LIMIT $items_per_page OFFSET $offset";
    $result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="../assets/css/asset.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main>
        <div class="content">
            <h1>Welcome to MethMart!</h1>
    
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'seller'): ?>
                <button class="add-btn" onclick="window.location.href='addProduct.php'">
                    Tambah Produk
                </button>
            <?php endif; ?>
    
            <div class="product-list">
                <?php while ($product = $result->fetch_assoc()): ?>
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
                        <p>Seller: <?= htmlspecialchars($product['seller_name']) ?></p>
                    
                        
    
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'buyer'): ?>
                            <button class="tombol" onclick="window.location.href='productDetail.php?id=<?= $product['product_id'] ?>'">Detail</button>
                        <?php endif; ?>
    
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>">Previous</a>
                <?php endif; ?>
        
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" <?php if ($i == $page) echo 'class="active"'; ?>><?php echo $i; ?></a>
                <?php endfor; ?>
        
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>">Next</a>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>

<?php
session_start();
include '../includes/db.php';


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


$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details from database
$query = "SELECT p.product_id, p.name AS product_name, p.p_desc AS description, p.price, p.stock, p.seller_id, p.category, u.username AS seller_name 
          FROM products p 
          JOIN users u ON p.seller_id = u.user_id 
          WHERE p.product_id = $product_id";

$result = $conn->query($query);

if (!$result) {
    echo "Error: " . $conn->error;
    exit;
}

$product = $result->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = intval($_POST['quantity']);
    $total_price = $quantity * $product['price'];
    $buyer_id = $_SESSION['user_id'];
    $seller_id = $product['seller_id'];

    // Check buyer's balance
    $balance_query = "SELECT balance FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($balance_query);
    $stmt->bind_param('i', $buyer_id);
    $stmt->execute();
    $balance_result = $stmt->get_result();
    $buyer = $balance_result->fetch_assoc();

    if ($buyer['balance'] < $total_price) {
        echo "<script>alert('Insufficient balance.'); window.location.href = 'productDetail.php?id=$product_id';</script>";
        exit;
    }

    // Insert transaction
    $insert_query = "INSERT INTO transactions (buyer_id, product_id, quantity, total, tr_date, status) VALUES (?, ?, ?, ?, NOW(), 'done')";
    $stmt = $conn->prepare($insert_query);

    if ($stmt === false) {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }

    $stmt->bind_param('iiid', $buyer_id, $product_id, $quantity, $total_price);

    if ($stmt->execute()) {
        // Update product stock
        $new_stock = $product['stock'] - $quantity;
        $update_query = "UPDATE products SET stock = ? WHERE product_id = ?";
        $stmt = $conn->prepare($update_query);

        if ($stmt === false) {
            echo "Error preparing statement: " . $conn->error;
            exit;
        }

        $stmt->bind_param('ii', $new_stock, $product_id);
        $stmt->execute();

        // Update seller balance
        $update_balance_query = "UPDATE users SET balance = balance + ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_balance_query);

        if ($stmt === false) {
            echo "Error preparing statement: " . $conn->error;
            exit;
        }

        $stmt->bind_param('di', $total_price, $seller_id);
        $stmt->execute();

        // Update buyer balance
        $update_buyer_balance_query = "UPDATE users SET balance = balance - ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_buyer_balance_query);

        if ($stmt === false) {
            echo "Error preparing statement: " . $conn->error;
            exit;
        }

        $stmt->bind_param('di', $total_price, $buyer_id);
        $stmt->execute();

        echo "<script>alert('Purchase successful!'); window.location.href = 'homepage.php';</script>";
    } else {
        echo "<script>alert('Error processing transaction: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>
    <link rel="stylesheet" href="../assets/css/asset.css">
    <script>
        function updateTotalPrice() {
            const price = parseFloat(document.getElementById('price').innerText);
            const quantity = parseInt(document.getElementById('quantity').value);
            const totalPrice = price * quantity;
            document.getElementById('total-price').innerText = 'Total: $' + totalPrice.toFixed(2);
        }
    </script>
</head>
<body>
    <?php include '../includes/header.php'; ?>
        <div class="details">
        <?php
            $image_filename = chooseImage($product['category']);
        ?> 
        <img src="../assets/image/<?php echo $image_filename;?>">
            <div class="details-info">
                <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <p>Price: $<span id="price"><?php echo htmlspecialchars($product['price']); ?></span></p>
                <p>Stock: <?php echo htmlspecialchars($product['stock']); ?></p>
                <p>Seller: <?php echo htmlspecialchars($product['seller_name']); ?></p>
            </div>
            <div class="details-form">
                <form action="productDetail.php?id=<?php echo $product_id; ?>" method="post">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" onchange="updateTotalPrice()" required>
                    <p class="total-price" id="total-price">Total: $<?php echo htmlspecialchars($product['price']); ?></p>
                    <button type="submit">Buy</button>
                </form>
            </div>
        </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
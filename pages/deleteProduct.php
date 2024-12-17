<?php
// Include database connection file
include_once '../includes/db.php';
session_start();

// Check if the user is logged in and is a seller or admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'seller' && $_SESSION['role'] !== 'admin')) {
    echo "<script>alert('Access denied. Only sellers or admins can delete products.'); window.location.href = 'homepage.php';</script>";
    exit();
}

// Check if the id parameter is set
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepare the SQL delete statement
    $query = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('Product deleted successfully.'); window.location.href = 'sellerProfile.php';</script>";
    } else {
        echo "<script>alert('Error deleting product: " . $conn->error . "'); window.location.href = 'sellerProfile.php';</script>";
    }

    // Close the statement
    $stmt->close();
} else {
    echo "<script>alert('No product id provided.'); window.location.href = 'sellerProfile.php';</script>";
}

// Close the database connection
$conn->close();
?>
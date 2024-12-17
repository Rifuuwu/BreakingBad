<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$dashboard_link = 'login.php'; // Default link if not logged in

if (isset($_SESSION['role'])) {
    $role = $_SESSION['role'];
    // Set the dashboard link based on the user's role
    if ($role === 'seller') {
        $dashboard_link = 'sellerProfile.php';
    } elseif ($role === 'buyer') {
        $dashboard_link = 'buyerProfile.php';
    }
}
?>
<header>
    <div class="container">
        <h1>MethMart</h1>
        <nav>
            <ul>
                <li><a href="../pages/aboutUs.php">About Us</a></li>
                <li><a href="../pages/contact.php">Contact Us</a></li>
                <li><a href="../pages/homepage.php">Products</a></li>
                <li><a href="<?php echo $dashboard_link; ?>">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>
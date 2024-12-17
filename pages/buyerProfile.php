<?php
session_start();
require_once '../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Fetch user details from database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $new_username = $_POST['username'];
    $new_password = $_POST['password'];
    $hashed_password = md5($new_password);

    $update_query = "UPDATE users SET username = ?, password = ? WHERE user_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param('ssi', $new_username, $hashed_password, $user_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Profile updated successfully');</script>";
        // Update session username
        $_SESSION['username'] = $new_username;
    } else {
        echo "<script>alert('Error updating profile: " . $conn->error . "');</script>";
    }
}

// Handle balance top-up
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['topup_balance'])) {
    $topup_amount = $_POST['topup_amount'];
    $new_balance = $user['balance'] + $topup_amount;

    $topup_query = "UPDATE users SET balance = ? WHERE user_id = ?";
    $topup_stmt = $conn->prepare($topup_query);
    $topup_stmt->bind_param('di', $new_balance, $user_id);

    if ($topup_stmt->execute()) {
        echo "<script>alert('Balance topped up successfully');</script>";
        // Update user balance
        $user['balance'] = $new_balance;
    } else {
        echo "<script>alert('Error topping up balance: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Profile</title>
    <link rel="stylesheet" href="../assets/css/asset.css">
</head>
<body>
    <?php include('../includes/header.php'); ?>
    <main>
        <div class="buyer-profile">
            <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?></h1>
            <p>Role: <?php echo htmlspecialchars($user['role']); ?></p>
            <p>Balance: <?php echo htmlspecialchars($user['balance']); ?></p>
            <h2>Update Profile</h2>
            <form action="buyerProfile.php" method="post">
                <input type="hidden" name="update_profile" value="1">
                <div class="input-box">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="input-box">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="btn-1">
                    <button type="submit">Update Profile</button>
                </div>
            </form>
            <h2>Top Up Balance</h2>
            <form action="buyerProfile.php" method="post" class>
                <input type="hidden" name="topup_balance" value="1">
                <div class="input-box">
                    <label for="topup_amount">Amount</label>
                    <input type="number" name="topup_amount" id="topup_amount" required>
                </div>
                <div class="btn-1">
                    <button type="submit">Top Up</button>
                </div>
            </form>
        </div>
    </main>
    <?php include('../includes/footer.php'); ?>
</body>
</html>
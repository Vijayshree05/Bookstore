<?php
session_start();
include 'db_connection.php';

if(isset($_SESSION['username'])) {
    $loggedIn = true;
    $username = $_SESSION['username'];
    $userId = $_SESSION['id'];
} else {
    header("Location: login.php");
    exit;
}

$sql = "SELECT book, quantity, price FROM orders WHERE user_id = $userId";
$result = $conn->query($sql);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Order History</title>
    <link href="styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="nav">
    <h1 >YOUR ORDERS</h1>
</div>
<header class="header">
    <div class="nav">
        <a href="home.php">Home</a>|<a href="all_books.php">Books</a> | <a href="fav.php">Favorites</a> |
        <a href="history.php">Order history</a>
    </div>
    <div class="button-container">
        <?php if($loggedIn): ?>
            <button type="button" class="logout">Logout</button>
        <?php else: ?>
            <button type="button" class="login">Login</button>
            <button type="button" class="register">Register</button>
        <?php endif; ?>
    </div>
</header>

<section class="header-content">
    <?php if($loggedIn): ?>
        <h2>Welcome to Book Emporium, <?php echo $username;?> </h2>
        <p>Your Order History.</p>
    <?php else: ?>
        <h2>Welcome to Book Emporium</h2>
        <p>Your Order History.</p>
    <?php endif; ?>
</section>

<section class="container">
    <table>
        <thead>
            <tr>
                <th>Book</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo $row['book']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="3">No orders placed yet.</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</section>

<footer class="footer">
    <section class="contact-info">
        <h2>Contact Information</h2>
        <p>Email: info@bookstore.com</p>
        <p>Phone: +91 9942128460</p>
    </section>
</footer>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const buyFlowersButton = document.querySelector('.logout');
        buyFlowersButton.addEventListener('click', function() {
            window.location.href = 'logout.php';
        });
    });
    document.addEventListener("DOMContentLoaded", function() {
        const loginButton = document.querySelector('.login');
        loginButton.addEventListener('click', function() {
            window.location.href = 'login.php';
        });
        const registerButton = document.querySelector('.register');
        registerButton.addEventListener('click', function() {
            window.location.href = 'register.php';
        });
    });
</script>

</body>
</html>
<?php
$conn->close(); // Close database connection
?>

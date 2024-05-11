<?php
session_start();


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['book'])) {
    $bookTitle = $_GET['book'];
} else {
    
    header("Location: home.php");
    exit;
}


include 'db_connection.php';


$sql = "SELECT price FROM books WHERE title = '$bookTitle'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $bookPrice = $row['price'];
} else {
   
    header("Location: home.php");
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form</title>
    <link href="styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <h1>Order Form</h1>
    <form action="submit_order.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $_SESSION['username']; ?>" required><br><br>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br><br>
        <label for="contact">Contact No:</label>
        <input type="text" id="contact" name="contact" required><br><br>
        <label for="book">Book:</label>
        <input type="text" id="book" name="book" value="<?php echo $bookTitle; ?>" readonly><br><br>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="1" min="1" required>
        <button type="button" onclick="decreaseQuantity()">-</button>
        <button type="button" onclick="increaseQuantity()">+</button><br><br>
        <input type="hidden" id="book_price" name="book_price" value="<?php echo $bookPrice; ?>">
        <label>Total Price: Rs. <span id="total_price">0</span></label><br><br>
        <label>Payment Mode:</label><br>
        <input type="radio" id="cash" name="payment" value="Cash" checked>
        <label for="cash">Cash on delivery</label><br>
       
        <input type="submit" value="Place Order">
    </form>

    <script>
    function calculatePrice() {
        var quantity = document.getElementById('quantity').value;
        var bookPrice = parseFloat(document.getElementById('book_price').value); 
        var totalPrice = quantity * bookPrice;
        document.getElementById('total_price').innerText = totalPrice;
    }

    function decreaseQuantity() {
        var quantityInput = document.getElementById('quantity');
        if (quantityInput.value > 1) {
            quantityInput.value--;
            calculatePrice();
        }
    }

    function increaseQuantity() {
        var quantityInput = document.getElementById('quantity');
        quantityInput.value++;
        calculatePrice();
    }

    
    document.addEventListener('DOMContentLoaded', function() {
        calculatePrice();
    });
    </script>

</body>
</html>

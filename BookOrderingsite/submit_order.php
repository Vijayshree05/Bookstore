<?php
session_start();
include 'db_connection.php';


if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];
} else {
    
    if (isset($_SESSION['register_id'])) {
    
        $userId = $_SESSION['register_id'];
    } else {
      
        header("Location: login.php");
        exit;
    }
}


$name = $_POST['name'];
$address = $_POST['address'];
$contact = $_POST['contact'];
$book = $_POST['book'];
$quantity = $_POST['quantity'];
$payment = $_POST['payment'];


$fetchPriceSql = "SELECT price FROM books WHERE title = '$book'";
$priceResult = $conn->query($fetchPriceSql);
if ($priceResult->num_rows > 0) {
    $row = $priceResult->fetch_assoc();
    $bookPrice = $row['price'];
} else {
 
    echo "Error: Book price not found.";
    exit;
}


$totalPrice = $quantity * $bookPrice;

$sql = "INSERT INTO orders (user_id, name, address, contact, book, quantity, payment, price) 
        VALUES ('$userId', '$name', '$address', '$contact', '$book', $quantity, '$payment', $totalPrice)";

if ($conn->query($sql) === TRUE) {
  
        // $_SESSION['order_placed'] = true;
        echo '<script>alert("Order placed successfully!")</script>';
         header("Location: home.php");
    // exit;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

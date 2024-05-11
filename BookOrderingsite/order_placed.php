<?php
session_start();


if (isset($_SESSION['order_placed']) && $_SESSION['order_placed']) {
    echo "<script>alert('Order placed successfully!');</script>";
    unset($_SESSION['order_placed']); 
}
?>

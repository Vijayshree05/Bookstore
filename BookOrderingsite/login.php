<?php
session_start();

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id FROM Users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
    
        $row = $result->fetch_assoc();
        $id = $row['id'];

        
        $_SESSION['username'] = $username;
        $_SESSION['id'] = $id;

   
        header("Location: home.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">

    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    
    <form action="" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>

</body>
</html>

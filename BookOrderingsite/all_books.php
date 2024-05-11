<?php
session_start();
include 'db_connection.php';

if(isset($_SESSION['username'])) {
    $loggedIn = true;
    $username = $_SESSION['username'];
    $userId = $_SESSION['id'];
} else {
    $loggedIn = false;
}

if(isset($_POST['add_to_favorites']) && isset($_POST['book_id'])) {
    $bookId = $_POST['book_id'];

    $checkFavorite = "SELECT * FROM Favorites WHERE user_id = $userId AND book_id = $bookId";
    $checkResult = $conn->query($checkFavorite);

    if($checkResult->num_rows == 0) {
        $addToFavorites = "INSERT INTO Favorites (user_id, book_id) VALUES ($userId, $bookId)";
        if($conn->query($addToFavorites)) {
            echo "Book added to favorites successfully!";
        } else {
            echo "Error adding book to favorites!";
        }
    } else {
        echo "Book already added to favorites!";
    }
    exit;
}

$sql = "SELECT * FROM books";
$result = $conn->query($sql);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>All Books - Bookshop Website</title>
    <link href="styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="nav">
    <h1 >Book Emporium</h1></div>
  <header class="header">
       <div class="nav">
          <a href="home.php">Home</a>|<a href="all_books.php">Books</a> | <a href="fav.php">Favorites</a> |
        <a href="history.php">Order history</a></div>
       
        <div class="button-container">
        <?php if($loggedIn): ?>
         
        <button type="button" class="logout">Logout</button>
        <?php else: ?>
                <button type="button" class="login">Login</button>
                <button type="button" class="register">Register</button>
            <?php endif; ?></div>
</header>

    <section class="header-content">
    <?php if($loggedIn): ?>
      <h2>Welcome to Book Emporium , <?php echo $username;?> </h2>
      <p>
      "Unlock the door to endless worlds with every book purchase at Book Emporium."
        </p> 
      <?php else:?>
        <h2>Welcome to Book Emporium</h2>
        <p>
        "Unlock the door to endless worlds with every book purchase at Book Emporium."
        </p> <?php endif; ?>
    </section>
    <h2>Featured Books</h2>
    <section class="container">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
        ?>
            <div class="book">
                <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['title']; ?>" />
                <h3><?php echo $row['title']; ?></h3>
                <p>Author: <?php echo $row['author']; ?></p>
                <p>Price: Rs. <?php echo $row['price']; ?></p>
                
                <button class="add-to-favorites" data-book-id="<?php echo $row['id']; ?>">Add to Favorites</button>
                <button class="buy-now" onclick="redirectToOrders('<?php echo $row['title']; ?>')">Buy Now</button>
            </div>
        <?php
            }
        } else {
            echo "No books found";
        }
        ?>
    </section>

    <section class="contact-info">
        <h2>Contact Information</h2>
        <p>Email: info@bookstore.com</p>
        <p>Phone: +91 9942128460</p>
    </section>

    <script>
        function redirectToOrders(bookTitle) {
            window.location.href = 'orders.php?book=' + encodeURIComponent(bookTitle);
        }

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

        document.addEventListener("DOMContentLoaded", function() {
            const buyFlowersButton = document.querySelector('.logout');

            buyFlowersButton.addEventListener('click', function() {
                window.location.href = 'logout.php';  
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const addToFavoritesButtons = document.querySelectorAll('.add-to-favorites');

            addToFavoritesButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const bookId = this.getAttribute('data-book-id');

                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'home.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            alert(xhr.responseText);
                        } else {
                            alert('Error adding book to favorites!');
                        }
                    };
                    xhr.send('add_to_favorites=true&book_id=' + encodeURIComponent(bookId));
                });
            });
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>

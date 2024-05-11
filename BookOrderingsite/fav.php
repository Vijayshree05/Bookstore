

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

if(isset($_POST['remove_from_favorites']) && isset($_POST['book_title'])) {
    $bookTitle = $_POST['book_title'];

    $removeFromFavorites = "DELETE FROM Favorites WHERE user_id = $userId AND book_id = (SELECT id FROM books WHERE title = '$bookTitle')";
    if($conn->query($removeFromFavorites)) {
        echo "Book removed from favorites successfully!";
    } else {
        echo "Error removing book from favorites!";
    }
    exit;
}

$sql = "SELECT b.title, b.author, b.price, b.image_url FROM Favorites f JOIN books b ON f.book_id = b.id WHERE f.user_id = $userId";
$result = $conn->query($sql);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Favorite Books</title>
    <link href="styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="nav">
    <h1 >YOUR FAVORITES</h1></div>
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
            <?php endif; ?>
        </div>
    </header>

    <section class="header-content">
        <?php if($loggedIn): ?>
            <h2>Welcome to Book Emporium , <?php echo $username;?> </h2>
            <p>
            "Unlock the door to endless worlds with every book purchase at Book Emporium."
            </p>
        <?php else: ?>
            <h2>Welcome to Book Emporium</h2>
            <p>
            "Unlock the door to endless worlds with every book purchase at Book Emporium."
            </p>
        <?php endif; ?>
    </section>

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
                    <button class="buy-now" onclick="redirectToOrders('<?php echo $row['title']; ?>')">Buy Now</button>
                    <button class="remove" onclick="removeFromFav('<?php echo $row['title']; ?>')">Remove</button>
                </div>
                <?php
            }
        } else {
            echo "<p>No books added to favorites.</p>";
        }
        ?>
    </section>

    <footer class="footer">
        <section class="contact-info">
            <h2>Contact Information</h2>
            <p>Email: info@bookstore.com</p>
            <p>Phone: +91 9942128460</p>
        </section>
    </footer>

    <script>
        function redirectToOrders(bookTitle) {
            window.location.href = 'orders.php?book=' + encodeURIComponent(bookTitle);
        }

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
        document.addEventListener("DOMContentLoaded", function() {
            const removeFromFavoritesButtons = document.querySelectorAll('.remove');
            removeFromFavoritesButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const bookTitle = this.parentNode.querySelector('h3').textContent;

                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'fav.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            alert(xhr.responseText);
                            window.location.reload();
                        } else {
                            alert('Error removing book from favorites!');
                        }
                    };
                    xhr.send('remove_from_favorites=true&book_title=' + encodeURIComponent(bookTitle));


                });
            });
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>
```
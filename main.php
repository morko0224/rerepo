<?php
session_start();

$host = "localhost";
$dbusername = "mika";
$dbpassword = "qwerty12345";
$database = "databass";

$connection = mysqli_connect($host, $dbusername, $dbpassword, $database);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM posts";
$result = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Main Page - Table of Contents</title>
</head>
<body>
    <h1>Welcome to the Main Page</h1>
    <p>This is the main page content.</p>

    <h2>Bulletin Boards</h2>
    
    <form action="search.php" method="get">
        <input type="text" name="search_query" placeholder="Search bulletin boards">
        <button type="submit">Search</button>
    </form>

    <ul>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li><a href=\"view_post.php?id=" . $row['id'] . "\">" . htmlspecialchars($row['title']) . "</a></li>";
        }
        ?>
    </ul>

    <ul>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li><a href=\"view_post.php?id=" . $row['id'] . "\">" . htmlspecialchars($row['title']) . "</a></li>";
        }
        ?>
    </ul>

    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) { ?>
        <p><a href="create_board.php">Create a New Bulletin Board</a></p>
        <p><a href="logout.php">Logout</a></p>
    <?php } else { ?>
        <p><a href="login.php">Login</a></p>
    <?php } ?>

</body>
</html>

<?php
mysqli_close($connection);
?>
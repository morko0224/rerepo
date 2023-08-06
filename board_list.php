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

$sql = "SELECT * FROM bulletin_boards";
$result = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bulletin Board List</title>
</head>
<body>
    <h1>Bulletin Board List</h1>

    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) { ?>
        <p><a href="create_board.php">Create a New Bulletin Board</a></p>
        <p><a href="?logout">Logout</a></p>
    <?php } else { ?>
        <p><a href="login.php">Login</a></p>
    <?php } ?>

    <ul>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <li><a href="view_board.php?id=<?php echo $row['board_id']; ?>"><?php echo htmlspecialchars($row['board_name']); ?></a></li>
        <?php } ?>
    </ul>

    <p><a href="main.php">Back to Main Page</a></p>
</body>
</html>

<?php
mysqli_close($connection);
?>

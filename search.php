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

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['search_query'])) {
    $search_query = mysqli_real_escape_string($connection, $_GET['search_query']);
    
    $sql = "SELECT * FROM posts WHERE title LIKE '%$search_query%' OR content LIKE '%$search_query%'";
    $result = mysqli_query($connection, $sql);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
</head>
<body>
    <h1>Search Results</h1>
    
    <?php if (mysqli_num_rows($result) > 0) { ?>
        <ul>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <li><a href="view_post.php?id=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['title']); ?></a></li>
            <?php } ?>
        </ul>
    <?php } else { ?>
        <p>No results found.</p>
    <?php } ?>

    <p><a href="main.php">Back to Main Page</a></p>
</body>
</html>

<?php
mysqli_close($connection);
?>

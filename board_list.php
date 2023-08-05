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
function escape($value)
{
        global $connection;
        return mysqli_real_escape_string($connection, $value);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['search_query'])) {
                $search_query = $_POST['search_query'];
                $sql = "SELECT * FROM posts WHERE title LIKE '%$search_query%' OR content LIKE '%$search_query%' ORDER BY date_posted DESC";
        } else {
                $sql = "SELECT * FROM posts ORDER BY date_posted DESC";
        }
} else {
        $sql = "SELECT * FROM posts ORDER BY date_posted DESC";
}

$result = mysqli_query($connection, $sql);
if (isset($_GET['logout'])) {
        session_destroy();
        header("Location: login.php");
        exit;
}
?>

<!DOCTYPE html>
<html>
<head>
        <title>Board List</title>
</head>
<body>

        <p><a href="create_board.php">Create a New Post</a></p>
        <h1>Board List</h1>
        <form action="board_list.php" method="GET">
                <input type="text" name="search" placeholder="Search for posts">
                <input type="submit" value="Search">
        </form>

        <table>
                <tr>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Posted By</th>
                        <th>Date Posted</th>
                        <th>View Comments</th>
                </tr>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['content']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['date_posted']) . "</td>";
                        echo "<td><a href=\"view_comments.php?id=" . $row['id'] . "\">View Comments</a></td>";
                        echo "</tr>";
                }
                ?>
        </table>
        <?php mysqli_close($connection);?>
        <?php
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
                echo '<p><a href="?logout=true">Logout</a></p>';
        } else {
                echo '<p><a href="login.php">Login</a></p>';
        }
        ?>

</body>
</html>

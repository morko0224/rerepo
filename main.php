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
function escape($value){
        global $connection;
        return mysqli_real_escape_string($connection,$value);
}

if($_SERVER["REQUEST_METHOD"]=="POST"){
        if(isset($_POST['comment']) && isset($_POST['post_id'])){
                $user_id = $_SESSION['user_id'];
                $post_id = $_POST['post_id'];
                $content = escape($_POST['comment']);
                $sql = "INSERT INTO comments (post_id, user_id, content) VALUES ('$post_id', '$user_id', '$content')";
                if (mysqli_query($connection, $sql)) {
                        echo "Comment added successfully!";
                } else {
                        echo "Error adding comment: " . mysqli_error($connection);
                }
        }
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

        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) { ?>
                <form action="create_comment.php" method="post">
                        <input type="hidden" name="post_id" value="">
                        <textarea name="comment" rows="4" required></textarea>
                        <br>
                        <input type="submit" value="Add Comment">
                </form>
        <?php } else { ?>
                <p>You need to <a href="login.php">login</a> to add comments.</p>
        <?php } ?>

        <h2>Table of Contents</h2>
        <table>
                <tr>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Posted By</th>
                        <th>Comments</th>
                </tr>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {

                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['content']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td><a href=\"view_comments.php?id=" . $row['id'] . "\">View Comments</a></td>";
                        echo "</tr>";
                }
                ?>
        </table>

        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) { ?>
                <p><a href="create_board.php">Create a New Post</a></p>
                <p><a href="?logout">Logout</a></p>
        <?php } else { ?>
                <p><a href="login.php">Login</a></p>
        <?php } ?>

        <p><a href="board_list.php">Go to Post List</a></p>

</body>
</html>

<?php
mysqli_close($connection);
?>


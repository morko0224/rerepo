<?php
$host = "localhost";
$dbusername = "mika";
$dbpassword = "qwerty12345";
$database = "databass";

$connection = mysqli_connect($host, $dbusername, $dbpassword, $database);
if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id = $_POST['id'];

        $sql = "DELETE FROM posts WHERE id='$id'";
        if (mysqli_query($connection, $sql)) {
                echo "Post deleted successfully!";
        } else {
                echo "Error deleting post: " . mysqli_error($connection);
        }
} else {
        $id = $_GET['id'];

        $sql = "SELECT * FROM posts WHERE id='$id'";
        $result = mysqli_query($connection, $sql);

        if (mysqli_num_rows($result) === 0) {
                die("Post not found.");
        }
        $post = mysqli_fetch_assoc($result);
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html>
<head>
        <title>Delete Post</title>
</head>
<body>
        <h1>Delete Post</h1>
        <p>Are you sure you want to delete this post?</p>
        <p><strong>Title:</strong> <?php echo htmlspecialchars($post['title']); ?></p>
        <p><strong>Content:</strong> <?php echo htmlspecialchars($post['content']); ?></p>
        <form action="delete_post.php" method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($post['id']); ?>">
                <input type="submit" value="Delete">
        </form>
        <a href="board_list.php">Cancel and Back to Board List</a>
</body>
</html>

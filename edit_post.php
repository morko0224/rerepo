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
        $title = $_POST['title'];
        $content = $_POST['content'];

        $sql = "UPDATE posts SET title='$title', content='$content' WHERE id='$id'";
        if (mysqli_query($connection, $sql)) {
                echo "Post updated successfully!";
        } else {
                echo "Error updating post: " . mysqli_error($connection);
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
        <title>Edit Post</title>
</head>
<body>
        <h1>Edit Post</h1>
        <form action="edit_post.php" method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($post['id']); ?>">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                <br>
                <label for="content">Content:</label>
                <textarea name="content" id="content" rows="4" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                <br>
                <input type="submit" value="Update">
        </form>
        <a href="board_list.php">Back to Board List</a>
</body>
</html>
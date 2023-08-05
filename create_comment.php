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
        $post_id = $_POST['post_id'];
        $content = $_POST['content'];

        $sql = "INSERT INTO comments (post_id, content) VALUES ('$post_id', '$content')";
        if (mysqli_query($connection, $sql)) {
                echo "Comment added successfully!";
        } else {
                echo "Error adding comment: " . mysqli_error($connection);
        }
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html>
<head>
        <title>Create Comment</title>
</head>
<body>
        <h1>Create Comment</h1>
        <form action="create_comment.php" method="post">
                <input type="hidden" name="post_id" value="<?php echo $_GET['id']; ?>">
                <label for="content">Comment:</label>
                <textarea name="content" id="content" rows="4" required></textarea>
                <br>
                <input type="submit" value="Submit">
        </form>
        <a href="board_list.php">Back to Board List</a>
</body>
</html>


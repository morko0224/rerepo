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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $comment_id = $_POST['comment_id'];
        $content = $_POST['content'];
        $sql = "UPDATE comments SET content='$content' WHERE id='$comment_id'";
        if (mysqli_query($connection, $sql)) {
                echo "Comment updated successfully!";
        } else {
                echo "Error updating comment: " . mysqli_error($connection);
        }
}

if (isset($_GET['id'])) {
        $comment_id = $_GET['id'];

        $sql = "SELECT * FROM comments WHERE id='$comment_id'";
        $result = mysqli_query($connection, $sql);
        $row = mysqli_fetch_assoc($result);

        if ($row['user_id'] === $_SESSION['user_id']) {
                ?>
                <!DOCTYPE html>
                <html>
                <head>
                        <title>Edit Comment</title>
                </head>
                <body>
                        <h1>Edit Comment</h1>
                        <form action="edit_comment.php" method="post">
                                <input type="hidden" name="comment_id" value="<?php echo $comment_id; ?>">
                                <label for="content">Comment:</label>
                                <textarea name="content" id="content" rows="4" required><?php echo htmlspecialchars($row['content']); ?></textarea>
                                <br>
                                <input type="submit" value="Save Changes">
                        </form>
                        <a href="board_list.php">Back to Board List</a>
                </body>
                </html>
                <?php
        } else {
                echo "You are not authorized to edit this comment.";
        }
}

mysqli_close($connection);
?>

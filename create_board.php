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
        return mysqli_real_escape_string($connection, $value);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        } else if (isset($_POST['title']) && isset($_POST['content'])) {
                $user_id = $_SESSION['user_id'];
                $title = escape($_POST['title']);
                $content = escape($_POST['content']);
                $sql = "INSERT INTO posts (user_id, title, content) VALUES ('$user_id', '$title', '$content')";
                if (mysqli_query($connection, $sql)) {
                        echo "Post created successfully!";
                } else {
                        echo "Error: creating post: " . mysqli_error($connection);
                }
        }
}
?>

<!DOCTYPE html>
<html>
<head>
        <title>Create Bulletin Board</title>
</head>
<body>
        <h1>Create Bulletin Board</h1>

        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) { ?>
                <form action="create_board.php"method="post">
                        <label for="title">Title:</label>
                        <input type="text" name="title" id="title" required>
                        <br>
                        <label for="content">Content:</label>
                        <textarea name="content" id="content" rows="5" cols="40" required></textarea>
                        <br>
                        <label for="fileToUpload">Select a file to upload:</label>
                        <input type="file" name="fileToUpload" id="fileToUpload">
                        <br>
                        <input type="submit" value="Create Post">
                </form>
        <?php } else { ?>
                <p>You need to <a href="login.php">login</a> to create posts.</p>
        <?php } ?>
        <a href="board_list.php">View Bulletin Board List</a>
</body>
</html>

<?php
mysqli_close($connection);
?>

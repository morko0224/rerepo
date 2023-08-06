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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment']) && isset($_POST['post_id'])) {
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

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];
    $sql = "SELECT * FROM posts WHERE id = '$post_id'";
    $result = mysqli_query($connection, $sql);
    $post = mysqli_fetch_assoc($result);
} else {
    header("Location: main.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Post</title>
</head>
<body>
    <h1><?php echo htmlspecialchars($post['title']); ?></h1>
    <p><?php echo htmlspecialchars($post['content']); ?></p>

    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) { ?>
        <form action="view_post.php?id=<?php echo $post_id; ?>" method="post">
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
            <textarea name="comment" rows="4" required></textarea>
            <br>
            <input type="submit" value="Add Comment">
        </form>
    <?php } else { ?>
        <p>You need to <a href="login.php">login</a> to add comments.</p>
    <?php } ?>

    <p><a href="main.php">Back to Main Page</a></p>
</body>
</html>

<?php
mysqli_close($connection);
?>

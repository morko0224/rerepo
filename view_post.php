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

$post_id = $_GET['id']; // Get the post id from the URL

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($post_id)) {

    $post_query = "SELECT * FROM posts WHERE id = ?";
    $stmt = mysqli_prepare($connection, $post_query);
    mysqli_stmt_bind_param($stmt, "i", $post_id);
    mysqli_stmt_execute($stmt);
    $post_result = mysqli_stmt_get_result($stmt);


    if ($post_result && mysqli_num_rows($post_result) > 0) {
        $post_row = mysqli_fetch_assoc($post_result);

        $comments_query = "SELECT * FROM comments WHERE post_id = ?";
        $stmt = mysqli_prepare($connection, $comments_query);
        mysqli_stmt_bind_param($stmt, "i", $post_id);
        mysqli_stmt_execute($stmt);
        $comments_result = mysqli_stmt_get_result($stmt);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment'])) {
    $user_id = $_SESSION['user_id'];
    $content = mysqli_real_escape_string($connection, $_POST['comment']);
    $file_path = null;
    if (!empty($_FILES['fileToUpload']['name'])) {
        $tempFilePath = $_FILES['fileToUpload']['tmp_name'];
        $target_dir = "uploads/";

        if (is_uploaded_file($tempFilePath) && $_FILES['fileToUpload']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "uploads/";
            $file_name = basename($_FILES['fileToUpload']['name']);
            $file_path = $target_dir . $file_name;

            if (move_uploaded_file($tempFilePath, $file_path)) {
                echo "File uploaded successfully.";
            } else {
                echo "Sorry, there was an error moving the uploaded file.";
            }
        } else {
            echo "File upload failed or encountered an error.";
        }
    }    

    $sql = "INSERT INTO comments (post_id, user_id, content, file_path) VALUES ('$post_id', '$user_id', '$content', '$file_path')";
    if (mysqli_query($connection, $sql)) {
        echo "Comment added successfully!";
    } else {
        echo "Error adding comment: " . mysqli_error($connection);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Bulletin Board Post</title>
</head>
<body>
    <h1>View Bulletin Board Post</h1>
    <?php if (isset($post_row)) { ?>
        <h2><?php echo htmlspecialchars($post_row['title']); ?></h2>
        <p><?php echo htmlspecialchars($post_row['content']); ?></p>
    <?php } else { ?>
A        <p>Post not found.</p>
    <?php } ?>

    <h2>Comments</h2>
    <?php if (isset($comments_result) && mysqli_num_rows($comments_result) > 0) { ?>
        <ul>
            <?php while ($comment_row = mysqli_fetch_assoc($comments_result)) { ?>
                <li>
                    <?php echo htmlspecialchars($comment_row['content']); ?>
                    <?php if (!empty($comment_row['file_path'])) { ?>
                        <a href="<?php echo $comment_row['file_path']; ?>" target="_blank">Download File</a>
                    <?php } ?>
                    <?php if ($_SESSION['user_id'] == $comment_row['user_id']) { ?>
                        <a href="delete_comment.php?id=<?php echo $comment_row['id']; ?>">Delete</a>
                        <a href="edit_comment.php?id=<?php echo $comment_row['id']; ?>">Edit</a>
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>
    <?php } else { ?>
        <p>No comments yet.</p>
    <?php } ?>

    <form action="view_post.php?id=<?php echo $post_id; ?>" method="post" enctype="multipart/form-data">
        <textarea name="comment" rows="4" required></textarea>
        <br>
        <label for="fileToUpload">Upload File:</label>
        <input type="file" name="fileToUpload" id="fileToUpload">
        <br>
        <input type="submit" value="Add Comment">
    </form>

    <p><a href="main.php">Back to Main Page</a></p>
</body>
</html>

<?php
mysqli_close($connection);
?>

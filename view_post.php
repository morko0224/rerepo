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

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $post_id = $_GET['id'];
    
    // Fetch the post details
    $post_query = "SELECT * FROM posts WHERE id = $post_id";
    $post_result = mysqli_query($connection, $post_query);
    $post_row = mysqli_fetch_assoc($post_result);

    // Fetch comments for the post
    $comments_query = "SELECT * FROM comments WHERE post_id = $post_id";
    $comments_result = mysqli_query($connection, $comments_query);
}

// Handle comments and file upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment'])) {
    $user_id = $_SESSION['user_id'];
    $content = mysqli_real_escape_string($connection, $_POST['comment']);
    $file_path = null;

    // Handle file upload
    if (!empty($_FILES['fileToUpload']['name'])) {
        $target_dir = "uploads/";
        $file_name = basename($_FILES['fileToUpload']['name']);
        $file_path = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $file_path)) {
            echo "File uploaded successfully.";
        } else {
            echo "Sorry, there was an error uploading your file.";
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
    <h2><?php echo htmlspecialchars($post_row['title']); ?></h2>
    <p><?php echo htmlspecialchars($post_row['content']); ?></p>

    <h2>Comments</h2>
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

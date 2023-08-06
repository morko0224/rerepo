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
    $comment_id = $_GET['id'];
    
    // Check if the comment belongs to the logged-in user
    $user_id = $_SESSION['user_id'];
    $check_query = "SELECT * FROM comments WHERE id = $comment_id AND user_id = $user_id";
    $check_result = mysqli_query($connection, $check_query);

    if (mysqli_num_rows($check_result) === 1) {
        // Comment belongs to the user, proceed with deletion
        $delete_query = "DELETE FROM comments WHERE id = $comment_id";
        if (mysqli_query($connection, $delete_query)) {
            // Get the post_id of the comment
            $get_post_id_query = "SELECT post_id FROM comments WHERE id = $comment_id";
            $post_id_result = mysqli_query($connection, $get_post_id_query);
            $post_id_row = mysqli_fetch_assoc($post_id_result);
            $post_id = $post_id_row['post_id'];

            // Redirect back to the view_post.php page
            header("Location: view_post.php?id=$post_id");
            exit();
        } else {
            echo "Error deleting comment: " . mysqli_error($connection);
        }
    } else {
        echo "You do not have permission to delete this comment.";
    }
} else {
    echo "Invalid request.";
}

mysqli_close($connection);
?>

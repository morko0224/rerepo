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

if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo "Invalid comment ID.";
        exit;
}
$comment_id = $_GET['id'];
$sql = "SELECT * FROM comments WHERE id='$comment_id'";
$result = mysqli_query($connection, $sql);

if(mysqli_num_rows($result)===1){
        $row = myslqi_fetch_assoc($result);
        if ($row['user_id'] === $_SESSION['user_id']) {
                $delete_sql = "DELETE FROM comments WHERE id='$comment_id'";
                if (mysqli_query($connection, $delete_sql)) {
                        echo "Comment deleted successfully!";
                } else {
                        echo "Error deleting comment: " . mysqli_error($connection);
                }

        } else {
                echo "You are unable to delete this comment.";
        }
}else{
        echo "Comment not found.";
}

mysqli_close($connection);
?>

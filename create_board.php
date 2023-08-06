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

function escape($value) {
    global $connection;
    return mysqli_real_escape_string($connection, $value);
}

function isAllowedFileExtension($extension) {
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif', 'pdf'); // Add more allowed extensions as needed

    return in_array($extension, $allowedExtensions);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['title']) && isset($_POST['content'])) {
        $user_id = $_SESSION['user_id'];
        $title = escape($_POST['title']);
        $content = escape($_POST['content']);
        
        $sql = "INSERT INTO posts (user_id, title, content) VALUES ('$user_id', '$title', '$content')";
        if (mysqli_query($connection, $sql)) {
            echo "Post created successfully!";
            
            // Insert into bulletin_boards table
            $board_name = escape($_POST['title']);
            $sql = "INSERT INTO bulletin_boards (board_name, created_by) VALUES ('$board_name', '$user_id')";
            mysqli_query($connection, $sql);
        } else {
            echo "Error creating post: " . mysqli_error($connection);
        }
    }

    if (!empty($_FILES['fileToUpload']['name'])) {
        $target_dir = "uploads/";
        $file_name = basename($_FILES['fileToUpload']['name']);
        $file_path = $target_dir . $file_name;
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (isAllowedFileExtension($file_extension)) {
            if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $file_path)) {
                echo "File uploaded successfully.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "Invalid file type. Only specific file types are allowed.";
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
    
    <form action="create_board.php" method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" name="title" required>
        <br>
        <label for="content">Content:</label>
        <textarea name="content" rows="4" required></textarea>
        <br>
        <label for="fileToUpload">Upload File:</label>
        <input type="file" name="fileToUpload" id="fileToUpload">
        <br>
        <input type="submit" value="Create Board">
    </form>

    <p><a href="main.php">Back to Main Page</a></p>
</body>
</html>

<?php
mysqli_close($connection);
?>

<?php
session_start();

$host = "localhost";
$dbusername = "mika";
$dbpassword = "qwerty12345";
$database = "databass";

if($_SERVER["REQUEST_METHOD"] === "POST"){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $connection = mysqli_connect($host, $dbusername, $dbpassword, $database);

        if (!$connection) {
                die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($connection, $sql);

        if (mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                $hashedPassword = $row['password'];

                if (password_verify($password, $hashedPassword)) {

                        $_SESSION['logged_in'] = true;
                        $_SESSION['user_id'] = $row['id'];
                        mysqli_close($connection);
                        header("Location: main.php");
                        exit;
                }
        }
        $error_message = "Invalid username or password.";
        mysqli_close($connection);
}
?>
<!DOCTYPE html>
<html>
<head>
        <title>Login</title>
</head>
<body>
        <h1>Login</h1>
        <?php if (isset($error_message)) { ?>
                <p><?php echo $error_message; ?></p>
        <?php } ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
                <br>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
                <br>
                <input type="submit" value="Login">
        </form>
        <p>Don't have an account? <a href="join.php">Register here</a>.</p>
</body>
</html>

<?php
$host = "localhost";
$dbusername = "mika";
$dbpassword = "qwerty12345";
$database = "databass";

if($_SERVER["REQUEST_METHOD"]==="POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $connection =  mysqli_connect($host, $dbusername, $dbpassword, $database);

        if (!$connection){
                die("connection failed: ". mysqli_connect_error());
        }
        $checkUsernameQuery = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($connection, $checkUsernameQuery);
        if (mysqli_num_rows($result) > 0) {
                echo "Username is already taken. Choose a different username";
        } else {
                $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashedPassword')";

                if (mysqli_query($connection, $sql)) {
                        $success_message = "Registration successful!";
                }else {
                        $error_message= "Error: " . mysqli_error($connection);
                }
        }
        mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html>
<head>
        <title>Join</title>
</head>
<body>
        <h1>Join</h1>
        <?php if (isset($error_message)) { ?>
                <p style="color: red;"><?php echo $error_message; ?></p>
        <?php } ?>
        <?php if (isset($success_message)) { ?>
                <p style="color: green;"><?php echo $success_message; ?></p>
        <?php } ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
                <br>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
                <br>
                <input type="submit" value="Signup">
        </form>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
</body>
</html>

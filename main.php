<!DOCTYPE html>
<html>
<head>
    <title>Main Page - Table of Contents</title>
</head>
<body>
    <h1>Welcome to the Main Page</h1>
    <p>This is the main page content.</p>

    <h2>Table of Contents</h2>
    <table>
        <tr>
            <th>Title</th>
            <th>Content</th>
            <th>Posted By</th>
            <th>Comments</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td><a href=\"view_post.php?id=" . $row['id'] . "\">" . htmlspecialchars($row['title']) . "</a></td>";
            echo "<td>" . htmlspecialchars($row['content']) . "</td>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td><a href=\"view_comments.php?id=" . $row['id'] . "\">View Comments</a></td>";
            echo "</tr>";
        }
        ?>
    </table>

    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) { ?>
        <p><a href="create_board.php">Create a New Post</a></p>
        <p><a href="?logout">Logout</a></p>
    <?php } else { ?>
        <p><a href="login.php">Login</a></p>
    <?php } ?>

    <p><a href="board_list.php">Go to Post List</a></p>

</body>
</html>

<?php
mysqli_close($connection);
?>

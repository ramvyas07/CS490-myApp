<?php

session_start();
require("nav.php");
require(__DIR__ . "/../lib/db.php");


$get_id = $_GET['post_id'];

getPostCom($db, $get_id);

?>

<html>

<head>
    <link rel="stylesheet" href="../styles/comments.css">
</head>

<body>

    <form method="get">
        <textarea class="form-control" id="comment" rows="6" name="comment" placeholder="Comment"></textarea><br>
        <input type="submit" name="submitCom" value="Comment">
    </form>

</body>

</html>

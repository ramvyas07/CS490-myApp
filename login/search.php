<?php
session_start();
require("nav.php");
$user_id = get_user_id();
if (isset($_GET["id"])) {
    $user_id = $_GET["id"];
}

require(__DIR__ . "/../lib/db.php");

?>
<html>

<body>
    <h3> Find People </h3>
    <form method="GET">
        <input type="text" name="searchbar" placeholder="Search for Friend">
        <input type="submit" name="submitSearch" value="Search">
    </form>
    <?php searchUser($db); ?>
</body>

</html>

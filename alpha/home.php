<?php
session_start();
require("nav.php");

$user_id = get_user_id();
if (isset($_GET["id"])) {
    $user_id = $_GET["id"];
}
error_reporting(E_ALL ^ E_WARNING); 
$isMe = $user_id == get_user_id();
require(__DIR__ . "/../lib/db.php");
$user_id = mysqli_real_escape_string($db, $user_id);
$query = "SELECT email, username,password, created, visibility,role FROM rv8_users where id = $user_id";
if (!$isMe) {
    $query .= " AND visibility > 0";
}

$retVal = mysqli_query($db, $query);
$result = [];
if ($retVal) {
    $result = mysqli_fetch_array($retVal, MYSQLI_ASSOC);
}

?>

<h1>Home</h1>

<?php if($isMe):?>
    <?php if($result['role'] == 'admin'):?>
        <div>
        <h1>This is Admin</h1>
        </div>
    <?php endif;?>
    <?php if($result['role'] != 'admin'):?>
        <div>
        <h1> This is User </h1>
        </div>
    <?php endif;?>
    <?php endif;?>

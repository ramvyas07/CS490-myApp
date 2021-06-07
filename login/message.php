<?php
session_start();
//$_SESSION["user"] = $result
$user = $_SESSION["username"];
echo "$user<br>";
echo "Hello $user you got here in message";
?>
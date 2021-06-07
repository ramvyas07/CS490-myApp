<?php
session_start();
$sidvalue = session_id();
// echo "<br>Your session id: " . $sidvalue . "<br>";
$_SESSION = array();        //Make $_SESSION  empty OR session_unset()
session_destroy();            //Terminate session on server
require("nav.php");
setcookie("PHPSESSID", "", time() - 3600);

header("refresh: 2; url = authenticate.php");
// echo "Your session is terminated.";
// echo "This uses the default 'path' for a session - <br>it should actually be a more specific path!";


?>

<body>
    <link rel="stylesheet" href="../styles/logout.css" />
    <div class="wrapper">
        <p>Thanks for Visiting us!</p>
        <p>Hope to see you soon</p>
    </div>
</body>

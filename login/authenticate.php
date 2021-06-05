<?php
require("nav.php");
//require(__DIR__ . "/../lib/myFunctions.php");
if (isset($_REQUEST["email"])) {
  $email = $_REQUEST["email"];
  $password = $_REQUEST["password"];
  if (is_empty_or_null($email) || is_empty_or_null($password)) {
    echo "Something's missing here....";
    exit();
  }
  require(__DIR__ . "/../lib/db.php");
  //$db = getDB();//invoked in db.php already
  $email = mysqli_real_escape_string($db, $email);
  $hash = password_hash($password, PASSWORD_BCRYPT);
  //mysqli still wants the single quotes in the query so can't just drop in the variables post-escape
  $sql = "SELECT id, email, password, username from rv8_users where email = '$email' OR username = '$email' LIMIT 1";
  $retVal = mysqli_query($db, $sql);
  //check username:
  if ($retVal) {
    $result = mysqli_fetch_array($retVal, MYSQLI_ASSOC);
    var_export($result);
    if ($result) {
      if (password_verify($password, $result["password"])) {
        echo "<br>";
        echo "Hey you logged in!";
        unset($result["password"]);
        session_start();
        $_SESSION["user"] = $result;
		$_SESSION["username"] = $result['username'];
		
        //uncomment below to have an example of malicious user data
        ///$_SESSION["user"]["email"] = "<script>alert('The cookie monster has your cookies!' + document.cookie);</script>";
        die(header("Location: home.php"));
        //echo "<br>";
        //var_export($_SESSION);
      } else {
        echo "You're not you, go away";
      }
    } else {
      echo "User or email not found";
      echo "<script> alert('Wrong Credentials')</script>";
    }
  } else {
    echo "Something didn't work out " . mysqli_error($db);
  }
  //TODO: don't forget to close your connection, don't want resource leaks
  mysqli_close($db);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/authentication.css">
    <meta http-equiv=" X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>


<body>
    <div class="wrapper">
        <img class="avatar" src="../images/User-male-02.svg" alt="user-logo">
        <h2>Log in Form</h2>
        <form class="form" method="POST">
            <label>Email/Username</label>
            <input class="email" type="text" name="email" autocomplete="off" required />
            <label>Password</label>
            <input class="password" type="password" name="password" autocomplete="off" required />
            <input type="submit" value="Login" />
        </form>
        <form class="form" action="register.php" method="POST">
            <input id="register" type="submit" value="Register" />
        </form>
    </div>
</body>

</html>
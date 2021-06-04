<?php require(__DIR__ . "/nav.php"); ?>

<?php
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



if(isset($_REQUEST["submit"])){
    if (password_verify($_REQUEST["oPass"], $result["password"])){
        if($_GET["nPass"] == $_GET["cPass"]){
            $hash = password_hash($_GET["nPass"], PASSWORD_BCRYPT);
            $qUpdate = "UPDATE rv8_users SET password = '$hash', rawPassword = '$_GET[nPass]' WHERE id = '$user_id'";
            $retVal_1 = mysqli_query($db, $qUpdate);
            if($retVal_1){
                echo "Updated Password";
                
            }
            echo "Not run";
        }
        else{
            echo "Enter Same password for both";
        }
    }
    else{
        echo "Please Enter correct old password";
    }
}


// Change Username:

if(isset($_REQUEST["usrSub"])){
  
        $count = preg_match('/^[a-z]{4,20}$/i', $_GET[usrNew], $matches);
        
        if($count != 0){
           
            $qUpdate = "UPDATE rv8_users SET username = '$_GET[usrNew]' WHERE id = '$user_id'";
            $retVal_1 = mysqli_query($db, $qUpdate);
            if($retVal_1){
                echo "Updated Username";
                
            }
            echo "Not run";
        }
        else{
            echo "Username must be between 4 and 20 characters and only contain alphabetical characters.";
            exit();
        }
  
}







?>


<?php if($isMe):?>
<p>Change Password</p>
<form method="GET">
<div>
<label for="oPass">Old Password</label>
<input type="password" id="oPass" name="oPass">
</div>
<div>
<label for="nPass">New Password</label>
<input type="password" id="nPass" name="nPass">
</div>
<div>
<label for="cPass">Confirm Password</label>
<input type="password" id="cPass" name="cPass">
</div>
<input type="submit" value="Change" name="submit" id="submit">
</form>

<p>Change Username</p>
<form method="GET">
<div>
<label for="usrOld">Old Username</label>
<input type="text" id="usrOld" name="usrOld">
</div>
<div>
<label for="usrNew">New Username</label>
<input type="text" id="usrNew" name="usrNew">
</div>
<input type="submit" value="Change" name="usrSub" id="usrSub">
</form>
<?php endif;?>


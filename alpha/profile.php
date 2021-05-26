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




if(isset($_GET["paddleH"])){
    $_SESSION["paddleHeight"] = $_GET["paddleH"];
}
else{
    $_SESSION["paddleHeight"] = 100;
}
if(isset($_GET["ballSpeed"])){
    $_SESSION["ballSpeed"] = $_GET["ballSpeed"];
}
else{
    $_SESSION["ballSpeed"] = 2;
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




if(isset($_POST["vis"])){
    $sql = "UPDATE rv8_users SET visibility = ?  Where id = ?";
    mysql_error_info($db);
    $stmt = mysqli_stmt_init($db);
    $visibility = $_POST["vis"];

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $visibility, $user_id);
    mysqli_stmt_execute($stmt);
    $val = mysql_error_info($db);
}



?>
<h3>Profile</h3>
<?php if($result):?>
<form method="POST" onsubmit="return false">
    <?php if($isMe):?>
    <div>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?php safe($result['email']);?>" readonly/>
    </div>
    <?php endif;?>
    <div>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?php safe($result['username']);?>" readonly/>
    </div>
    <div>
        <label for="created">Joined</label>
        <input type="text" id="created" value="<?php safe($result['created']);?>" readonly/>
    </div>
    <div>
        <label for="role">Role</label>
        <input type="text" id="role" name="role" value="<?php safe($result['role']);?>" readonly/>
    </div>
    </form>
    <?php if($isMe):?>
    <form method="POST">
    <div>
        <label for="vis">Visibility</label>
        <select name="vis" id="vis" readonly>
            <option <?php echo ($result['visibility'] == 0?'selected="selected"':'');?> value="0">Private</option>
            <option <?php echo ($result['visibility'] == 1?'selected="selected"':'');?> value="1">Public</option>
        </select>
    </div>
    <input type="submit" value="Change" name="subV" id="subV">
    </form>
    <?php endif;?>
    <br>
    <br>
    <?php if($isMe):?>
    <?php if($result['role'] == 'admin'):?>
        <div>
            <p>Admin Role Game Changes</p>
            <form method="GET">
            <div>
            <label for="paddleH">Paddle Height</label>
            <input type="text" id="paddleH" name="paddleH">
            <div>

            <div>
            <label for="ballSpeed">Ball Speed </label>
            <input type="text" id="ballSpeed" name="ballSpeed">
            <div>
            <input type="submit" value="Change">
            </form>
        </div>
    <?php endif;?>
    <?php endif;?>
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

<h5>Scores</h5>
<?php 
$results = getScores($db, $user_id);
?>
<?php if($results && count($results) > 0):?>
    <?php if($result['role'] == 'admin'):?>
        <p>Admin View</p>
        <?php foreach($results as $score):?>
            <div><?php safe($score["score"]);?> - <?php safe($score["created"]);?></div>
        <?php endforeach;?>
    <?php else: ?>
        <?php foreach($results as $score):?>
            <div><?php safe($score["score"]);?> - <?php safe($score["created"]);?></div>
        <?php endforeach;?>
    <?php endif;?>
<?php else: ?>
    <d>This user has no scores</div>
<?php endif;?>
<?php else:?>
<p>This profile is private</p>
<?php endif;?>

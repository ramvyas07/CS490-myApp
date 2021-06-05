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
<html>
<body>
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

    <div>
	<div id="insert_post" >
		<center>
		<form action="home.php?id=<?php echo $user_id; ?>" method="post" id="postForm" enctype="multipart/form-data">
		<textarea class="form-control" id="content" rows="4" name="content" placeholder="Post"></textarea><br>
		<label  id="upload_image_button">Add
		<input type="file" name="upload_image" size="30">
		</label><br>
		<button id="btn-post"  name="submitPost">Post</button>
		</form>
		<?php insertPost($db); ?>
        <br>
        <?php getPost($db); ?>
		</center>
	</div>

</body>
</html>

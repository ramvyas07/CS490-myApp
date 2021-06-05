<?php
require(__DIR__ . "/../lib/db.php");
session_start();

$user = $_SESSION["username"];
global $user_name;
echo "$user<br>";
echo "Hello $user you got here in message<br>";
  //$db = getDB();//invoked in db.php already
$s = "select * from rv8_users";
	($t=mysqli_query($db,$s)) or die (mysqli_error($db));
	//Wrap output in HTML table tags:
	echo"<table border = 2 width = 10%>";
	echo"<tr><th>Users</th></tr>";
	while($r=mysqli_fetch_array($t,MYSQLI_ASSOC))
	{
		echo"<tr>";
		$username=$r["username"];
		$user_id = $r["id"];
		//<input type=radio id="LT" name="choice" value="LT" onClick="javascript:func();">
		//echo"<td><input type='button' value='$username' id='$user_id' onClick='javascript:func();'</td>";
		// <li><a href="home.php">Home</a></li>
		echo"<td><a href='message.php?user_name=$username'>$username</a></td>";
		echo"</tr>";
	}
	echo"</table>";
	if(isset($_GET['user_name']))
	{
		$u_sent = $_GET['user_name'];
		if($u_sent == "$user")
		{
			echo"
			<form>
			<center><h3>Please select user to start conversation</h3></center>
			<textarea disabled class='form-control' placeholder='Enter your Message'></textarea>
			<input type='submit' class='btn btn-default' disabled value='send'>
			</form><br><br>
			";
		}
		else
		{
			echo"
			<br><br>
			<form action='' method='POST'>
			<textarea class='form-control' placeholder='Enter your Message'
			 name='msg_box' id='msg_textarea'></textarea>
			<input type='submit' name='send_msg' value='send'>
			</form><br><br>
			";			
		}
	}
	if(isset($_POST['send_msg']))
	{
		$msg = htmlentities($_POST['msg_box']);
		if($msg == "")
		{
			echo"<h3 style='color:red;'>Message can't be empty!</h3>";
	
		}
		else
		{
			$insert = "insert into message
			(user_to,user_from,message_text,date_sent)
			values('$u_sent','$user','$msg',NOW())";
			($t=mysqli_query($db,$insert)) or die (mysqli_error($db));
		}
	}

?>

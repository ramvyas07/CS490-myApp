<?php
session_start();//to get logged in user.
require("nav.php"); //links to navigate another page.
require(__DIR__ . "/../lib/db.php");//$db = getDB();//invoked in db.php already.

//chat background image:
echo "<style>
body  {
  background-image: url('chat.jpg');
}
</style>";

//logged in user:
$user = $_SESSION["username"];
global $user_name;
global $u_sent;

//Greeting message.
echo "<h1 style = 'text-decoration: underline;font-style: italic; cursor: pointer; text-align: center;'>
Hello <span style='color:#0000FF;'>$user!</span>Welcome to Message center.</h1><br>";

 //connect to db and list all users from rv8_users table. 
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
	
		echo"<td><a style = 'font-weight: bold;  cursor: pointer; color: #FF0000;'href='message.php?user_name=$username'>$username</a></td>";
		echo"</tr>";
	}
	echo"</table>";

	//Get user name whome we want to send message.
	if(isset($_GET['user_name']))
	{
		$u_sent = $_GET['user_name'];
		if ($u_sent != $user)
		{
			echo"<center><h3><span style='color:#0000FF; font-style:italic;'>$user</span> Started Conversation with <span style='color:#008000; font-style:italic;'>$u_sent</span></h3></center>";
		}
	
	}

	//Display messages (chatting):
	echo"
	<div class= 'col-sm-6'>
		<div class= 'load_msg' id= 'scroll_messages'>";
		$msg = "select * from message where (user_to='$u_sent' AND user_from='$user') OR (user_from = '$u_sent' AND user_to='$user') ORDER BY message_id ASC";
		($t=mysqli_query($db,$msg)) or die (mysqli_error($db));
		while($r=mysqli_fetch_array($t,MYSQLI_ASSOC))
	{
		$user_to=$r["user_to"];
	  $user_from= $r["user_from"];
		$msg_text = $r["message_text"];
		$msg_date = $r["date_sent"];
		echo"<center><div id='loaded_msg'><p>";

		if ($user_to == $u_sent  AND  $user_from == $user)
		{
			echo"<div class= 'message' id='blue' data-toggle= 'tooltip' title= '$msg_date'><span style='background-color:#DBF3F8;'>$user_from :$msg_text    [ $msg_date ]</span></div><br>";
		}
		else if ($user_from == $u_sent AND $user_to == $user)
		{
			echo"<div class= 'message' id='green' data-toggle= 'tooltip' title= '$msg_date'><span style='background-color:#d2f8d2';>$user_from :$msg_text     [ $msg_date ]</span></div><br>";
		}
  echo"</p></div></center>";
	echo"</div></div>";
	}
	//Send message:
	if(isset($_GET['user_name']))
	{
		$u_sent = $_GET['user_name'];
		if($u_sent == "$user")
		{
			echo"
			<form>
			<center><h3>Please select user to start conversation</h3>
			<textarea disabled class='form-control' placeholder='Enter your Message'></textarea>
			<input type='submit' class='btn btn-default' disabled value='send'>
			</center></form><br><br>
			";
		}
		else
		{
			echo"
			<br>
			<form action='message.php?user_name=$u_sent' method='POST'><center>
			<textarea class='form-control' placeholder='Enter your Message'
			 name='msg_box' id='msg_textarea'></textarea>
			<input type='submit' name='send_msg' value='send'>
			</center></form>
			";			
		}
	}
	if(isset($_POST['send_msg']))
	{
		$msg = htmlentities($_POST['msg_box']);
		if($msg == "")
		{
			echo"<center><h1 style='color:#FF0000;'>Message can't be empty!</h1></center>";
	
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

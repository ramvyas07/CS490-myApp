<?php
function is_empty_or_null($variable)
{
	return !isset($variable) || empty($variable);
}

function setDebug($data)
{
	$_SESSION["debug"] = $data;
}

function getDebug($dumpAll = false)
{
	if (isset($_SESSION["debug"]) && !$dumpAll) {

		echo var_export($_SESSION["debug"], true);
		unset($_SESSION["debug"]);
	} else {
		echo var_export($_SESSION, true);
	}
}


function safe($var)
{
	echo htmlentities($var, ENT_QUOTES, "utf-8");
}

function is_logged_in()
{
	return isset($_SESSION["user"]) && isset($_SESSION["user"]["id"]);
}

function get_email()
{
	if (is_logged_in()) {
		return $_SESSION["user"]["email"];
	}
	return "";
}

function get_user_id()
{
	if (is_logged_in()) {
		return $_SESSION["user"]["id"];
	}
	return -1;
}
function isAdmin()
{
	return $_SESSION["user"]["role"] === "admin";
}

function get_username()
{
	if (is_logged_in()) {
		if (isset($_SESSION["user"]["username"])) {
			return $_SESSION["user"]["username"];
		}
		return get_email();
	}
	return "";
}
function getUser($db, $userID)
{
	$q = "SELECT username from `rv8_users` where id = '$userID'";
	$run = mysqli_query($db, $q);
	if ($run) {
		while ($row_posts = mysqli_fetch_array($run)) {
			return $row_posts['username'];
		}
	}
}
function mysql_error_info($db)
{
	if ($db != null) {
		$code = mysqli_errno($db);
		$state = mysqli_sqlstate($db);
		$error = mysqli_error($db);
		$returnStr = "";
		switch ($code) {
			case 1062:
				$returnStr =  "This email is already in use";
				break;
			case 1054:
				$returnStr =  "Sorry folks, the developer forgot to add the column";
				break;
			default:
				$returnStr =  "An unhandled error occurred: $code - $state - $error";
				break;
		}
		return $returnStr;
	} else {
		return "Database is null???";
	}
}

function insertPost($db)
{
	if (isset($_POST['submitPost'])) {
		$user_id = get_user_id();


		$content = htmlentities($_POST['content']);
		$upload_image = $_FILES['upload_image']['name'];
		if (strlen($upload_image) >= 1) {
			$image = addslashes(file_get_contents($_FILES['upload_image']['tmp_name']));
		}

		$random_number = rand(1, 100);

		if (strlen($content) > 250) {
			echo "Please Post again";
			echo "<script>window.open('home.php', '_self')</script>";
		} else {
			if (strlen($upload_image) >= 1 && strlen($content) >= 1) {

				$insert = "insert into post_table (id, post_content, upload_image, post_date) values('$user_id', '$content', '$image', NOW())";

				$run = mysqli_query($db, $insert);

				if ($run) {
					echo "Uploaded";
					echo "<script>window.open('home.php', '_self')</script>";
				}

				exit();
			} else {
				if ($upload_image == '' && $content == '') {
					echo "Error Occured while Uploading";
					echo "<script>window.open('home.php', '_self')</script>";
				} else {
					if ($content == '') {

						$insert = "insert into post_table (id,post_content,upload_image,post_date) values ('$user_id','NaDa','$image',NOW())";
						$run = mysqli_query($db, $insert);

						if ($run) {
							echo "Uploaded";
							echo "<script>window.open('home.php', '_self')</script>";
						}

						exit();
					} else {
						$insert = "insert into post_table (id, post_content, post_date) values('$user_id', '$content','',NOW())";
						$run = mysqli_query($db, $insert);

						if ($run) {
							echo "Uploaded";
							echo "<script>window.open('home.php', '_self')</script>";
						}
					}
				}
			}
		} //base64_encode($row_posts['upload_image']) getUser($db,$userID)
	}
}


function getPost($db)
{

	$q = "SELECT * from post_table ORDER BY post_date DESC";
	$run = mysqli_query($db, $q);

	while ($row_posts = mysqli_fetch_array($run)) {
		$post_id = $row_posts['post_id'];
		$user_id = $row_posts['id'];
		$content = substr($row_posts['post_content'], 0, 40);
		$upload_image = $row_posts['upload_image'];
		$post_date = $row_posts['post_date'];

		$user = getUser($db, $user_id);

		$enc = base64_encode($row_posts['upload_image']);



		//now displaying posts from database

		if ($content == "NaDa" && strlen($upload_image) >= 1) {
			echo "
			<div class='row'>
				<div class='col-sm-3'>
				</div>
				<div id='posts' class='col-sm-6'>
					<div class='row'>

						<div class='col-sm-6'>
							<h3><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='profile.php?id=$user_id'>$user</a></h3>
						</div>
						<div class='col-sm-4'>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-12'>
							<img id='posts-img' src=data:image/jpeg;base64," . base64_encode($row_posts["upload_image"]) . " style='height:350px;'>
						</div>
						<small>Updated a post on <span class='date'>$post_date</span></small>
					</div><br>
					<a href='comments.php?post_id=$post_id' style='float:right;'><button class='btn btn-info btn-comment'>Comment</button></a><br>
				</div>
				<div class='col-sm-3'>
				</div>
			</div><br><br>
			";
		} else if (strlen($content) >= 1 && strlen($upload_image) >= 1) {
			echo "
			<div class='row'>
				<div class='col-sm-3'>
				</div>
				<div id='posts' class='col-sm-6'>
					<div class='row'>
						<div class='col-sm-2'>

						</div>
						<div class='col-sm-6'>
							<h3><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='profile.php?id=$user_id'>$user</a></h3>
						</div>
						<div class='col-sm-4'>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-12'>
							<p>$content</p>
							<img id='posts-img' src=data:image/jpeg;base64," . base64_encode($row_posts["upload_image"]) . " style='height:350px;'>
						</div>
						<small>Updated a post on <span class='date'>$post_date</span></small>
					</div><br>
					<a href='comments.php?post_id=$post_id' style='float:right;'><button class='btn btn-info btn-comment'>Comment</button></a><br>
				</div>
				<div class='col-sm-3'>
				</div>
			</div><br><br>
			";
		} else {
			echo "
			<div class='row'>
				<div class='col-sm-3'>
				</div>
				<div id='posts' class='col-sm-6'>
					<div class='row'>
						<div class='col-sm-2'>

						</div>
						<div class='col-sm-6'>
							<h3><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='profile.php?id=$user_id'>$user</a></h3>
						</div>
						<div class='col-sm-4'>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-12'>
							<h3><p>$content</p></h3>
						</div>
						<small>Updated a post on <span class='date'>$post_date</span></small>
					</div><br>
					<a href='comments.php?post_id=$post_id' style='float:right;'><button class='btn btn-info btn-comment'>Comment</button></a><br>
				</div>
				<div class='col-sm-3'>
				</div>
			</div><br><br>
			";
		}
	}
}

function getPostCom($db, $postID)
{

	$q = "SELECT * from post_table where post_id='$postID'";
	$run = mysqli_query($db, $q);

	while ($row_posts = mysqli_fetch_array($run)) {
		$post_id = $row_posts['post_id'];
		$user_id = $row_posts['id'];
		$content = substr($row_posts['post_content'], 0, 40);
		$upload_image = $row_posts['upload_image'];
		$post_date = $row_posts['post_date'];

		$user = getUser($db, $user_id);

		$enc = base64_encode($row_posts['upload_image']);



		//now displaying posts from database

		if ($content == "NaDa" && strlen($upload_image) >= 1) {
			echo "
			<div class='row'>
				<div class='col-sm-3'>
				</div>
				<div id='posts' class='col-sm-6'>
					<div class='row'>
						<div class='col-sm-6'>
							<h3><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='profile.php?id=$user_id'>$user</a></h3>
						</div>
						<div class='col-sm-4'>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-12'>
							<img id='posts-img' src=data:image/jpeg;base64," . base64_encode($row_posts["upload_image"]) . " style='height:350px;'>
						</div>
						<small>Updated a post on <span class='date'>$post_date</span></small>
					</div><br>

				</div>
				<div class='col-sm-3'>
				</div>
			</div><br><br>
			";
		} else if (strlen($content) >= 1 && strlen($upload_image) >= 1) {
			echo "
			<div class='row'>
				<div class='col-sm-3'>
				</div>
				<div id='posts' class='col-sm-6'>
					<div class='row'>
						<div class='col-sm-2'>

						</div>
						<div class='col-sm-6'>
							<h3><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='profile.php?id=$user_id'>$user</a></h3>
						</div>
						<div class='col-sm-4'>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-12'>
							<p>$content</p>
							<img id='posts-img' src=data:image/jpeg;base64," . base64_encode($row_posts["upload_image"]) . " style='height:350px;'>
						</div>
						<small>Updated a post on <span class='date'>$post_date</span></small>
					</div><br>

				</div>
				<div class='col-sm-3'>
				</div>
			</div><br><br>
			";
		} else {
			echo "
			<div class='row'>
				<div class='col-sm-3'>
				</div>
				<div id='posts' class='col-sm-6'>
					<div class='row'>
						<div class='col-sm-2'>

						</div>
						<div class='col-sm-6'>
							<h3><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='profile.php?id=$user_id'>$user</a></h3>
						</div>
						<div class='col-sm-4'>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-12'>
							<h3><p>$content</p></h3>
						</div>
						<small>Updated a post on <span class='date'>$post_date</span></small>
					</div><br>

				</div>
				<div class='col-sm-3'>
				</div>
			</div><br><br>
			";
		}
	}
}

function saveComments($db, $postID)
{
	if (isset($_POST['submitCom'])) {
		$user_id = get_user_id();
		$comment_author = getUser($db, $user_id);
		$comment = htmlentities($_POST['comment']);


		$runQ = "INSERT INTO comments (`post_id`, `id`, `comment`, `comment-auth`, `date`) VALUES('$postID','$user_id','$comment','$comment_author',NOW())";
		$run = mysqli_query($db, $runQ);

		if ($run) {
			echo "Comment Uploaded";
			echo "<script>window.open('comments.php?post_id=$postID', '_self')</script>";
		}
	}
}

function getComment($db, $postID)
{
	$get_com = "SELECT * from comments where post_id='$postID' ORDER by 1 DESC";

	$run = mysqli_query($db, $get_com);

	if ($run) {
		while ($row = mysqli_fetch_array($run)) {

			$comment = $row['comment'];
			$username = $row['comment-auth'];
			$date = $row['date'];

			echo "
				<div>
					<div>
						<div>
						<div>
							<div>
								<h4><strong>$username </strong><i>commented</i> on $date</h4><br>
								<p>$comment</p>
							</div>
						</div>
						</div>
					</div>
				</div>
			";
		}
	}
}

function searchUser($db)
{

	if (isset($_GET['submitSearch'])) {
		$search = htmlentities($_GET['searchbar']);
		$q = "SELECT * from rv8_users where username like '%$search%' OR email like '%$search%'";
	} else {
		$q = "SELECT * from rv8_users";
	}
	$run = mysqli_query($db, $q);
	while ($row = mysqli_fetch_array($run)) {
		$username = $row['username'];
		$email = $row['email'];
		echo "
        <div class='user'>
        <p class='username'>$username</p>
        <p class='email'>$email</p>
        </div>
        ";
	}
}

function getProPost($db, $userID)
{

	$q = "SELECT * from post_table where id='$userID'";
	$run = mysqli_query($db, $q);

	while ($row_posts = mysqli_fetch_array($run)) {
		$post_id = $row_posts['post_id'];
		$user_id = $row_posts['id'];
		$content = substr($row_posts['post_content'], 0, 40);
		$upload_image = $row_posts['upload_image'];
		$post_date = $row_posts['post_date'];

		$user = getUser($db, $user_id);

		$enc = base64_encode($row_posts['upload_image']);



		//now displaying posts from database

		if ($content == "NaDa" && strlen($upload_image) >= 1) {
			echo "
			<div class='row'>
				<div class='col-sm-3'>
				</div>
				<div id='posts' class='col-sm-6'>
					<div class='row'>

						<div class='col-sm-6'>
							<h3><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='profile.php?id=$user_id'>$user</a></h3>
						</div>
						<div class='col-sm-4'>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-12'>
							<img id='posts-img' src=data:image/jpeg;base64," . base64_encode($row_posts["upload_image"]) . " style='height:350px;'>
						</div>
						<small>Updated a post on <span class='date'>$post_date</span></small>
					</div><br>

				</div>
				<div class='col-sm-3'>
				</div>
			</div><br><br>
			";
		} else if (strlen($content) >= 1 && strlen($upload_image) >= 1) {
			echo "
			<div class='row'>
				<div class='col-sm-3'>
				</div>
				<div id='posts' class='col-sm-6'>
					<div class='row'>
						<div class='col-sm-2'>

						</div>
						<div class='col-sm-6'>
							<h3><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='profile.php?id=$user_id'>$user</a></h3>
						</div>
						<div class='col-sm-4'>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-12'>
							<p>$content</p>
							<img id='posts-img' src=data:image/jpeg;base64," . base64_encode($row_posts["upload_image"]) . " style='height:350px;'>
						</div>
						<small>Updated a post on <span class='date'>$post_date</span></small>
					</div><br>

				</div>
				<div class='col-sm-3'>
				</div>
			</div><br><br>
			";
		} else {
			echo "
			<div class='row'>
				<div class='col-sm-3'>
				</div>
				<div id='posts' class='col-sm-6'>
					<div class='row'>
						<div class='col-sm-2'>

						</div>
						<div class='col-sm-6'>
							<h3><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='profile.php?id=$user_id'>$user</a></h3>

						</div>
						<div class='col-sm-4'>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-12'>
							<h3><p>$content</p></h3>
						</div>
						<small>Updated a post on <span class='date'>$post_date</span></small>
					</div><br>

				</div>
				<div class='col-sm-3'>
				</div>
			</div><br><br>
			";
		}
	}
}

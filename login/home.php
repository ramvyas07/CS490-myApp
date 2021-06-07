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

<head>
    <link rel="stylesheet" href="../styles/home.css">
</head>

<body>

    <?php if ($isMe) : ?>
    <?php if ($result['role'] == 'admin') : ?>
    <div class="main_admin">
        <aside class="search">

            <?php
                    $user_id = get_user_id();
                    if (isset($_GET["id"])) {
                        $user_id = $_GET["id"];
                    }
                    ?>
            <html>

            <body>
                <h3> Find People </h3>
                <form method="GET">
                    <input class="search_people" type="text" name="searchbar" placeholder="Search for Friend">
                    <input type="submit" name="submitSearch" value="Search">
                </form>
                <?php searchUser($db); ?>
            </body>

            </html>




        </aside>
        <main>

            <div id="insert_post">
                <section>
                    <form action="home.php?id=<?php echo $user_id; ?>" method="post" id="postForm"
                        enctype="multipart/form-data">
                        <textarea class="form-control" id="content" rows="4" name="content"
                            placeholder="share your thoughts"></textarea>
                        <div class="add_post">
                            <input id="image_upload" type="file" name="upload_image" size="30">
                            <button id="btn-post" name="submitPost">Post</button>
                        </div>
                    </form>
                    <?php insertPost($db); ?>
                    <br>
                    <?php getPost($db); ?>
                </section>
            </div>

        </main>
        <aside class="api">API</aside>
    </div>
    <?php endif; ?>


    <?php if ($result['role'] != 'admin') : ?>
    <div class="main_reg">
        <aside class="search">
            <?php
                    $user_id = get_user_id();
                    if (isset($_GET["id"])) {
                        $user_id = $_GET["id"];
                    }
                    ?>
            <h3> Find People </h3>
            <form method="GET">
                <input class="search_people" type="text" name="searchbar" placeholder="Search for Friend">
                <input class="btn-search" type="submit" name="submitSearch" value="Search">
            </form>
            <?php searchUser($db); ?>


</html>
</aside>
<main>

    <div id="insert_post">
        <section>
            <form action="home.php?id=<?php echo $user_id; ?>" method="post" id="postForm"
                enctype="multipart/form-data">
                <textarea class="form-control" id="content" rows="4" name="content"
                    placeholder="share your thoughts"></textarea>
                <div class="add_post">
                    <input id="image_upload" type="file" name="upload_image" size="30">
                    <button id="btn-post" name="submitPost">Post</button>
                </div>
            </form>
            <?php insertPost($db); ?>
            <br>
            <?php getPost($db); ?>
        </section>
    </div>

</main>
<aside class="api">
    <section>
        <link rel="stylesheet" href="../styles/api.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <h3>Cars Data</h3>
        <div id="cars"></div>
        <script src="./index.js"></script>
    </section>

</aside>
</div>
</div>
<?php endif; ?>
<?php endif; ?>
</body>

</html>

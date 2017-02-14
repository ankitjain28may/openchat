<?php

require_once (dirname(__DIR__).'/vendor/autoload.php');
use ChatApp\User;
use ChatApp\Profile;
use ChatApp\Session;
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();
// die("Hello");

$user = explode("/", $_SERVER['REQUEST_URI']);
$user = $user[count($user) - 1];
$userId = Session::get('start');
if ($userId != null && $user == "account.php") {
    $obUser = new User();
    $row = $obUser->userDetails($userId, True);

    if ($row != NULL) {
        $location = getenv('APP_URL')."/views/account.php/".$row['username'];
        header("Location:".$location);
    }
} elseif ($user != "account.php") {
    $obUser = new User();
    $row = $obUser->userDetails($user, False);
    if ($row != NULL) {
        $userId = $row['login_id'];
        $details = Profile::getProfile($userId);
        if ($details != NULL) {
            $row = array_merge($row, $details);
        } else {
            header("Location:".getenv('APP_URL')."/views/error.php");
        }
?>

        <!Doctype html>
        <html>
            <head>
                <title>OpenChat || Profile</title>
                <link rel="stylesheet" href="../../public/assests/css/profile.css">
            </head>
            <body>

                <div class="header">
                    <a id="brand" href="">OpenChat</a>
                    <ul class="nav-right">
                        <li><a href="../../index.php">About</a></li>
                        <?php if (Session::get('start') != null) { ?>
                            <li><a href="../message.php">Message</a></li>
                            <li><a href="../logout.php">Log out</a></li>
                        <?php } else { ?>
                            <li><a href="../login.php">Login</a></li>
                            <li><a href="../register.php">Register</a></li>
                        <?php } ?>
                    </ul>
                </div>

                <div class="main">
                    <div class="boxx" >

                        <div class="pic">
                            <img src="../../public/assests/img/ankit.png">
                        </div>

                        <div class="brief">
                            <h1 id="name">Name: <?php echo $row['name']; ?></h1><br>
                            <?php foreach ($row as $key => $value) {
                                if ($key == 'username' && $value != null) {
                                    echo '<p>Username: '.$row["username"].'</p><br>';
                                }
                                if ($key == 'email' && $value != null) {
                                    echo '<p>Email Id: '.$row["email"].'</p><br>';
                                }
                                if ($key == 'status' && $value != null) {
                                    echo '<p>Status: '.$row["status"].'</p><br>';
                                }
                                if ($key == 'education' && $value != null) {
                                    echo '<p>Education: '.$row["education"].'</p><br>';
                                }
                                if ($key == 'gender' && $value != null) {
                                    echo '<p>Gender:     '.$row["gender"].'</p><br>';
                                }
                            }
                            ?>
                        </div>
                        <?php if (Session::get('start') == $row['login_id']) { ?>
                            <div class="edit">
                                <a href="#">Edit Profile</a>
                            </div>
                        <?php } ?>
                    </div>

                    <?php
                        if (Session::get('start') == $row['login_id']) {
                    ?>

                    <div class="boxx" id="profile">
                        <form method="post" action="../profile_generate.php">
                            <label>Status : </label>
                            <textarea name="status" id="status"><?php echo $row['status']; ?></textarea>
                            <label>Education : </label>
                            <input type="text" name="education" id="education" value="<?php echo $row['education']; ?>"></input>
                            <label>Gender : </label><br>
                            <input type="radio" name="gender" id="gender" value="Male" <?php echo ($row['gender'] == 'Male') ? 'checked' : '' ?>> Male<br>
                            <input type="radio" name="gender" id="gender" value="Female" <?php echo ($row['gender'] == 'Female') ? 'checked' : '' ?>> Female<br>
                            <input type="submit" name="submit" value="Done" id="submit">
                        </form>
                    </div>
                    <?php } ?>
                </div>

                <div class="footer">
                    <h3 class="footer_text">Made with love by <a href="#">Ankit Jain</a></h3>
                </div>

                <script type="text/javascript" src="../../public/assests/js/jquery-3.0.0.min.js"></script>
                <script type="text/javascript" src="../../public/assests/js/profile.js"></script>
                <script type="text/javascript" src="../../node_modules/place-holder.js/place-holder.min.js"></script>
            </body>
        </html>
<?php
    } else {
        header("Location:".getenv('APP_URL')."/views/error.php");
    }
} else {
    header("Location: ".getenv('APP_URL')."/views/");
}
?>


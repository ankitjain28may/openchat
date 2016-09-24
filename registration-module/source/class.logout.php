<?php
require_once 'database.php';
session_start();
$connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if(isset($_SESSION['start']))
{
    $id = $_SESSION['start'];
    $query = "Update login set login_status = '' where login_id = '$id'";
    if($result = $connect->query($query))
    {
        unset($_SESSION['start']);
        header('Location: ../../index.php');
    }

}
else
{
	echo "Please Login";
}
?>

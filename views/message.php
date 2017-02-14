<?php

require_once (dirname(__DIR__).'/vendor/autoload.php');
use ChatApp\Session;
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();

if (Session::get('start') != null && empty($_GET['user'])) {
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Messages</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../public/assests/css/bootstrap.min.css">
  <link rel="stylesheet" href="../public/assests/css/message.css">
  <!-- Font Awesome File -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="../public/assests/js/jquery-3.0.0.min.js"></script>
  <script src="../public/assests/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="../public/assests/js/message.js"></script>
</head>

<body>



</body>

</html>

<?php
} else {
    header('Location:'.getenv('APP_URL')."/index.php");
}
?>

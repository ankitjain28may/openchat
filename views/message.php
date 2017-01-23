<?php

require_once (dirname(__DIR__) . '/vendor/autoload.php');
use ChatApp\Session;
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();

if(Session::get('start') != null && empty($_GET['user']))
{

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

  <div class="container app">
    <div class="row app-one">

      <div class="col-sm-4 side">
        <div class="side-one">
          <!-- Heading -->
          <div class="row heading">
            <div class="col-sm-3 col-xs-3 heading-avatar">
              <div class="heading-avatar-icon">
                <img src="../public/assests/img/ankit.png">
              </div>
            </div>
            <div class="col-sm-1 col-xs-2  heading-dot  pull-right dropdown">
              <i class="fa fa-ellipsis-v fa-2x dropdown-toggle" aria-hidden="true" data-toggle="dropdown"></i>
              <ul class="dropdown-menu">
                <li><a href="#">HTML</a></li>
                <li><a href="#">CSS</a></li>
                <li><a href="#">JavaScript</a></li>
              </ul>
            </div>
            <div class="col-sm-2 col-xs-2 heading-compose  pull-right">
              <i class="fa fa-comments fa-2x" aria-hidden="true"></i>
            </div>
          </div>
          <!-- Heading End -->

          <!-- SearchBox -->
          <div class="row searchBox">
            <div class="col-sm-12 searchBox-inner">
              <div class="form-group has-feedback">
                <input id="searchText" type="text" class="form-control" name="searchText" placeholder="Search">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
              </div>
            </div>
          </div>

          <!-- Search Box End -->
          <!-- sideBar -->
          <div class="row sideBar" id="message">

          </div>
          <!-- Sidebar End -->
        </div>
        <div class="side-two">

          <!-- Heading -->
          <div class="row newMessage-heading">
            <div class="row newMessage-main">
              <div class="col-sm-2 col-xs-2 newMessage-back">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
              </div>
              <div class="col-sm-10 col-xs-10 newMessage-title">
                New Chat
              </div>
            </div>
          </div>
          <!-- Heading End -->

          <!-- ComposeBox -->
          <div class="row composeBox">
            <div class="col-sm-12 composeBox-inner">
              <div class="form-group has-feedback">
                <input id="composeText" type="text" class="form-control" name="searchText" placeholder="Search People">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
              </div>
            </div>
          </div>
          <!-- ComposeBox End -->

          <!-- sideBar -->
          <div class="row compose-sideBar" id="compose">
            <div class="notFound">
              Start New Chat
            </div>
          </div>
        </div>
        <!-- Sidebar End -->
      </div>


      <!-- New Message Sidebar End -->

      <!-- Conversation Start -->
      <div class="col-sm-8 conversation">
        <!-- Heading -->
        <div class="row heading" id="conversationHeading">

        </div>
        <!-- Heading End -->

        <!-- Message Box -->
        <div class="row message" id="conversation">

        </div>
        <!-- Message Box End -->

        <!-- Reply Box -->
        <div class="row reply">
          <div class="col-sm-1 col-xs-1 reply-icons reply-emojis">
            <i class="fa fa-smile-o fa-2x"></i>
          </div>
          <div class="col-sm-9 col-xs-10 reply-main">
            <textarea class="form-control" rows="1" id="text_reply"></textarea>
          </div>
          <div class="col-sm-1 col-xs-1 reply-icons reply-recording">
            <i class="fa fa-microphone fa-2x" aria-hidden="true"></i>
          </div>
          <div class="col-sm-1 col-xs-2 reply-icons reply-send">
            <i class="fa fa-send fa-2x" aria-hidden="true"></i>
          </div>
        </div>
        <!-- Reply Box End -->


        <!-- Mobile lower Bar -->
        <div class="row lowerBar">
          <div class="col-xs-3 col-xs-offset-1 lowerBar-icons lowerBar-back">
            <i class="fa fa-arrow-left fa-2x"></i>
          </div>
          <div class="col-xs-3 col-xs-offset-1 lowerBar-icons lowerBar-emojis">
            <i class="fa fa-smile-o fa-2x"></i>
          </div>
          <div class="col-xs-3 col-xs-offset-1 lowerBar-icons lowerBar-recording">
            <i class="fa fa-microphone fa-2x"></i>
          </div>
        </div>
        <!-- Mobile lower Bar End -->
      </div>
      <!-- Conversation End -->
    </div>
    <!-- App One End -->
  </div>

  <!-- App End -->

</body>

</html>

<?php
}
else{
  header('Location:'. getenv('APP_URL')."/index.php");
}
?>

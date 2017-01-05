<?php

require_once (__DIR__ . '/vendor/autoload.php');
use ChatApp\Session;
use Dotenv\Dotenv;
$dotenv = new Dotenv(__DIR__);
$dotenv->load();

// die("Hello");
if(Session::get('start') != null)
{
    header("Location:".getenv('APP_URL')."/account.php");
}
?>
  <!Doctype html>
  <html>

  <head>
    <title>Open Chat</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="js/jquery-3.0.0.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- Latest compiled JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <style type="text/css">
      .navbar {
                border-radius: 0;
              }
              .heading {
                padding: 10px;
              }
            input {
              border-radius: 0px;
            }
    </style>
  </head>

  <body>
    <!-- Navbar -->
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
          <a class="navbar-brand" href="">OpenChat</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
        <!--     <li><a href="#Register"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
            <li><a href="#Login"><span class="glyphicon glyphicon-log-in"></span> Login</a></li> -->
          </ul>
        </div>
      </div>
    </nav>

    <!-- Main Body -->
    <div class="container"><br><br>
      <div class="row">

      <!-- Login -->
        <div class="col-sm-4 col-sm-offset-1">
          <div class="row">
            <div class="col-sm-12 text-center">
              <h2 class="heading">Login</h2>
            </div>
            <hr>
          </div>
          <form>
            <div class="form-group">
              <label for="login" id="loginLabel">Email or Username</label>
              <input type="text" name="login" id="login" class="form-control" placeholder="Email or Username">
            </div>
            <div class="form-group">
              <label for="passLogin" id="passLabelLogin">Password</label>
              <input type="password" name="passLogin" id="passLogin" class="form-control" placeholder="Password">
            </div>
            <button type="button" class="btn btn-success" onclick="loginCheck()" value="Login">Submit</button>
          </form>
        </div>

        <!-- Register -->
        <div class="col-sm-4 col-sm-offset-2">
          <div class="row">
            <div class="col-sm-12 text-center">
              <h2>Register</h2>
            </div>
            <hr>
          </div>
          <form>
            <div class="form-group">
              <label for="name" id="nameLabel">Your Name</label>
              <input type="text" name="name" class="form-control" id="name" placeholder="Name">
            </div>
            <div class="form-group">
              <label for="email" id="emailLabel">Email</label>
              <input type="email" name="email" id="email" class="form-control" placeholder="Email id">
            </div>
            <div class="form-group">
              <label id="userLabel">Username</label>
              <input type="text" name="username" id="username" class="form-control" placeholder="Username">
            </div>
            <div class="form-group">
              <label id="mobLabel">Mobile No.</label>
              <input type="text" name="mob" id="mob" class="form-control" placeholder="99******00">
            </div>
            <div class="form-group">
              <label for="passRegister" id="passLabelRegister">Password</label>
              <input type="password" name="passRegister" id="passRegister" class="form-control" class="form-control" placeholder="Password">
            </div>
            <button type="button" class="btn btn-success" onclick="registerCheck()" value="Register">Register</button>
          </form>
        </div>
      </div>
      <br><br><br>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Error</h4>
        </div>
        <div class="modal-body">
          <p>Fill all the details that are required.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

    <!-- Scripts -->
    <script type="text/javascript" src="js/login_validate.js"></script>
    <script type="text/javascript" src="js/register_validate.js"></script>
  </body>


  </html>
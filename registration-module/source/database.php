<?php
  // Define database connection constants
  define('DB_HOST', 'localhost');
  define('DB_USER', 'root');
  define('DB_PASSWORD','');
  define('DB_NAME', 'openchat');
  define('URL', URL());

   function URL()
  {
    $http = "http://";
    $host = $_SERVER['SERVER_NAME'];
    $port = $_SERVER['SERVER_PORT'];
    $fol = "";
    if(@$_SERVER['SERVER_ADDR']!=NULL)
    {
        $fol = "/".explode('/', $_SERVER['PHP_SELF'])[1];
    }
    $url = $http.$host.":".$port.$fol;
    return $url;
  }
?>

<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'rohit');
define('DB_PASSWORD', 'parayilla');
define('DB_DATABASE', 'shopcart');
$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
if (!$db) 
   {
      die("Connection failed: " . mysqli_connect_error());
   }
?>
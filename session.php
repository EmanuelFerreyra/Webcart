<?php
session_start();
include('config.php');
   $user= $_SESSION['login_user'];
   $ses_sql = mysqli_query($conn->on(),"select username from users where username = '$user' ");
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   $login_session = $row['username'];

   if(!isset($_SESSION['login_user']))
   {
      header("location:login.php");
      exit;
   }
?>

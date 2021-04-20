<?php
   session_start();
   $_SESSION['login_user']="";
   $user="";
   if(session_destroy()) {
      header("Location: login.php");
   }
?>

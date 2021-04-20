<?php
session_start();
include("config.php");

$username=$password="";
$username_err = $password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST") 
{
   if(empty(trim($_POST['username'])))
      $username_err = "Please enter a username.";
   else
      $username = trim($_POST['username']);

   if(empty(trim($_POST["password"])))
      $password_err = "Please enter a password.";
   else
      $password = trim($_POST["password"]);

   $sql = "SELECT id FROM users WHERE username = ? and password = ?";

   if($stmt = mysqli_prepare($db, $sql))
   {
      mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
      $param_username = $username;
      $param_password = $password;

      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_store_result($stmt);
      $row = mysqli_stmt_fetch($stmt);
      $active = $row['active'];
      $count = mysqli_stmt_num_rows($stmt);
      if($count == 1) 
      {
         $_SESSION['login_user']=$username;
         header("location: shop.php");
         exit;
      }
      else 
         $error = "Your Login Name or Password is invalid";
   }
   mysqli_stmt_close($stmt);
   mysqli_close($db);
}
?>
<html>
   <head>
      <title>Login </title>
      <style type = "text/css">
         body {
            font-family:Arial, Helvetica, sans-serif;
            font-size:14px;
         }
         label {
            font-weight:bold;
            width:100px;
            font-size:14px;
         }
         .box {
            border:#666666 solid 1px;
         }
      </style>
   </head>
   <body>
      <div align = "center">
         <div style = "background-color:#000000; color:#FFFFFF; padding:3px;"><b>Login</b></div>
         <div style = "margin:30px">
            <form action = "" method = "post">
               <label>UserName  :</label>
               <input type = "text" name = "username"/><span><?php echo $username_err; ?></span><br/><br />
               <label>Password  :</label>
               <input type = "password" name = "password"/><span><?php echo $password_err; ?></span><br/><br />
               <input type = "submit" value = " Submit "/><br />
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a>.</p>
            <div style = "font-size:11px; color:#FF0000; margin-top:10px"><?php echo $error; ?></div>
         </div>
      </div>
   </body>
</html>
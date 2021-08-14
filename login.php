<?php
session_start();
include("config.php");

$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   if (empty(trim($_POST['username'])))
      $username_err = "Please enter a username.";
   else
      $username = trim($_POST['username']);

   if (empty(trim($_POST["password"])))
      $password_err = "Please enter a password.";
   else
      $password = trim($_POST["password"]);

   $sql = "SELECT id FROM users WHERE username = ? and password = ?";

   if ($stmt = mysqli_prepare($conn->on(), $sql)) {
      mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
      $param_username = $username;
      $param_password = $password;

      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_store_result($stmt);
      $row = mysqli_stmt_fetch($stmt);
      $active = $row['active'];
      $count = mysqli_stmt_num_rows($stmt);
      if ($count == 1) {
         $_SESSION['login_user'] = $username;
         header("location: shop.php");
         exit;
      } else
         $error = "Your Login Name or Password is invalid";
   }
   mysqli_stmt_close($stmt);
   mysqli_close($conn->on());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
   <link rel="stylesheet" href="./normalize.css">
   <link rel="stylesheet" href="style.css">

</head>

<body>
   <div class="text-center login"><b>Login</b></div>
   <form class="form-login" action="" method="post">
      <input type="text" name="username" placeholder="User Name" />
     
      <?php print (empty($username_err)) ? '' : '<span class="error-login">'.$username_err.'</span>' ; ?>
      <br /><br />
      <input type="password" name="password" placeholder="Password" />
      <?php print (empty($password_err)) ? '' : '<span class="error-login">'.$password_err.'</span>' ; ?>
      <br /><br />
      <button class="btn btn-primary" type="submit" value=" Submit ">Enter</button><br />
      <div class="register">
      <p class="text-center">Don't have an account? <a href="register.php">Register here</a>.</p>
      <?php print (empty($error)) ? '' : '<div class="error-login">' . $error . '</div>'; ?>
   </div>
   </form>

</body>

</html>
<?php session_start();
include("config.php");

$username = $password = $email ="";
$username_err = $password_err = $email_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST")
{

    if(empty(trim($_POST['username'])))
    {
        $username_err = "Please enter a username.";
    }
    else
    {
        $temp=trim($_POST["username"]);
        $sql = "SELECT id FROM users WHERE username = '$temp'";
        $result = mysqli_query($conn->on(),$sql);
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
//        $active = $row['active'];
        $count = mysqli_num_rows($result);
        if($count== 1)
            $username_err = "This username is already taken.";
        else
            $username = trim($_POST["username"]);
    }

    if(empty(trim($_POST["password"])))
        $password_err = "Please enter a password.";
    elseif(strlen(trim($_POST["password"])) < 6)
        $password_err = "Password must have atleast 6 characters.";
    else
        $password = trim($_POST["password"]);

    if(empty(trim($_POST["email"])))
        $email_err = "Please enter an email";
    else
        $email = trim($_POST["email"]);

    if(empty($username_err) && empty($password_err) && empty($email_err))
    {
        $sql = "INSERT INTO users (username, password,email) VALUES (?, ?, ?)";

        if($stmt = mysqli_prepare($conn->on(), $sql))
        {
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password,$param_email);
            $param_username = $username;
            $param_password = $password;
            $param_email = $email;

            if(mysqli_stmt_execute($stmt))
            {
                header("location: login.php");
                die;
            } else
            {
                echo "Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }
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
    <div>
        <div class="wrapper">
        <h2 class="text-center">Sign Up</h2>
        <p class="text-center">Please fill this form to create an account.</p>
        <form class="form-login" method="post" >
            <div >
                <input type="text" name="username" placeholder="Username" class="form-control" value="">
                <?php print (empty($username_err)) ? '' : '<span class="error-login">'.$username_err.'</span>' ; ?>
                <br />
            </div>
            <div >
                <input type="password" name="password" placeholder="Password" class="form-control" value="">
                <?php print (empty($password_err)) ? '' : '<span class="error-login">'.$password_err.'</span>' ; ?>
                <br />
            </div>
            <div >
                <input type="email" name="email" placeholder="Email" class="form-control" value="">
                <?php print (empty($email_err)) ? '' : '<span class="error-login">'.$email_err.'</span>' ; ?>
                <br />
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit" value="Submit">Submit</button>
                <button class="btn btn-primary" type="reset" value="Reset">Reset</button>
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
    </div>
</body>
</html>

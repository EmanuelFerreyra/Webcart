<?php session_start();
include("config.php");

$username = $password = $confirm_password = $email ="";
$username_err = $password_err = $confirm_password_err = $email_err = "";

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
        $result = mysqli_query($db,$sql);
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        $active = $row['active'];
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

    if(empty(trim($_POST["confirm_password"])))
        $confirm_password_err = "Please confirm password.";
    else
    {
        $confirm_password = trim($_POST["confirm_password"]);
        
        if(empty($password_err) && ($password != $confirm_password))
            $confirm_password_err = "Password did not match.";
    }

    if(empty(trim($_POST["email"])))
        $email_err = "Please enter an email";
    else
        $email = trim($_POST["email"]);

    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err))
    {
        $sql = "INSERT INTO users (username, password,email) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($db, $sql))
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
    mysqli_close($db);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
</head>
<body>
    <div>
        <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form method="post" >
            <div >
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="">
                <span><?php echo $username_err; ?></span><br />
            </div>    
            <div >
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="">
                <span><?php echo $password_err; ?></span><br />
            </div>
            <div >
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="">
                <span><?php echo $confirm_password_err; ?></span><br />
            </div>
            <div >
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="">
                <span><?php echo $email_err; ?></span><br />
            </div>
            <div class="form-group">
                <input type="submit" value="Submit">
                <input type="reset" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
    </div>    
</body>
</html>
<?php
session_start();
include('config.php');
include('session.php');
if($_SESSION['login_user']!='admin')
{
   header("location:shop.php");
   exit;
}

$sql="";
$status ="";
$name = $price = "";
$name_err = $price_err = "";
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	if (isset($_POST['action']) && $_POST['action']=="remove")
	{
		$id=trim($_POST["id"]);
        $sql = "DELETE FROM products WHERE id = '$id'";
        if($result = mysqli_query($db,$sql))
            $status="Product removed successfully";      
        else
            $status=" Something intervened";
        //remove image
	}
if (isset($_POST['name']) && isset($_POST['price']))
{
    if(empty(trim($_POST['name'])))
        $name_err = "Please enter a name.";
    else
    {
        $temp=trim($_POST["name"]);
        $sql = "SELECT id FROM products WHERE name = ?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "s", $temp);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_store_result($stmt);
        $row = mysqli_stmt_fetch($stmt);
        $active = $row['active'];
        $count = mysqli_stmt_num_rows($stmt);               
        if($count== 1)
            $name_err = "There is a product with the same name.";
        else
            $name = trim($_POST["name"]);
    }
    if (isset($_POST['price']))
    {
        if(empty(trim($_POST["price"])))
            $price_err = "Please enter a price.";
        else
            $price = trim($_POST["price"]);
    }
    $target = "images/".basename($_FILES["fileToUpload"]["name"]);
    $target_dir = $_SERVER['DOCUMENT_ROOT']."/shopcart/images/";
    $target_file = $target_dir.basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 0;
    $imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION));
    if(in_array($imageFileType, array('jpg', 'jpeg', 'png', 'gif')))
    {
        if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target))
            $uploadOk=1;
        else
            $status="Something went wrong ";
    }
    else
    {
        die("Not an image Try image.online-convert.com");
    }

    if(empty($name_err) && empty($price_err) && $uploadOk)
    {
        $sql = "INSERT INTO products (name, price, image) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_price,$param_image);
            $param_name = $name;
            $param_price = $price;
            $param_image = $target;

            if(mysqli_stmt_execute($stmt))
                $status="Product added Successfully";
            else
                $status="Something went wrong. Please try again later.";

            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($db);
}
}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="style.css">
	<title>Admin panel</title>
</head>
<body>
	<div style = "background-color:#000000; color:#FFFFFF; padding:3px;">
        <h1 align="center">Admin Panel</h1>
    </div>
	<div align = "right">
		<h3>Welcome <?php echo $login_session; ?></h3> 
		<button onclick="document.location='logout.php'">Logout</button>
	</div>
<form action="" method="post" enctype="multipart/form-data">
	<div>
		<label>Select image of product:</label>
		<input type="file" name="fileToUpload" id="fileToUpload">
	</div>
	<div class="form-group" >
		<label>Name of product</label>
		<input type="text" name="name" class="form-control" value="">
		<span><?php echo $name_err; ?></span><br/>
	</div>    
	<div class="form-group">
		<label>Price of product</label>
		<input type="text" name="price" class="form-control" value="">
		<span><?php echo $price_err; ?></span><br/>
	</div>
	<input type="submit" value="Upload product" name="submit">
</form>
<div style = "font-size:11px; color:#FF0000; margin-top:10px"><?php echo $error; ?></div>
<table class="table">
<tr>
<td>IMAGE</td>
<td>ID</td>
<td>PRODUCT NAME</td>
<td>PRICE</td>
</tr> 
<?php 
$result = mysqli_query($db,"SELECT * FROM `products`");
while ($product=mysqli_fetch_array($result))
{
?>
<tr>
<td>
<img src='<?php echo $product["image"]; ?>' width="70" height="70" />
</td>
<td>
	<?php echo $product["id"]; ?>
</td>
<td><?php echo $product["name"]; ?><br />
<form method='post' action=''>
<input type='hidden' name='id' value="<?php echo $product['id']; ?>" />
<input type='hidden' name='action' value="remove" />
<button type='submit' class='remove'>Remove Item</button>
</form>
</td>
<td><?php echo $product["price"]."Rs"; ?></td>
</tr>
<?php
}
?>
</table> 
<?php echo $status; ?>
</body>
</html>
<?php
session_start();
include('config.php');
include('session.php');
if($_SESSION['login_user']=='admin')
{
   header("location:admin.php");
   exit;
}
?>
<html>
	<head>
		<link rel="stylesheet" href="style.css">
		<title>SHOP</title>
	</head>
	<body>
        <h1 align="center">Welcome to shopcart</h1>
		<div align = "right">
			<h3>Welcome <?php echo $login_session; ?></h3> 
			<button onclick="document.location='logout.php'">Logout</button>
		</div>
	</body>
</html>
<?php
$status="";
if (isset($_POST['id']) && $_POST['id']!="")
{
    $id = $_POST['id'];
    $result = mysqli_query($db,"SELECT * FROM `products` WHERE `id`='$id'");
    $row = mysqli_fetch_assoc($result);
    $name = $row['name'];
    $id = $row['id'];
    $price = $row['price'];
    $image = $row['image'];

    $cartArray = array(
        $id=>array(
            'name'=>$name,
            'id'=>$id,
            'price'=>$price,
            'quantity'=>1,
            'image'=>$image));

    if(empty($_SESSION["webcart"])) 
    {
        $_SESSION["webcart"] = $cartArray;
        $status = "<div class='box'>Product is added to your cart!</div>";
    }
    else
    {
        $array_keys = array_keys($_SESSION["webcart"]);
        if(in_array($id,$array_keys)) 
        {
            $status = "<div style='color:red;'>Product is already added to your cart!</div>";
        } 
        else 
        {
            $_SESSION["webcart"] = array_merge($_SESSION["webcart"],$cartArray);
            $status = "<div class='box'>Product is added to your cart!</div>";
        }
    }
}
?>
<?php
if(!empty($_SESSION["webcart"])) 
{
    $cart_count = count(array_keys($_SESSION["webcart"]));
}
else
{
    $cart_count = 0;
}
?>
<div class="cart_img">
<a href="cart.php"><img src="images/cart.png" width="80" height="80"/> Cart<span>
<?php echo $cart_count; ?></span></a></div>
<?php
$result = mysqli_query($db,"SELECT * FROM `products`");
while($row = mysqli_fetch_assoc($result))
{
    echo "<div class='product'>
    <form method='post' action=''>
    <input type='hidden' name='id' value=".$row['id']." />
    <div class='image'><img src='".$row['image']."' /></div>
    <div class='name'>".$row['name']."</div>
    <div class='price'>".$row['price']."-Rs</div>
    <button type='submit' class='buy'>Add to cart</button>
    </form>
    </div>";
}
mysqli_close($db);
?>
<div style="clear:both;"></div>
 
<div class="message_box" style="margin:10px 0px;">
<?php echo $status; ?>
</div>


<?php
session_start();
include('config.php');
include('session.php');
?>
<?php
$status="";
if (isset($_POST['action']) && $_POST['action']=="remove")
{
	if(!empty($_SESSION["webcart"])) 
	{
		foreach($_SESSION["webcart"] as $key => $value) 
		{
			if($_POST["id"] == $key)
			{
				unset($_SESSION["webcart"][$key]);
				$status = "<div style='color:red;'>Product is removed from your cart!</div>";
			}
			if(empty($_SESSION["webcart"]))
				unset($_SESSION["webcart"]);
		} 
	}
}
if (isset($_POST['action']) && $_POST['action']=="change")
{
	foreach($_SESSION["webcart"] as $value)
	{
		if($value['id'] === $_POST["id"])
		{
			$value['quantity'] = $_POST["quantity"];
			break;
		}
	}
}
?>
<html>
<head>
	<link rel="stylesheet" href="style.css">
	<title>Cart</title>
</head>
<div class="cart">
<?php
if(isset($_SESSION["webcart"]))
{
    $total_price = 0;
?> 
<table class="table">
<tbody>
<tr>
<td>IMAGE</td>
<td>PRODUCT NAME</td>
<td>QUANTITY</td>
<td>UNIT PRICE</td>
<td>TOTAL</td>
</tr> 
<?php 
foreach ($_SESSION["webcart"] as $product)
{
?>
<tr>
<td>
<img src='<?php echo $product["image"]; ?>' width="60" height="60" />
</td>
<td><?php echo $product["name"]; ?><br />
<form method='post' action=''>
<input type='hidden' name='id' value="<?php echo $product['id']; ?>" />
<input type='hidden' name='action' value="remove" />
<button type='submit' class='remove'>Remove Item</button>
</form>
</td>
<td>
<form method='post' action=''>
<input type='hidden' name='id' value="<?php echo $product['id']; ?>" />
<input type='hidden' name='action' value="change" />
<input type="text" name="quantity" value="<?php echo $product['quantity']; ?>"/>
<button type='submit' class='remove'>Change quantity</button>
</form>
</td>
<td><?php echo $product["price"]."Rs"; ?></td>
<td><?php echo $product["price"]*$product["quantity"]."Rs"; ?></td>
</tr>
<?php
$total_price += ($product["price"]*$product["quantity"]);
}
?>
<tr>
<td colspan="5" align="right">
<b>TOTAL: <?php echo $total_price."Rs"; ?></b>
</td>
</tr>
</tbody>
</table> 
  <?php
}
else
{
	echo "<h3>Your cart is empty!</h3>";
}
?>
</div>
<div style="clear:both;"></div>
<div class="message_box" style="margin:10px 0px;">
<?php echo $status; ?>
</div>
<div align = "right" ><button type='submit' class='remove'>Proceed to checkout</button></div>
</html>
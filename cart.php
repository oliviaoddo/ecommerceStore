<?php 
session_start();
//Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "connect_to_mysql.php";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Mangia Bene</title>

    <!-- Bootstrap core CSS -->
   <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Custom styles for this template -->
    <link href="carousel.css" rel="stylesheet">
    <link href="mangiabene.css" rel="stylesheet">
    
  </head>

	<body>
	
<header id="logo">

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.html">HOME</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="about.html">ABOUT <span class="sr-only">(current)</span></a></li>
        <li><a href="contact.html">CONTACT</a></li>
        <li><a href="menu.html">MENU</a></li>
        <li><a href="order.php">ORDER ONLINE</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="cart.php">CART</a></li>
        <li><a href="admin_login.php">LOG IN</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

	
</header>

<?php
if (isset($_POST['pid'])) {
    $pid = $_POST['pid'];
	$wasFound = false;
	$i = 0;
	// If the cart session variable is not set or cart array is empty
	if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) { 
	    // RUN IF THE CART IS EMPTY OR NOT SET
		$_SESSION["cart_array"] = array(0 => array("item_id" => $pid, "quantity" => 1));
	} else {
		// RUN IF THE CART HAS AT LEAST ONE ITEM IN IT
		foreach ($_SESSION["cart_array"] as $each_item) { 
		      $i++;
		      while (list($key, $value) = each($each_item)) {
				  if ($key == "item_id" && $value == $pid) {
					  // That item is in cart already so let's adjust its quantity using array_splice()
					  array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $pid, "quantity" => $each_item['quantity'] + 1)));
					  $wasFound = true;
				  } // close if condition
		      } // close while loop
	       } // close foreach loop
		   if ($wasFound == false) {
			   array_push($_SESSION["cart_array"], array("item_id" => $pid, "quantity" => 1));
		   }
	}
	header("location: cart.php"); 
    exit();
}

?>
<?php
//if user deletes from cart
if(isset($_POST['index_to_remove']) && $_POST['index_to_remove']!=""){
	//Access the array and run code to remove that array index 

	$key_to_remove = $_POST['index_to_remove'];
	echo 'Index -'.$key_to_remove.': Count -';
	if(count($_SESSION["cart_array"]) <= 1){
		unset($_SESSION["cart_array"]);
	}else {
		unset($_SESSION["cart_array"]["$key_to_remove"]);
		sort($_SESSION["cart_array"]);
		echo count($_SESSION["cart_array"]);
	}

}

?>
<?php
//user adjusts quantity
if(isset($_POST['item_to_adjust']) && $_POST['item_to_adjust'] !="") {
	$item_to_adjust = $_POST['item_to_adjust'];
	$quantity = $_POST['quantity'];
	$quantity = preg_replace('#[^0-9]#i', '', $quantity); // filter everything but numbers
	if ($quantity >= 100) { $quantity = 99; }
	if ($quantity < 1) { $quantity = 1; }
	if ($quantity == "") { $quantity = 1; }
	$i = 0;
	foreach ($_SESSION["cart_array"] as $each_item) { 
		      $i++;
		      while (list($key, $value) = each($each_item)) {
				  if ($key == "item_id" && $value == $item_to_adjust) {
					  // That item is in cart already so let's adjust its quantity using array_splice()
					  array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $item_to_adjust, "quantity" => $quantity)));
				  } // close if condition
		      } // close while loop
	       } // close foreach loop
	
}
?>

<?php
//If user empties shopping cart 
if(isset($_GET['cmd']) && $_GET['cmd'] == "emptycart") {
	unset($_SESSION["cart_array"]);
}
?>

<?php
//cart for viewing 

$cartOutput = "";
$cartTotal = "";
if(!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1){
	$cartOutput = "<h2 align = 'center'>Your Shopping Cart is empty</h2>";
}else{
	$i = 0;
	foreach($_SESSION["cart_array"] as $each_item) {
		$item_id = $each_item['item_id'];
		$sql = mysqli_query($link, "SELECT * FROM products WHERE id ='$item_id' LIMIT 1");
		while ($row = mysqli_fetch_array($sql)){
			$product_name = $row['name'];
			$price = $row['price'];
			$description = $row['description'];
		}
		$priceTotal = $price * $each_item['quantity'];
		$cartTotal = $priceTotal + $cartTotal;

		setlocale(LC_MONETARY, "en_US");
		$priceTotal = money_format("%10.2n", $priceTotal);
		//Dynamic table row assembly 

		$cartOutput .="<tr>";
		$cartOutput .= "<td> <a href =\"product.php?id=$item_id\">$product_name</a> <br /><img src=\"$item_id.jpg\" alt = \"$product_name\" width =\"40\" height=\"52\" border =\"1\"/></td>";
		$cartOutput .= "<td>" . $description . "</td>";
		$cartOutput .= "<td>$" . $price . "</td>";
		$cartOutput .= '<td><form action="cart.php" method="post">
		<input name="quantity" type="text" value="' . $each_item['quantity'] . '" size="1" maxlength="2" />
		<input name="adjustBtn' . $item_id . '" type="submit" value="change" />
		<input name="item_to_adjust" type="hidden" value="' . $item_id . '" />
		</form></td>';
		//$cartOutput .= "<td>" . $each_item['quantity'] . "</td>";
		$cartOutput .= "<td>" . $priceTotal . "</td>";
		$cartOutput .= '<td><form action="cart.php" method="post"><input name="deleteBtn' . $item_id . '" type="submit" value="X" /><input name="index_to_remove" type="hidden" value="' . $i . '" /></form></td>';
		$cartOutput .="</tr>";
		$i++;

	}
	setlocale(LC_MONETARY, "en_US");
	$cartTotal = money_format("%10.2n", $cartTotal);
	$cartTotal = "<div style = 'font-size: 18px; margin-top:12px;' align='right'>Cart Total: ".$cartTotal."USD</div>";
}
?>

<div id="pageContent">
    <div style="margin:24px; text-align:left;">
	
    <br />
    <table width="100%" border="1" cellspacing="0" cellpadding="6">
      <tr>
        <td width="18%" bgcolor="#C5DFFA"><strong>Product</strong></td>
        <td width="45%" bgcolor="#C5DFFA"><strong>Product Description</strong></td>
        <td width="10%" bgcolor="#C5DFFA"><strong>Unit Price</strong></td>
        <td width="9%" bgcolor="#C5DFFA"><strong>Quantity</strong></td>
        <td width="9%" bgcolor="#C5DFFA"><strong>Total</strong></td>
        <td width="9%" bgcolor="#C5DFFA"><strong>Remove</strong></td>
      </tr>
<?php echo $cartOutput; ?>
  <!-- <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr> -->
    </table>
       <?php echo $cartTotal; ?>
<br /> <br />
<a href = "cart.php?cmd=emptycart">Click here to empty shopping cart</a>
</div>
<br />
</div>
</div>
<footer class="footer">
       <div id="footer">
        <div class="row">
        
        	<section class="col-sm-4">
    			<h3>LOCATION</h3>
    			<br /> 
    			<br />
    			<p>Address
    			<br />27281 La Paz Road, Suite I
    			<br />Laguna Niguel, CA 92677
				</p>
				</section>
			
      			<section class="col-sm-4">
    				<h3>HOURS</h3>
    				</br>
    				<br /><p>Sun- Thurs 11:30 am. to 9:30 pm.
    				<br />Friday & Sat 11:30 am - 10:30 pm.</p>
    				<br/>
    				<!-- Trigger the modal with a button -->
<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" id="happyhours">Happy Hours</button>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title">Happy Hour!</h2>
      </div>
      <div class="modal-body">
        <p>Enjoy happy hour daily at the Mangia Bene Bar!</p>
        <br /><p>11:30 am - 6:30pm</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
    				
    			</section>
    			
      			<section class="col-sm-4">
    			<h3>FOLLOW US</h3>
    			<a href="https://www.instagram.com/"><img class="twitter" src="images/instagramgood.png" alt="Twitter" ></a>
    			<a href="https://twitter.com/?lang=en"><img class="twitter" src="images/twittergood.png" alt="Twitter" ></a>
    			<a href="https://www.facebook.com/MangiaBeneCucinas"><img class="twitter" src="images/facebookgood.png" alt="Twitter" ></a>
    			</section>
    		
    		</div>
      </div>
    </footer>

</body>
</html>

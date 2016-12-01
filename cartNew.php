<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Order Online</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
        <link href="mangiabene.css" rel="stylesheet">
    </head>
    <body onload = "viewCart()">
    	<div>
            <?php include_once("nav.php");?>
         </div>

         <div>
			<table id="cartTable">
			  <tr>
			    <th>Product</th>
			    <th>Description</th> 
			    <th>Unit Price</th>
			    <th>Quantity</th>
			    <th>Total</th>
                <th>Remove</th>
			  </tr>
			</table>
         </div>
         <h5 id="subtotal">Subtotal: $</h5>
         <a id="checkout" href = "checkout.php"><button type = "button">Checkout</button></a>
         <button id = "clearCart" type="button">Clear Cart</button>

 		<div>
            <?php include_once("footer.php");?>
        </div>

        <script src="http://code.jquery.com/jquery-1.11.0.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="lightbox.js" type="text/javascript" charset="utf-8"></script>
        <script src="viewCart.js" type="text/javascript" charset="utf-8"></script>
        <script src="deleteItem.js" type="text/javascript" charset="utf-8"></script>
        <script src="updateQuantity.js" type="text/javascript" charset="utf-8"></script>
    </body>
</html>
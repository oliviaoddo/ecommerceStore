function product(id){
 	$('.popUp').hide();
 	var url = window.location.href;
 	var idText = "?id=";
 	var strip_text = url.substring(0, url.indexOf(idText) + idText.length);
 	var id = url.substring(strip_text.length, url.length);

 	var xhr = new XMLHttpRequest();
 	xhr.onreadystatechange = function(){
 		if(xhr.readyState === 4 && xhr.status === 200) {
 			var jsonResponse = JSON.parse(xhr.responseText);
 			document.getElementById('productName').innerHTML = jsonResponse.productName;
 			document.getElementById('productPrice').innerHTML = "$" + jsonResponse.productPrice;
 			document.getElementById('productDescription').innerHTML = jsonResponse.productDesc;
 			document.getElementById('productCalories').append(jsonResponse.productCalories);
 			document.getElementById('productFat').append(jsonResponse.productFat);
 			document.getElementById('productSugar').append(jsonResponse.productSugar);
 			document.getElementById('productProtein').append(jsonResponse.productProtein);
 			document.getElementById('productCarbs').append(jsonResponse.productCarbs);
 			document.getElementById('productImage').createElement

 			var ext = ".png"; //File Extension 

			var link = document.createElement('a');
			var elem = document.createElement("img");
			link.setAttribute("href", "inventory_images/" + id + ext);
			elem.setAttribute("src", "inventory_images/" + id + ext);
			elem.setAttribute("alt", jsonResponse.productName);

		    elem.setAttribute("height", "200px");
		    elem.setAttribute("width", "300px");

		    link.appendChild(elem);
		    document.getElementById("productImage").appendChild(link);

		    $('#addToCart').click(function(){
		    		var cartEntries = JSON.parse(localStorage.getItem("allEntries"));
		    		var found = false;
		    		if(cartEntries == null) cartEntries = [];
		    		for(i = 0; i<cartEntries.length; i++){
		    			if(cartEntries[i].productId == jsonResponse.productId){
		    				//console.log(cartEntries[i].productId++);
		    				console.log(cartEntries[i].productQuantity);

		    				localStorage.setItem("cartEntries[i].productQuantity", JSON.stringify(cartEntries[i].productQuantity++));
		    				console.log(cartEntries[i].productQuantity);
		    				found = true;
		    				//var price = parseInt(cartEntries[i].productPrice);
		    				//var quantity = cartEntries[i].productQuantity;
		    				//var total = price * quantity;
		    				//console.log(total);
		    				//console.log(quantity);
		    				//localStorage.setItem("cartEntries[i].productTotal", JSON.stringify(total));
		    			}
		    		}
		    		console.log(found);
		    		console.log(cartEntries);
		    		//create new product in the cart 
		    		if(found == false){
		    			var quantity = 1;
			    		var cartEntry = {
			    			"productId": jsonResponse.productId,
					        "productName": jsonResponse.productName,  
				    		"productDesc": jsonResponse.productDesc, 
				    		"productPrice": jsonResponse.productPrice, 
				    		"productQuantity": quantity, 
				    		"productTotal": jsonResponse.productPrice * quantity
	    				}
	    				localStorage.setItem("cartEntry", JSON.stringify(cartEntry));
	   					// Save allEntries back to local storage
	    				cartEntries.push(cartEntry);
	    				localStorage.setItem("allEntries", JSON.stringify(cartEntries));
	    				//console.log(cartEntries);
	    				//console.log(cartEntries[0].productName);
    				}
    		
    			console.log(cartEntries);
	
    			var $overlay = $('<div id="overlay"></div>'); 
				var $popUp = $("div.popUp");
				console.log($popUp);
				$overlay.append($popUp);
				$("body").append($overlay);
				$overlay.show();
				$('.popUp').show();
				$overlay.click(function(){
				  //Hide the overlay
				  $overlay.hide();
				})
		    })
		    
			 
		}

 	}
 
 	var queryString = "?id="  + id;
 
 	xhr.open("GET", "productPage.php" + queryString, true);
 	xhr.send();
 };
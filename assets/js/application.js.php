	$(document).ready(function () {
			//add leading zeros to a number function
			function pad(str, max) {
				return str.length < max ? pad("0" + str, max) : str;
			}
			//handle first box change
			$('#firstone').change(function () {
				
				//remove anything that has been populated already
				$('#dynamicboxes').html('');
				
				//remove the price since we will need to be recalculating this
				$('#price').html('--');
				
				//remove the price drop-down, we will be adding this back soon 
				$('#pricebox').html('');

				//make the request for the next level of items depending on their first selection
				$.ajax({
					type: "POST",
					url: "<?php echo INSTALL_PATH; ?>/index.php/json/" + $('option:selected', this).val() + "/" + $('option:selected', this).attr('data-level') + "/" + $('option:selected', this).attr('data-order') + "/",
					dataType: 'json',
										
					//show the loading animation GIF
					beforeSend: function () {
						$('#loading-image').fadeTo('fast', 1);
					},
					
					//now hide it
					complete: function () {
						$('#loading-image').fadeTo('fast', 0);
					},

					success: function (data) {
						$i = 0;
						$.each(data, function (i, value) {
							value = $.parseJSON(value);
							$i = $i + 1;
							//build the dropdown using the returned JSON data
							if ($i != 1) {
								$('#box' + $papaid).append('<option value="' + value.id + '" data-level=' + value.level + ' >' + value.name + '</option>');
							} else {
								//if it is the firat item do the wrapper and initial drop down option
								$('#dynamicboxes').append('<div class="control-group" data-order="1"><label id=' + value.level + ' data-level=' + value.level + '>' + value.name + '</label><select class="span3" id="box' + value.id + '" data-order=' + value.order + ' data-level="' + value.level + '"><option value ="">-- Choose One</option></select></div>');
								$papaid = value.id;
							}
						});
					}
				});
			})
			
			//handle all other dropdown changes 
			$('#dynamicboxes').on("change", "select", function () {
				
				//remove the price since we will need to be recalculating this
				$('#price').html('--');
				
				//remove the price drop-down, we will be adding this back soon 
				$('#pricebox').html('');
				
				//define some variables
				$productID = "";
				$curLev = parseInt($(this).attr('data-order')); //current Level that the user is making changes on
				
				
				//remove any boxes that go after this once, since they COULD be different since the user changed their selection
				$("[data-order]").filter(function () {
					return parseInt($(this).attr('data-order'), 10) > $curLev;
				}).remove();
				
				//lets get the md5 value of current product
				$('option:selected').each(function () {
					$productID = $productID + pad($(this).attr('value'), 3);
				});
				
				//figure out what attribute we need to display next
				$nextStep = parseInt($('#box' + $papaid).attr('data-order'));
				if (!$nextStep) {
					$nextStep = $curLev
				}
				$nextStep = $nextStep + 1;
				
				//now that we know what the next item order number is, lets ask for its JSON data
				$.ajax({
					type: "POST",
					url: "<?php echo INSTALL_PATH; ?>/index.php/json/" + $('#firstone option:selected').val() + "/1/" + $nextStep + "/" + $('option:selected', this).val(),
					dataType: 'json',
					
					//show the loading animation GIF
					beforeSend: function () {
						$('#loading-image').fadeTo('fast', 1);
					},
					
					//now hide it
					complete: function () {
						$('#loading-image').fadeTo('fast', 0);
					},
					success: function (data) {
						
						//alright, lets run through the JSON data and build the next dropdown box to show more options 
						$i = 0;
						$.each(data, function (i, value) {
							value = $.parseJSON(value);
							$i = $i + 1;
							if ($i == 1) {
								
								//if this is the first item, build the wrapper and first item to show
								$('#dynamicboxes').append('<div class="control-group" data-order="' + value.order + '"><label id="box' + value.level + '" data-level=' + value.level + '>' + value.name + '</label><select class="span3" id="box' + value.id + '" data-order=' + value.order + ' data-level="' + value.level + '"><option value ="">-- Choose One</option></select></div>');
								$papaid = value.id;
							} else {
								
								//add the item to the dropdown
								$('#box' + $papaid).append('<option value="' + value.id + '"  data-level=' + value.level + ' >' + value.name + '</option>');
							}
						});
					}
				}); //end ajax request
				
				
				
				//lets check the hash value of what we currently have to see if there is
				//a mathcing record in the database for a pricing table
				
				//show the current hash number 
				$('#productnumber').html($.md5($productID));
				
				//make the link active for the product number
				$('#combolink').html($.md5($productID));
				$("#combolink").attr("href", "<?php echo INSTALL_PATH; ?>/index.php/pricelist/" + $.md5($productID) + "/");
				
				//get the pricelist if there is one for the combo id 
				$.ajax({
					type: "POST",
					url: "<?php echo INSTALL_PATH; ?>/index.php/jsonPrices/" + $.md5($productID) + "/",
					dataType: 'json',
					success: function (data) {
						
						//since there is one, lets create the dropdown of items by running through the JSON data
						$i = 0;
						$.each(data, function (i, value) {
							value = $.parseJSON(value);
							$i = $i + 1;
							if ($i != 1) {
								
								//this is not the first item, so just add it to the list of options
								$('#quantities').append('<option value="' + value.id + '" data-price="' + value.price + '" >' + value.quantity + '</option>');
							} else {
								
								//this is the first item so make the wrapper and add the first blank  option to the list
								$('#pricebox').append('<div class="control-group"><label id="labelboxp' + value.id + '">Quantity</label><select class="span3" id="quantities" data-order=' + value.order + ' data-level="' + value.level + '"><option value ="">-- Choose One</option><option value="' + value.id + '"  data-price="' + value.price + '" >' + value.quantity + '</option></select></div>');
							}
						});
					}
				}); //end ajax request
				
				//if you select an option in the price dropdown then update the price area in the HTML section 
				$('#pricebox').on("change", "#quantities", function () {
					$price = $('option:selected', this).attr('data-price');
					$('#price').html($price);
				});
			})
		})
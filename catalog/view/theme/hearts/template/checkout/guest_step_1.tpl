<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
	<div class="top">
		<div class="left"></div>
		<div class="right"></div>
		<div class="center">
			<h1><?php echo $heading_title; ?></h1>
		</div>
	</div>
	<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<div class="middle">
		<div id="zpayment"><?if ($payment) { echo '<div id="fpayment">' . $payment. '</div>'; } else {?><p><?php echo $text_account_already; ?></p>
		<?if ($ccpic) {?><div class="light" style="padding: 10px; margin-bottom: 10px;">
		<img src='image/ccinfo.png'/>
		</div><br>
		<p>Fill in all the info below and hit Continue - if your information is valid, you'll be able to fill in your credit card details</p>
		<br>
		
		<?} }?></div>
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="guest">
			<b style="margin-bottom: 2px; display: block;"><?php echo $text_your_details; ?></b>
			<div class="light" style="padding: 10px; margin-bottom: 10px;">
				<table>
					<tr>
						<td><span class="required">*</span> <?php echo $entry_email; ?></td>
						<td><input id="email" type="text" name="email" value="<?php echo $email; ?>" />
							<?php if ($error_email) { ?>
							<span class="error"><?php echo $error_email; ?></span>
							<?php } ?></td>
					</tr><?if (!$payment) {?>
					<tr>
						<td><div class="guestlogin"><?php echo $entry_password; ?></div></td>
						<td>
							<div class="guestlogin">
								<input id="password" type="password" name="password" value="" />
								<br />
								<a onclick="guestLogin();" style="text-decoration: none;" class="button"><span><?php echo $button_login; ?></span></a>
								<br />
								<?php echo $text_login; ?>
								<input type="hidden" name="redirect" value="<?php echo $onepage; ?>" />
							</div>
						</td>
					</tr><?}?>
					<tr>
						<td width="150"><span class="required">*</span> <?php echo $entry_firstname; ?></td>
						<td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
							<?php if ($error_firstname) { ?>
							<span class="error"><?php echo $error_firstname; ?></span>
							<?php } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
						<td><input type="text" name="lastname" value="<?php echo $lastname; ?>" />
							<?php if ($error_lastname) { ?>
							<span class="error"><?php echo $error_lastname; ?></span>
							<?php } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
						<td><input type="text" name="telephone" value="<?php echo $telephone; ?>" />
							<?php if ($error_telephone) { ?>
							<span class="error"><?php echo $error_telephone; ?></span>
							<?php } ?></td>
					</tr>
					<tr>
						<td><?php echo $entry_fax; ?></td>
						<td><input type="text" name="fax" value="<?php echo $fax; ?>" /></td>
					</tr>
					<tr>
					<td><span class="required">*</span> Password:</td><td><input id="pass1" type="password" name="pass1" value="<?echo $pass1;?>" /></td></tr>
					<tr><td><span class="required">*</span> Confirm Password:</td><td><input id="pass2" type="password" name="pass2" value="<?echo $pass2;?>" /></td></tr>
					<tr><td></td><td><?php if ($error_password) { ?>
							<span class="error"><?php echo $error_password; ?></span>
							<?php } ?></td></tr>
				</table>
			</div>
			<b style="margin-bottom: 2px; display: block;"><?php echo $text_your_payment_address; ?></b>
			<div id="paymentAddress" class="light" style="padding: 10px; margin-bottom: 10px;">
				<table>
					<tr>
						<td width="150"><?php echo $entry_company; ?></td>
						<td><input type="text" name="payment_company" value="<?php echo $payment_company; ?>" onblur="updatePayment()" /></td>
					</tr>
					<tr>
						<td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
						<td><input type="text" name="payment_address_1" value="<?php echo $payment_address_1; ?>" onblur="updatePayment()" />
							<?php if ($error_payment_address_1) { ?>
							<span class="error"><?php echo $error_payment_address_1; ?></span>
							<?php } ?></td>
					</tr>
					<tr>
						<td><?php echo $entry_address_2; ?></td>
						<td><input type="text" name="payment_address_2" value="<?php echo $payment_address_2; ?>" onblur="updatePayment()" /></td>
					</tr>
					<tr>
						<td><span class="required">*</span> <?php echo $entry_city; ?></td>
						<td><input type="text" name="payment_city" value="<?php echo $payment_city; ?>" onblur="updatePayment()" />
							<?php if ($error_payment_city) { ?>
							<span class="error"><?php echo $error_payment_city; ?></span>
							<?php } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span> <?php echo $entry_postcode; ?></td>
						<td><input type="text" name="payment_postcode" value="<?php echo $payment_postcode; ?>" onblur="updatePayment()" /></td>
					</tr>
					<tr>
						<td><span class="required">*</span> <?php echo $entry_country; ?></td>
						<td><select name="payment_country_id" id="payment_country_id" onchange="$('select[name=\'payment_zone_id\']').load('index.php?route=checkout/guest_step_1/zone&country_id=' + this.value + '&zone_id=<?php echo $payment_zone_id; ?>'); updatePayment();">
							<option value="FALSE"><?php echo $text_select; ?></option>
							<?php foreach ($countries as $country) { ?>
							<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
							<?php } ?>
							</select>
							<?php if ($error_payment_country) { ?>
							<span class="error"><?php echo $error_payment_country; ?></span>
							<?php } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span> <?php echo $entry_zone; ?></td>
						<td><select name="payment_zone_id" onchange="updatePayment();">
							</select>
							<?php if ($error_payment_zone) { ?>
							<span class="error"><?php echo $error_payment_zone; ?></span>
							<?php } ?></td>
					</tr>
				</table>
			</div>
			<?php if ($is_shipping) { ?>
			<b style="margin-bottom: 2px; display: block;"><?php echo $text_your_shipping_address; ?></b>
			<div id="shippingAddress" class="light" style="padding: 10px; margin-bottom: 10px;">
				<table>
					<tr>
						<td width="150"><?php echo $entry_same_address; ?></td>
						<td><a id="sameAddress" class="button"><span><?php echo $button_copy_address; ?></span></a></td>
					</tr>
					<tr>
						<td width="150"><?php echo $entry_company; ?></td>
						<td><input type="text" name="shipping_company" value="<?php echo $shipping_company; ?>" onchange="updateShipping();" /></td>
					</tr>
					<tr>
						<td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
						<td><input type="text" name="shipping_address_1" value="<?php echo $shipping_address_1; ?>" onchange="updateShipping();" />
							<?php if ($error_shipping_address_1) { ?>
							<span class="error"><?php echo $error_shipping_address_1; ?></span>
							<?php } ?></td>
					</tr>
					<tr>
						<td><?php echo $entry_address_2; ?></td>
						<td><input type="text" name="shipping_address_2" value="<?php echo $shipping_address_2; ?>" onchange="updateShipping();" /></td>
					</tr>
					<tr>
						<td><span class="required">*</span> <?php echo $entry_city; ?></td>
						<td><input type="text" name="shipping_city" value="<?php echo $shipping_city; ?>" onchange="updateShipping();" />
							<?php if ($error_shipping_city) { ?>
							<span class="error"><?php echo $error_shipping_city; ?></span>
							<?php } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span> <?php echo $entry_postcode; ?></td>
						<td><input type="text" name="shipping_postcode" value="<?php echo $shipping_postcode; ?>" onchange="updateShipping();" /></td>
					</tr>
					<tr>
						<td><span class="required">*</span> <?php echo $entry_country; ?></td>
						<td><select name="shipping_country_id" id="shipping_country_id" onchange="$('select[name=\'shipping_zone_id\']').load('index.php?route=checkout/guest_step_1/zone&country_id=' + this.value + '&zone_id=<?php echo $shipping_zone_id; ?>'); updateShipping();">
							<option value="FALSE"><?php echo $text_select; ?></option>
							<?php foreach ($countries as $country) { ?>
							<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
							<?php } ?>
							</select>
							<?php if ($error_shipping_country) { ?>
							<span class="error"><?php echo $error_shipping_country; ?></span>
							<?php } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span> <?php echo $entry_zone; ?></td>
						<td><select name="shipping_zone_id" onchange="updateShipping();">
							</select>
							<?php if ($error_shipping_zone) { ?>
							<span class="error"><?php echo $error_shipping_zone; ?></span>
							<?php } ?></td>
					</tr>
				</table>
			</div>
			<?php } ?>
			<div id="paymentMethods"></div>
			<div id="shippingMethods">
			<?php if ($shipping_methods) { ?>
			<b style="margin-bottom: 2px; display: block;"></b>
			<div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
			  <p>Please select the preferred shipping method to use on this order.</p>
			  <table width="536" cellpadding="3">
				 <?php foreach ($shipping_methods as $shipping_method) { ?>
				 <tr>
					<td colspan="3"><b><?php echo $shipping_method['title']; ?></b></td>
				 </tr>
				 <?php if (!$shipping_method['error']) { ?>
				 <?php foreach ($shipping_method['quote'] as $quote) { ?>
				 <tr>
					<td width="1"><label for="<?php echo $quote['id']; ?>">
						 <?php if ($quote['id'] == $shipping) { ?>
						 <input type="radio" name="shipping_method" value="<?php echo $quote['id']; ?>" id="<?php echo $quote['id']; ?>" checked="checked" style="margin: 0px;" />
						 <?php } else { ?>
						 <input type="radio" name="shipping_method" value="<?php echo $quote['id']; ?>" id="<?php echo $quote['id']; ?>" style="margin: 0px;" />
						 <?php } ?>
					  </label></td>
					<td width="534"><label for="<?php echo $quote['id']; ?>" style="cursor: pointer;"><?php echo $quote['title']; ?></label></td>
					<td align="right"><label for="<?php echo $quote['id']; ?>" style="cursor: pointer;"><?php echo $quote['text']; ?></label></td>
				 </tr>
				 <?php } ?>
				 <?php } else { ?>
				 <tr>
					<td colspan="3"><div class="error"><?php echo $shipping_method['error']; ?></div></td>
				 </tr>
				 <?php } ?>
				 <?php } ?>
			  </table>
			</div>
			<?php } ?>
			</div>
			<b style="margin-bottom: 2px; display: block;"><?php echo $text_discounts; ?></b>
			<div id="redemptions" class="content">
				<p><?php echo $text_gift_certificate; ?></p>
				<table>
					<tr>
						<td><input type="text" name="redemption" id="redemption" value="" /></td>
						<td><a id="button_gift_certificate" class="button" onclick="redeem();"><span><?php echo $button_gift_certificate; ?></span></a></td>
					</tr>
				</table>
			</div>
			<div class="content">
				<table width="100%">
				<tr>
					<th align="center"><?php echo $column_remove; ?></th>
					<th align="left"><?php echo $column_product; ?></th>
					<th align="left"><?php echo $column_model; ?></th>
					<th align="right" colspan="2"><?php echo $column_quantity; ?></th>
					<th align="right"><?php echo $column_price; ?></th>
					<th align="right"><?php echo $column_total; ?></th>
				</tr>
					<?php foreach ($products as $product) { ?>
					<div>
						<tr id="product_<?php echo $product['key']; ?>">
							<td align="center" valign="top"><a id="remove_<?php echo $product['key']; ?>" onclick="removeProduct('<?php echo $product['key']; ?>')"><img src="catalog/view/theme/default/image/cart_delete.png" height="16" width="16" alt="x" title="Remove from cart" /></a></td>
							<td align="left" valign="top"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
								<?php foreach ($product['option'] as $option) { ?>
								<br />
								&nbsp;<small> - <?php echo $option['name']; ?> <?php echo $option['value']; ?></small>
								<?php } ?></td>
							<td align="left" valign="top"><?php echo $product['model']; ?></td>
							<td align="right" valign="top"><div id="qty_<?php echo $product['key']; ?>_actions"></div><a id="edit_<?php echo $product['key']; ?>" onclick="editProduct('<?php echo $product['key']; ?>')"><img src="catalog/view/theme/default/image/cart_edit.png" height="16" width="16" alt="Change" title="Change quantity" /></a></td>
							<td align="right" valign="top" id="qty_<?php echo $product['key']; ?>"><?php echo $product['quantity']; ?></td>
							<td align="right" valign="top"><?php echo $product['price']; ?></td>
							<td align="right" valign="top" id="price_<?php echo $product['key']; ?>"><?php echo $product['total']; ?></td>
						</tr>
					</div>
					<?php } ?>
				</table>
				<br />
				<div style="width: 100%; display: inline-block;">
					<table style="float: right; display: inline-block;" id="totals">
						<?php foreach ($totals as $total) { ?>
						<tr>
							<td align="right"><?php echo $total['title']; ?></td>
							<td align="right"><?php echo $total['text']; ?></td>
						</tr>
						<?php } ?>
					</table>
					<br />
				</div>
			</div>
			<b style="margin-bottom: 2px; display: block;"><?php echo $text_comment; ?></b>
			<div class="light" style="padding: 10px; margin-bottom: 10px;">
				<textarea name="comment" rows="8" style="width: 99%;"><?php echo $comment; ?></textarea>
			</div>
			<?php if (true) { ?>
			<div id="payment"><?if (!$payment) {?>
				<table>
					<tr>
						<td align="left"><a onclick="location='<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
						<td align="right" style="padding-right: 5px;"><?php echo $text_agree; ?></td>
						<td width="5" style="padding-right: 10px;"><?php if ($agree) { ?>
							<input type="checkbox" name="agree" value="1" checked="checked" />
							<?php } else { ?>
							<input type="checkbox" name="agree" value="1" />
							<?php } ?></td><td align="right"><a onclick="$('#guest').submit();" class="button"><span><?php echo $button_continue; ?></span></a></td>
						
					</tr>
				</table><?}?>
			</div>
			<?php } else { ?>
			<div class="buttons">
				<table>
					<tr>
						<td align="left"><a onclick="location='<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
						<td align="right"><a onclick="$('#guest').submit();" class="button"><span><?php echo $button_continue; ?></span></a></td>
					</tr>
				</table>
			</div>
			<?php } ?>
		</form>
	</div>
	<div class="bottom">
		<div class="left"></div>
		<div class="right"></div>
		<div class="center"></div>
	</div>
</div>
<script type="text/javascript"><!--
$(document).ready(function () {

	$('#payment_country_id').attr('value', '<?php echo $payment_country_id; ?>');
	$('select[name=\'payment_zone_id\']').load(
		'index.php?route=checkout/guest_step_1/zone&country_id=<?php echo $payment_country_id; ?>&zone_id=<?php echo $payment_zone_id; ?>',
		function () {
			updatePayment();
	});
	$('#shipping_country_id').attr('value', '<?php echo $shipping_country_id; ?>');
	$('select[name=\'shipping_zone_id\']').load(
		'index.php?route=checkout/guest_step_1/zone&country_id=<?php echo $shipping_country_id; ?>&zone_id=<?php echo $shipping_zone_id; ?>',
		function () {
			updateShipping();
	});
	$('.guestlogin').hide();
});

$('#sameAddress').click(function () {
	$('input[name="shipping_company"]').val($('input[name="payment_company"]').val());
	$('input[name="shipping_address_1"]').val($('input[name="payment_address_1"]').val());
	$('input[name="shipping_address_2"]').val($('input[name="payment_address_2"]').val());
	$('input[name="shipping_city"]').val($('input[name="payment_city"]').val());
	$('input[name="shipping_postcode"]').val($('input[name="payment_postcode"]').val());
	$('#shipping_country_id').val($('#payment_country_id').val());
	$('select[name=\'shipping_zone_id\']').load('index.php?route=checkout/guest_step_1/zone&country_id=' + $('#payment_country_id').val() + '&zone_id=' + $('select[name=\'payment_zone_id\']').val(), function(){
		updateShipping();
	});
});

$("#email").blur(function(){
	$.ajax({
		type: "post",
		url: "index.php?route=checkout/guest_step_1/ajaxEmailCheck",
		data: "&email=" + $('#email').val(),
		success: function(data) {
			if (data == 'success') {
				$('.guestlogin').show(2000);
				$('input[name="password"]').focus();
			} else {
				$('.guestlogin').hide();
				$('input[name="firstname"]').focus();
			}
		}
	});
});

function guestLogin() {
	$("#guest").attr("action", "<?php echo $guestlogin; ?>");
	$('#guest').submit();
}

function updatePayment() {
	var valid = true;
	
	if ($('input[name="payment_address_1"]').val().length < 3 || $('input[name="payment_address_1"]').val().length > 128) {
		valid = false;
	} else if ($('input[name="payment_city"]').val().length < 3 || $('input[name="payment_city"]').val().length > 128) {
		valid = false;
	} else if (!isInt($('#payment_country_id').val())) {
		valid = false;
	} else if(!isInt($('select[name=\'payment_zone_id\']').val())) {
		valid = false;
	}
	
	if (valid) {
		$.ajax({
			type: "post",
			url: "index.php?route=checkout/guest_step_1/ajaxPayment",
			data: "&address_1=" + $('input[name="payment_address_1"]').val() + "&address_2=" + $('input[name="payment_address_2"]').val() + "&city=" + $('input[name="payment_city"]').val() + "&postcode=" + $('input[name="payment_postcode"]').val() + "&country_id=" + $('#payment_country_id').val() + "&zone_id=" + $('select[name=\'payment_zone_id\']').val(),
			success: function(data) {
				$("#paymentMethods").html(data).effect("pulsate", { times:1 }, 1200);											//$("#payment").html('test').effect("pulsate", { times:1 }, 1200);
			}
		});
	}	
	updateTotals();
}

//$("input[name='payment_method']").change(
 $('#paymentMethods').change(function(){
test = $('[name=payment_method]:checked').val();
//alert(test);
$.ajax({
		type: "post",
		url: "index.php?route=checkout/guest_step_1/ajaxPay",
		data: "&pay=" + test,
		success: function(data) {
			$("#payment").html("<?echo $this->db->escape($buttons);?>"); $("#zpayment").html("");
		}
	});
});

function updateShipping() {
	var valid = true;
	
	if ($('input[name="shipping_address_1"]').val().length < 3 || $('input[name="shipping_address_1"]').val().length > 128) {
		valid = false;
	} else if ($('input[name="shipping_city"]').val().length < 3 || $('input[name="shipping_city"]').val().length > 128) {
		valid = false;
	} else if (!isInt($('#shipping_country_id').val())) {
		valid = false;
	} else if(!isInt($('select[name=\'shipping_zone_id\']').val())) {
		valid = false;
	}
	
	if (valid) {
		$.ajax({
			type: "post",
			url: "index.php?route=checkout/guest_step_1/ajaxShipping",
			data: "&address_1=" + $('input[name="shipping_address_1"]').val() + "&address_2=" + $('input[name="shipping_address_2"]').val() + "&city=" + $('input[name="shipping_city"]').val() + "&postcode=" + $('input[name="shipping_postcode"]').val() + "&country_id=" + $('#shipping_country_id').val() + "&zone_id=" + $('select[name=\'shipping_zone_id\']').val(),
			success: function(data) {
				$("#shippingMethods").html(data).effect("pulsate", { times:2 }, 1200);
			}
		});
	}
	//$("#payment").html("<?echo $this->db->escape($buttons);?>"); $("#zpayment").html("");
	updateTotals();
}

function cancelUpdate(key, qty) {
	$("#qty_" + key + "_actions").html('<a id="edit_' + key + '" onclick="editProduct(\'' + key + '\')"><img src="catalog/view/theme/default/image/cart_edit.png" height="16" width="16" alt="Change" title="Change quantity" /></a> ');
	$("#qty_" + key).html(qty);
}

function removeProduct(key) {
	$.ajax({
		type: "post",
		url: "index.php?route=checkout/guest_step_1/ajaxRemove",
		data: "key=" + key,
		success: function(data) {
			if (data == 'empty') {
				window.location.replace("index.php");
			} else {
				$("#product_" + key).hide();
				updateTotals();
				$("#payment").html("<?echo $this->db->escape($buttons);?>"); $("#zpayment").html("");
			}
		}
	});
}

function updateProduct(key) {
	var qty = $('input[name="qty_' + key + '_input"]').val();
	
	if (isInt(qty) && parseInt(qty) > 0) {
		$.ajax({
			type: "post",
			url: "index.php?route=checkout/guest_step_1/ajaxQuantity",
			data: "key=" + key + "&qty=" + qty,
			success: function(data) {
				cancelUpdate(key, qty);
				$("#price_" + key).html(data);
				$("#payment").html("<?echo $this->db->escape($buttons);?>"); $("#zpayment").html("");
				updateTotals();
				updateShipping();
			}
		});
	} else {
		alert('You cannot purchase ' + qty + ' of this!');
	}
}

function editProduct(key) {
	var qty = $("#qty_" + key).html();
	
	$("#edit_" + key).hide();
	$("#qty_" + key).html('<input name="qty_' + key + '_input" id="qty_' + key + '_input" value="' + qty + '" size="1" />');
	$("#qty_" + key + "_actions").html(
		'<a onclick="updateProduct(\'' + key + '\')"><img src="catalog/view/theme/default/image/apply.png" height="16" width="16" alt="Update" title="Update quantity" /></a> ' +
		'<a onclick="cancelUpdate(\'' + key + '\', ' + qty + ')"><img src="catalog/view/theme/default/image/cancel.png" height="16" width="16" alt="Cancel" title="Cancel" /></a>'
		
	);
	$("#payment").html("<?echo $this->db->escape($buttons);?>"); $("#zpayment").html("");
}

function updateTotals() {
	var shipping = '';
	if ($('[name=shipping_method]:checked').val()) {
		shipping = $('[name=shipping_method]:checked').val();
	}
	$.ajax({
		type: "post",
		url: "index.php?route=checkout/guest_step_1/ajaxTotals",
		data: "shipping_method=" + shipping,
		success: function(data) {
			$("#totals").html(data).effect("pulsate", { times:2 }, 1200);
		}
	});
}

function updateTotalsShip() {
	var shipping = '';
	if ($('[name=shipping_method]:checked').val()) {
		shipping = $('[name=shipping_method]:checked').val();
	}
	$.ajax({
		type: "post",
		url: "index.php?route=checkout/guest_step_1/ajaxTotals",
		data: "shipping_method=" + shipping,
		success: function(data) {
			$("#totals").html(data).effect("pulsate", { times:2 }, 1200);
		}
	});
	$("#payment").html("<?echo $this->db->escape($buttons);?>"); $("#zpayment").html("");
}

function redeem() {
	gc_code = $('#redemption').val();
	$.ajax({
		type: "post",
		url: "index.php?route=checkout/guest_step_1/ajaxRedeem",
		data: "redemption=" + gc_code,
		success: function(data) {
			//alert(data);
			updateTotals();
		}
	});
}

function isInt(x) {
	var y = parseInt(x);
	
	if (isNaN(y)) {
		return false;
	}
	
	return x == y && x.toString() == y.toString();
}
//--></script>
<?php echo $footer; ?> 
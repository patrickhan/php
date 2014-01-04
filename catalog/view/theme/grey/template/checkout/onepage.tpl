<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
	<div class="top">
		<div class="left"></div>
		<div class="right"></div>
		<div class="center">
			<h1><?php echo $heading_title; ?></h1>
		</div>
	</div>
	<div class="middle">
		<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
		<?php } ?>
		<div id="zpayment"><?if ($payment) { echo '<div id="fpayment">' . $payment. '</div>'; } else {?><p><?php echo $text_account_already; ?></p>
		<?if ($ccpic) {?><div class="light" style="padding: 10px; margin-bottom: 10px;">
		<img src='image/ccinfo.png'/>
		</div><br>
		<p>Fill in all the info below and hit Continue - if your information is valid, you'll be able to fill in your credit card details</p>
		<br>
		
		<?} }?></div>
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="onepage">
			<?php if ($is_shipping) { ?>
			<b style="margin-bottom: 2px; display: block;"><?php echo $text_shipping_address; ?></b>
			<div class="content">
				<table width="100%">
					<tr>
						<td width="50%" valign="top"><?php echo $text_shipping_to; ?><br />
							<br />
							<div style="text-align: center;"><a id="changeShipping" class="button"><span><?php echo $button_change_address; ?></span></a></div></td>
						<td width="50%" valign="top" id="shippingAddress"><b><?php echo $text_shipping_address; ?></b><br />
							<?php echo $address; ?></td>
					</tr>
				</table>
			</div>
			<?php if ($shipping_methods) { ?>
			<?if (count($shipping_methods) > 1) {?><b style="margin-bottom: 2px; display: block;"><?php echo $text_shipping_method; ?></b><?}?>
			<div class="content" <?if (count($shipping_methods) == 1) { echo "style='display:none;'"; }?>>
				<p><?php echo $text_shipping_methods; ?></p>
					<table width="100%" cellpadding="3" id="shippingMethods">
						<?php foreach ($shipping_methods as $shipping_method) { ?>
						<tr>
							<td colspan="3"><b><?php echo $shipping_method['title']; ?></b></td>
						</tr>
						<?php if (!$shipping_method['error']) { ?>
						<?php foreach ($shipping_method['quote'] as $quote) { ?>
						<tr>
							<td width="1"><label for="<?php echo $quote['id']; ?>">
								<?php if ($quote['id'] === $shipping) { ?>
								<input type="radio" name="shipping_method" onclick="updateTotals();" value="<?php echo $quote['id']; ?>" id="<?php echo $quote['id']; ?>" checked="checked" style="margin: 0px;" />
								<?php } else { ?>
								<input type="radio" name="shipping_method" onclick="updateTotals();" value="<?php echo $quote['id']; ?>" id="<?php echo $quote['id']; ?>" style="margin: 0px;" />
								<?php } ?>
							</label></td>
							<td width="534"><label for="<?php echo $quote['id']; ?>" style="cursor: pointer;"><?php echo $quote['title']; ?></label></td>
							<td width="1" align="right"><label for="<?php echo $quote['id']; ?>" style="cursor: pointer;"><?php echo $quote['text']; ?></label></td>
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
			<?php } ?>
			<?php if ($is_payment) { ?>
			<b style="margin-bottom: 2px; display: block;"><?php echo $text_payment_address; ?></b>
			<div class="content">
				<table width="100%">
					<tr>
						<td width="50%" valign="top"><?php echo $text_payment_to; ?><br />
							<br />
							<div style="text-align: center;"><a id="changePayment" class="button"><span><?php echo $button_change_address; ?></span></a></div></td>
						<td width="50%" valign="top" id="paymentAddress"><b><?php echo $text_payment_address; ?></b><br />
							<?php echo $address; ?></td>
					</tr>
				</table>
			</div>
			<?php
			if ($payment_methods) {
			if (count($payment_methods) == 1) {
				reset($payment_methods);
				$pay_key = key($payment_methods); 
			?>
			<input type="hidden" name="payment_method" value="<?php echo $payment_methods[$pay_key]['id']; ?>" id="<?php echo $payment_methods[$pay_key]['id']; ?>" style="margin: 0px;" />
			<?php } else { ?>
			<b style="margin-bottom: 2px; display: block;"><?php echo $text_payment_method; ?></b>
			<div class="content">
				<p><?php echo $text_payment_methods; ?></p>
				<table width="100%" cellpadding="3" id="paymentMethods">
				<?php foreach ($payment_methods as $payment_method) { ?>
					<tr>
						<td width="1"><?php if ($payment_method['id'] == $payment1) { ?>
							<input type="radio" name="payment_method" value="<?php echo $payment_method['id']; ?>" id="<?php echo $payment_method['id']; ?>" checked="checked" style="margin: 0px;" />
							<?php } else { ?>
							<input type="radio" name="payment_method" value="<?php echo $payment_method['id']; ?>" id="<?php echo $payment_method['id']; ?>" style="margin: 0px;" />
							<?php } ?></td>
						<td><label for="<?php echo $payment_method['id']; ?>" style="cursor: pointer;"><?php echo $payment_method['title']; ?></label></td>
					</tr>
				<?php } ?>
			</table>
			</div>
			<?php } ?>
			<?php } ?>
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
			<?php } ?>
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
				<div >
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
			<b style="margin-bottom: 2px; display: block;"><?php echo $text_comments; ?></b>
			<div class="content">
				<textarea name="comment" rows="8" style="width: 99%;"><?php echo $comment; ?></textarea>
			</div>
			<?php if (true) { ?>
			<div id="payment"><?if ($payment) { } else {?>				<table>					<tr>						<td align="left"><a onclick="location='<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>						<td align="right" style="padding-right: 5px;"><?php echo $text_agree; ?></td>						<td width="5" style="padding-right: 10px;"><?php if ($agree) { ?>							<input type="checkbox" name="agree" value="1" checked="checked" />							<?php } else { ?>							<input type="checkbox" name="agree" value="1" />							<?php } ?></td><td align="right"><a onclick="$('#onepage').submit();" class="button"><span><?php echo $button_continue; ?></span></a></td>											</tr>				</table><?}?>			</div>
			<?php } else { ?>
			<div class="buttons">
				<table>
					<tr>
						<td align="left"><a onclick="location='<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
						<td align="right"><a onclick="$('#onepage').submit();" class="button"><span><?php echo $button_continue; ?></span></a></td>
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
<div id="dialog_shipping" title="Change Shipping Address" style="text-align: left;">
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">New Address</a></li>
			<li><a href="#tabs-2">Existing Addresses</a></li>
		</ul>
		<form>
		<div id="tabs-1">
			<p id="validateTips"><span class="required">*</span> Required.</p>
			<table>
				<tr>
					<td><label for="firstname_shipping"><span class="required">*</span> First Name:</label></td>
					<td><input type="text" name="firstname_shipping" id="firstname_shipping" class="text ui-widget-content ui-corner-all" /></td>
				</tr>
				<tr>
					<td><label for="lastname_shipping"><span class="required">*</span> Last Name:</label></td>
					<td><input type="text" name="lastname_shipping" id="lastname_shipping" class="text ui-widget-content ui-corner-all" /></td>
				</tr>
				<tr>
					<td><label for="company_shipping">Company:</label></td>
					<td><input type="text" name="company_shipping" id="company_shipping" class="text ui-widget-content ui-corner-all" /></td>
				</tr>
				<tr>
					<td><label for="address_1_shipping"><span class="required">*</span> Address 1:</label></td>
					<td><input type="text" name="address_1_shipping" id="address_1_shipping" class="text ui-widget-content ui-corner-all" /></td>
				</tr>
				<tr>
					<td><label for="address_2_shipping">Address 2:</label></td>
					<td><input type="text" name="address_2_shipping" id="address_2_shipping" class="text ui-widget-content ui-corner-all" /></td>
				</tr>
				<tr>
					<td><label for="city_shipping"><span class="required">*</span> City:</label></td>
					<td><input type="text" name="city_shipping" id="city_shipping" class="text ui-widget-content ui-corner-all" /></td>
				</tr>
				<tr>
					<td><label for="post_code_shipping">Post Code:</label></td>
					<td><input type="text" name="post_code_shipping" id="post_code_shipping" class="text ui-widget-content ui-corner-all" /></td>
				</tr>
				<tr>
					<td><label for="country_shipping"><span class="required">*</span> Country:</label></td>
					<td>
						<select name="country_shipping" id="country_shipping" onchange="$('select[name=\'zone_id_shipping\']').load('index.php?route=account/address/zone&country_id=' + this.value);">
							<option value="FALSE"><?php echo $text_select; ?></option>
							<?php foreach ($countries as $country) { ?>
							<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td><label for="zone_id_shipping"><span class="required">*</span> Region / State:</label></td>
					<td><select name="zone_id_shipping" id="zone_id_shipping"></select></td>
				</tr>
			</table>
		</div>
		<div id="tabs-2">
			<table cellpadding="3">
				<?php foreach ($addresses as $address) { ?>
				<?php if ($address['address_id'] == $selected_shipping_address) { ?>
				<tr>
					<td width="1"><input type="radio" name="address_id_shipping" value="<?php echo $address['address_id']; ?>" id="address_id[<?php echo $address['address_id']; ?>]" checked="checked" style="margin: 0px;" /></td>
					<td><label for="address_id[<?php echo $address['address_id']; ?>]" style="cursor: pointer;"><?php echo $address['address']; ?></label></td>
				</tr>
				<?php } else { ?>
				<tr>
					<td width="1"><input type="radio" name="address_id_shipping" value="<?php echo $address['address_id']; ?>" id="address_id[<?php echo $address['address_id']; ?>]" style="margin: 0px;" /></td>
					<td><label for="address_id[<?php echo $address['address_id']; ?>]" style="cursor: pointer;"><?php echo $address['address']; ?></label></td>
				</tr>
				<?php } ?>
				<?php } ?>
			</table>
		</div>
		</form>
	</div>
</div>
<div id="dialog_payment" title="Change Payment Address" style="text-align: left;">
	<div id="ptabs">
		<ul>
			<li><a href="#ptabs-1">New Address</a></li>
			<li><a href="#ptabs-2">Existing Addresses</a></li>
		</ul>
		<form>
		<div id="ptabs-1">
			<p id="validateTips"><span class="required">*</span> Required.</p>
			<table>
				<tr>
					<td><label for="firstname_payment"><span class="required">*</span> First Name:</label></td>
					<td><input type="text" name="firstname_payment" id="firstname_payment" class="text ui-widget-content ui-corner-all" /></td>
				</tr>
				<tr>
					<td><label for="lastname_payment"><span class="required">*</span> Last Name:</label></td>
					<td><input type="text" name="lastname_payment" id="lastname_payment" class="text ui-widget-content ui-corner-all" /></td>
				</tr>
				<tr>
					<td><label for="company_payment">Company:</label></td>
					<td><input type="text" name="company_payment" id="company_payment" class="text ui-widget-content ui-corner-all" /></td>
				</tr>
				<tr>
					<td><label for="address_1_payment"><span class="required">*</span> Address 1:</label></td>
					<td><input type="text" name="address_1_payment" id="address_1_payment" class="text ui-widget-content ui-corner-all" /></td>
				</tr>
				<tr>
					<td><label for="address_2_payment">Address 2:</label></td>
					<td><input type="text" name="address_2_payment" id="address_2_payment" class="text ui-widget-content ui-corner-all" /></td>
				</tr>
				<tr>
					<td><label for="city_payment"><span class="required">*</span> City:</label></td>
					<td><input type="text" name="city_payment" id="city_payment" class="text ui-widget-content ui-corner-all" /></td>
				</tr>
				<tr>
					<td><label for="post_code_payment">Post Code:</label></td>
					<td><input type="text" name="post_code_payment" id="post_code_payment" class="text ui-widget-content ui-corner-all" /></td>
				</tr>
				<tr>
					<td><label for="country_payment"><span class="required">*</span> Country:</label></td>
					<td>
						<select name="country_payment" id="country_payment" onchange="$('select[name=\'zone_id_payment\']').load('index.php?route=account/address/zone&country_id=' + this.value);">
							<option value="FALSE"><?php echo $text_select; ?></option>
							<?php foreach ($countries as $country) { ?>
							<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td><label for="zone_id_payment"><span class="required">*</span> Region / State:</label></td>
					<td><select name="zone_id_payment"  id="zone_id_payment"></select></td>
				</tr>
			</table>
		</div>
		<div id="ptabs-2">
			<table cellpadding="3">
				<?php foreach ($addresses as $address) { ?>
				<?php if ($address['address_id'] == $selected_payment_address) { ?>
				<tr>
					<td width="1"><input type="radio" name="address_id_payment" value="<?php echo $address['address_id']; ?>" id="address_id[<?php echo $address['address_id']; ?>]" checked="checked" style="margin: 0px;" /></td>
					<td><label for="address_id[<?php echo $address['address_id']; ?>]" style="cursor: pointer;"><?php echo $address['address']; ?></label></td>
				</tr>
				<?php } else { ?>
				<tr>
					<td width="1"><input type="radio" name="address_id_payment" value="<?php echo $address['address_id']; ?>" id="address_id[<?php echo $address['address_id']; ?>]" style="margin: 0px;" /></td>
					<td><label for="address_id[<?php echo $address['address_id']; ?>]" style="cursor: pointer;"><?php echo $address['address']; ?></label></td>
				</tr>
				<?php } ?>
				<?php } ?>
			</table>
		</div>
	</div>
	</form>
</div>
<script type="text/javascript"><!--

function updateTotals() {
	$.ajax({
		type: "post",
		url: "index.php?route=checkout/onepage/ajaxTotals",
		data: "shipping_method=" + $('[name=shipping_method]:checked').val(),
		success: function(data) {
			$("#totals").html(data).effect("pulsate", { times:2 }, 1200);
		}
	});
}

function cancelUpdate(key, qty) {
	$("#qty_" + key + "_actions").html('<a id="edit_' + key + '" onclick="editProduct(\'' + key + '\')"><img src="catalog/view/theme/default/image/cart_edit.png" height="16" width="16" alt="Change" title="Change quantity" /></a> ');
	$("#qty_" + key).html(qty);
}

function editProduct(key) {
	var qty = $("#qty_" + key).html();
	
	$("#edit_" + key).hide();
	$("#qty_" + key).html('<input name="qty_' + key + '_input" id="qty_' + key + '_input" value="' + qty + '" size="1" />');
	$("#qty_" + key + "_actions").html(
		'<a onclick="updateProduct(\'' + key + '\')"><img src="catalog/view/theme/default/image/apply.png" height="16" width="16" alt="Update" title="Update quantity" /></a> ' +
		'<a onclick="cancelUpdate(\'' + key + '\', ' + qty + ')"><img src="catalog/view/theme/default/image/cancel.png" height="16" width="16" alt="Cancel" title="Cancel" /></a>'
	);$("#payment").html("<?echo $this->db->escape($buttons);?>"); $("#zpayment").html("");
}

function removeProduct(key) {
	$.ajax({
		type: "post",
		url: "index.php?route=checkout/onepage/ajaxRemove",
		data: "key=" + key,
		success: function(data) {
			if (data == 'empty') {
				window.location.replace("index.php");
			} else {
				$("#product_" + key).hide();
				updateTotals();
			}
		}
	});$("#payment").html("<?echo $this->db->escape($buttons);?>"); $("#zpayment").html("");
}

function updateProduct(key) {
	var qty = $('input[name="qty_' + key + '_input"]').val();
	
	if (isInt(qty) && parseInt(qty) > 0) {
		$.ajax({
			type: "post",
			url: "index.php?route=checkout/onepage/ajaxQuantity",
			data: "key=" + key + "&qty=" + qty,
			success: function(data) {
				cancelUpdate(key, qty);
				$("#price_" + key).html(data);
				updateTotals();
				updateShipping();
			}
		});
	} else {
		alert('You cannot purchase ' + qty + ' of this!');
	}$("#payment").html("<?echo $this->db->escape($buttons);?>"); $("#zpayment").html("");
}

function updateShipping() {
	$.ajax({
		type: "post",
		url: "index.php?route=checkout/onepage/ajaxShipping",
		data: "",
		success: function(data) {
			$("#shippingMethods").html(data).effect("pulsate", { times:2 }, 1200);
		}
	});
}

function redeem() {
	gc_code = $('#redemption').val();
	$.ajax({
		type: "post",
		url: "index.php?route=checkout/onepage/ajaxRedeem",
		data: "redemption=" + gc_code,
		success: function(data) {
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

$(document).ready(function () {
	

	var firstname_shipping = $("#firstname_shipping"),
	lastname_shipping = $("#lastname_shipping"),
	address_1_shipping = $("#address_1_shipping"),
	address_2_shipping = $("#address_2_shipping"),
	city_shipping = $("#city_shipping"),
	country_shipping = $("#country_shipping"),
	zone_id_shipping = $("#zone_id_shipping"),
	post_code_shipping = $("#post_code_shipping"),
	company_shipping = $("#company_shipping"),
	firstname_payment = $("#firstname_payment"),
	lastname_payment = $("#lastname_payment"),
	address_1_payment = $("#address_1_payment"),
	address_2_payment = $("#address_2_payment"),
	city_payment = $("#city_payment"),
	country_payment = $("#country_payment"),
	zone_id_payment = $("#zone_id_payment"),
	post_code_payment = $("#post_code_payment"),
	company_payment = $("#company_payment"),
	allFields = $([]).add(firstname_shipping).add(lastname_shipping).add(address_1_shipping).add(city_shipping).add(post_code_shipping).add(company_shipping)
	.add(country_shipping).add(zone_id_shipping).add(firstname_payment).add(lastname_payment).add(address_1_payment).add(address_2_payment)
	.add(city_payment).add(country_payment).add(zone_id_payment).add(post_code_payment).add(company_payment),
	tips = $("#validateTips");

	function updateTips(t) {
		tips.text(t).effect("highlight",{},1500);
	}
		
	function check(o) {
		if (o.val().length < 1 ) {
			o.addClass('ui-state-error');
			updateTips("Required.");
			return false;
		} else {
			return true;
		}
	}

	function checkRegexp(o,regexp) {
		if ( !( regexp.test( o.val() ) ) ) {
			o.addClass('ui-state-error');
			updateTips("Required.");
			return false;
		} else {
			return true;
		}
	}
	
	$("#dialog_shipping").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 450,
		width: 500,
		modal: true,
		buttons: {
			'Change Address': function() {
				var bValid = true;
				allFields.removeClass('ui-state-error');
				if (!firstname_shipping.val()) {
					$.ajax({
							type: "post",
							url: "index.php?route=checkout/onepage/ajaxShippingAddress",
							data: "address_id=" + $('[name=address_id_shipping]:checked').val(),
							success: function(data) {
								$("#shippingAddress").fadeOut("slow");
								$("#shippingAddress").html(data).fadeIn("slow");
								updateShipping();$("#payment").html("<?echo $this->db->escape($buttons);?>"); $("#zpayment").html("");
							}
						});
						$(this).dialog('close');
				} else {
					bValid = bValid && check(firstname_shipping);
					bValid = bValid && check(lastname_shipping);
					bValid = bValid && check(address_1_shipping);
					bValid = bValid && check(city_shipping);
					bValid = bValid && checkRegexp(country_shipping, /^([0-9])+$/i);
					bValid = bValid && checkRegexp(zone_id_shipping, /^([0-9])+$/i);
					if (bValid) {
						$.ajax({
							type: "post",
							url: "index.php?route=checkout/onepage/ajaxShippingAddress",
							data: "firstname=" + firstname_shipping.val() + "&lastname=" + lastname_shipping.val() + "&company=" + company_shipping.val() + "&address_1=" + address_1_shipping.val() + "&address_2=" + address_2_shipping.val() + "&city=" + city_shipping.val() + "&postcode=" + post_code_shipping.val() + "&country_id=" + country_shipping.val() + "&zone_id=" + zone_id_shipping.val(),
							success: function(data) {
								$("#shippingAddress").fadeOut("slow");
								$("#shippingAddress").html(data).fadeIn("slow");
								updateShipping();
							}
						});
						$(this).dialog('close');
					}
				}
			},
			Cancel: function() {
				$(this).dialog('close');
			}
		},
		close: function() {
			allFields.val('').removeClass('ui-state-error');
		}
	});
	$("#dialog_payment").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 450,
		width: 500,
		modal: true,
		buttons: {
			'Change Address': function() {
				var bValid = true;
				allFields.removeClass('ui-state-error');
				if (!firstname_payment.val()) {
					$.ajax({
							type: "post",
							url: "index.php?route=checkout/onepage/ajaxPaymentAddress",
							data: "address_id=" + $('[name=address_id_payment]:checked').val(),
							success: function(data) {
								$("#paymentAddress").fadeOut("slow");
								$("#paymentAddress").html(data).fadeIn("slow");$("#payment").html("<?echo $this->db->escape($buttons);?>"); $("#zpayment").html("");
							}
						});
						$(this).dialog('close');
				} else {
					bValid = bValid && check(firstname_payment);
					bValid = bValid && check(lastname_payment);
					bValid = bValid && check(address_1_payment);
					bValid = bValid && check(city_payment);
					bValid = bValid && checkRegexp(country_payment, /^([0-9])+$/i);
					bValid = bValid && checkRegexp(zone_id_payment, /^([0-9])+$/i);
					if (bValid) {
						$.ajax({
							type: "post",
							url: "index.php?route=checkout/onepage/ajaxPaymentAddress",
							data: "firstname=" + firstname_payment.val() + "&lastname=" + lastname_payment.val() + "&company=" + company_payment.val() + "&address_1=" + address_1_payment.val() + "&address_2=" + address_2_payment.val() + "&city=" + city_payment.val() + "&postcode=" + post_code_payment.val() + "&country_id=" + country_payment.val() + "&zone_id=" + zone_id_payment.val(),
							success: function(data) {
								$("#paymentAddress").fadeOut("slow");
								$("#paymentAddress").html(data).fadeIn("slow");$("#payment").html("<?echo $this->db->escape($buttons);?>"); $("#zpayment").html("");
							}
						});
						$(this).dialog('close');
					}
				}
			},
			Cancel: function() {
				$(this).dialog('close');
			}
		},
		close: function() {
			allFields.val('').removeClass('ui-state-error');
		}
	});
	$('#changeShipping').click(function() {
		$('#dialog_shipping').dialog('open');
	});
	$('#changePayment').click(function() {
		$('#dialog_payment').dialog('open');
	});
});$('#paymentMethods').change(function(){test = $('[name=payment_method]:checked').val();			
$("#payment").html("<?echo $this->db->escape($buttons);?>"); $("#zpayment").html("");});
//--></script>
<?php echo $footer; ?> 
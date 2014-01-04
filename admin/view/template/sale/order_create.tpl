<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($error_products) { ?>
<div class="error"><?php echo $error_products; ?></div>
<?php } ?>
<?php if ($error_payment_country) { ?>
<div class="error"><?php echo $error_payment_country; ?></div>
<?php } ?>
<?php if ($error_shipping_country) { ?>
<div class="error"><?php echo $error_shipping_country; ?></div>
<?php } ?>
<div class="box" style="width:900px;margin:0 auto;">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('view/image/order.png');"><?php echo $heading_create; ?></h1>
		<div class="buttons"><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
	</div>
	<div class="content">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div style="margin-bottom: 15px;">
				<div style="background: #547C96; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_order_details; ?></div>
				<div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px;">
					<table class="list">
						<thead>
							<tr>
								<td class="left" width="25%"><b><?php echo $text_currency; ?></b></td>
								<td class="left" width="25%"><b><?php echo $text_currency_value; ?></b></td>
								<td class="left" width="25%"><b><?php echo $text_payment_method; ?></b></td>
								<td class="left" width="25%"><b><?php echo $text_shipping_method; ?></b></td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="left"><select name="payment_currency">
									<?php foreach ($currencies as $currency) { ?>
									<?php if ($currency['code'] == $payment_currency) { ?>
									<option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
									<?php } ?>
									<?php } ?>
								</select></td>
								<td class="left"><input name="currency_value" value="<?php echo $currency_value; ?>" /></td>
								<td class="left"><select name="payment_method">
									<?php foreach ($payment_methods as $payment) { ?>
									<?php if ($payment_method == $payment) { ?>
									<option value="<?php echo $payment; ?>" selected="selected"><?php echo $payment; ?></option>
									<?php } else { ?>
									<option value="<?php echo $payment; ?>"><?php echo $payment; ?></option>
									<?php } ?>
									<?php } ?>
								</select></td>
								<td class="left"><select name="shipping_method">
									<?php foreach ($shipping_methods as $shipping) { ?>
									<?php if ($shipping_method == $shipping) { ?>
									<option value="<?php echo $shipping; ?>" selected="selected"><?php echo $shipping; ?></option>
									<?php } else { ?>
									<option value="<?php echo $shipping; ?>"><?php echo $shipping; ?></option>
									<?php } ?>
									<?php } ?>
								</select></td>
							</tr>
						</tbody>
					</table>
				</div>
				</div>
				<?php if (isset($split_value)) { ?>
      <div style="margin-bottom: 15px;">
        <div style="background: #547C96; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;">Split Order</div>
        <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px;">
          <table class="list">
            <thead>
              <tr>
                <td class="left" width="25%"><b>Owner Name</b></td>
                <td class="left" width="25%"><b>Exp. date</b></td>
                <td class="left" width="25%"><b>Last Digits</b></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="left"><?php echo $split_name; ?></td>
                <td class="left"><?php echo $split_date; ?></td>
                <td class="left"><?php echo $split_value; ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <?php } ?>
			
			<div style="margin-bottom: 15px;">
				<div style="background: #547C96; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_contact_details; ?></div>
				<div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px;">
					<table class="list">
						<thead>
							<tr>
								<td class="left" width="33.3%"><b><?php echo $text_firstname; ?></b></td>
								<td class="left" width="33.3%"><b><?php echo $text_lastname; ?></b></td>
								<td class="left" width="33.3%"><b><?php echo $text_email; ?></b></td>
								<td class="left" width="33.3%"><b><?php echo $text_telephone; ?></b></td>
								<td class="left" width="33.3%"><b><?php echo $text_fax; ?></b></td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="left"><input type="text" id="firstname" name="firstname" value="<?php echo $firstname; ?>" /></td>
								<td class="left"><input type="text" id="lastname" name="lastname" value="<?php echo $lastname; ?>" /></td>
								<td class="left"><input type="text" name="email" value="<?php echo $email; ?>" /></td>
								<td class="left"><input type="text" name="telephone" value="<?php echo $telephone; ?>" /></td>
								<td class="left"><input type="text" name="fax" value="<?php echo $fax; ?>" /></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div style="margin-bottom: 15px;">
				<div style="background: #547C96; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_address_details; ?></div>
				<div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px;">
					<table class="list">
						<thead>
							<tr>
								<td class="left"><span class="required">*</span> <b><?php echo $text_payment_address; ?></b></td>
								<td class="left"><span class="required">*</span> <b><?php echo $text_shipping_address; ?></b> - <span style="font-size:10px;"><?php echo $text_same_address; ?></span> <input type="checkbox" name="sameAddress" id="sameAddress" /></td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="left">
									<table class="list">
										<tr>
											<td class="right"><b><?php echo $text_payment_firstname; ?></b></td>
											<td><input type="text" name="payment_firstname" value="<?php echo $payment_address['firstname']; ?>" /></td>
										</tr>
										<tr>
											<td class="right"><b><?php echo $text_payment_lastname; ?></b></td>
											<td><input type="text" name="payment_lastname" value="<?php echo $payment_address['lastname']; ?>" /></td>
										</tr>
										<tr>
											<td class="right"><b><?php echo $text_payment_company; ?></b></td>
											<td><input type="text" name="payment_company" value="<?php echo $payment_address['company']; ?>" /></td>
										</tr>
										<tr>
											<td class="right"><b><?php echo $text_payment_address; ?></b></td>
											<td><input type="text" name="payment_address_1" value="<?php echo $payment_address['address_1']; ?>" /></td>
										</tr>
										<tr>
											<td class="right"></td>
											<td><input type="text" name="payment_address_2" value="<?php echo $payment_address['address_2']; ?>" /></td>
										</tr>
										<tr>
											<td class="right"><b><?php echo $text_payment_city; ?></b></td>
											<td><input type="text" name="payment_city" value="<?php echo $payment_address['city']; ?>" /></td>
										</tr>
										<tr>
											<td class="right"><b><?php echo $text_payment_postcode; ?></b></td>
											<td><input type="text" name="payment_postcode" value="<?php echo $payment_address['postcode']; ?>" /></td>
										</tr>
										<tr>
											<td class="right"><b><?php echo $text_payment_country; ?></b></td>
											<td><select name="payment_country_id" id="payment_country_id" onchange="$('select[name=\'payment_zone_id\']').load('index.php?route=sale/order/zone&country_id=' + this.value + '&zone_id=<?php echo $payment_address['zone_id']; ?>');">
												<option value="FALSE"><?php echo $text_select; ?></option>
												<?php foreach ($countries as $country) { ?>
												<?php if ($country['country_id'] == $payment_address['country']) { ?>
												<option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
												<?php } ?>
												<?php } ?>
											</select></td>
										</tr>
										<tr>
											<td class="right"><b><?php echo $text_payment_zone; ?></b></td>
											<td><select name="payment_zone_id"></select></td>
										</tr>
									 </table> 
								</td>
								<td class="left">
									<table class="list">
										<tr>
											<td class="right"><b><?php echo $text_payment_firstname; ?></b></td>
											<td><input type="text" name="shipping_firstname" value="<?php echo $shipping_address['firstname']; ?>" /></td>
										</tr>
										<tr>
											<td class="right"><b><?php echo $text_payment_lastname; ?></b></td>
											<td><input type="text" name="shipping_lastname" value="<?php echo $shipping_address['lastname']; ?>" /></td>
										</tr>
										<tr>
											<td class="right"><b><?php echo $text_payment_company; ?></b></td>
											<td><input type="text" name="shipping_company" value="<?php echo $shipping_address['company']; ?>" /></td>
										</tr>
										<tr>
											<td class="right"><b><?php echo $text_payment_address; ?></b></td>
											<td><input type="text" name="shipping_address_1" value="<?php echo $shipping_address['address_1']; ?>" /></td>
										</tr>
										<tr>
											<td class="right"></td>
											<td><input type="text" name="shipping_address_2" value="<?php echo $shipping_address['address_2']; ?>" /></td>
										</tr>
										<tr>
											<td class="right"><b><?php echo $text_payment_city; ?></b></td>
											<td><input type="text" name="shipping_city" value="<?php echo $shipping_address['city']; ?>" /></td>
										</tr>
										<tr>
											<td class="right"><b><?php echo $text_payment_postcode; ?></b></td>
											<td><input type="text" name="shipping_postcode" value="<?php echo $shipping_address['postcode']; ?>" /></td>
										</tr>
										<tr>
											<td class="right"><b><?php echo $text_payment_country; ?></b></td>
											<td><select name="shipping_country_id" id="shipping_country_id" onchange="$('select[name=\'shipping_zone_id\']').load('index.php?route=sale/order/zone&country_id=' + this.value + '&zone_id=<?php echo $shipping_address['zone_id']; ?>');">
												<option value="FALSE"><?php echo $text_select; ?></option>
												<?php foreach ($countries as $country) { ?>
												<?php if ($country['country_id'] == $shipping_address['country']) { ?>
												<option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
												<?php } ?>
												<?php } ?>
											</select></td>
										</tr>
										<tr>
											<td class="right"><b><?php echo $text_payment_zone; ?></b></td>
											<td><select name="shipping_zone_id"></select></td>
										</tr>
									 </table> 
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div style="margin-bottom: 15px;">
				<div style="background: #547C96; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_products; ?></div>
				<div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px;">
					<table class="list">
						<thead>
							<tr>
								<td></td>
								<td class="left"><span class="required">*</span> <b><?php echo $column_product; ?></b></td>
								<td class="left"><b><?php echo $column_model; ?></b></td>
								<td class="left"><b><?php echo $column_quantity; ?></b></td>
								<td class="left"><b><?php echo $column_price; ?></b></td>
								<td class="left"><b><?php echo $column_tax; ?></b></td>
								<td class="left"><b><?php echo $column_total; ?></b></td>
							</tr>
						</thead>
						<tbody id ="order_products">
							<?php if ($order_products) { ?>
							<?php foreach ($order_products as $order_product) { ?>
							<tr><td><a class="delete"><img src="view/image/filemanager/edit-delete.png" /></a></td><td><input name="product_name[<?php echo $order_product['product_id']; ?>]" value="<?php echo $order_product['name']; ?>" /></td><td class="right"><input name="product_model[<?php echo $order_product['product_id']; ?>]" value="<?php echo $order_product['model']; ?>" /></td><td><input size="3" name="product_quantity[<?php echo $order_product['product_id']; ?>]" value="<?php echo $order_product['quantity']; ?>" /></td><td class="right"><input name="product_price[<?php echo $order_product['product_id']; ?>]" value="<?php echo $order_product['price']; ?>" /></td><td class="right"><input name="product_tax[<?php echo $order_product['product_id']; ?>]" value="<?php echo $order_product['tax']; ?>" /></td><td class="right"><input name="product_total[<?php echo $order_product['product_id']; ?>]" value="<?php echo $order_product['total']; ?>" /></td></tr>
							<?php } ?>
							<?php } ?>
						</tbody>
					</table>
					<table class="list">
						<tbody>
							<?if ($_GET['route'] == 'sale/order/create') {?><tr>
								<td class="right" colspan="3">
									<select name="product" id="products" onchange="">
										<?php foreach ($products as $product) { ?>
										<option value="<?php echo $product['product_id']; ?>"><?php echo $product['name']; ?></option>
										<?php } ?>
									</select>
								<a id="addProduct" class="button"><span><?php echo $button_add_product; ?></span></a></td>
							</tr>
							<tr>
								<td class="right" colspan="2"><b><?php echo $column_sub_total; ?></b></td>
								<td class="left">
									<input name="sub_total" value="<?php echo $sub_total; ?>" />
									<input type="hidden" name="product_ids" value="<?php //echo $product_ids; ?>" />
								</td>
							</tr>
							<!--<tr>
								<td class="right" colspan="2" width="700"><b><?php echo $column_shipping_tax; ?></b></td>
								<td class="left"><input name="shipping_tax" value="<?php echo $shipping_tax; ?>" /></td>
							</tr>-->
							<tr>
								<td class="right" colspan="2" width="700"><b><?php echo $column_tax; ?></b></td>
								<td class="left"><input name="tax" value="<?php echo $tax; ?>" /></td>
							</tr>
							<tr>
								<td class="right" colspan="2" width="700"><b><?php echo $column_shipping; ?></b></td>
								<td class="left"><input onblur="updateTotal()" name="shipping_cost" value="<?php echo $shipping_cost; ?>" /></td>
							</tr>
							<tr>
								<td class="right"><!--<a id="calculateTotal" class="button"><span><?php echo $button_calculate_total; ?></span></a>--></td>
								<td class="right"><b><?php echo $column_total; ?></b></td>
								<td class="left"><input name="total" value="<?php echo $total; ?>" /></td>
							</tr><?} else
							{
							?> <?php foreach ($totalss as $total) { ?>
              <tr>
                <td class="right" colspan="4"><b><?php echo $total['title']; ?></b></td>
                <td class="right"><?php echo $total['text']; ?></td>
              </tr>
              <?php } ?><?
							}?>
						</tbody>
					</table>
				</div>
			</div>
			<div style="margin-bottom: 15px;">
				<div style="background: #547C96; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_update; ?></div>
				<div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px;">
					<table class="list">
						<thead>
							<tr>
								<td class="left"><b><?php echo $entry_status; ?></b></td>
								<td class="left"><b><?php echo $entry_notify; ?></b></td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="left"><select name="order_status_id">
										<?php foreach ($order_statuses as $order_status) { ?>
										<?php if ($order_status['order_status_id'] == $order_status_id) { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
										<?php } ?>
										<?php } ?>
									</select></td>
								<td class="left"><?php if ($notify) { ?>
									<input type="checkbox" name="notify" value="1" checked="checked" />
									<?php } else { ?>
									<input type="checkbox" name="notify" value="1" />
									<?php } ?></td>
							</tr>
						<thead>
							<tr>
								<td class="left" colspan="2"><b><?php echo $entry_comment; ?></b></td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="left" colspan="2"><textarea name="comment" cols="40" rows="8" style="width: 99%"><?php echo $comment; ?></textarea></td>
							</tr>
							<tr>
								<td class="right" colspan="2"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button" style="margin-left: 5px;"><span><?php echo $button_cancel; ?></span></a></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript"><!--
$(document).ready(function () {
	
	//var new_sub_total = 0;
	
	$('select[name=\'shipping_zone_id\']').load('index.php?route=sale/order/zone&country_id=<?php echo $shipping_address['country']; ?>&zone_id=<?php echo $shipping_address['zone_id']; ?>');
	$('select[name=\'payment_zone_id\']').load('index.php?route=sale/order/zone&country_id=<?php echo $payment_address['country']; ?>&zone_id=<?php echo $payment_address['zone_id']; ?>');
	
	$('#addProduct').click(function () {
		
		
		
		var product_id = $('#products').val();
		var country_id = $('#payment_country_id').val();
		var zone_id = $('select[name=\'payment_zone_id\']').val();
		$.ajax({
			type: "get",
			url: "index.php?route=sale/order/addProduct",
			data: "product_id=" + product_id + "&country_id=" + country_id + "&zone_id=" + zone_id,
			success: function(data) {
				
				
				
				$('#order_products').append(data);
				/*
				///////////////////////////////////////////////////////////////////////////
				var temp_sub_total = parseFloat($('input[name="product_total['+product_id+']"]').val());
				new_sub_total = new_sub_total + temp_sub_total;
				alert(new_sub_total);
				/////////////////////////////////////////////////////////////////////////////
				*/
				var product_ids = $('input[name="product_ids"]').val();
				
				if (product_ids.length > 0) {
					$('input[name="product_ids"]').val(product_ids + ',' + product_id);
				} else {
					$('input[name="product_ids"]').val(product_id);
				}
				
				
				
				updateSubTotal();
				updateTaxTotal();
				updateTotal();
			}
		});
	});
	
	$('#sameAddress').click(function () {
		if ($('input[name="sameAddress"]:checkbox:checked').val() == 'on') {
			$('input[name="shipping_firstname"]').val($('input[name="payment_firstname"]').val());
			$('input[name="shipping_lastname"]').val($('input[name="payment_lastname"]').val());
			$('input[name="shipping_company"]').val($('input[name="payment_company"]').val());
			$('input[name="shipping_address_1"]').val($('input[name="payment_address_1"]').val());
			$('input[name="shipping_address_2"]').val($('input[name="payment_address_2"]').val());
			$('input[name="shipping_city"]').val($('input[name="payment_city"]').val());
			$('input[name="shipping_postcode"]').val($('input[name="payment_postcode"]').val());
			$('#shipping_country_id').val($('#payment_country_id').val());
			$('select[name=\'shipping_zone_id\']').load('index.php?route=sale/order/zone&country_id=' + $('#payment_country_id').val() + '&zone_id=' + $('select[name=\'payment_zone_id\']').val());
		} else {
			$('input[name="shipping_firstname"]').val('');
			$('input[name="shipping_lastname"]').val('');
			$('input[name="shipping_company"]').val('');
			$('input[name="shipping_address_1"]').val('');
			$('input[name="shipping_address_2"]').val('');
			$('input[name="shipping_city"]').val('');
			$('input[name="shipping_postcode"]').val('');
			$('#shipping_country_id').val('');
			$('select[name=\'shipping_zone_id\']').val('');
		}
	});
	
	/*
	$('#calculateTotal').click(function () {
		$.ajax({
			type: "post",
			url: "index.php?route=sale/order/calculateTotal",
			data: $("#form").serialize(),
			success: function(data) {
				alert(data);
			}
		});
		// set shipping cost
		// add things up
	});
	*/
	
	$('#firstname').blur(function () {
		if ($('input[name="payment_firstname"]').val() == '') {
			$('input[name="payment_firstname"]').val($('input[name="firstname"]').val());
		}
	});
	
	$('#lastname').blur(function () {
		if ($('input[name="payment_lastname"]').val() == '') {
			$('input[name="payment_lastname"]').val($('input[name="lastname"]').val());
		}
	});
});

function updateProductTotal(id) {
	var quantity = parseFloat($('input[name="product_quantity[' + id + ']"]').val());
	var price = parseFloat($('input[name="product_price[' + id + ']"]').val());
	//var tax = parseFloat($('input[name="product_tax[' + id + ']"]').val());
	var total = (quantity * (price));
	$('input[name="product_total[' + id + ']"]').val(total.toFixed(2));
	updateSubTotal();
	updateTaxTotal();
	updateTotal();
}

function updateSubTotal() {
	var product_ids = $('input[name="product_ids"]').val();
	var productList = product_ids.split(',');
	var total = 0.0;
	$.each(productList, function(key, value) { 
		var p_total = parseFloat($('input[name="product_total[' + value + ']"]').val());
		
		if (isNaN(p_total) == false) {
			total += parseFloat(p_total);
		}
	});
	
	$('input[name="sub_total"]').val(total.toFixed(2));
}

function updateTaxTotal() {
	var product_ids = $('input[name="product_ids"]').val();
	var productList = product_ids.split(',');
	var total = 0.0;
	$.each(productList, function(key, value) { 
		var p_total = parseFloat($('input[name="product_total[' + value + ']"]').val());
		var p_tax = parseFloat($('input[name="product_tax[' + value + ']"]').val());
		
		if (isNaN(p_total) == false && isNaN(p_tax) == false) {
			total += parseFloat(p_total * (p_tax / 100));
		}
	});
	
	$('input[name="tax"]').val(total.toFixed(2));
}

function updateTotal() {
	var sub_total = 0;
	var tax = 0;
	var shipping_cost = 0;
	
	if (isNaN(parseFloat($('input[name="sub_total"]').val())) == false) {
		sub_total = parseFloat($('input[name="sub_total"]').val());
	}
	if (isNaN(parseFloat($('input[name="tax"]').val())) == false) {
		tax = parseFloat($('input[name="tax"]').val());
	}
	if (isNaN(parseFloat($('input[name="shipping_cost"]').val())) == false) {
		shipping_cost = parseFloat($('input[name="shipping_cost"]').val());
	}
	
	var total = parseFloat(sub_total) + parseFloat(tax) + parseFloat(shipping_cost);
	
	$('input[name="total"]').val(total.toFixed(2));
}

$(".delete").live('click', function(event) {
	if (confirm('Are you sure you want to remove this product from this order?')) {
		$(this).parent().parent().remove();
		updateSubTotal();
		updateTaxTotal();
		updateTotal();
	}
});
//--></script>
<?php echo $footer; ?>
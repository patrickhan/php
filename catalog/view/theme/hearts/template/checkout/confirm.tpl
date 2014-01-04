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
		<div class="content">
			<table width="100%">
				<tr>
					<td width="33.3%" valign="top">
						<?php if ($shipping_method) { ?>
						<b><?php echo $text_shipping_method; ?></b><br />
						<?php echo $shipping_method; ?><br />
						<a href="<?php echo $checkout_shipping; ?>"><?php echo $text_change; ?></a><br />
						<br />
						<?php } ?>
						<?php if ($payment_method) { ?>
						<b><?php echo $text_payment_method; ?></b><br />
						<?php echo $payment_method; ?><br />
						<a href="<?php echo $checkout_payment; ?>"><?php echo $text_change; ?></a></td>
						<?php } ?>
					<td width="33.3%" valign="top">
						<?php if ($shipping_address) { ?>
						<b><?php echo $text_shipping_address; ?></b><br />
						<?php echo $shipping_address; ?><br />
						<a href="<?php echo $checkout_shipping_address; ?>"><?php echo $text_change; ?></a>
						<?php } ?></td>
					<td width="33.3%" valign="top">
						<?php if ($payment_address) { ?>
						<b><?php echo $text_payment_address; ?></b><br />
						<?php echo $payment_address; ?><br />
						<a href="<?php echo $checkout_payment_address; ?>"><?php echo $text_change; ?></a></td>
						<?php } ?>
				</tr>
			</table>
		</div>
		<div class="content">
			<table width="100%">
				<tr>
					<th align="left"><?php echo $column_product; ?></th>
					<th align="left"><?php echo $column_model; ?></th>
					<th align="right"><?php echo $column_quantity; ?></th>
					<th align="right"><?php echo $column_price; ?></th>
					<th align="right"><?php echo $column_total; ?></th>
				</tr>
				<?php foreach ($products as $product) { ?>
				<tr>
					<td align="left" valign="top"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
						<?php foreach ($product['option'] as $option) { ?>
						<br />
						&nbsp;<small> - <?php echo $option['name']; ?> <?php echo $option['value']; ?></small>
						<?php } ?></td>
					<td align="left" valign="top"><?php echo $product['model']; ?></td>
					<td align="right" valign="top"><?php echo $product['quantity']; ?></td>
					<td align="right" valign="top"><?php echo $product['price']; ?></td>
					<td align="right" valign="top"><?php echo $product['total']; ?></td>
				</tr>
				<?php } ?>
			</table>
			<br />
			<div style="width: 100%; display: inline-block;">
				<table style="float: right; display: inline-block;">
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
		<?php if ($comment) { ?>
		<b style="margin-bottom: 2px; display: block;"><?php echo $text_comment; ?></b>
		<div class="content"><?php echo $comment; ?></div>
		<?php } ?>
		<?php if ($this->cart->hasPayment()) { ?>
		<div id="payment"><?php echo $payment; ?></div>
		<?php } else { ?>
			<div class="buttons">
			  <table>
				 <tr>
					<td align="left"><a onclick="location='<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
					<td align="right"><a id="checkout" class="button"><span><?php echo $button_confirm; ?></span></a></td>
				 </tr>
			  </table>
			</div>
			<script type="text/javascript"><!--
			$('#checkout').click(function() {
				$.ajax({ 
					type: 'GET',
					url: 'index.php?route=payment/free/confirm',
					success: function() {
						location = '<?php echo $continue; ?>';
					}		
				});
			});
			//--></script>
		<?php } ?>
	</div>
	<div class="bottom">
		<div class="left"></div>
		<div class="right"></div>
		<div class="center"></div>
	</div>
</div>
<?php echo $footer; ?> 
<?php echo $header; ?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('view/image/review.png');"><?php echo $heading_title; ?></h1>
		<div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
	</div>
	<div class="content">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<table>
				<tr>
					<td><?php echo $entry_to_product; ?></td>
					<td><select name="product">
						<?php foreach ($products as $product) { ?>
						<option value="<?php echo $product['product_id'] ?>"><?php echo $product['name'] ?></option>
						<?php } ?>
					</select></td>
				</tr>
				<tr>
					<td><?php echo $entry_delete_options; ?></td>
					<td>
						<input type="radio" name="delete_options" value="1" checked="checked" />
						<?php echo $text_yes; ?>
						<input type="radio" name="delete_options" value="0" />
						<?php echo $text_no; ?>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>
<script type="text/javascript"><!--
function getProducts() {
	$('#product option').remove();
	
	$.ajax({
		url: 'index.php?route=catalog/review/category&category_id=' + $('#category').attr('value'),
		dataType: 'json',
		success: function(data) {
			for (i = 0; i < data.length; i++) {
	 			$('#product').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + '</option>');
			}
		}
	});
}
//--></script>
<?php echo $footer; ?>
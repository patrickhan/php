<?php echo $header; ?>
<?php if ($error_warning) { 

//admin/view/template/module/product_display.tpl?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('view/image/module.png');"><?php echo $heading_title; ?></h1>
		<div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
	</div>
	<div class="content">
		<form action="" method="post" enctype="multipart/form-data" id="form">
			<table class="form">
				<tr>
					<td><?php echo $entry_position; ?></td>
					<td><select name="product_display_position">
						<?php if ($product_display_position == 'left') { ?>
						<option value="left" selected="selected"><?php echo $text_left; ?></option>
						<?php } else { ?>
						<option value="left"><?php echo $text_left; ?></option>
						<?php } ?>
						<?php if ($product_display_position == 'right') { ?>
						<option value="right" selected="selected"><?php echo $text_right; ?></option>
						<?php } else { ?>
						<option value="right"><?php echo $text_right; ?></option>
						<?php } ?>
					</select></td>
				</tr>
				<tr>
					<td><?php echo $entry_status; ?></td>
					<td><select name="product_display_status">
						<?php if ($product_display_status) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<?php } ?>
					</select></td>
				</tr>
				<tr>
					<td><?php echo $entry_sort_order; ?></td>
					<td><input type="text" name="product_display_sort_order" value="<?php echo $product_display_sort_order; ?>" size="1" /></td>
				</tr>
				<tr>
					<td><?php echo $entry_type; ?></td>
					<td><select name="product_display_type">
						<?php if ($product_display_type == 'all') { ?>
						<option value="all" selected="selected"><?php echo $text_all; ?></option>
						<?php } else { ?>
						<option value="all"><?php echo $text_all; ?></option>
						<?php } ?>
						<?php if ($product_display_type == 'featured') { ?>
						<option value="featured" selected="selected"><?php echo $text_featured; ?></option>
						<?php } else { ?>
						<option value="featured"><?php echo $text_featured; ?></option>
						<?php } ?>
						<?php if ($product_display_type == 'special') { ?>
						<option value="special" selected="selected"><?php echo $text_special; ?></option>
						<?php } else { ?>
						<option value="special"><?php echo $text_special; ?></option>
						<?php } ?>
					</select></td>
				</tr>
			</table>
		</form>
	</div>
</div>
<?php echo $footer; ?>
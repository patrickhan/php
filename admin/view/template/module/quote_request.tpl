<?php echo $header; ?>
<?php if ($error_warning) { ?>
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
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="form">
				<tr>
					<td><?php echo "Email Address"; ?></td>
					<td><input type="text" name="quote_email" value="<?php echo $quote_email; ?>" size="50" /></td>
				</tr>
				<tr>
					<td><?php echo "Quote Request Page"; ?></td>
					<td><textarea name="quote_description"><?php echo $quote_description; ?></textarea></td>
				</tr>
				<tr>
					<td><?php echo $entry_position; ?></td>
					<td><select name="quote_request_position">
						<?php if ($quote_request_position == 'left') { ?>
						<option value="left" selected="selected"><?php echo $text_left; ?></option>
						<?php } else { ?>
						<option value="left"><?php echo $text_left; ?></option>
						<?php } ?>
						<?php if ($quote_request_position == 'right') { ?>
						<option value="right" selected="selected"><?php echo $text_right; ?></option>
						<?php } else { ?>
						<option value="right"><?php echo $text_right; ?></option>
						<?php } ?>
					</select></td>
				</tr>
				<tr>
					<td><?php echo $entry_status; ?></td>
					<td><select name="quote_request_status">
						<?php if ($quote_request_status) { ?>
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
					<td><input type="text" name="quote_request_sort_order" value="<?php echo $quote_request_sort_order; ?>" size="1" /></td>
				</tr>
			</table>
			<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
CKEDITOR.replace('quote_description');
//--></script>
		</form>
	</div>
</div>
<?php echo $footer; ?>
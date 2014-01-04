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
				<td><?php echo $entry_position; ?></td>
				<td><select name="information_position">
					<?php if ($information_position == 'left') { ?>
					<option value="left" selected="selected"><?php echo $text_left; ?></option>
					<?php } else { ?>
					<option value="left"><?php echo $text_left; ?></option>
					<?php } ?>
					<?php if ($information_position == 'right') { ?>
					<option value="right" selected="selected"><?php echo $text_right; ?></option>
					<?php } else { ?>
					<option value="right"><?php echo $text_right; ?></option>
					<?php } ?>
					</select></td>
			</tr>
			<tr>
				<td><?php echo $entry_status; ?></td>
				<td><select name="information_status">
					<?php if ($information_status) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
					</select></td>
			</tr>
			<tr>
				<td><?php echo $entry_display_sitemap; ?></td>
				<td><?php if ($information_display_sitemap) { ?>
					<input type="radio" name="information_display_sitemap" value="1" checked="checked" />
					<?php echo $text_yes; ?>
					<input type="radio" name="information_display_sitemap" value="0" />
					<?php echo $text_no; ?>
					<?php } else { ?>
					<input type="radio" name="information_display_sitemap" value="1" />
					<?php echo $text_yes; ?>
					<input type="radio" name="information_display_sitemap" value="0" checked="checked" />
					<?php echo $text_no; ?>
					<?php } ?></td>
			</tr>
			<tr>
				<td><?php echo $entry_display_printable_catalog; ?></td>
				<td>
					<?php if ($this->config->get('premium') === TRUE) { ?>
					<?php if ($information_display_printable_catalog) { ?>
					<input type="radio" name="information_display_printable_catalog" value="1" checked="checked" />
					<?php echo $text_yes; ?>
					<input type="radio" name="information_display_printable_catalog" value="0" />
					<?php echo $text_no; ?>
					<?php } else { ?>
					<input type="radio" name="information_display_printable_catalog" value="1" />
					<?php echo $text_yes; ?>
					<input type="radio" name="information_display_printable_catalog" value="0" checked="checked" />
					<?php echo $text_no; ?>
					<?php } ?></td>
					<?php } else { ?>
					<input disabled type="radio" name="information_display_printable_catalog" value="1" />
					<?php echo $text_yes; ?>
					<input disabled type="radio" name="information_display_printable_catalog" value="0" checked="checked" />
					<?php } ?>
			</tr>
			<tr>
				<td><?php echo $entry_sort_order; ?></td>
				<td><input type="text" name="information_sort_order" value="<?php echo $information_sort_order; ?>" size="1" /></td>
			</tr>
		</table>
		</form>
	</div>
</div>
<?php echo $footer; ?>
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
					<td><select name="social_media_position">
						<?php if ($social_media_position == 'left') { ?>
						<option value="left" selected="selected"><?php echo $text_left; ?></option>
						<?php } else { ?>
						<option value="left"><?php echo $text_left; ?></option>
						<?php } ?>
						<?php if ($social_media_position == 'right') { ?>
						<option value="right" selected="selected"><?php echo $text_right; ?></option>
						<?php } else { ?>
						<option value="right"><?php echo $text_right; ?></option>
						<?php } ?>
					</select></td>
				</tr>
				<tr>
					<td><?php echo $entry_status; ?></td>
					<td><select name="social_media_status">
						<?php if ($social_media_status) { ?>
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
					<td><input type="text" name="social_media_sort_order" value="<?php echo $social_media_sort_order; ?>" size="1" /></td>
				</tr>
				<tr>
					<td><?php echo $entry_facebook; ?></td>
					<td><input type="text" name="social_media_facebook_link" value="<?php echo $social_media_facebook_link; ?>" size="50" /></td>
				</tr>
				<tr>
					<td><?php echo $entry_twitter; ?></td>
					<td><input type="text" name="social_media_twitter_link" value="<?php echo $social_media_twitter_link; ?>" size="50" /></td>
				</tr>
				<tr>
					<td><?php echo $entry_myspace; ?></td>
					<td><input type="text" name="social_media_my_space_link" value="<?php echo $social_media_my_space_link; ?>" size="50" /></td>
				</tr>
				<tr>
					<td><?php echo $entry_linkedin; ?></td>
					<td><input type="text" name="social_media_linked_in_link" value="<?php echo $social_media_linked_in_link; ?>" size="50" /></td>
				</tr>
			</table>
		</form>
	</div>
</div>
<?php echo $footer; ?>
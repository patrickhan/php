<?php echo $header; ?>
<script type="text/javascript">
<!--
function confirmation() {
	var answer = confirm("Are you sure you want to delete these headers?")
	if (answer){
		$('#form').submit();
	}
}
//-->
</script>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('view/image/banner.png');"><?php echo $heading_title; ?></h1>
		<div class="buttons"><?if ($amount < 3) {?><a onclick="location='<?php echo $add; ?>'" class="button"><span><?php echo $button_add; ?></span></a><?}?> <a onclick="$('#form1').submit();" class="button"><span>Save Settings</span></a> <a onclick="confirmation()" class="button"><span><?php echo $button_delete; ?></span></a></div>
	</div>
	<div class="content">
		<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">		<?if ($amount >= 3) {?>		You may only have up to three images listed - for more you may purchase our premium header banner manager, which also gives you access to the Slider and Fade display options, contact sales@topseowebdesign.com for details<br><br>		<?}?>
			<table class="list">
				<thead>
					<tr>
						<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
						<?/*<td class="left"><?php echo $column_id; ?></td> */?>
						<td class="left"><?php echo $column_title; ?></td>
						<td class="left"><?php echo $column_image; ?></td>
						<td class="right"><?php echo $column_status; ?></td>
						<td class="right"><?php echo $column_date_added; ?></td>
						<td class="right"><?php echo $column_date_modified; ?></td>
						<td class="right"><?php echo $column_action; ?></td>
					</tr>
				</thead>
				<tbody>
					<?php if (isset($header_images)) { ?>
					<?php $class = 'odd'; ?>
					<?php foreach ($header_images as $key => $header) { ?>
					<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
					<tr class="row" id="row<?php echo $key; ?>" >
						<td style="text-align: center;"><?php if ($header['selected']) { ?>
						<input type="checkbox" name="selected[]" value="<?php echo $header['header_id']; ?>" checked="checked" onclick="highlight(this, 'row<?php echo $key; ?>')"  />
						<?php } else { ?>
						<input type="checkbox" name="selected[]" value="<?php echo $header['header_id']; ?>" onclick="highlight(this, 'row<?php echo $key; ?>')" />
						<?php } ?></td>
						<?/*<td class="left"><?php echo $header['header_id']; ?></td> */?>
						<td class="left"><?php echo $header['title']; ?></td>
						<td class="left"><?php echo $header['image']; ?></td>
						<td class="right"><?php echo $header['status']; ?></td>
						<td class="right"><?php echo $header['date_added']; ?></td>
						<td class="right"><?php echo $header['date_modified']; ?></td>
						<td class="right"><?php foreach ($header['action'] as $action) { ?>
						[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
						<?php } ?></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr class="even">
						<td class="center" colspan="9"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<table></form>
			<form id="form1" action="index.php?route=extension/header_image/changeSettings" method="post">
				<tr>
					<td><?php echo $entry_type; ?></td>
					<td><select name="display_type">
						<?php if ($amount > 0) { ?>
						<?php if ($display_type == 'one') { ?>
						<option value="one" selected="selected"><?php echo $text_one; ?></option>
						<?php } else { ?>
						<option value="one"><?php echo $text_one; ?></option>
						<?php } ?>
						<?php } ?>
						<?php if ($amount > 1) { ?>
						<?php if ($display_type == 'refresh') { ?>
						<option value="refresh" selected="selected"><?php echo $text_refresh; ?></option>
						<?php } else { ?>
						<option value="refresh"><?php echo $text_refresh; ?></option>
						<?php } ?>
						<?php if (true) { 
							if ($display_type == 'slider') { ?>
						<option value="slider" selected="selected"><?php echo $text_slider; ?></option>
						<?php 	} else { ?>
						<option value="slider"><?php echo $text_slider; ?></option>
						<?php 	}
						if ($display_type == 'fade') { ?>
						<option value="fade" selected="selected">Fade</option>
						<?php 	} else { ?>
						<option value="fade">Fade</option>
						<?php 	}
						} ?>
						<?php /*if ($display_type == 'eslider') { ?>
						<option value="eslider" selected="selected"><?php echo $text_eslider; ?></option>
						<?php } else { ?>
						<option value="eslider"><?php echo $text_eslider; ?></option>
						<?php }*/ ?>
						<?php } ?>
						<?php if ($display_type == 'off') { ?>
						<option value="off" selected="selected"><?php echo $text_off; ?></option>
						<?php } else { ?>
						<option value="off"><?php echo $text_off; ?></option>
						<?php } ?>
					</select></td>
				</tr>
				<tr><td>Length:</td><td><input size="8" type='text' name='sec' value='<?echo $sec;?>'></td>
				</tr>
			</table>
			1000 = 1 second, 5000 = 5 seconds. This only applies to the slider and fade options
			<?/* echo $text_useWhich; ?><input type="text" name="headersUsed" /><br>
			<small><? echo $text_useInstructions; ?></small><br>*/ ?>
			<br>
			<div style="margin: 10px 100px;">
				<table style="border: solid 1px black;" >
					<thead colspan="3" style="text-align:center"><b><?php echo $text_original; ?></b><small><?php echo $text_replacement; ?></small></thead>
					<tr>
						<td><?php echo $text_path; ?></td><td><?php echo $ppath; ?></td><td rowspan="3" style="text-align: center"><img src="../<?php echo $uurl; ?>" width="70%" /></td>
					</tr>
					<tr>
						<td><?php echo $text_height; ?> </td><td><input size='4' type='text' id='height' name='height' value='<?php echo $hheight; ?>'/> </td>
					</tr>
					<tr>
						<td><?php echo $text_width; ?> </td><td><input size='4' type='text' id='width' name='width' value='<?php echo $wwidth; ?>'/> </td>
					</tr>
				</table>
				<div style="text-align: center; margin-top: 20px">
					<a href="../<?php echo $uurl; ?>" class="button"><span><?php echo $button_download; ?></span></a><br>
					<small><?php echo $text_save; ?></small>
				</div>
			</div>
			<input type="hidden" name="Submit" value="Submit" />
		</form>
	</div>
</div>
<?php echo $footer; ?>
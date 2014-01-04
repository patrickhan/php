<?php echo $header; ?>
<script type="text/javascript">
<!--
function confirmation() {
	var answer = confirm("Are you sure you want to delete these banners?")
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
		<div class="buttons"><a onclick="location='<?php echo $add; ?>'" class="button"><span><?php echo $button_add; ?></span></a><a onclick="confirmation()" class="button"><span><?php echo $button_delete; ?></span></a></div>
	</div>
	<div class="content">
		<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="list">
				<thead>
					<tr>
						<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
						<td class="left"><?php echo $column_title; ?></td>
						<td class="left"><?php echo $column_group; ?></td>
						<td class="right"><?php echo $column_status; ?></td>
						<td class="right"><?php echo $column_date_added; ?></td>
						<td class="right"><?php echo $column_date_modified; ?></td>
						<td class="right"><?php echo $column_clicks; ?></td>
						<td class="right"><?php echo $column_views; ?></td>
						<td class="right"><?php echo $column_action; ?></td>
					</tr>
				</thead>
				<tbody>
					<?php if ($banners) { ?>
					<?php $class = 'odd'; ?>
					<?php foreach ($banners as $key => $banner) { ?>
					<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
					<tr class="row" id="row<?php echo $key; ?>" >
						<td style="text-align: center;"><?php if ($banner['selected']) { ?>
						<input type="checkbox" name="selected[]" value="<?php echo $banner['banner_id']; ?>" checked="checked" onclick="highlight(this, 'row<?php echo $key; ?>')" />
						<?php } else { ?>
						<input type="checkbox" name="selected[]" value="<?php echo $banner['banner_id']; ?>" onclick="highlight(this, 'row<?php echo $key; ?>')" />
						<?php } ?></td>
						<td class="left"><?php echo $banner['title']; ?></td>
						<td class="left"><?php echo $banner['group']; ?></td>
						<td class="right"><?php echo $banner['status']; ?></td>
						<td class="right"><?php echo $banner['date_added']; ?></td>
						<td class="right"><?php echo $banner['date_modified']; ?></td>
						<td class="right"><?php echo $banner['clicks']; ?></td>
						<td class="right"><?php echo $banner['views']; ?></td>
						<td class="right"><?php foreach ($banner['action'] as $action) { ?>
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
		</form>
	</div>
</div>
<?php echo $footer; ?>
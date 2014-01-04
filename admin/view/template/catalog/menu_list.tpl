<?php echo $header; ?>

<script type="text/javascript">
<!--
function confirmation() {
	var answer = confirm("Are you sure you want to delete these menu items?")
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
		<h1 style="background-image: url('view/image/link.png');"><?php echo $heading_title; ?></h1>
		<div class="buttons"><a onclick="location='<?php echo $add; ?>'" class="button"><span><?php echo $button_add; ?></span></a><a onclick="confirmation()" class="button"><span><?php echo $button_delete; ?></span></a></div>
	</div>
	<div class="content">
		<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="list">
				<thead>
					<tr>
						<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
						<td class="left"><?php echo $column_title; ?></td>
						<td class="left"><?php echo $column_url; ?></td>
						<td class="right"><?php echo $column_status; ?></td>
						<td class="right"><?php echo $column_action; ?></td>
					</tr>
				</thead>
				<tbody>
					<?php if ($menus) { ?>
					<?php $class = 'odd'; ?>
					<?php foreach ($menus as $key => $menu) { ?>
						<?php foreach ($menuComplete as $mcomplete) { ?>
							<?php if ($mcomplete['menu_id'] == $menu['menu_id']) { ?>
								<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
								<tr class="row" id="row<?php echo $key; ?>" >
								  <td style="text-align: center;"><?php if ($menu['selected']) { ?>
									<input type="checkbox" name="selected[]" value="<?php echo $menu['menu_id']; ?>" checked="checked" onclick="highlight(this, 'row<?php echo $key; ?>')" />
									<?php } else { ?>
									<input type="checkbox" name="selected[]" value="<?php echo $menu['menu_id']; ?>" onclick="highlight(this, 'row<?php echo $key; ?>')" />
									<?php } ?></td>
									<td class="left"><?php echo $mcomplete['title']; ?></td>
									<td class="left"><?php echo $menu['url']; ?></td>
									<td class="right"><?php echo $menu['status']; ?></td>
									<td class="right"><?php foreach ($menu['action'] as $action) { ?>
									[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
									<?php } ?></td>
								</tr>
							<?php } ?>
						<?php } ?>
					<?php } ?>
					<?php } else { ?>
					<tr class="even">
						<td class="center" colspan="6"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		<div class="pagination"><?php echo $pagination; ?></div>
	</div>
</div>
<?php echo $footer; ?>
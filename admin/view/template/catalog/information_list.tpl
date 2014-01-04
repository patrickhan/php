<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?><h3>Show: <input type="radio" name="set" onClick='window.location="index.php?route=catalog/information/setOff"' <?if (!$alls && !$dis) {?>checked='yes'<?}?> value='Off'/>Enabled Only <input type="radio" name="set" onClick='window.location="index.php?route=catalog/information/setDis"' <?if ($dis) {?>checked='yes'<?}?> value='Dis'/>Disabled Only</h3>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('view/image/information.png');"><?php echo $heading_title; ?></h1>
		<div class="buttons"><a class="tooltip"><?echo $qmark;?><span>Sets a URL Alias for every content page with Sitemap set to "Yes" that doesn't have one, making your site more search-engine friendly</span></a><a onclick="location='<?php echo $sAlias; ?>'" class="button"><span>Set Alias</span></a><a onclick="location='<?php echo $insert; ?>'" class="button"><span><?php echo $button_add_information; ?></span></a><a onclick="$('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
	</div>
	<div class="content">
		<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="list">
				<thead>
					<tr>
						<td width="1" style="align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
						<td class="left"><?php if ($sort == 'id.title') { ?>
							<a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_title; ?></a>
							<?php } else { ?>
							<a href="<?php echo $sort_title; ?>"><?php echo $column_title; ?></a>
							<?php } ?></td>
							<td class="right"><?php if ($sort == 'i.sitemap') { ?>
							<a href="<?php echo $sort_sitemap; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sitemap; ?></a>
							<?php } else { ?>
							<a href="<?php echo $sort_sitemap; ?>"><?php echo $column_sitemap; ?></a>
							<?php } ?></td>
							<td class="right"><?php if ($sort == 'i.location') { ?>
							<a href="<?php echo $sort_location; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_location; ?></a>
							<?php } else { ?>
							<a href="<?php echo $sort_location; ?>"><?php echo $column_location; ?></a>
							<?php } ?></td>
							<td class="right">
							<select id="type" onchange="var type = document.getElementById('type'); window.location = type.options[type.selectedIndex].value;">
							<option value="<?echo $url;?>&type=-1">Show Type</option>
							<option value="<?echo $url;?>&type=-1">All</option>
							<option value="<?echo $url;?>&type=1" <?php if ($type == '1') echo 'SELECTED'; ?>> ECA</option>
							<option value="<?echo $url;?>&type=2" <?php if ($type == '2') echo 'SELECTED'; ?>> Content Page</option>
							<option value="<?echo $url;?>&type=3" <?php if ($type == '3') echo 'SELECTED'; ?>> Module</option>
							<option value="<?echo $url;?>&type=0" <?php if ($type == '0') echo 'SELECTED'; ?>> None</option>
							</select>
							</td>
						<td class="right"><?php if ($sort == 'i.sort_order') { ?>
							<a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
							<?php } else { ?>
							<a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
							<?php } ?></td>
						<td class="right"><?php if ($sort == 'i.date_modified') { ?>
							<a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_modified; ?></a>
							<?php } else { ?>
							<a href="<?php echo $sort_date_modified; ?>"><?php echo $column_modified; ?></a>
							<?php } ?></td>
						<td class="right"><?php echo $column_action; ?></td>
					</tr>
				</thead>
				<tbody>
					<?php if ($informations) { ?>
					<?php foreach ($informations as $key => $information) { ?>
					<?if ($information['information_id'] == '1')
					{
					?>
					<tr class="rowmeta" id="row<?php echo $key; ?>" >
					<?} else {?>
					<tr class="row" id="row<?php echo $key; ?>" >
					<?}?>
						<td style="align: center;"><?php if ($information['selected']) { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $information['information_id']; ?>" checked="checked" onclick="highlight(this, 'row<?php echo $key; ?>')" />
							<?php } else { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $information['information_id']; ?>" onclick="highlight(this, 'row<?php echo $key; ?>')" />
							<?php } ?></td>
						<td class="left"><?php echo $information['title']; ?></td>
						<td class="right"><?php echo $information['sitemap']; ?></td>
						<td class="right"><?php echo $information['location']; ?></td>
						<td class="right"><?php echo $information['type']; ?></td>
						<td class="right"><?php echo $information['sort_order']; ?></td>
						<td class="right"><?php echo $information['date_modified']; ?></td>
						<td class="right"><?php foreach ($information['action'] as $action) { ?>
							[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
							<?php } ?></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="center" colspan="4"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		<?if ($show) {?>
		<div class="pagination"><?php echo $pagination; ?></div>
		<?}?>
	</div>
</div>
<?php echo $footer; ?>
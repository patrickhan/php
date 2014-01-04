<?php echo $header; ?>
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
		<h1 style="background-image: url('view/image/customer.png');"><?php echo $heading_title; ?></h1>
		<div class="buttons"><a onclick="location='<?php echo $insert; ?>'" class="button"><span><?php echo $button_insert; ?></span></a><a onclick="$('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
	</div>
	<div class="content">
		<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
		<table class="list">
			<thead>
				<tr>
					<td width="1" style="align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
					<td class="left"><?php if ($sort == 'email') { ?>
						<a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_email; ?></a>
						<?php } else { ?>
						<a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a>
						<?php } ?></td>
					<td class="left"><?php if ($sort == 'date_added') { ?>
						<a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
						<?php } else { ?>
						<a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
						<?php } ?></td>
					<td class="right"><?php echo $column_action; ?></td>
				</tr>
			</thead>
			<tbody>
				<tr class="filter">
					<td></td>
					<td><input type="text" name="filter_email" value="<?php echo $filter_email; ?>" /></td>
					<td></td>
					<td align="right"><a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></td>
				</tr>
				<?php if ($customers) { ?>
				<?php foreach ($customers as $customer) { ?>
				<tr>
					<td style="align: center;"><?php if ($customer['selected']) { ?>
						<input type="checkbox" name="selected[]" value="<?php echo $customer['email']; ?>" checked="checked" />
						<?php } else { ?>
						<input type="checkbox" name="selected[]" value="<?php echo $customer['email']; ?>" />
					<?php } ?></td>
					<td class="left"><?php echo $customer['email']; ?></td>
					<td class="left"><?php echo $customer['date_added']; ?></td>
					<td class="right"><?php foreach ($customer['action'] as $action) { ?>
						[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
						<?php } ?></td>
				</tr>
				<?php } ?>
				<?php } else { ?>
				<tr>
					<td class="center" colspan="6"><?php echo $text_no_results; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		</form>
		<div class="pagination"><?php echo $pagination; ?></div>
	</div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=sale/newsletter';
	
	var filter_email = $('input[name=\'filter_email\']').attr('value');
	
	if (filter_email) {
		url += '&filter_email=' + encodeURIComponent(filter_email);
	}
	
	location = url;
}

//--></script>
<?php echo $footer; ?>
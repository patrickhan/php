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
		<h1 style="background-image: url('view/image/discount.png');"><?php echo $heading_title; ?></h1>
		<div class="buttons"><a onclick="location='<?php echo $insert; ?>'" class="button"><span>Add Discount</span></a><a onclick="$('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
	</div>
	<div class="content">
		<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="list">
				<thead>
					<tr>
						<td width="1" style="align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
						<td class="left">Minimum Order</td>
						<td class="left">Discount</td>
						<td class="left">Type</td>
						
						<td class="right"><?php echo $column_action; ?></td>
					</tr>
				</thead>
				<tbody>
					<?php if ($discounts) { ?>
					<?php foreach ($discounts as $key => $discount) { ?>
					<tr class="row" id="row<?php echo $key; ?>" >
						<td style="align: center;"><?php if (false) { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $discount['discount_id']; ?>" checked="checked" onclick="highlight(this, 'row<?php echo $key; ?>')" />
							<?php } else { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $discount['discount_id']; ?>" onclick="highlight(this, 'row<?php echo $key; ?>')" />
							<?php } ?></td>
						<td class="left">$<?php echo $discount['amount']; ?></td>
						<td class="left"><?php echo $discount['cost']; ?></td>
						<td class="left"><?php echo $discount['type']; ?></td>
						<td class="right"><?php foreach ($discount['action'] as $action) { ?>
							[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
							<?php } ?></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="center" colspan="5"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		<div class="pagination"><?php echo $pagination; ?></div>
	</div>
</div>
<?php echo $footer; die; ?>
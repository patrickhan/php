<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background: url('view/image/information.png') 2px 9px no-repeat;"><?php echo $heading_title; ?></h1>
		<div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
	</div>
	<div class="content">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="tabs">
				<?php foreach ($languages as $language) { ?>
				<a tab="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
				<?php } ?>
			</div>

				<table class="form">
					<tr>
						<td>Minimum Order</td>
						<td>$<input name="amount" value="<?php echo $amount; ?>" /></td>
					</tr>
					<tr>
						<td>Amount Off</td>
						<td><input name="cost" value="<?php echo $cost; ?>" /></td>
					</tr>
					<tr>
						<td>Discount Type</td>
						<td>
						<select name='type' id='type'>
						<option value='0' <?if ($type=='0') {?>selected='selected'<?}?>>Dollars Off</option>
						<option value='1' <?if ($type=='1') {?>selected='selected'<?}?>>Percent Off</option>
						</select></td>
					</tr>
					
				</table>

		</form>
	</div>
</div>

<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
<?php echo $footer; die; ?>
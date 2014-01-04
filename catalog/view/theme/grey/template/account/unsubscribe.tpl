<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
	<div class="top">
		<div class="left"></div>
		<div class="right"></div>
		<div class="center">
			<h1><?php echo $heading_title; ?></h1>
		</div>
	</div>
	<div class="middle">
		<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
		<?php } ?>
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="unsubscribe">
			<div class="light" style="padding: 10px; margin-bottom: 10px;">
				<table>
					<tr>
						<td><?php echo $entry_email; ?></td>
						<td><input type="text" name="email" /></td>
						<td><a onclick="$('#unsubscribe').submit();" class="button"><span><?php echo $button_unsubscribe; ?></span></a></td>
					</tr>
				</table>
			</div>
		</form>
	</div>
	<div class="bottom">
		<div class="left"></div>
		<div class="right"></div>
		<div class="center"></div>
	</div>
</div>
<?php echo $footer; ?> 
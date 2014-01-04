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
		<h1 style="background-image: url('view/image/backup.png');"><?php echo $heading_title; ?></h1>
		<div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_restore; ?></span></a><a onclick="location='<?php echo $backup; ?>'" class="button"><span>Download Database</span></a><a onclick="location='<?php echo $backup_save; ?>'" class="button"><span>Backup Database</span></a><a onclick="location='<?php echo $backup_full; ?>'" class="button"><span>Full Site Backup</span></a></div>
	</div>
	<div class="content">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="form">
				<tr>
					<td width="25%"><?php echo $entry_restore; ?></td>
					<td><input type="file" name="import" /></td>
				</tr>
			</table>
		</form>
	<?php if ($latest_filename && $latest_date_created) { ?>
		<p><?php echo $text_last_backup . ' ' . $latest_date_created . ' : ' . $latest_filename; ?></p>				<?if (file_exists('backups/sitebackup.zip')) {?>	<a href='backups/sitebackup.zip'>Download Full Site Backup</a>
	<?php } } ?>
	</div>
</div>
<?php echo $footer; ?>
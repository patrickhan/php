<?php echo $header; ?>

<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('view/image/link.png');"><?php echo $heading_title; ?></h1>
		<div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
	</div>
	<div class="content">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="tabs">
				<?php foreach ($languages as $language) { ?>
				<a tab="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
				<?php } ?>
			</div>
			<?php foreach ($languages as $language) { ?>
			<div id="language<?php echo $language['language_id']; ?>">
				<table class="form">
					<tr>
						<td><span class="required">*</span> <?php echo $entry_title; ?></td>
						<td><input name="menu_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($menu_descriptions[$language['language_id']]) ? $menu_descriptions[$language['language_id']]['title'] : ''; ?>" />
							<?php if (isset($error_title[$language['language_id']])) { ?>
							<span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
							<?php } ?></td>
					</tr>
				</table>
			</div>
			<?php } ?>
			<table class="form">
				<tr>
					<td><span class="required">*</span> <?php echo $entry_url; ?></td>
					<td><input type="text" name="url" value="<?php echo $url; ?>" />
						<?php if (isset($error_url)) { ?>
						<span class="error"><?php echo $error_url; ?></span>
						<?php } ?></td>
				</tr>
				<tr>
					<td><span class="required">*</span> <?php echo $entry_status; ?></td>
					<td><select name="status">
						<?php if ($status) { ?>
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
					<td><input name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
				</tr>
                
            <!--    DROPDOWN MENU
            <tr>
					<td><?php echo $entry_parent; ?></td>
					<td>
						<select name="parent_id">
						  <option value="0"><?php echo $text_none; ?></option>
						  <?php foreach ($menuComplete as $menuc) { ?>
							<?php if ($menuc['menu_id'] == $parent_id) { ?>
								<option value="<?php echo $menuc['menu_id']; ?>" selected="selected"><?php echo $menuc['title']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $menuc['menu_id']; ?>"><?php echo $menuc['title']; ?></option>
							<?php } ?>
						  <?php } ?>
						</select>
					</td>
				</tr>
            -->   
                
			</table>
		</form>
	</div>
</div>
<script type="text/javascript"><!--

$.tabs('.tabs a'); 

//--></script>
<?php echo $footer; ?>
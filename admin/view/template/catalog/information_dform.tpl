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
			<?if (false) {?>
					
					<?php foreach ($languages as $language) { ?>
			<div id="language<?php echo $language['language_id']; ?>">
				<table class="form">
					<tr>
						<td colspan='2'><div class='biglink'>This section is only for editing the metatags on the main index page and for editing the robots.txt file</div></td>
						<td><input type='hidden' name="information_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['title'] : ''; ?>" />
							<?php if (isset($error_title[$language['language_id']])) { ?>
							<span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
							<?php } ?></td>
					</tr>
					<tr class='meta1'><td colspan='2'>Metatags</td></tr>
					<tr class='meta1'>
						<td><?php echo $entry_title_tag; ?>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<a class="tooltip">
<?echo $qmark;?>
<span>The Title Tag is the Title what will be displayed when your site is listed on the Search Engines. You can control what is there and this is very important. An interesting and catchy title tag will entice people to click to go to your website.</span></a></td>
						<td><input class="longtext" name="information_description[<?php echo $language['language_id']; ?>][title_tag]" value="<?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['title_tag'] : ''; ?>" /></td>
					</tr>
					
					<tr class='meta1'>
						<td><?php echo $entry_meta_keywords; ?>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<a class="tooltip">
<?echo $qmark;?>
<span>Here you list the keywords you would like for your site. When someone searches for one of these keywords you would like them to find your site. The keywords should be separated by a comma (Please read more in the SEO Manual)</span></a></td>
						<td><textarea name="information_description[<?php echo $language['language_id']; ?>][meta_keywords]" cols="100" rows="3"><?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['meta_keywords'] : ''; ?></textarea></td>
					</tr>
					<tr class='meta1'>
						<td><?php echo $entry_meta_description; ?>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a class="tooltip">
<?echo $qmark;?>
<span>This is the content that will displayed on the Search Engines when your site is listed in a search. Again it is very important to have a clear and catchy description to entice people to go to your site.</span></a></td>
						<td><textarea name="information_description[<?php echo $language['language_id']; ?>][meta_description]" cols="100" rows="3"><?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['meta_description'] : ''; ?></textarea></td>
					</tr>
					<tr class='meta1'>
						<td>robots.txt File Content&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a class="tooltip">
<?echo $qmark;?>
<span>This file is used to tell the search engines what to index and what not to index.</span></a></td>
						<td><textarea name="robots" cols="100" rows="3"><?php echo $robots; ?></textarea></td>
					</tr>
					<tr>
					
						
						<td><input type='hidden' name="information_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>" value="<?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['description'] : ''; ?>"/>
							<?php if (isset($error_description[$language['language_id']])) { ?>
							<span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
							<?php } ?></td>
					</tr>
					<input type="hidden" name="information_description[<?php echo $language['language_id']; ?>][short]" value="" />
				</table>
			</div>
			<?php } ?>
			
					
					<input type="hidden" name="sitemap" value="0" />
					<input type="hidden" name="type" value="0" />
					<input type="hidden" name="location" value="0" />
					<input type="hidden" name="sort_order" value="<?php echo $sort_order; ?>" />
					<input type="hidden" name="keyword" value="<?php echo $keyword; ?>" />
					<?}
					else
					{?>
			<?php foreach ($languages as $language) { ?>
			<div id="language<?php echo $language['language_id']; ?>">
				<table class="form">
					<tr>
						<td><span class="required">*</span> <?php echo $entry_title; ?></td>
						<td><input class="longtext" name="information_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['title'] : ''; ?>" />
							<?php if (isset($error_title[$language['language_id']])) { ?>
							<span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
							<?php } ?></td>
					</tr>
					<tr style='display:none;' class='meta1'><td colspan='2'>Metatags</td></tr>
					<tr style='display:none;' class='meta1'>
						<td><?php echo $entry_title_tag; ?> &nbsp; &nbsp;<a class="tooltip">
<?echo $qmark;?>
<span>The Title Tag is the Title what will be displayed when your site is listed on the Search Engines. You can control what is there and this is very important. An interesting and catchy title tag will entice people to click to go to your website.</span></a></td>
						<td><input class="longtext" name="information_description[<?php echo $language['language_id']; ?>][title_tag]" value="<?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['title_tag'] : ''; ?>" /></td>
					</tr>
					
					<tr style='display:none;' class='meta1'>
						<td><?php echo $entry_meta_keywords; ?>&nbsp; &nbsp; &nbsp;<a class="tooltip">
<?echo $qmark;?>
<span>Here you list the keywords you would like for your site. When someone searches for one of these keywords you would like them to find your site. The keywords should be separated by a comma (Please read more in the SEO Manual)</span></a></td>
						<td><textarea name="information_description[<?php echo $language['language_id']; ?>][meta_keywords]" cols="100" rows="3"><?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['meta_keywords'] : ''; ?></textarea></td>
					</tr>
					<tr style='display:none;' class='meta1'>
						<td>Metatag Description <a class="tooltip">
<?echo $qmark;?>
<span>This is the content that will displayed on the Search Engines when your site is listed in a search. Again it is very important to have a clear and catchy description to entice people to go to your site.</span></a></td>
						<td><textarea name="information_description[<?php echo $language['language_id']; ?>][meta_description]" cols="100" rows="3"><?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['meta_description'] : ''; ?></textarea></td>
					</tr>
					<input type='hidden' name="information_description[<?php echo $language['language_id']; ?>][short]" id="short<?php echo $language['language_id']; ?>" value=''/>
					<tr>
					
						<td><span class="required">*</span> <?php echo $entry_description; ?></td>
						<td><textarea name="information_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['description'] : ''; ?></textarea>
							<?php if (isset($error_description[$language['language_id']])) { ?>
							<span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
							<?php } ?></td>
					</tr>
				</table>
			</div>
			<?php } ?>
			<table class="form">
				<input type="hidden" name="keyword" value="<?php echo $keyword; ?>" />
				<tr>
					<td><?php echo $entry_sort_order; ?></td>
					<td><input class="longtext" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
				</tr>				<tr>					<td>Status</td>					<td>						<input type='radio' name='status' value="1" <?php if ($status == '1') echo 'checked'; ?>>Enabled</option>						<input type='radio' name='status' value="0" <?php if ($status == '0') echo 'checked'; ?>>Disabled</option>						</td>				</tr>				
				<tr>
					<td>Show In Sitemap</td>
					<td>
						<input type='radio' name='sitemap' value="1" <?php if ($sitemap == '1') echo 'checked'; ?>>Yes</option>
						<input type='radio' name='sitemap' value="0" <?php if ($sitemap == '0') echo 'checked'; ?>>No</option>
						</td>
				</tr>
				<tr>
					<td><?php echo $entry_location; ?></td>
					<td>
						<input type='radio' name='location' value="1" <?php if ($location == '1') echo 'checked'; ?>> Left Menu</input>
						<input type='radio' name='location' value="2" <?php if ($location == '2') echo 'checked'; ?>> Footer Menu</input>
						<?/*?><input type='radio' name='location' value="3" <?php if ($location == '3') echo 'checked'; ?>> RC Module</input>
						<input type='radio' name='location' value="4" <?php if ($location == '4') echo 'checked'; ?>> LC Module</input>
						<input type='radio' name='location' value="5" <?php if ($location == '5') echo 'checked'; ?>> Menu Manager</input><?
						<input type='radio' name='location' value="6" <?php if ($location == '6') echo 'checked'; ?>> Articles Page</input>*/?>
						<input type='radio' name='location' value="0" <?php if ($location == '0') echo 'checked'; ?>> Not Displayed</input>
						</td>
				</tr>
				<tr>
					<td><?php echo $entry_type; ?></td>
					<td>
						<input type='radio' name='type' value="1" <?php if ($type == '1') echo 'checked'; ?>> Editable Content Area</input>
						<input type='radio' name='type' value="2" <?php if ($type == '2') echo 'checked'; ?>> Content Page</input>
						<input type='radio' name='type' value="3" <?php if ($type == '3') echo 'checked'; ?>> Module</input>
						<?/*<input type='radio' name='type' value="4" <?php if ($type == '4') echo 'checked'; ?>> Article</input>*/?>
						<input type='radio' name='type' value="0" <?php if ($type == '0') echo 'checked'; ?>> None</input>
						</td>
				</tr>
				
			</table>
			<?}?>
		</form>
	</div>
</div>
<script type="text/javascript" src="view/javascript/ckfinder/ckfinder.js"></script>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
<?php if (!$meta) { foreach ($languages as $language) { ?>
    var editor = CKEDITOR.replace('description<?php echo $language['language_id']; ?>');
    CKFinder.setupCKEditor( editor, '/admin/view/javascript/ckfinder/');
<?php }
} ?>
//--></script>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
<?php echo $footer; ?>
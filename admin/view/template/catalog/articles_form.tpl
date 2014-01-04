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
					<tr class='meta1'><td colspan='2'>Metatags</td></tr>
					<tr class='meta1'>
						<td><?php echo $entry_title_tag; ?> &nbsp; &nbsp;<a class="tooltip">
<?echo $qmark;?>
<span>The Title Tag is the Title what will be displayed when your site is listed on the Search Engines. You can control what is there and this is very important. An interesting and catchy title tag will entice people to click to go to your website.</span></a></td>
						<td><input class="longtext" name="information_description[<?php echo $language['language_id']; ?>][title_tag]" value="<?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['title_tag'] : ''; ?>" /></td>
					</tr>
					
					<tr class='meta1'>
						<td><?php echo $entry_meta_keywords; ?>&nbsp; &nbsp; &nbsp;<a class="tooltip">
<?echo $qmark;?>
<span>Here you list the keywords you would like for your site. When someone searches for one of these keywords you would like them to find your site. The keywords should be separated by a comma (Please read more in the SEO Manual)</span></a></td>
						<td><textarea name="information_description[<?php echo $language['language_id']; ?>][meta_keywords]" cols="100" rows="3"><?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['meta_keywords'] : ''; ?></textarea></td>
					</tr>
					<tr class='meta1'>
						<td>Metatag Description <a class="tooltip">
<?echo $qmark;?>
<span>This is the content that will displayed on the Search Engines when your site is listed in a search. Again it is very important to have a clear and catchy description to entice people to go to your site.</span></a></td>
						<td><textarea name="information_description[<?php echo $language['language_id']; ?>][meta_description]" cols="100" rows="3"><?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['meta_description'] : ''; ?></textarea></td>
					</tr>
					<tr>
					
						<td>Short: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a class="tooltip">
<?echo $qmark;?>
<span>This is the little blurb that will appear on the articles page</span></a></td>
						<td><textarea name="information_description[<?php echo $language['language_id']; ?>][short]" cols="100" rows="3" id="short<?php echo $language['language_id']; ?>"><?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['short'] : ''; ?></textarea></td>
					</tr>
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
				<tr>
					<td><?php echo $entry_keyword; ?></td>
					<td><input class="longtext" type="text" name="keyword" value="<?php echo $keyword; ?>" /></td>
				</tr>
				<tr>
					<td><?php echo $entry_sort_order; ?></td>
					<td><input class="longtext" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
				</tr>
								<tr>					<td>Status</td>					<td>						<input type='radio' name='status' value="1" <?php if ($status == '1') echo 'checked'; ?>>Enabled</option>						<input type='radio' name='status' value="0" <?php if ($status == '0') echo 'checked'; ?>>Disabled</option>						</td>				</tr>				
						<input type='hidden' id='sitemap' name='sitemap' value="1"/> 
				
						<input type='hidden' name='location' id='location' value="6"/>
				
						<input type='hidden' id='type' name='type' value="4"/>
				
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
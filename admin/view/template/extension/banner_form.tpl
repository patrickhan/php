<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/banner.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_title; ?></td>
            <td><input type="text" name="title" value="<?php echo $title; ?>" />
              <?php if (isset($error_title)) { ?>
              <span class="error"><?php echo $error_title; ?></span>
              <?php } ?></td>
          </tr>
		  <tr>
            <td><?php echo $entry_url; ?></td>
            <td><input type="text" name="url" value="<?php echo $url; ?>" />
              <?php if (isset($error_url)) { ?>
              <span class="error"><?php echo $error_url; ?></span>
              <?php } ?></td>
          </tr>
		  <tr>
            <td><?php echo $entry_image; ?></td>
            <td><input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
              <img src="<?php echo $preview; ?>" alt="" id="preview" style="border: 1px solid #EEEEEE;" />&nbsp;<img src="view/image/image.png" alt="" style="cursor: pointer;" align="top" onclick="image_upload('image', 'preview');" /></td>
          </tr>
		  <tr>
            <td><?php echo $entry_html; ?></td>
            <td><textarea name="html" cols="40" rows="5"><?php echo $html; ?></textarea></td>
          </tr>
		  <tr>
            <td><?php echo $entry_start; ?></td>
            <td><input type="text" name="start_date" value="<?php echo $start_date; ?>" size="12" class="date" /></td>
          </tr>
		  <tr>
            <td><?php echo $entry_end; ?></td>
            <td><input type="text" name="end_date" value="<?php echo $end_date; ?>" size="12" class="date" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
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
            <td><?php echo $entry_group; ?></td>
            <td><select name="group">
			  <?php foreach ($banner_groups as $banner_group) { ?>
              <?php if ($banner_group['group_id'] == $group) { ?>
              <option value="<?php echo $banner_group['group_id']; ?>" selected="selected"><?php echo $banner_group['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $banner_group['group_id']; ?>"><?php echo $banner_group['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
          </tr>
		  <tr>
            <td><?php echo $entry_pages; ?></td>
            <td><table>
                <tr>
                  <td style="padding: 0;"><select multiple="multiple" id="possiblepages" size="10" style="width: 200px;">
				    <?php foreach ($pages as $page) { ?>
					<option value="<?php echo $page; ?>"><?php echo $page; ?></option>
					<?php } ?>
                    </select></td>
                  <td style="vertical-align: middle;"><input type="button" value="--&gt;" onclick="addPage();" />
                    <br />
                    <input type="button" value="&lt;--" onclick="removePage();" /></td>
                  <td style="padding: 0;"><select multiple="multiple" id="selectedpages" size="10" style="width: 200px;">
				    <?php if ($selected) { ?>
					<?php foreach ($selected as $adaction) { ?>
                    <option value="<?php echo $adaction; ?>"><?php echo $adaction; ?></option>
                    <?php } ?>
                    <?php } ?>
                    </select></td>
                </tr>
              </table>
              <div id="selected">
                <?php if ($selected) { ?>
				<?php foreach ($selected as $adaction) { ?>
                <input type="hidden" name="selected[]" value="<?php echo $adaction; ?>" />
                <?php } ?>
				<?php } ?>
              </div></td>
          </tr>
        </table>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.draggable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.resizable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.dialog.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/external/bgiframe/jquery.bgiframe.js"></script>
<script type="text/javascript"><!--
function addPage() {
	$('#possiblepages :selected').each(function() {
		$(this).remove();
		
		$('#selectedpages option[value=\'' + $(this).attr('value') + '\']').remove();
		
		$('#selectedpages').append('<option value="' + $(this).attr('value') + '">' + $(this).text() + '</option>');
		
		$('#selected input[value=\'' + $(this).attr('value') + '\']').remove();
		
		$('#selected').append('<input type="hidden" name="selected[]" value="' + $(this).attr('value') + '" />');
	});
}

function removePage() {
	$('#selectedpages :selected').each(function() {
		$(this).remove();
		
		$('#selected input[value=\'' + $(this).attr('value') + '\']').remove();
		
		$('#possiblepages').append('<option value="' + $(this).attr('value') + '">' + $(this).text() + '</option>');
	});
}
function image_upload(field, preview) {
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image',
					type: 'POST',
					data: 'image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(data) {
						$('#' + preview).replaceWith('<img src="' + data + '" alt="" id="' + preview + '" style="border: 1px solid #EEEEEE;" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 700,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<?php echo $footer; ?>
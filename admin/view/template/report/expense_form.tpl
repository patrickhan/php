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
        <table class="form">
          <tr>
            <td><span class="required">*</span> Name</td>
            <td><input type="text" name="name" value="<?php echo $name; ?>" />
                <?php if (isset($error_name)) { ?>
                <span class="error"><?php echo $error_name; ?></span>
                <?php } ?></td>
          </tr>
            <tr>
              <td><span class="required">*</span> Default Monthly Cost</td>
              <td><input type="text" name="default" value="<?php echo $default; ?>" />
                <?php if (isset($error_default)) { ?>
                <span class="error"><?php echo $error_default; ?></span>
                <?php } ?></td>
            </tr>
			<tr>
              <td><span class="required">*</span> Tax Rate</td>
              <td><input type="text" name="tax" value="<?php echo $tax; ?>" />% - 0 if no tax
                <?php if (isset($error_default)) { ?>
                <span class="error"><?php echo $error_default; ?></span>
                <?php } ?></td>
            </tr>
            <?/*<tr>
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
              <td><?php echo $entry_description; ?></td>
              <td><textarea name="description" cols="40" rows="5"><?php echo $description; ?></textarea></td>
            </tr>
          <tr>
            <td><?php echo $entry_comments; ?></td>
            <td><textarea name="comments" cols="40" rows="5"><?php echo $comments; ?></textarea></td>
          </tr><?*/?>
        </table>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.draggable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.resizable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.dialog.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/external/bgiframe/jquery.bgiframe.js"></script>
<?php echo $footer; ?>
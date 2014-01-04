<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background: url('view/image/points.png') 2px 9px no-repeat;"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><?php echo $entry_status; ?></td>
            <td><select name="points_status">
              <?php if ($points_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
          </select></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_signup_points; ?></td>
          <td><input type="text" name="signup_points" value="<?php echo $signup_points; ?>" />
            <?php if ($error_signup_points) { ?>
            <span class="error"><?php echo $error_signup_points; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_point_value; ?></td>
          <td><input type="text" name="point_value" value="<?php echo $point_value; ?>" />
            <?php if ($error_point_value) { ?>
            <span class="error"><?php echo $error_point_value; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_points_per_unit; ?></td>
          <td><input type="text" name="points_per_unit" value="<?php echo $points_per_unit; ?>" />
            <?php if ($error_points_per_unit) { ?>
            <span class="error"><?php echo $error_points_per_unit; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="points_sort_order" value="<?php echo $points_sort_order; ?>" size="1" /></td>
        </tr>
       </table>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.draggable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.resizable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.dialog.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/external/bgiframe/jquery.bgiframe.js"></script>
<?php echo $footer; ?>
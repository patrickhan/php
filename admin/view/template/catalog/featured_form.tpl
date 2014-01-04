<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/amor.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" id="form">
      <table class="form">
        <tr>
          <td><?php echo $entry_product; ?></td>
          <td><select name="product_id">
            <option value="0" selected="selected"><?php echo $text_select; ?></option>
            <?php foreach ($products as $product) { ?>
            <?php if ($product['product_id'] == $product_id) { ?>
            <option value="<?php echo $product['product_id']; ?>" selected="selected"><?php echo $product['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $product['product_id']; ?>"><?php echo $product['name']; ?></option>
            <?php } ?>
            <?php } ?>
            </select>
            <br />
            <?php if ($error_product) { ?>
            <span class="error"><?php echo $error_product; ?></span>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td><?php echo $entry_expire; ?></td>
          <td><input type="text" name="expire" value="<?php echo $expire; ?>" size="12" class="date" /></td>
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
            </select>
          </td>
        </tr>
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="2" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<link rel="stylesheet" type="text/css" href="view/stylesheet/datepicker.css" />
<script type="text/javascript" src="view/javascript/jquery/ui/ui.core.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<?php echo $footer; ?>
<?php echo (isset($header)) ? $header : '' ?>
<?php if (isset($error_warning)) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/shipping.png');"><?php echo $heading_title; ?></h1>
    <?php if (file_exists(DIR_SYSTEM . 'engine/action.php')) { //v1.4.0 ?>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    <?php } else { //v1.3.4 or less ?>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
    <?php } ?>
  </div>
<div class="content">
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page">
    <table class="form">
      <tr>
	    <td><?php echo $entry_test; ?></td>
	    <td>
	      <input type="radio" name="fedex_test" value="1"<?php echo ($fedex_test)?' checked="checked"':''?> />
  	      <?php echo $text_yes; ?>
	      <input type="radio" name="fedex_test" value="0"<?php echo (!$fedex_test)?' checked="checked"':''?> />
	      <?php echo $text_no; ?>
	  </tr>
      <tr>
	    <td><span class="required">*</span> <?php echo $entry_account; ?></td>
	    <td><input type="text" name="fedex_account" value="<?php echo $fedex_account; ?>" />
	    <?php if (isset($error_account)) { ?>
	      <br />
	      <span class="error"><?php echo $error_account; ?></span>
	    <?php  } ?></td>
	  </tr>
	  <tr>
        <td width="25%"><span class="required">*</span> <?php echo $entry_country; ?></td>
        <td><select name="fedex_country_id" id="country" onchange="$('#zone').load('index.php?route=setting/setting/zone&country_id=' + this.value + '&zone_id=<?php echo $fedex_zone_id; ?>');">
            <?php foreach ($countries as $country) { ?>
            <?php if ($country['country_id'] == $fedex_country_id) { ?>
            <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
        <td><select name="fedex_zone_id" id="zone">
          </select></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_postcode; ?></td>
        <td><input type="text" name="fedex_postcode" value="<?php echo $fedex_postcode; ?>" />
        <?php if (isset($error_postcode)) { ?>
        <br />
	    <span class="error"><?php echo $error_postcode; ?></span>
	    <?php  } ?></td>
      </tr>
      <tr>
	    <td><span class="required">*</span> <?php echo $entry_meter; ?></td>
	    <td><input type="text" name="fedex_meter" value="<?php echo $fedex_meter; ?>" /><?php if (isset($error_account)) { ?>
	      <br />
	      <span class="error"><?php echo $error_meter; ?></span>
	    <?php  } ?></td>
	  </tr>
      <tr>
        <td><?php echo $entry_packaging; ?></td>
        <td><select name="fedex_package">
            <?php foreach ($packaging as $package) { ?>
            <?php if ($package['code'] == $fedex_package) { ?>
            <option value="<?php echo $package['code']; ?>" selected="selected"><?php echo $package['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $package['code']; ?>"><?php echo $package['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_dropoff; ?></td>
        <td><select name="fedex_pickup">
            <?php foreach ($pickup as $p) { ?>
            <?php if ($p['code'] == $fedex_pickup) { ?>
            <option value="<?php echo $p['code']; ?>" selected="selected"><?php echo $p['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $p['code']; ?>"><?php echo $p['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_d_services; ?></td>
        <td>
          <div class="scrollbox">
            <?php $j=1; ?>
            <?php foreach ($d_services as $serv) { ?>
            <?php if($j != 1) {$j = 1;}else{$j = 0;} ?>
			<?php if($j == 0) {$class = 'even';}elseif($j == 1){$class = 'odd';} ?>
            <div class="<?php echo $class;?>">
      		  <input type="checkbox" name="fedex_d_<?php echo $serv; ?>" value="1"<?php echo (${"fedex_d_$serv"})?' checked="checked"':'' ?> />
              <?php echo ${"text_d_$serv"}; ?>
            </div>
            <?php } ?>
          </div>
        </td>
      </tr>
	  <tr>
        <td><?php echo $entry_dimension; ?></td>
        <td><input type="text" name="fedex_length" value="<?php echo $fedex_length; ?>" size="4" />
          <input type="text" name="fedex_width" value="<?php echo $fedex_width; ?>" size="4" />
          <input type="text" name="fedex_height" value="<?php echo $fedex_height; ?>" size="4" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_tax; ?></td>
        <td><select name="fedex_tax_class_id">
            <option value="0"><?php echo $text_none; ?></option>
            <?php foreach ($tax_classes as $tax_class) { ?>
            <?php if ($tax_class['tax_class_id'] == $fedex_tax_class_id) { ?>
            <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_geo_zone; ?></td>
        <td><select name="fedex_geo_zone_id">
            <option value="0"><?php echo $text_all_zones; ?></option>
            <?php foreach ($geo_zones as $geo_zone) { ?>
            <?php if ($geo_zone['geo_zone_id'] == $fedex_geo_zone_id) { ?>
            <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td width="25%"><?php echo $entry_status; ?></td>
        <td><select name="fedex_status">
            <?php if ($fedex_status) { ?>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <option value="0"><?php echo $text_disabled; ?></option>
            <?php } else { ?>
            <option value="1"><?php echo $text_enabled; ?></option>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_cost; ?></td>
        <td><input type="text" name="fedex_cost" value="<?php echo $fedex_cost; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_sort_order; ?></td>
        <td><input type="text" name="fedex_sort_order" value="<?php echo $fedex_sort_order; ?>" size="1" /></td>
      </tr>
    </table>
  </div>
</form>
</div>
</div>
<script type="text/javascript"><!--
$('#zone').load('index.php?route=setting/setting/zone&country_id=' + $('#country').attr('value') + '&zone_id=<?php echo $fedex_zone_id; ?>');
$('#country_id').attr('value', '<?php echo $fedex_country_id; ?>');
$('#zone_id').attr('value', '<?php echo $fedex_zone_id; ?>');
//--></script>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
<?php echo (isset($footer)) ? $footer : '' ?>
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
	    <td><span class="required">*</span> <?php echo $entry_user; ?><span class="help"><?php echo $help_ups; ?></span></td>
	    <td><input type="text" name="upsxml_user" value="<?php echo $upsxml_user; ?>" />
	    <?php if (isset($error_user)) { ?>
	      <br />
	      <span class="error"><?php echo $error_user; ?></span>
	    <?php  } ?></td>
	  </tr>
	  <tr>
	    <td><span class="required">*</span> <?php echo $entry_pass; ?></td>
	    <td><input type="text" name="upsxml_pass" value="<?php echo $upsxml_pass; ?>" />
	    <?php if (isset($error_pass)) { ?>
	      <br />
	      <span class="error"><?php echo $error_pass; ?></span>
	    <?php  } ?></td>
	  </tr>
	  <tr>
	    <td><span class="required">*</span> <?php echo $entry_access; ?></td>
	    <td><input type="text" name="upsxml_access" value="<?php echo $upsxml_access; ?>" />
	    <?php if (isset($error_access)) { ?>
	      <br />
	      <span class="error"><?php echo $error_access; ?></span>
	    <?php  } ?></td>
	  </tr>
	  <tr>
        <td><span class="required">*</span> <?php echo $entry_country; ?></td>
        <td><select name="upsxml_country">
              <?php foreach ($countries as $country) { ?>
              <?php  if ($country['iso_code_2'] == $upsxml_country) { ?>
              <option value="<?php echo $country['iso_code_2']; ?>" selected="selected"><?php echo $country['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $country['iso_code_2']; ?>"><?php echo $country['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_zipcode; ?></td>
        <td><input type="text" name="upsxml_zipcode" value="<?php echo $upsxml_zipcode; ?>" />
        <?php if (isset($error_zipcode)) { ?>
        <br />
	    <span class="error"><?php echo $error_zipcode; ?></span>
	    <?php  } ?></td>
      </tr>
      <tr>
	    <td><span class="required">*</span> <?php echo $entry_shipper; ?></td>
	    <td><input type="text" name="upsxml_shipper" value="<?php echo $upsxml_shipper; ?>" />
	    <?php if (isset($error_shipper)) { ?>
	      <br />
	      <span class="error"><?php echo $error_shipper; ?></span>
	    <?php  } ?></td>
	  </tr>
      <tr>
        <td><?php echo $entry_packaging; ?></td>
        <td><select name="upsxml_package">
            <?php foreach ($packaging as $package) { ?>
            <?php if ($package['code'] == $upsxml_package) { ?>
            <option value="<?php echo $package['code']; ?>" selected="selected"><?php echo $package['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $package['code']; ?>"><?php echo $package['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_pickup; ?></td>
        <td><select name="upsxml_pickup">
            <?php foreach ($pickup as $p) { ?>
            <?php if ($p['code'] == $upsxml_pickup) { ?>
            <option value="<?php echo $p['code']; ?>" selected="selected"><?php echo $p['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $p['code']; ?>"><?php echo $p['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_type; ?><br />
          <span class="help"><?php echo $help_type; ?></span></td>
        <td><select name="upsxml_type">
            <?php foreach ($types as $type) { ?>
            <?php if ($type['code'] == $upsxml_type) { ?>
            <option value="<?php echo $type['code']; ?>" selected="selected"><?php echo $type['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $type['code']; ?>"><?php echo $type['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      
	  <tr>
	    <td><?php echo $entry_test; ?></td>
	    <td>
	      <input type="radio" name="upsxml_test" value="1"<?php echo ($upsxml_test)?' checked="checked"':''?> />
  	      <?php echo $text_yes; ?>
	      <input type="radio" name="upsxml_test" value="0"<?php echo (!$upsxml_test)?' checked="checked"':''?> />
	      <?php echo $text_no; ?>
	  </tr>
	  <tr>
	    <td><?php echo $entry_box_weight; ?></td>
	    <td><input type="text" name="upsxml_box_weight" value="<?php echo $upsxml_box_weight; ?>" /></td>
	  </tr>
	  <tr>
        <td><?php echo $entry_dimension; ?></td>
        <td><input type="text" name="upsxml_length" value="<?php echo $upsxml_length; ?>" size="4" />
          <input type="text" name="upsxml_width" value="<?php echo $upsxml_width; ?>" size="4" />
          <input type="text" name="upsxml_height" value="<?php echo $upsxml_height; ?>" size="4" /></td>
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
      		  <input type="checkbox" name="upsxml_d_<?php echo $serv; ?>" value="1"<?php echo (${"upsxml_d_$serv"})?' checked="checked"':'' ?> />
              <?php echo ${"text_d_$serv"}; ?>
            </div>
            <?php } ?>
          </div>
        </td>
      </tr>
            
      <tr>
        <td><?php echo $entry_tax; ?></td>
        <td><select name="upsxml_tax_class_id">
            <option value="0"><?php echo $text_none; ?></option>
            <?php foreach ($tax_classes as $tax_class) { ?>
            <?php if ($tax_class['tax_class_id'] == $upsxml_tax_class_id) { ?>
            <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_geo_zone; ?></td>
        <td><select name="upsxml_geo_zone_id">
            <option value="0"><?php echo $text_all_zones; ?></option>
            <?php foreach ($geo_zones as $geo_zone) { ?>
            <?php if ($geo_zone['geo_zone_id'] == $upsxml_geo_zone_id) { ?>
            <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td width="25%"><?php echo $entry_status; ?></td>
        <td><select name="upsxml_status">
            <?php if ($upsxml_status) { ?>
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
        <td><input type="text" name="upsxml_cost" value="<?php echo $upsxml_cost; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_sort_order; ?></td>
        <td><input type="text" name="upsxml_sort_order" value="<?php echo $upsxml_sort_order; ?>" size="1" /></td>
      </tr>
    </table>
  </div>
</form>
</div>
</div>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
<?php echo (isset($footer)) ? $footer : '' ?>
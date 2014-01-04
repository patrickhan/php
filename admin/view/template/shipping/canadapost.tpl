<?php
/* 
 * OpenCart Canada Post Shipping Module
 * Version: 1.0
 * Author: Jason Mitchell
 * Email: jason@attemptone.com
 * Web: http://www.attemptone.com  
 * Description: Connects with Canada Post sellonline server to provide a
 *              shipping estimate.
*/
?>
<?php echo $header; ?>
<?php if ($error_warning) { ?>

<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/shipping.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <!--<div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><?php echo $entry_standard ?></td>
          <td><select name="canadapost_standard">
              <?php if ($canadapost_standard) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_express ?></td>
          <td><select name="canadapost_express">
              <?php if ($canadapost_express) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_postcode; ?></td>
          <td><input type="text" name="canadapost_postcode" size="4" maxlength="4" value="<?php echo $canadapost_postcode; ?>" />
            <?php if ($error_postcode) { ?>
            <span class="error"><?php echo $error_postcode; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_handling; ?></td>
          <td><input type="text" name="canadapost_handling" size="5" maxlength="5" value="<?php echo $canadapost_handling; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_estimate ?></td>
          <td><select name="canadapost_estimate">
              <?php if ($canadapost_estimate) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_tax; ?></td>
          <td><select name="canadapost_tax_class_id">
              <option value="0"><?php echo $text_none; ?></option>
              <?php foreach ($tax_classes as $tax_class) { ?>
              <?php if ($tax_class['tax_class_id'] == $canadapost_tax_class_id) { ?>
              <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_geo_zone; ?></td>
          <td><select name="canadapost_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $canadapost_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_status ?></td>
          <td><select name="canadapost_status">
              <?php if ($canadapost_status) { ?>
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
          <td><input type="text" name="canadapost_sort_order" value="<?php echo $canadapost_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div> -->
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div id="tab_general" class="page">
        <table class="form">
          <tr>
            <td><?php echo $entry_status ?></td>
            <td><select name="canadapost_status">
                <?php if ($canadapost_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
        <td><?php echo $entry_language ?></td>
        <td><select name="canadapost_language">
            <?php if ($canadapost_language == 'fr') { ?>
            <option value="fr" selected="selected"><?php echo $text_french; ?></option>
            <option value="en"><?php echo $text_eng; ?></option>
            <?php } else { ?>
            <option value="fr"><?php echo $text_french; ?></option>
            <option value="en" selected="selected"><?php echo $text_eng; ?></option>
            <?php } ?>
          </select></td>
      </tr>
          <tr>
            <td><?php echo $entry_server; ?></td>
            <td><input type="text" name="canadapost_server" size="30" value="<?php if($canadapost_server == ""){echo 'sellonline.canadapost.ca';} else{echo $canadapost_server;} ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_port; ?></td>
            <td><input type="text" name="canadapost_port" size="30" value="<?php if($canadapost_port == ""){echo '30000';} else{echo $canadapost_port;} ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_merchantId; ?></td>
            <td><input type="text" name="canadapost_merchantId" size="30" value="<?php if($canadapost_merchantId == ""){echo 'CPC_DEMO_XML';} else{echo $canadapost_merchantId;} ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_origin; ?></td>
            <td><input type="text" name="canadapost_origin" size="10" maxlength="7" value="<?php echo $canadapost_origin; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_handling; ?></td>
            <td><input type="text" name="canadapost_handling" size="5" maxlength="5" value="<?php if($canadapost_handling == ""){echo '0.00';} else{echo $canadapost_handling;} ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_turnAround; ?></td>
            <td><input type="text" name="canadapost_turnAround" size="5" maxlength="5" value="<?php if($canadapost_turnAround == ""){echo '0';} else{echo $canadapost_turnAround;} ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_originalPackaging; ?></td>
            <td><?php if ($canadapost_originalPackaging == 1) { ?>
              <input type="radio" name="canadapost_originalPackaging" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="canadapost_originalPackaging" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="canadapost_originalPackaging" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="canadapost_originalPackaging" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_tax; ?></td>
            <td><select name="canadapost_tax_class_id">
                <option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($tax_classes as $tax_class) { ?>
                <?php if ($tax_class['tax_class_id'] == $canadapost_tax_class_id) { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_geo_zone; ?></td>
            <td><select name="canadapost_geo_zone_id">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $canadapost_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="canadapost_sort_order" value="<?php echo $canadapost_sort_order; ?>" size="1" /></td>
          </tr>
          <tr>
            <td height="24"></td>
            <td></td>
          </tr>
          <tr>
            <td style="vertical-align: middle;"><?php echo $entry_version_status ?></td>
            <td style="vertical-align: middle;"><strong>You are using version : 1.5</strong><br /><a href="http://www.olivierlabbe.com/opencart/" target="CanadaPost OpenCart shipping module on olivierlabbe.com"><img src="http://www.votreespace.net/opencart/canadapost/versioncheck.png" alt="Update Check" border="0"></a></td>
          </tr>
          <tr>
            <td><?php echo $entry_author; ?></td>
            <td><strong>Version 1.5 </strong><br />
				Olivier Labb&eacute;<br />
              Email: <a href="mailto:olivier.labbe@votreespace.net" target="_blank">olivier.labbe@votreespace.net</a><br />
              Web: <a href="http://www.votreespace.net/" target="_blank">http://www.votreespace.net/</a><br /><br />
            <strong>Version 1.0</strong> <br />
Jason Mitchell<br />
              Email: <a href="mailto:jason@attemptone.com" target="_blank">jason@attemptone.com</a><br />
              Web: <a href="http://attemptone.com" target="_blank">http://attemptone.com/</a><br /><br />
             </td>
          </tr>
          <tr>
            <td><?php echo $entry_contributor; ?></td>
            <td><br />
              AusPost Module by: <a href="http://www.pixeldrift.net/opencart/" target="_blank">SuperJuice (Sam)</a><br />
              <a href="http://addons.oscommerce.com/info/391" target="_blank">Canada Post Shipping Module</a> for osCommerce by: Kelvin Zhang </td>
          </tr>
        </table>
      </div>
    </form>
  </div>
</div>
<?php echo $footer; ?>
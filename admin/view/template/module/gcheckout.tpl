<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/module.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">

    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><?php echo $entry_geo_zone; ?></td>
          <td><select name="gcheckout_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $gcheckout_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </td>
        </tr>
        <tr>
          <td><?php echo $entry_merchantid; ?></td>
          <td><input type="text" name="gcheckout_merchantid" value="<?php echo $gcheckout_merchantid; ?>" size="15" />
            <?php if ($error_merchantid) { ?>
            <span class="error"><?php echo $error_merchantid; ?></span>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td><?php echo $entry_merchantkey; ?></td>
          <td><input type="text" name="gcheckout_merchantkey" value="<?php echo $gcheckout_merchantkey; ?>" size="30" />
            <?php if ($error_merchantkey) { ?>
            <span class="error"><?php echo $error_merchantkey; ?></span>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td><?php echo $entry_test; ?></td>
          <td><?php if ($gcheckout_test) { ?>
            <input type="radio" name="gcheckout_test" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="gcheckout_test" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="gcheckout_test" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="gcheckout_test" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td valign="top"><?php echo $entry_currency; ?></td>
          <td>
            <?php foreach ($currencies as $currency) { ?>
            <?php if ($currency['selected']) { ?>
            <input type="radio" name="gcheckout_currency" value="<?php echo $currency['value']; ?>" checked="checked" />
            <?php echo $currency['text']; ?><br />
            <?php } else { ?>
            <input type="radio" name="gcheckout_currency" value="<?php echo $currency['value']; ?>" />
            <?php echo $currency['text']; ?><br />
            <?php } ?>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td><?php echo $entry_merchant_calculation; ?></td>
          <td><?php if ($gcheckout_merchant_calculation) { ?>
            <input type="radio" name="gcheckout_merchant_calculation" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="gcheckout_merchant_calculation" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="gcheckout_merchant_calculation" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="gcheckout_merchant_calculation" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td><?php echo $entry_position; ?></td>
          <td><select name="gcheckout_position">
              <?php if ($gcheckout_position == 'left') { ?>
              <option value="left" selected="selected"><?php echo $text_left; ?></option>
              <?php } else { ?>
              <option value="left"><?php echo $text_left; ?></option>
              <?php } ?>
              <?php if ($gcheckout_position == 'right') { ?>
              <option value="right" selected="selected"><?php echo $text_right; ?></option>
              <?php } else { ?>
              <option value="right"><?php echo $text_right; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="gcheckout_status">
              <?php if ($gcheckout_status) { ?>
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
          <td><input type="text" name="gcheckout_sort_order" value="<?php echo $gcheckout_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>
<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?><h3>Show: <input type="radio" name="set" onClick='window.location="index.php?route=catalog/category/setOff"' <?if (!$alls && !$dis) {?>checked='yes'<?}?> value='Off'/>Enabled Only <input type="radio" name="set" onClick='window.location="index.php?route=catalog/category/setDis"' <?if ($dis) {?>checked='yes'<?}?> value='Dis'/>Disabled Only</h3>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/category.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="location='<?php echo $insert; ?>'" class="button"><span><?php echo $button_add_category; ?></span></a><a onclick="$('#form').submit();" class="button"><span>Disable</span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left"><?php echo $column_name; ?></td>
            <td class="right"><?php echo $column_sort_order; ?></td>
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($categories) { ?>
          <?php foreach ($categories as $key => $category) { ?>
          <tr class="row" id="row<?php echo $key; ?>" >
            <td style="text-align: center;"><?php if ($category['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $category['category_id']; ?>" checked="checked" onclick="highlight(this, 'row<?php echo $key; ?>')" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $category['category_id']; ?>" onclick="highlight(this, 'row<?php echo $key; ?>')" />
              <?php } ?></td>
            <td class="left"><?php echo $category['name']; ?></td>
            <td class="right"><?php echo $category['sort_order']; ?></td>
            <td class="right"><?php foreach ($category['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>
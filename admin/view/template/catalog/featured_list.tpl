<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/amor.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="location='<?php echo $insert; ?>'" class="button"><span><?php echo $button_add_featured; ?></span></a><a onclick="$('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="align: center;"><input type="checkbox" onclick="$('input[name*=\'delete\']').attr('checked', this.checked);" /></td>
            <td class="left"><?php if ($sort == 'pd.name') { ?>
              <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
              <?php } ?>
			</td>
            <td class="left"><?php if ($sort == 'f.status') { ?>
              <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
              <?php } ?>
            </td>
            <td class="left"><?php if ($sort == 'f.sort_order') { ?>
              <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_order; ?>"><?php echo $column_sort_order; ?></a>
              <?php } ?>
            </td>
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($featured) { ?>
          <?php $class = 'odd'; ?>
          <?php foreach ($featured as $key => $one_featured) { ?>
          <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
          <tr class="row" id="row<?php echo $key; ?>" >
            <td style="align: center;"><?php if ($one_featured['delete']) { ?>
              <input type="checkbox" name="delete[]" value="<?php echo $one_featured['featured_id']; ?>" checked="checked" onclick="highlight(this, 'row<?php echo $key; ?>')" />
              <?php } else { ?>
              <input type="checkbox" name="delete[]" value="<?php echo $one_featured['featured_id']; ?>" onclick="highlight(this, 'row<?php echo $key; ?>')" />
              <?php } ?>
            </td>
            <td class="left"><?php echo $one_featured['name']; ?></td>
            <td class="left"><?php echo $one_featured['status']; ?></td>
            <td class="left"><?php echo $one_featured['sort_order']; ?></td>
            <td class="right"><?php foreach ($one_featured['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?>
		    </td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr class="even">
            <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
<?php echo $footer; ?>
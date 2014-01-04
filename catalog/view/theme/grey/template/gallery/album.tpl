<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="top">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center">
      <h1><?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="middle">
    
        <?php if ($albums) { ?>
        <div class="sort">
          <div class="div1">
            <select name="sort" onchange="location = this.value">
              <?php foreach ($sorts as $sorts) { ?>
              <?php if (($sort . '-' . $order) == $sorts['value']) { ?>
              <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
          <div class="div2"><?php echo $text_sort; ?></div>
        </div>
        <table class="list">
          <?php for ($i = 0; $i < sizeof($albums); $i = $i + 4) { ?>
          <tr>
            <?php for ($j = $i; $j < ($i + 4); $j++) { ?>
            <td width="25%"><?php if (isset($albums[$j])) { ?>
              <a href="<?php echo $albums[$j]['href']; ?>"><img src="<?php echo $albums[$j]['thumb']; ?>" title="<?php echo $albums[$j]['name']; ?>" alt="<?php echo $albums[$j]['name']; ?>" /></a><br />
              <a href="<?php echo $albums[$j]['href']; ?>" style="text-decoration:none;"><?php echo $albums[$j]['name']; ?></a><br />
              <span style="color: #999; font-size: 11px;"><?php echo $albums[$j]['date_added'][0]; ?></span><br />
     
              <?php } ?>
              </td>
            <?php } ?>
          </tr>
          <?php } ?>
        </table>
        <div class="pagination"><?php echo $pagination; ?></div>
        <?php } ?>
       
  </div>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>
</div>
<?php echo $footer; ?> 
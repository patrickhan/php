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
    <table width="100%">
      <tr>
        <td style="width: 50%; vertical-align: top;">
          <ul>
            <?php foreach ($links as $link) { ?>
            <li><a href="<?php echo $link['url']; ?>" target="_blank"><?php echo $link['title']; ?></a> - <?php echo $link['description']; ?></a></li>
            <?php } ?>
          </ul>
        </td>
      </tr>
    </table>
  </div>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>
</div>
<?php echo $footer; ?> 
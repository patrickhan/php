<?php echo $header; ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/report.png');"><?php echo $heading_title; ?></h1>
  </div>
  <h3>Show: <input type="radio" name="set" onClick='window.location="index.php?route=report/carts/setR"' <?if ($reg) {?>checked='yes'<?}?> value='Reg'/>Registered <input type="radio" name="set" onClick='window.location="index.php?route=report/carts/setG"' <?if ($ges) {?>checked='yes'<?}?> value='Guest'/>Guests</h3>
  <div class="content">
    <table class="list">
      <thead>
        <tr>
          <td class="left">Customer</td>
          <td class="left">Cart</td>
        
        </tr>
      </thead>
      <tbody>
        <?php if ($carts) { ?>
        <?php foreach ($carts as $product) { ?>
        <tr>
          <td class="left"><?php echo $product['name']; ?></td>
          <td class="left"><?php echo $product['cart']; ?></td>
         
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
<?php echo $footer; ?>
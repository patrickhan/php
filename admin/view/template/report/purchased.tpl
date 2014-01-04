<?php echo $header; ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/report.png');"><?php echo $heading_title; ?></h1>
  </div>
  <div class="content">
    <table class="list">
      <thead>
        <tr>
          <td class="left"><?php echo $column_name; ?></td>
          <td class="left"><?php echo $column_model; ?></td>
          <td class="left">Cost</td>
          <td class="left">Sale Price</td>
          <td class="left">Inventory</td>
          <td class="left">Viewed</td>
          <td class="right"># Sold</td>
          <td class="right">Sales</td>
          <td class="right">Profit</td>
        </tr>
      </thead>
      <tbody>
        <?php if ($products) { //echo "<pre>";print_r($products); echo "</pre>";die;?>
        <?php foreach ($products as $product) { if ($product['viewed'] != '') {?>
        <tr>
          <td class="left"><?if ($product['name'] == '') { echo '<i>Product Deleted</i>'; } elseif ($product['viewed'] == '') { echo "<i>" . $product['name'] . " - Deleted</i>"; } else {?><a target='_blank' href='index.php?route=catalog/product/update&product_id=<?php echo $product['product_id']; ?>'><?php echo $product['name']; ?></a><?}?></td>
          <td class="left"><?php echo $product['model']; ?></td>
         
          <td class="left"><form id="oprice<?php echo $product['product_id'] ?>"  method="post" action="index.php?route=report/purchased/oprice&product_id=<? echo $product['product_id'] ?>">$<input size='5' type='text'name='op<?php echo $product['product_id'] ?>' id='op<?php echo $product['product_id'] ?>' value='<?php echo $product['oprice']; ?>'> 
	            	<a onclick="$('#oprice<? echo $product['product_id'] ?>').submit();" class="nobutton"><b style='font-size:120%;'>Set</b></a>
            	</form></td>
          <td class="left"><form id="price<?php echo $product['product_id'] ?>"  method="post" action="index.php?route=report/purchased/price&product_id=<? echo $product['product_id'] ?>">$<input size='5' type='text'name='p<?php echo $product['product_id'] ?>' id='p<?php echo $product['product_id'] ?>' value='<?php echo $product['price']; ?>'> 
	            	<a onclick="$('#price<? echo $product['product_id'] ?>').submit();" class="nobutton"><b style='font-size:120%;'>Set</b></a></form></td>
		   
          <td class="left"><form id="inv<?php echo $product['product_id'] ?>"  method="post" action="index.php?route=report/purchased/inv&product_id=<? echo $product['product_id'] ?>"><input size='5' type='text'name='qt<?php echo $product['product_id'] ?>' id='qt<?php echo $product['product_id'] ?>' value='<?php echo $product['inv']; ?>'> 
	            	<a onclick="$('#inv<? echo $product['product_id'] ?>').submit();" class="nobutton"><b style='font-size:120%;'>Set</b></a></form></td>
	      <td class="left"><?php echo $product['viewed']; ?></td>
          <td class="right"><?php echo $product['quantity']; ?></td>
          <td class="right"><?php echo $product['total']; ?></td>
          <td class="right"><?php echo $product['profit']; ?></td>
        </tr>
        <?php } }?>
        <?php } else { ?>
        <tr>
          <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
   
  </div>
</div>
<?php echo $footer; ?>
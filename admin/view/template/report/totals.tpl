<?php echo $header; ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/report.png');"><?php echo $heading_title; ?></h1>
  </div>
  <div class="content">
    <div style="width:100%;background: #E7EFEF; padding: 3px; margin-bottom: 5px;">
      Date Range: 
            <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date_start" size="12" style="margin-top: 4px;" /> to 
            <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date_end" size="12" style="margin-top: 4px;" /> <a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a>
    </div>
	 <?php if ($ords) { ?>
	<strong>Totals</strong><br /><br>
    <table class="list">
      <thead>
        <tr>
          <td class="left"><?php echo $column_date_start; ?></td>
          <td class="left"><?php echo $column_date_end; ?></td>
          <td class="right"><?php echo $column_orders; ?></td>
          <td class="right">Products Sold</td>
		  <td class="right">Product Total</td>
          <td class="right">Product Cost</td>
          <td class="right">Taxes</td>
          <td class="right">Profit</td>
          <td class="right">Total</td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $order) { ?>
        <tr>
          <td class="left"><?php echo $order['date_start']; ?></td>
          <td class="left"><?php echo $order['date_end']; ?></td>
          <td class="right"><?php echo $order['orders']; ?></td>
		  <td class="right"><?php echo $order['prods']; ?></td>
          <td class="right"><?php echo $order['price']; ?></td>
		  <td class="right"><?php echo $order['oprice']; ?></td>
          <td class="right"><?php echo $order['taxes']; ?></td>
          <td class="right"><?php echo $order['profit']; ?></td>
          <td class="right"><?php echo $order['total']; ?></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        No orders have been placed during this period
        <?php } ?>
      </tbody>
    </table><br>
    <?if ($ords) {?>
	<strong>Orders Placed</strong><br /><br />
	<table class='list2'>
	<thead>
        <tr>
          <td class="left">Order ID</td>
          <td class="left">Name</td>
		  <td class="left">Date</td>
          <td class="left">Products</td>
          <td class="right">Total</td>
        </tr>
      </thead>
	<?foreach ($ords as $order){?>
	<tr>
				<td class="left" style='width:55px;'><a target='_blank' href='index.php?route=sale/order/update&order_id=<?php echo $order['order_id']; ?>'><?php echo $order['order_id']; ?></a></td>
				<td class="left"><?php echo $order['name']; ?></td>
				
				<td class="left" style='width:75px;'><?php echo $order['date_added']; ?></td>
				<td class="left"><?php echo $order['prods']; ?></td>
				<td class="right"><?php echo $order['total']; ?></td>
				</tr>
				<?}?>
				</table>
				<?}?>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=report/totals';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

	location = url;
}
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date_start').datepicker({dateFormat: 'yy-mm-dd'});
	
	$('#date_end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<?php echo $footer; ?>
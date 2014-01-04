<?php echo $header; ?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('view/image/home.png');"><?php echo $heading_title; ?></h1>
	</div>
	<div class="content">
		<div style="display: inline-block; width: 100%; margin-bottom: 15px; clear: both;">
			
				<div style="background: #003e78; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 10px; font-size: 14px; font-weight: bold; clear:right;">
				Orders: <?if ($ords == 'Latest') {?><a style='font-weight:bold;text-decoration:underline;color:white' href='index.php?route=common/home&ord=Latest'>Latest Ten</a><?} else {?><a style='font-weight:normal;text-decoration:none;color:white' href='index.php?route=common/home&ord=Latest'>Latest Ten</a><?}?> | <?if ($ords == 'Pending') {?>
				
				<a style='font-weight:bold;text-decoration:underline;color:white' href='index.php?route=common/home&ord=Pending'>Pending</a><?} else {?><a style='font-weight:normal;text-decoration:none;color:white' href='index.php?route=common/home&ord=Pending'>Pending</a><?}?> | 
				
				<?if ($ords == 'Week') {?><a style='font-weight:bold;text-decoration:underline;color:white' href='index.php?route=common/home&ord=Week'>Last Week</a><?} else {?><a style='font-weight:normal;text-decoration:none;color:white' href='index.php?route=common/home&ord=Week'>Last Week</a><?}?> | 
				
				<?if ($ords == 'Month') {?><a style='font-weight:bold;text-decoration:underline;color:white' href='index.php?route=common/home&ord=Month'>Last Month</a><?} else {?><a style='font-weight:normal;text-decoration:none;color:white' href='index.php?route=common/home&ord=Month'>Last Month</a><?}
				
				?> | <?if ($ords == 'All') {?><a style='font-weight:bold;text-decoration:underline;color:white' href='index.php?route=common/home&ord=All'>View All</a><?} else {?><a style='font-weight:normal;text-decoration:none;color:white' href='index.php?route=common/home&ord=All'>View All</a><?}?>

				| <a style='font-weight:normal;text-decoration:none;color:white' href='index.php?route=sale/order'>Orders Page</a></div>
				
		<div class="buttons" style="float:right; padding: 12px;"><a onclick="location='<?php echo $create; ?>'" class="button"><span><?php echo $button_create_order; ?></span></a><a onclick="$('#form').attr('action', '<?php echo $invoice; ?>'); $('#form').attr('target', '_blank'); $('#form').submit();" class="button"><span><?php echo $button_invoices; ?></span></a><a onclick="$('#form').attr('action', '<?php echo $packingslip; ?>'); $('#form').attr('target', '_blank'); $('#form').submit();" class="button"><span><?php echo $button_packingslips; ?></span></a><a onclick="$('#form').attr('action', '<?php echo $delete; ?>'); $('#form').attr('target', '_self'); $('#form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
		
		<div style="display: inline-block; width: 100%; margin-bottom: 15px; clear: both;">
			<form action="" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td class="right"><?php if ($sort == 'o.order_id') { ?>
								<a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_order; ?>"><?php echo $column_order; ?></a>
								<?php } ?></td>
							<td class="left"><?php if ($sort == 'name') { ?>
								<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
								<?php } ?></td>
							<td class="left"><?php if ($sort == 'status') { ?>
								<a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
								<?php } ?></td>
							<td class="left"><?php if ($sort == 'o.date_added') { ?>
								<a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
								<?php } ?></td>
							<td class="right"><?php if ($sort == 'o.total') { ?>
								<a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
								<?php } ?></td>
							<td class="right"><?php echo $column_action; ?></td>
						</tr>
					</thead>
					<tbody>
						<tr class="filter">
							<td></td>
							<td align="right"><input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" size="4" style="text-align: right;" /></td>
							<td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
							<td><select name="filter_order_status_id">
								<option value="*"></option>
								<?php if ($filter_order_status_id == '0') { ?>
								<option value="0" selected="selected"><?php echo $text_missing_orders; ?></option>
								<?php } else { ?>
								<option value="0"><?php echo $text_missing_orders; ?></option>
								<?php } ?>
								<?php foreach ($order_statuses as $order_status) { ?>
								<?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
								<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
								<?php } else { ?>
								<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
								<?php } ?>
								<?php } ?>
								</select></td>
							<td><input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" size="12" id="date" /></td>
							<td align="right"><input type="text" name="filter_total" value="<?php echo $filter_total; ?>" size="4" style="text-align: right;" /></td>
							<td align="right"><a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></td>
						</tr>
						<?php if ($orders) { ?>
						<?php foreach ($orders as $order) { ?>
						<tr>
							<td style="align: center;"><?php if ($order['selected']) { ?>
								<<input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />
								<?php } else { ?>
								<input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
								<?php } ?></td>
							<td class="right"><?php echo $order['order_id']; ?></td>
							<td class="left"><?php echo $order['name']; ?></td>
							<td class="left"><?php echo $order['status']; ?></td>
							<td class="left"><?php echo $order['date_added']; ?></td>
							<td class="right"><?php echo $order['total']; ?></td>
							<td class="right"><?php foreach ($order['action'] as $action) { ?>
								[ <a <?php echo $action['js']; ?> href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
								<?php } ?></td>
						</tr>
						<?php } ?>
						<?php } else { ?>
						<tr>
							<td class="center" colspan="7"><?php echo $text_no_results; ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</form>
			<?/*<div class="pagination"><?php echo $pagination; ?></div>*/?>
		</div>
		<div style="float: left; width: 49%;">
		<div style="background: #003e78; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_overview; ?></div>
				<div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px; height: 350px;">
					<table cellpadding="2" style="width: 100%;">
						<tr>
							<td width="80%"><?php echo $text_total_sale; ?></td>
							<td align="right"><?php echo $total_sale; ?></td>
						<tr>
							<td>Total Sales Between<br> <input onChange='datee()' name="filter_date_start" name="filter_date_start" value='' id="filter_date_start" class="date-picker" /> <label for="filter_date_end"> and </label>    <input onChange='datee()' name="filter_date_end" value='' id="filter_date_end" class="date-picker" /></td>
							<td align="right"><div id='totpr'>$0.00</div></td>
						</tr>
						<tr>
							<td><?php echo $text_total_order; ?></td>
							<td align="right"><?php echo $total_order; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_total_customer; ?></td>
							<td align="right"><?php echo $total_customer; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_total_customer_approval; ?></td>
							<td align="right"><?php echo $total_customer_approval; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_total_product; ?></td>
							<td align="right"><?php echo $total_product; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_total_review; ?></td>
							<td align="right"><?php echo $total_review; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_total_review_approval; ?></td>
							<td align="right"><?php echo $total_review_approval; ?></td>
						</tr>
					</table><br><br>
				</div>
			</div>
			<div style="float: right; width: 49%;">
				<div style="background: #003e78; color: #FFF; border-bottom: 1px solid #8EAEC3;">
					<div style="width: 100%; display: inline-block;">
					
						<div style="float: left; font-size: 14px; font-weight: bold; padding: 5px; line-height: 12px;">Products: <?if ($prod == 'Latest') {?><a style='font-weight:bold;text-decoration:underline;color:white' href='index.php?route=common/home&prod=Latest'>Latest Ten</a><?} else {?><a style='font-weight:normal;text-decoration:none;color:white' href='index.php?route=common/home&prod=Latest'>Latest Ten</a><?}?> | <?if ($prod == 'Viewed') {?><a style='font-weight:bold;text-decoration:underline;color:white' href='index.php?route=common/home&prod=Viewed'>Most Viewed</a><?} else {?><a style='font-weight:normal;text-decoration:none;color:white' href='index.php?route=common/home&prod=Viewed'>Most Viewed</a><?}?> | <?if ($prod == 'Sold') {?><a style='font-weight:bold;text-decoration:underline;color:white' href='index.php?route=common/home&prod=Sold'>Bestsellers</a><?} else {?><a style='font-weight:normal;text-decoration:none;color:white' href='index.php?route=common/home&prod=Sold'>Bestsellers</a><?}?> | <a style='color:white;font-weight:normal;text-decoration:none;' href='index.php?route=catalog/product'>Products Page</a></div>
						
						</div>				</div>				
						
						<div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px; height: 350px;">
						
						<table width='100%' cellpadding='5px'>
						<tr><td>Name</td><td>Model</td><td>Amount Sold</td><td>Times Viewed</td>
						</tr>				
						<?foreach ($prds as $prd) {?>				<tr>				<td><a href='index.php?route=catalog/product/update&product_id=<?echo $prd['product_id'];?>'><?echo $prd['name'];?></a>
						</td>				
						<td><?echo $prd['model'];?></td>				
						<td><?echo $prd['sold'];?></td>
						<td><?echo $prd['viewed'];?></td>
						</tr>				<?}?>				</table>					<?/*<div id="report" style="width: 400px; height: 180px; margin: auto;"></div>*/?>				
						</div>
						</div>
					</div>
				</div>

		
		<div>
			<div style="background: #003e78; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_whos_online; ?></div>
			<div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px;">
				<table class="list">
					<thead>
						<tr>
							<td class="left"><?php echo $column_customer_id; ?></td>
							<td class="left"><?php echo $column_full_name; ?></td>
							<td class="left"><?php echo $column_ip_address; ?></td>
							<td class="left"><?php echo $column_entry_time; ?></td>
							<td class="left"><?php echo $column_last_click; ?></td>
							<td class="left"><?php echo $column_last_url; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php if ($whos_online) { ?>
						<?php foreach ($whos_online as $who) { ?>
						<tr>
							<td class="left"><?php echo $who['customer_id']; ?></td>
							<td class="left"><?php echo $who['full_name']; ?></td>
							<td class="left"><?php echo $who['ip_address']; ?></td>
							<td class="left"><?php echo $who['time_entry']; ?></td>
							<td class="left"><?php echo $who['time_last_click']; ?></td>
							<td class="left"><a href="<?php echo $store . $who['last_page_url']; ?>" target="_blank"><?php echo $who['last_page_url']; ?></a></td>
						</tr>
						<?php } ?>
						<?php } else { ?>
						<tr>
							<td class="center" colspan="7"><?php echo $text_no_results; ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!--[if IE]>
<script type="text/javascript" src="view/javascript/jquery/flot/excanvas.js"></script>
<![endif]-->
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#filter_date_start').datepicker({dateFormat: 'yy-mm-dd'});
	$('#filter_date_end').datepicker({dateFormat: 'yy-mm-dd'});
});

function getSalesChart(range) {
	$.ajax({
		type: 'GET',
		url: 'index.php?route=common/home/chart&range=' + range,
		dataType: 'json',
		async: false,
		success: function(json) {
			var option = {	
				shadowSize: 0,
				lines: { 
					show: true,
					fill: true,
					lineWidth: 1
				},
				grid: {
					backgroundColor: '#FFFFFF'
				},	
				xaxis: {
					ticks: json.xaxis
				}
			}
			
			$.plot($('#report'), [json.order, json.customer], option);
		}
	});
}
function datee() {
var start = $('input[name=\'filter_date_start\']').attr('value');
var end = $('input[name=\'filter_date_end\']').attr('value');
if (start && end) {
	$.ajax({
		type: 'GET',
		url: 'index.php?route=common/home/sales&start=' + start + '&end=' + end,
		success: function(data) {
			 $( '#totpr' ).html( data );
		}
	});
}

}


//--></script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=sale/order';
	
	var filter_order_id = $('input[name=\'filter_order_id\']').attr('value');
	
	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').attr('value');
	
	if (filter_order_status_id != '*') {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}	

	var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}

	var filter_total = $('input[name=\'filter_total\']').attr('value');

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}
	
	location = url;
}
//--></script>
<?php echo $footer; ?>
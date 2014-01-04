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
            <input type="text" class='date-picker' name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date_start" size="12" style="margin-top: 4px;" /> to 
            <input type="text" class='date-picker' name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date_end" size="12" style="margin-top: 4px;" /> <a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a>
    </div>
	<?php $order = $orders[0]; ?><br />
	<!--<center><table class='list2'>
	<tr><td>Total Sales:</td><td> <?php echo $order['tot']; ?></td></tr>
    <tr><td>Taxes Collected:</td><td> <?php echo $order['taxes']; ?></td></tr>
    <tr><td>Taxes Paid Out:</td><td> <?php echo $order['otaxes']; ?></td></tr>
    <tr><td>Taxes To Remit:</td><td> <?php echo $order['remit']; ?></td></tr>
	</table></center>-->
	
		<center><table class='list2'>
	<thead><tr><td>Total Sales</td><td>Taxes Collected</td><td>Taxes Paid Out</td><td>Taxes To Remit</td></tr></thead>
	<tbody>
	<tr>
	<td> <?php echo $order['tot']; ?></td>
    <td> <?php echo $order['taxes']; ?></td>
    <td> <?php echo $order['otaxes']; ?></td>
    <td> <?php echo $order['remit']; ?></td></tr></tbody>
	</table></center>
  </div>
</div>
<script type="text/javascript"><!--
$(function() {
    $('.date-picker').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'MM yy',
        onClose: function(dateText, inst) { 
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
        }
    });
});
function filter() {
	url = 'index.php?route=report/taxes';
	
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
	$('#date_start').datepicker({dateFormat: 'MM YY'});
	
	$('#date_end').datepicker({dateFormat: 'MM YY'});
});
//--></script>
<?php echo $footer; ?>
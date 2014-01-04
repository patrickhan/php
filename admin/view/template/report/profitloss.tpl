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
	<?php //$order = $orders[0]; ?><br />
	<center><table class='list2' cellpadding='5' cellspacing='5'>
	<thead><tr><td><b>Profit/Loss Report</b></td><td></td><td colspan="3"><?echo $filter_date_start;?> to <?echo $filter_date_end;?></tr></thead>
	<tr><td colspan='5'><span style="font-style: bold; text-decoration: underline;">Revenue</span></td></tr>
	<tr><td><strong>Gross Sales</strong></td></td><td><td>$<?php echo number_format($gsale,2); ?></td><td colspan="2"></td></tr>
    <tr><td><strong>Cost Of Sales</strong></td></td><td><td>$<?php echo number_format($csale,2); ?></td><td colspan="2"></td></tr>
    <tr><td><strong>Gross Profit</strong></td></td><td><td>$<?php echo number_format($gprof,2); ?></td><td></td><td>$<?php echo number_format($gprof,2); ?></td></tr>
	<tr><td colspan='5'></td></tr>
	<tr><td colspan='5'><span style="font-style: bold; text-decoration: underline;">Expenses</span></td></tr>
	<?foreach ($expss as $exp) {?><tr><td><?echo $exp['name'];?></td><td></td><td>$<?php echo number_format($exp['amount'],2); ?></td><td colspan="2"></td></tr><? }?>
	<tr><td colspan='5'></td></tr>
    
    <tr><td></td><td><strong>Total Expenses</strong></td><td>$<?php echo number_format($exps,2); ?></td></td><td><td>$(<?php echo number_format($exps,2); ?>)</td></tr>
	<tr><td colspan='5'></td></tr>
    <tr></td><td><td><strong>Net Profit</strong></td><td></td><td></td><td>$<?php echo number_format($net,2); ?></td></tr>
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
	url = 'index.php?route=report/profitloss';
	
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
	$('#date_start').datepicker({dateFormat: 'MM yy'});
	
	$('#date_end').datepicker({dateFormat: 'MM yy'});
});
//--></script>
<?php echo $footer; ?>
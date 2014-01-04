<?php echo $header; ?>
<script type="text/javascript">
<!--
function confirmation() {
	var answer = confirm("Are you sure you want to delete these links?")
	if (answer){
		$('#form').submit();
	}
}
//-->
</script>
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
		<h1 style="background-image: url('view/image/link.png');"><?php echo $heading_title; ?></h1>
		<div style='display:none;' class="buttons"><a onclick="location='<?php echo $add; ?>'" class="button"><span>Add New Expense</span></a><a onclick="location='<?php echo $add2; ?>'" class="button"><span>New Expense Report</span></a><a style='display:none;' onclick="confirmation()" class="button"><span><?php echo $button_delete; ?></span></a></div>
	</div>
	<div class="content">
	<form style='width:350px;float:left;' action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form1">
			<a onclick="location='<?php echo $add; ?>'" class="button"><span>Add New Expense</span></a><br><br><table class="list">
				<thead>
					<tr>
						
						<td class="left">Expense</td>
						<td class="left">Default Monthly Cost</td>
						<td class="left">Tax Rate</td>
						<td class="right"><?php echo $column_action; ?></td>
					</tr>
				</thead>
				<tbody>
					<?php $ttot = 0; $taxs = 0; if ($exps) { ?>
					<?php $class = 'odd'; $mtot = 0; ?>
					<?php foreach ($exps as $key => $link) { ?>
					<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
					<tr class="row" id="row<?php echo $key; ?>" >
						
						<td class="left"><?php echo $link['name']; ?></td>
						<td class="left">$<?php echo $link['default']; $mtot += $link['default'];?></td><td class="left"><?php echo $link['tax'];?>%</td>
						<td class="right"><?php foreach ($link['action'] as $action) { ?>
						[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
						<?php } ?></td>
					</tr>
					<?php } ?>
					<?/*<tr><td class="left"><b>Total</b></td><td class="left">$<?echo $mtot;?></td><td></td></tr>*/?>
					<?php } else { ?>
					<tr class="even">
						<td class="center" colspan="6"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form><br><br>
		<div style="width:100%;float:right;background: #E7EFEF; padding: 3px; margin-top:5px;margin-bottom: 5px;">
      <b>Expense Report:</b><table cellspacing="0" cellpadding="2" style="margin-bottom: 5px;">
        <tr>
		<td><label for="filter_date_start">Date Range:</label>
    <input name="filter_date_start" value='<?echo $filter_date_start;?>' id="filter_date_start" class="date-picker" /></td><td><label for="filter_date_end"> to </label>
    <input name="filter_date_end" value='<?echo $filter_date_end;?>' id="filter_date_end" class="date-picker" /></td>
          <?/*<td>Date Start:<br />
            <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date_start" size="12" style="margin-top: 4px;" /></td>
          <td>Date End:<br />
            <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date_end" size="12" style="margin-top: 4px;" /></td>*/?>
          <td align="right"><a onclick="filter();" class="button"><span>Search</span></a><a onclick="location='<?php echo $add2; ?>'" class="button"><span>New Expense Report</span></a></td>
        </tr>
      </table>
    </div><div style='clear:both;'>
		<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
		<?php if ($links) { ?>
			<table class="list">
				<thead>
					<tr>
						<?/*<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td><?*/?>
						<td class="left">Date</td>
						<?foreach ($exps as $exp) {?>
						<td class="left"><?echo $exp['name'];?></td>
						<?}?>
						<td class="right">Total</td>
						<td class="right"><?php echo $column_action; ?></td>
					</tr>
				</thead>
				<tbody>
					
					<?php $class = 'odd'; ?>
					<?php foreach ($links as $key => $link) { ?>
					<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
					<tr class="row" id="row<?php echo $key; ?>" >
						
						<td class="left"><?php echo $link['date']; ?></td>
						<?$staxs = 0;?>
						<?foreach ($exps as $exp) { ?>
						<td class="left">
						<?if (isset($expss[$link['link_id']][$exp['expense_id']])) {
			echo "$" . number_format($expss[$link['link_id']][$exp['expense_id']],2); 
			
			if ($exp['tax']) { 
			
	echo "<br><span style='color:red;'> $" .  number_format(($expss[$link['link_id']][$exp['expense_id']] * ($exp['tax']/100)),2) . "</span>"; 
	$taxs += $expss[$link['link_id']][$exp['expense_id']] * ($exp['tax']/100);
	$staxs += $expss[$link['link_id']][$exp['expense_id']] * ($exp['tax']/100);

	}
						} else { 
						echo "N/A"; }?>
						
						</td>
						<?}?>
						<td class="right">$<?php echo number_format($link['tot'],2); 
						if ($staxs > 0) { 
						echo "<br><span style='color:red;'> $" .  number_format($staxs,2) . "</span>";  
						} 
						$ttot += $link['tot']; ?></td>
						<td class="right"><?php foreach ($link['action'] as $action) { ?>
						[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
						<?php } ?></td>
					</tr>
					
					<?php } ?>
					</tbody>
			</table>
			<br><b>Total: </b>$<?echo number_format($ttot,2); if (taxs) { echo "<br><span style='color:red;'> + $" . number_format($taxs,2) . " taxes</span>";  }?>
					<?php } ?>
		</form>
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
	url = 'index.php?route=report/expense';
	
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

<?php echo $footer; ?>
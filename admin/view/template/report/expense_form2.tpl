<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/link.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
		<tr><td>Report Date</td><td>Month <select name="month">
<option value=<?echo $month;?>><?switch($month) { case 0:break; case 1:echo "January"; break; case 2:echo "February"; break; case 3:echo "March"; break; case 4:echo "April"; break; case 5:echo "May"; break; case 6:echo "June"; break; case 7:echo "July"; break; case 8:echo "August"; break; case 9:echo "September"; break; case 10:echo "October"; break; case 11:echo "November"; break; case 12:echo "December"; break; }?></option>
<option value = "1">January</option>
<option value = "2">February</option>
<option value = "3">March</option>
<option value = "4">April</option>
<option value = "5">May</option>
<option value = "6">June</option>
<option value = "7">July</option>
<option value = "8">August</option>
<option value = "9">September</option>
<option value = "10">October</option>
<option value = "11">November</option>
<option value = "12">December</option> 
</select> Year <input type='text' name='year' value='<?echo $year;?>'/></td></tr>
        <?foreach ($expenses as $exp) {?>
		<tr>
		<td><?echo $exp['name'];?></td><td><input type='text' name='amount[<?echo $exp['expense_id']?>]' value='<?echo $exp['amts']?>'/></td>
		</tr>
		<?}?>
		<?foreach ($expenses2 as $exp) {?>
		<tr>
		<td><?echo $exp['name'];?></td><td><input type='text' name='amount[<?echo $exp['expense_id']?>]' value='<?echo $exp['default']?>'/></td>
		</tr>
		<?}?>
        </table>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.draggable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.resizable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.dialog.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/external/bgiframe/jquery.bgiframe.js"></script>
<?php echo $footer; ?>
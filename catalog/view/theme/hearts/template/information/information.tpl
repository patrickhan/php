<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="top">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center">
      <h1><?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="middle"><?php echo $description; ?>
    <!--<div class="buttons">
      <table>
        <tr>
          <td align="right"><a onclick="location='<?php echo $continue; ?>'" class="button"><span><?php echo $button_continue; ?></span></a></td>
        </tr>
      </table>
    </div>-->
	<?if ($art) {?>
	<div id="review"></div>
			<div class="heading" id="review_title">Leave A Comment</div>
			<div class="light" style="padding: 10px; margin-bottom: 10px;"><b>Name</b><br />
				<input type="text" name="name" value="" />
				<br />
				<br />
				<b>Comment</b>
				<textarea name="text" style="width: 99%;" rows="8"></textarea>
				
				<br />
				<b>Rating</b> <span>Bad</span>&nbsp;
				<input type="radio" name="rating" value="1" style="margin: 0;" />
				&nbsp;
				<input type="radio" name="rating" value="2" style="margin: 0;" />
				&nbsp;
				<input type="radio" name="rating" value="3" style="margin: 0;" />
				&nbsp;
				<input type="radio" name="rating" value="4" style="margin: 0;" />
				&nbsp;
				<input type="radio" name="rating" value="5" style="margin: 0;" />
				&nbsp; <span>Good</span><br />
				<br />
				<b>Enter the code in the box below:</b><br />						<input type='text' id='cap1' name='cap1'/><br><div style='font-weight:bold;font-size:150%;' name='cap2' id='cap2'></div>
			</div>
			<div class="buttons">
				<table>
					<tr>
						<td align="right"><a onclick="captcha();" class="button"><span>Submit Comment</span></a></td>
					</tr>
				</table>
			</div>
	<script type="text/javascript"><!--
$('#review').load('index.php?route=information/information/review&information_id=<?php echo $information_id; ?>');

function review() {
	$.ajax({
		type: 'post',
		url: 'index.php?route=information/information/write&information_id=<?php echo $information_id; ?>',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : ''),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#review_button').attr('disabled', 'disabled');
			$('#review_title').after('<div class="wait"><img src="catalog/view/theme/default/image/loading_1.gif" alt="" />Review is being submitted</div>');
		},
		complete: function() {
			$('#review_button').attr('disabled', '');
			$('.wait').remove();
		},
		success: function(data) {
			if (data.error) {
				$('#review_title').after('<div class="warning">' + data.error + '</div>');
			}
			
			if (data.success) {
				$('#review_title').after('<div class="success">' + data.success + '</div>');
				
				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').attr('checked', '');
				$('input[name=\'captcha\']').val('');
			}
		}
	});
}
//--></script>
	<?}?>
  </div>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>
</div>
<style>#cap2 {
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}</style>
<script language="javascript">
$(document).ready(function() {
var a = Math.ceil(Math.random() * 9)+ '';
        var b = Math.ceil(Math.random() * 9)+ '';       
        var c = Math.ceil(Math.random() * 9)+ '';  
        var d = Math.ceil(Math.random() * 9)+ '';  
        var e = Math.ceil(Math.random() * 9)+ '';  
        //var f = Math.ceil(Math.random() * 10)+ '';  
        //var g = Math.ceil(Math.random() * 10)+ '';  
        var code = a + ' ' + b + ' ' + ' ' + c + ' ' + d + ' ' + e;// + ' '+ f + ' ' + g;
        document.getElementById("cap2").innerHTML = code;
		});
function captcha() {
 var str1 = removeSpaces(document.getElementById('cap1').value);
        var str2 = removeSpaces(document.getElementById('cap2').innerHTML);
        if (str1 == str2) {
		review();
		}
		else
		{
		alert('The captcha code you have entered is incorrect!');
		}
}
function removeSpaces(string) {
        return string.split(' ').join('');
    }
</script>
<?php echo $footer; ?> 
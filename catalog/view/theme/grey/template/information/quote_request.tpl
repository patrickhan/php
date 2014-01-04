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
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="quote_request">
		<div class="content">
			<?php echo $description; ?>
		</div>
		<div class="content">
<?php echo $entry_captcha; ?><br />
						<input type='text' id='cap1' name='cap1'/><br><div style='font-weight:bold;font-size:150%;' name='cap2' id='cap2'></div>
		</div>
		<div class="buttons">
			<table>
				<tr>
					<td align="right"><a onclick="captcha();" class="button"><span>Submit</span></a></td>
				</tr>
			</table>
		</div>
	</form>
	</div>
	<div class="bottom">
		<div class="left"></div>
		<div class="right"></div>
		<div class="center"></div>
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
        //var f = Math.ceil(Math.random() * 9)+ '';  
        //var g = Math.ceil(Math.random() * 9)+ '';  
        var code = a + ' ' + b + ' ' + ' ' + c + ' ' + d + ' ' + e;// + ' '+ f + ' ' + g;
        document.getElementById("cap2").innerHTML = code;
		});
function captcha() {
 var str1 = removeSpaces(document.getElementById('cap1').value);
        var str2 = removeSpaces(document.getElementById('cap2').innerHTML);
        if (str1 == str2) {
		document.getElementById("quote_request").submit();
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
</div>
<?php echo $footer; ?> 
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

		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="contact">

		<div class="content">

			<?php echo $description; ?><br />

			<div style="display: inline-block; padding-left: 5px; width: 100%;">

				<div style="float: left; display: inline-block; width: 100%;"><b><?php echo $text_address; ?></b><br />

					<?php echo $store; ?><br />

					<?php echo $address; ?></br></br>

					<?php if ($telephone) { ?>

					<b><?php echo $text_telephone; ?></b><br />

					<?php echo $telephone; ?><br />

					<br />

					<?php } ?>

					<?php if ($fax) { ?>

					<b><?php echo $text_fax; ?></b><br />

					<?php echo $fax; ?><br /><br />

					<?php } ?>

					<b><?php echo $text_email; ?></b><br />

					<?php echo $cemail; ?></div><br /><br />

				<div style="float: left; display: inline-block; width: 100%;">

				<?php if (true) { ?>

		<div id="map-canvas"></div><br />

		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">

    <meta charset="utf-8">

    <style>

      #map-canvas {

	  height: 350px;

	  width: 100%;

        margin: 0;

        padding: 0;

        

      }

    </style>

    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>

<script type="text/javascript"><!-- 

var map;

var geocoder;

$(document).ready(function() {

initialize();

});



function initialize() {

    var mapOptions = {

    zoom: 12,   

    mapTypeId: google.maps.MapTypeId.ROADMAP

  };

  map = new google.maps.Map(document.getElementById('map-canvas'),

      mapOptions);



	google.maps.event.addDomListener(window, 'load', initialize);

	codeAddress();

	

  }

function codeAddress() {

	geocoder = new google.maps.Geocoder();

   var address = '<? echo trim($map_address);?>';

    geocoder.geocode( { 'address': address}, function(results, status) {

      if (status == google.maps.GeocoderStatus.OK) {

        map.setCenter(results[0].geometry.location);

        var marker = new google.maps.Marker({

            map: map,

            position: results[0].geometry.location,

			title:"<?php echo $store; ?>"

        });

      } else {

        alert("Geocode was not successful for the following reason: " + status);

      }

    });

  }

    //--></script>

			

		

		<?php } ?>

				</div>

			</div>

		</div>

		

		<div class="content">

			<table width="100%">

				<tr>

					<td><?php echo $entry_name; ?><br />

						<input type="text" name="name" value="<?php echo $name; ?>" />

						<?php if ($error_name) { ?>

						<span class="error"><?php echo $error_name; ?></span>

						<?php } ?></td>

				</tr>

				<tr>

					<td><?php echo $entry_email; ?><br />

						<input type="text" name="email" value="<?php echo $email; ?>" />

						<?php if ($error_email) { ?>

						<span class="error"><?php echo $error_email; ?></span>

						<?php } ?></td>

				</tr>

				<tr>

					<td>Phone Number: <br />

						<input type="text" name="phone" value="<?php echo $phone; ?>" />

						<?php if ($error_phone) { ?>

						<span class="error"><?php echo $error_phone; ?></span>

						<?php } ?></td>

				</tr>

				<tr>

					<td>How should we contact you: <br />

	<input type="radio" name="contact" value="email" <?if ($contact=='email') { echo "checked"; }?>> Email<br>

	<input type="radio" name="contact" value="phone" <?if ($contact=='phone') { echo "checked"; }?>> Phone

</td>

				</tr>

				<tr>

					<td>Best Time To Call:<br />

						<input type="text" name="calltime" value="<?php echo $calltime; ?>" />

						<?php if ($error_calltime) { ?>

						<span class="error"><?php echo $error_calltime; ?></span>

						<?php } ?></td>

				</tr>

				<tr>

					<td><?php echo $entry_enquiry; ?><br />

						<textarea name="enquiry" style="width: 99%;" rows="10"><?php echo $enquiry; ?></textarea>

						<?php if ($error_enquiry) { ?>

						<span class="error"><?php echo $error_enquiry; ?></span>

						<?php } ?></td>

				</tr>

				<tr>

					<td><?php echo $entry_captcha; ?><br />
						<input type='text' id='cap1' name='cap1'/><br><div style='font-weight:bold;font-size:150%;' name='cap2' id='cap2'></div></td>

				</tr>

			</table>

		</div>

		<div class="buttons">

			<table>

				<tr>

					<td align="right"><a onClick='captcha()' class="button"><span><?php echo $button_continue; ?></span></a></td>

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
		document.getElementById("contact").submit();
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
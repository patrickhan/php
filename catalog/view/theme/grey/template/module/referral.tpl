<div class="box">
  <div class="top"><?php echo $heading_title; ?></div>
  <div id="referral" class="middle">
    <p><?php echo $text_signup; ?></p>
    <label for="referral_email"><?php echo $text_email_address; ?></label>
    <p><input name="referral_email" id="referral_email" />
	 <label for="referral_description">Email Content</label>
    <textarea cols="17" name="referral_description" id="referral_description"><?echo $referral_description;?></textarea><input id="referral_signup" type="submit" value="Send Email" /></p>
	<p id="referral_message"></p>
  </div>
  <div class="bottom">&nbsp;</div>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#referral_message').hide();
	$('#referral_signup').click(function() {
		$.ajax({
			type: "post",
			dataType: "html",
			url: "index.php?route=module/referral/sendmail",
			data: "referral_email=" + $("#referral_email").val() + "&referral_description=" + $("#referral_description").val(),
			success: function(data) {
				if (data == 'true') {
					$("#referral_email").val('');
					$('#referral_message').html('<?php echo $text_email_success; ?>');
				} else {
					$('#referral_message').html('<?php echo $text_email_error; ?>');
				}
				$('#referral_message').show();
			}
		});
	});
});
//--></script>
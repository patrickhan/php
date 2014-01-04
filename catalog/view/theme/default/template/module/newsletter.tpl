<div class="box">
  <div class="top"><img src="catalog/view/theme/default/image/newsletter.png" alt="" /><?php echo $heading_title; ?></div>
  <div id="newsletter" class="middle">
    <p><?php echo $text_signup; ?></p>
    <label for="newsletter_email"><?php echo $text_email_address; ?></label>
    <p><input name="newsletter_email" id="newsletter_email" /><input id="newsletter_signup" type="submit" value="<?php echo $button_signup; ?>" /></p>
	<p id="newsletter_message"></p>
  </div>
  <div class="bottom">&nbsp;</div>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#newsletter_message').hide();
	$('#newsletter_signup').click(function() {
		$.ajax({
			type: "post",
			dataType: "html",
			url: "index.php?route=module/newsletter/addEmail",
			data: "newsletter_email=" + $("#newsletter_email").val(),
			success: function(data) {
				if (data == 'true') {
					$("#newsletter_email").val('');
					$('#newsletter_message').html('<?php echo $text_email_success; ?>');
				} else {
					$('#newsletter_message').html('<?php echo $text_email_error; ?>');
				}
				$('#newsletter_message').show();
			}
		});
	});
});
//--></script>
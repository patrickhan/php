<div class="box" id="gcheckout">
  <div class="top"><img src="catalog/view/theme/default/image/icon_checkout.png" alt="" /><?php echo $heading_title; ?></div>
  <div class="middle" style="text-align: center;">
      <?php if ($available) { ?>
        <form action="<?php echo $payment_url; ?>" method="post">
          <input type="image" value="send" src="https://checkout.google.com/buttons/checkout.gif?merchant_id=<? echo $merchant_id; ?>&amp;w=160&amp;h=43&amp;style=trans&amp;variant=text&amp;loc=en_US">
          <input type="hidden" name="send" value="<? echo $merchant_id; ?>">
        </form>
      <?php } else { ?>
        <img src="https://checkout.google.com/buttons/checkout.gif?merchant_id=<? echo $merchant_id; ?>&amp;w=160&amp;h=43&amp;style=trans&amp;variant=disabled&amp;loc=en_US" />
      <?php } ?>
  </div>
  <div class="bottom">&nbsp;</div>
</div>
<?php if (($ajax) && (!$callback)) { ?>
<script type="text/javascript"><!--

var refreshing_gcheckout = false;

function gcheckout_refresh() {
	$.ajax({
		type: 'get',
		url: 'index.php?route=module/gcheckout/callback',
		dataType: 'html',
		success: function (html) {
			$('#gcheckout').empty().append( $(html).html() );
		},
	});
}

function gcheckout_ajaxStop_handler() {
	if (!refreshing_gcheckout) {
		refreshing_gcheckout = true;
		gcheckout_refresh();
	} else {
		refreshing_gcheckout = false;
	}
}

$(document).ready(function () {
	$('#gcheckout').ajaxStop(gcheckout_ajaxStop_handler);
});
//--></script>
<?php } ?>
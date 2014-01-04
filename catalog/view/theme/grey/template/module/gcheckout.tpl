<div class="box" id="gcheckout" >
  <div class="top"><img src="catalog/view/theme/default/image/icon_checkout.png" alt="" /><?php echo $heading_title; ?></div>
  <div class="middle" style="text-align: center;">
    <div style="text-align:center; width:92px; margin:0 auto 0 auto;">
      <link rel="stylesheet" href="https://checkout.google.com/seller/accept/s.css" type="text/css" media="screen" />
      <?php if (!$callback) { ?>
      <script type="text/javascript" src="https://checkout.google.com/seller/accept/j-en_GB.js"></script> 
      <script type="text/javascript">showMark(1);</script> 
      <noscript>
      <?php } ?>
        <img src="https://checkout.google.com/seller/accept/images/st-en_GB.gif" width="92" height="88" alt="Google Checkout Acceptance Mark" />
      <?php if (!$callback) { ?>
      </noscript>
      <?php } ?>
    </div>
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
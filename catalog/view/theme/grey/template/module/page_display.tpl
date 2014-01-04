<div class="box">
	<div class="top"><!--<img src="catalog/view/theme/default/image/information.png" alt="" />--><?php echo $heading_title; ?></div>
	<div id="page_display" style="text-align:center" class="middle">    
<?php
echo $link;	?>
<!--	<br><br>
<a href="javascript: void(0)" onclick="updateSite()">Next</a>-->
	</div><div class="bottom">&nbsp;</div>
	</div>
	<script type="text/javascript">
<!--
function updateSite2() {
var num = <?php echo $num; ?>;
	$.ajax({
		type: "post",
		url: "index.php?route=module/page_display/ajaxDisplay",
		data: "num=" + num,
		success: function(data) {
		alert(data);
			$("#page_display").html(data);
			//$("#page_display").append('<br><br><a href="javascript: void(0)" onclick="updateSite()">View Another Site</a>')
		autoUpdate2();
		}
	});
}

$(document).ready(function () {
autoUpdate2();
});
function autoUpdate2() {
var t=setTimeout("updateSite2()", 5000);
}
     
-->
</script>
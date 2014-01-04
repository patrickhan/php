<div class="box">
	<div class="top"><img src="catalog/view/theme/default/image/information.png" alt="" />HOT DEALS</div>
	<div id="product_display" style="text-align:center" class="middle">    
<?php
echo $link;	?>
<!--	<br><br>
<a href="javascript: void(0)" onclick="updateProduct()">Next</a>-->
	</div><div class="bottom">&nbsp;</div>
	</div>
	<script type="text/javascript">
<!--
function updateProduct() {
	$.ajax({
		type: "post",
		url: "index.php?route=module/product_display/ajaxDisplay",
		data: "",
		success: function(data) {
			$("#product_display").html(data);
			//$("#product_display").append('<br><br><a href="javascript: void(0)" onclick="updateProduct()">View Another Product</a>')
		setTimeout("autoUpdate()", 5000);
		}
	});
}

$(document).ready(function () {
var t=setTimeout("updateProduct()", 5000);
});
function autoUpdate() {
updateProduct()
}
     
-->
</script>
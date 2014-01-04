<div class="box">
	<div class="top"><!--<img src="catalog/view/theme/default/image/information.png" alt="" />--><?php echo $heading_title; ?></div>
	<div id="image_display" style="text-align:center" class="middle">    
<?php
//catalog/view/theme/default/template/module/image_display.tpl
/*foreach ($media as $medium) { 
	if ($medium['href'] != "") {
		switch ($medium['key']) {
			case "image_display_link":
			$image = $medium['href'];
				print "<img src='$image' target='_blank' alt='Test' />";
				break;
		}
	} 
}*/
//print "<img src='$image' target='_blank' alt='Test' max-width='180px' max-height='180px' />";
?>
<img src="<?php echo $image; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>">
	</div><div class="bottom">&nbsp;</div>
	</div>
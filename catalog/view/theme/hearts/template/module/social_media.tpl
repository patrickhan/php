<div class="box">
	<div class="top"><!--<img src="catalog/view/theme/default/image/information.png" alt="" />--><?php echo $heading_title; ?></div>
	<div id="social_media" style="text-align:center" class="middle">    
<?php
//catalog/view/theme/default/template/module/social_media.tpl
foreach ($media as $medium) { 
	if ($medium['href'] != "") {
		switch ($medium['key']) {
			case "social_media_facebook_link":
				echo '<a href="' . $medium['href'] . '"><img src="image/data/social/facebook.png" target="_blank" alt="Facebook" /></a>';
				break;
			case "social_media_twitter_link":
				echo '<a href="' . $medium['href'] . '"><img src="image/data/social/twitter.png" target="_blank" alt="Twitter" /></a>';
				break;
			case "social_media_my_space_link":
				echo '<a href="' . $medium['href'] . '"><img src="image/data/social/myspace.png" target="_blank" alt="MySpace" /></a>';
				break;
			case "social_media_linked_in_link":
				echo '<a href="' . $medium['href'] . '"><img src="image/data/social/linkedin.png" target="_blank" alt="Linked In" /></a>';
				break;
		}
	} 
}
?>
	</div>
	<div class="bottom">&nbsp;</div>
</div>
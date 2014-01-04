<div class="box">
	<div class="top"><img src="catalog/view/theme/default/image/information.png" alt="" /><?php echo $heading_title; ?></div>
	<div id="information" class="middle">
		<ul>
		<?php foreach ($informations as $information) { ?>
		<li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
		<?php } ?>
		<li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
		<?php if ($display_printable) { ?>
		<li><a href="<?php echo $printable; ?>"><?php echo $text_printable; ?></a></li>
		<?php } ?>
		<?php if ($display_sitemap) { ?>
		<li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
		<?php } ?>
		<li><a href="<?php echo $resources; ?>"><?php echo $text_resources; ?></a></li>				<li><a href="articles">Articles</a></li>
		</ul>
	</div>
	<div class="bottom">&nbsp;</div>
</div>
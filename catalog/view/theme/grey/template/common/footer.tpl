
<div id="footer">
	<div id="footer_links">
		<?php
		$first = TRUE;
		foreach ($links as $link) {
			if ( ! $first) {
				echo ' | ';
			}
			$first = FALSE;
			echo '<a href="' . $link['href'] . '">' . $link['title'] . '</a>';
		}
		?>
	</div>
	
	<div class="div1"><?php if (!$this->config->get('brochure')) { ?><a onclick="window.open('http://www.paypal.com/');"><img src="catalog/view/theme/default/image/payment.png" alt="" /></a><?php } ?></div>
	<div class="div2"><?php echo $text_powered_by; ?></div>
</div>
</div>
<?php echo $google_analytics; ?>
</body></html>
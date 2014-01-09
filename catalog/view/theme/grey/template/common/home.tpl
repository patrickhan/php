<?php echo $header; ?>
<div id="content">
	<div class="top">
		<div class="left"></div>
		<div class="right"></div>
		<div class="center">
			<h1><?php echo $heading_title; ?></h1>
		</div>
	</div>
	<div class="middle">
		<div><?php echo $welcome; ?></div>
		<?php if (!$this->config->get('brochure')) {
			if ($config_cats) { ?>
		<div class="heading"><?php echo $text_categories; ?></div>
		<table class="list">
		<?php for ($i = 0; $i < sizeof($categories); $i = $i + 4) { ?>
			<tr>
			<?php for ($j = $i; $j < ($i + 4); $j++) { ?>
				<td width="25%"><?php if (isset($categories[$j])) { ?>
					<a href="<?php echo $categories[$j]['href']; ?>"><img src="<?php echo $categories[$j]['thumb']; ?>" title="<?php echo $categories[$j]['name']; ?>" alt="<?php echo $categories[$j]['name']; ?>" style="margin-bottom: 3px;" /></a><br />
					<a href="<?php echo $categories[$j]['href']; ?>"><?php echo $categories[$j]['name']; ?></a>
				<?php } ?></td>
			<?php } ?>
			</tr>
		<?php } ?>
		</table>
		<?php }
		} ?>
		<?php if (!$this->config->get('brochure')) {
			if ( ! empty($featured) && $display_featured) { ?>
		<div class="heading"><?php echo $text_featured; ?></div>
		<table class="list">
		<?php if ($config_prods) { for ($i = 0; $i < sizeof($featured); $i = $i + 1) {?>
			<tr>
			<?php for ($j = $i; $j < ($i + 1); $j++) { ?>
				<td><?php if (isset($featured[$j])) { ?>
					<a href="<?php echo $featured[$j]['href']; ?>"><img src="<?php echo $featured[$j]['thumb']; ?>" title="<?php echo $featured[$j]['name']; ?>" alt="<?php echo $featured[$j]['name']; ?>" /><br><?php echo $featured[$j]['name']; ?></a></td>
					<td style='text-align:left;'>
					<?php echo $featured[$j]['desc']; ?>
					</td>
					<td>
					<?php if ($display_price) { ?>
					<?php if (!$featured[$j]['special']) { ?>
					<span style="color: #900; font-weight: bold;"><?php echo $featured[$j]['price']; ?></span>
					<?php } else { ?>
					<span style="color: #900; font-weight: bold; text-decoration: line-through;"><?php echo $featured[$j]['price']; ?></span> <span style="color: #F00;"><?php echo $featured[$j]['special']; ?></span>
					<?php } ?>
					<?php } ?><br><form action="<?echo $paction;?>" method="post" enctype="multipart/form-data" id="productl<?echo $featured[$j]['product_id'];?>"><div>Quantity:
								<input type="text" name="quantity" size="3" value="1" />
							<input type="hidden" name="product_id" value="<?php echo $featured[$j]['product_id']; ?>" /><br>
							<input type="hidden" name="redirect" value="index.php?route=common/home" />
								<a onclick="$('#productl<?echo $featured[$j]['product_id'];?>').submit();" id="add_to_cartl<?echo $featured[$j]['product_id'];?>" class="button"><span>Add</span></a></div>
							
						</form></td>
				<?php } ?></td>
			<?php } ?>
			</tr>
		<?php } } else { for ($i = 0; $i < sizeof($featured); $i = $i + 4) { ?>
			<tr>
			<?php for ($j = $i; $j < ($i + 4); $j++) { ?>
				<td style="width: 25%;"><?php if (isset($featured[$j])) { ?>
					<a href="<?php echo $featured[$j]['href']; ?>"><img src="<?php echo $featured[$j]['thumb']; ?>" title="<?php echo $featured[$j]['name']; ?>" alt="<?php echo $featured[$j]['name']; ?>" /></a><br />
					<a href="<?php echo $featured[$j]['href']; ?>"><?php echo $featured[$j]['name']; ?></a><br />
					<span style="color: #999; font-size: 11px;"><?php echo $featured[$j]['model']; ?></span><br />
					<?php if ($display_price) { ?>
					<?php if (!$featured[$j]['special']) { ?>
					<span style="color: #900; font-weight: bold;"><?php echo $featured[$j]['price']; ?></span><br />
					<?php } else { ?>
					<span style="color: #900; font-weight: bold; text-decoration: line-through;"><?php echo $featured[$j]['price']; ?></span> <span style="color: #F00;"><?php echo $featured[$j]['special']; ?></span>
					<?php } ?>
					<?php } ?>
					<?php if ($featured[$j]['rating']) { ?>
					<img src="catalog/view/theme/default/image/stars_<?php echo $featured[$j]['rating'] . '.png'; ?>" alt="<?php echo $featured[$j]['stars']; ?>" />
					<?php } ?>
				<?php } ?></td>
			<?php } ?>
			</tr>
		<?php } } ?>
		</table>
		<?php 	}
		} ?>    
		<?php if (!$this->config->get('brochure')) { 
			if ( ! empty($specials) && $display_specials) { ?>
		<div class="heading"><?php echo $text_specials; ?></div>
		<table class="list">
		<?php if ($config_prods) { for ($i = 0; $i < sizeof($specials); $i = $i + 1) {?>
			<tr>
			<?php for ($j = $i; $j < ($i + 1); $j++) { ?>
				<td><?php if (isset($specials[$j])) { ?>
					<a href="<?php echo $specials[$j]['href']; ?>"><img src="<?php echo $specials[$j]['thumb']; ?>" title="<?php echo $specials[$j]['name']; ?>" alt="<?php echo $specials[$j]['name']; ?>" /><br><?php echo $specials[$j]['name']; ?></a></td>
					<td style='text-align:left;'>
					<?php echo $specials[$j]['desc']; ?>
					</td>
					<td>
					<?php if ($display_price) { ?>
					<?php if (!$specials[$j]['special']) { ?>
					<span style="color: #900; font-weight: bold;"><?php echo $specials[$j]['price']; ?></span>
					<?php } else { ?>
					<span style="color: #900; font-weight: bold; text-decoration: line-through;"><?php echo $specials[$j]['price']; ?></span> <span style="color: #F00;"><?php echo $specials[$j]['special']; ?></span>
					<?php } ?>
					<?php } ?><br><form action="<?echo $paction;?>" method="post" enctype="multipart/form-data" id="productl<?echo $specials[$j]['product_id'];?>"><div>Quantity:
								<input type="text" name="quantity" size="3" value="1" />
							<input type="hidden" name="product_id" value="<?php echo $specials[$j]['product_id']; ?>" /><br>
							<input type="hidden" name="redirect" value="index.php?route=common/home" />
								<a onclick="$('#productl<?echo $specials[$j]['product_id'];?>').submit();" id="add_to_cartl<?echo $specials[$j]['product_id'];?>" class="button"><span>Add</span></a></div>
							
						</form></td>
				<?php } ?></td>
			<?php } ?>
			</tr>
		<?php } } else { for ($i = 0; $i < sizeof($specials); $i = $i + 4) { ?>
			<tr>
			<?php for ($j = $i; $j < ($i + 4); $j++) { ?>
				<td width="25%"><?php if (isset($specials[$j])) { ?>
					<a href="<?php echo $specials[$j]['href']; ?>"><img src="<?php echo $specials[$j]['thumb']; ?>" title="<?php echo $specials[$j]['name']; ?>" alt="<?php echo $specials[$j]['name']; ?>" /></a><br />
					<a href="<?php echo $specials[$j]['href']; ?>"><?php echo $specials[$j]['name']; ?></a><br />
					<span style="color: #999; font-size: 11px;"><?php echo $specials[$j]['model']; ?></span><br />
					<?php if ($display_price) { ?>
					<?php if (!$specials[$j]['special']) { ?>
					<span style="color: #900; font-weight: bold;"><?php echo $specials[$j]['price']; ?></span><br />
					<?php } else { ?>
					<span style="color: #900; font-weight: bold; text-decoration: line-through;"><?php echo $specials[$j]['price']; ?></span> <span style="color: #F00;"><?php echo $specials[$j]['special']; ?></span>
					<?php } ?>
					<?php } ?>
					<?php if ($specials[$j]['rating']) { ?>
					<img src="catalog/view/theme/default/image/stars_<?php echo $specials[$j]['rating'] . '.png'; ?>" alt="<?php echo $specials[$j]['stars']; ?>" />
					<?php } ?>
				<?php } ?></td>
			<?php } ?>
			</tr>
		<?php } }?>
		</table>
		<?php 	}
		} ?> 
		<?php if (!$this->config->get('brochure')) { 
			if ($display_new) { ?>
		<div class="heading"><?php echo $text_latest; ?></div>
		<table class="list">
		<?php if ($config_prods) { for ($i = 0; $i < sizeof($products); $i = $i + 1) {?>
			<tr>
			<?php for ($j = $i; $j < ($i + 1); $j++) { ?>
				<td><?php if (isset($products[$j])) { ?>
					<a href="<?php echo $products[$j]['href']; ?>"><img src="<?php echo $products[$j]['thumb']; ?>" title="<?php echo $products[$j]['name']; ?>" alt="<?php echo $products[$j]['name']; ?>" /><br><?php echo $products[$j]['name']; ?></a></td>
					<td style='text-align:left;'>
					<?php echo $products[$j]['desc']; ?>
					</td>
					<td>
					<?php if ($display_price) { ?>
					<?php if (!$products[$j]['special']) { ?>
					<span style="color: #900; font-weight: bold;"><?php echo $products[$j]['price']; ?></span>
					<?php } else { ?>
					<span style="color: #900; font-weight: bold; text-decoration: line-through;"><?php echo $products[$j]['price']; ?></span> <span style="color: #F00;"><?php echo $products[$j]['special']; ?></span>
					<?php } ?>
					<?php } ?><br><form action="<?echo $paction;?>" method="post" enctype="multipart/form-data" id="productl<?echo $products[$j]['product_id'];?>"><div>Quantity:
								<input type="text" name="quantity" size="3" value="1" />
							<input type="hidden" name="product_id" value="<?php echo $products[$j]['product_id']; ?>" /><br>
							<input type="hidden" name="redirect" value="index.php?route=common/home" />
								<a onclick="$('#productl<?echo $products[$j]['product_id'];?>').submit();" id="add_to_cartl<?echo $products[$j]['product_id'];?>" class="button"><span>Add</span></a></div>
							
						</form></td>
				<?php } ?></td>
			<?php } ?>
			</tr>
		<?php } } else { for ($i = 0; $i < sizeof($products); $i = $i + 4) { ?>
			<tr>
			<?php for ($j = $i; $j < ($i + 4); $j++) { ?>
				<td style="width: 25%;"><?php if (isset($products[$j])) { ?>
					<a href="<?php echo $products[$j]['href']; ?>"><img src="<?php echo $products[$j]['thumb']; ?>" title="<?php echo $products[$j]['name']; ?>" alt="<?php echo $products[$j]['name']; ?>" /></a><br />
					<a href="<?php echo $products[$j]['href']; ?>"><?php echo $products[$j]['name']; ?></a><br />
					<span style="color: #999; font-size: 11px;"><?php echo $products[$j]['model']; ?></span><br />
					<?php if ($display_price) { ?>
					<?php if (!$products[$j]['special']) { ?>
					<span style="color: #900; font-weight: bold;"><?php echo $products[$j]['price']; ?></span><br />
					<?php } else { ?>
					<span style="color: #900; font-weight: bold; text-decoration: line-through;"><?php echo $products[$j]['price']; ?></span> <span style="color: #F00;"><?php echo $products[$j]['special']; ?></span>
					<?php } ?>
					<?php } ?>
					<?php if ($products[$j]['rating']) { ?>
					<img src="catalog/view/theme/default/image/stars_<?php echo $products[$j]['rating'] . '.png'; ?>" alt="<?php echo $products[$j]['stars']; ?>" />
					<?php } ?>
				<?php } ?></td>
			<?php } ?>
			</tr>
		<?php } } ?>
		</table>
		<?php 	}
		} ?>
	</div>
	<div class="bottom">
		<div class="left"></div>
		<div class="right"></div>
		<div class="center"></div>
	</div>
</div>
<?php echo $footer; ?> 

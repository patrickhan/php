<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
<link rel="stylesheet" type="text/css" href="view/javascript/jquery/ui/themes/ui-lightness/ui.all.css" />
<?php foreach ($styles as $style) { ?>
<link rel="stylesheet" type="text/css" href="view/stylesheet/<?php echo $style; ?>" />
<?php } ?>
<script type="text/javascript" src="view/javascript/jquery/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.core.js"></script>
<script type="text/javascript" src="view/javascript/jquery/superfish/js/superfish.js"></script>
<script type="text/javascript" src="view/javascript/jquery/tab.js"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="view/javascript/jquery/<?php echo $script; ?>"></script>
<?php } ?>
</head>
<body>
<div id="header">
	<div class="div1"><img src="../image/<?php echo $config_admin_logo; ?>" title="<?php echo $heading_title; ?>" onclick="location='<?php echo $home; ?>'" /></div>
	<?php if ($logged) { ?>
	
	<div class="div2"><div style='font-size:16px;font-weight:bold;padding-top:0px;margin-right:170px;'><a style='color:white;' onclick="window.open('../install/Manual.doc');">QE Cart Manual</a> | 						<?if (file_exists('../install/SEO.doc')) {?>
				<a style='color:white;' onclick="window.open('../install/SEO.doc');">SEO Manual</a>
				<?} else {?>
				<a style='color:white;' href='index.php?route=common/extra' style='color:grey'>SEO Manual</a>
				<?}?></div><img src="view/image/lock.png" alt="" style="position: relative; top: 3px;" />&nbsp;<?php echo $logged; ?></div>
	<?php } ?>
</div>
<?php if ($logged) { ?>
<div id="menu">
	<ul class="nav left" style="display: none;">
	<li id="dashboard"><a href="<?php echo $home; ?>" class="top"><img class="icon" src="view/image/cog-16x16.png" border="0" />Admin Home</a></li>
		<?php if ($this->config->get('brochure') === FALSE) { ?>
		<li id="catalog"><a class="top"><img class="icon" src="view/image/light-16x16.png" /><?php echo $text_product; ?></a>
			<ul>
				<li><a href="<?php echo $category; ?>"><?php echo $text_category; ?></a></li>
				<li><a href="<?php echo $product; ?>"><?php echo $text_product; ?></a></li>
				<li><a href="<?php echo $review; ?>"><?php echo $text_review; ?></a></li>
				<li><a href="<?php echo $feed; ?>"><?php echo $text_feed; ?></a></li>
				<li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
				<li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
				<li><a href="<?php echo $featured; ?>"><?php echo $text_featured; ?></a></li>
				<li><a class="parent"><?php echo $text_gallery; ?></a>
					<ul>
						<li><a href="<?php echo $gallery_album; ?>"><?php echo $text_gallery_album; ?></a></li>
						<li><a href="<?php echo $gallery_image; ?>"><?php echo $text_gallery_image; ?></a></li>
					</ul>
				</li>
			</ul>
		</li>
		<li id="sale"><a class="top"><img class="icon" src="view/image/money_dollar.png" /><?php echo $text_sale; ?></a>
			<ul>
				<li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
				<li><a href="<?php echo $total; ?>"><?php echo $text_total; ?></a></li>
				<li><a href="<?php echo $customer; ?>"><?php echo $text_customer; ?></a></li>
				<li><a href="<?php echo $customer_group; ?>"><?php echo $text_customer_group; ?></a></li>
				<li><a href="<?php echo $coupon; ?>"><?php echo $text_coupon; ?></a></li>
				<li><a href="index.php?route=sale/discount">Total Discount</a></li>
				<li><a href="index.php?route=report/carts">Abandoned Carts</a></li>
			</ul>
		</li>
		<?php } ?>
		<li id="site manager"><a class="top"><img class="icon" src="view/image/tools-16x16.png" /><?php echo $text_site_manager; ?></a>
			<ul>
				<li><a href="<?php echo $information; ?>"><?php echo $text_information; ?></a></li>
				<li><a href="<?php echo $banners; ?>"><?php echo $text_banners; ?></a></li>
				<li><a href="<?php echo $headers; ?>"><?php echo $text_headers; ?></a></li>
				<?php if ($this->config->get('premium')) { ?><li><a href="<?php echo $images; ?>"><?php echo $text_images; ?></a></li><?php } ?>
				<li><a href="<?php echo $menu; ?>"><?php echo $text_menu; ?></a></li>
				<li><a class="parent"><?php echo $text_users; ?></a>
					<ul>
						<li><a href="<?php echo $user; ?>"><?php echo $text_user; ?></a></li>
						<li><a href="<?php echo $user_group; ?>"><?php echo $text_user_group; ?></a></li>
					</ul>
				</li>
				<li><a class="parent"><?php echo $text_localisation; ?></a>
					<ul>
						<li><a href="<?php echo $language; ?>"><?php echo $text_language; ?></a></li>
						<?php if ($this->config->get('brochure') === FALSE) { ?>
						<li><a href="<?php echo $currency; ?>"><?php echo $text_currency; ?></a></li>
						<li><a href="<?php echo $stock_status; ?>"><?php echo $text_stock_status; ?></a></li>
						<li><a href="<?php echo $order_status; ?>"><?php echo $text_order_status; ?></a></li>
						<li><a href="<?php echo $country; ?>"><?php echo $text_country; ?></a></li>
						<li><a href="<?php echo $tax_class; ?>"><?php echo $text_tax_class; ?></a></li>
						<li><a href="<?php echo $zone; ?>"><?php echo $text_zone; ?></a></li>
						<li><a href="<?php echo $geo_zone; ?>"><?php echo $text_geo_zone; ?></a></li>
						<li><a href="<?php echo $measurement_class; ?>"><?php echo $text_measurement_class; ?></a></li>
						<li><a href="<?php echo $weight_class; ?>"><?php echo $text_weight_class; ?></a></li>
						<?php } ?>
					</ul>
				</li>
				<?php if ($this->config->get('brochure') === FALSE) { ?>
				<li><a href="<?php echo $shipping; ?>"><?php echo $text_shipping; ?></a></li>
				<?php } ?>
				<li><a href="<?php echo $module; ?>"><?php echo $text_module; ?></a></li>
				<li><a href="<?php echo $setting; ?>"><?php echo $text_setting; ?></a>
				<li><a href="<?php echo $error_log; ?>"><?php echo $text_error_log; ?></a></li>
				<li><a href="<?php echo $backup; ?>"><?php echo $text_backup; ?></a></li>
				<?php if ($this->config->get('brochure') === FALSE) { ?>
				<li><a href="<?php echo $export; ?>"><?php echo $text_export; ?></a></li>
				<li><a href="<?php echo $payment; ?>"><?php echo $text_payment; ?></a></li>
				<?php } ?>
			</ul>
		</li>
	<?php if ($this->config->get('brochure') === FALSE) { ?>
		<li id="reports"><a class="top"><img class="icon" src="view/image/report-16x16.png" />Accounting</a>		
		<ul>			<?if (file_exists('view/template/report/taxes.tpl')) {?>				<li><a href="index.php?route=report/expense">Expense Report</a></li>				<li><a href="index.php?route=report/totals<?php //echo $report_sale; ?>">Sales Report</a></li>				<li><a href="<?php echo $report_purchased; ?>">Product Report</a></li>				<li><a href="index.php?route=report/taxes">Tax Report</a></li>				<li><a href="index.php?route=report/profitloss">Profit/Loss Report</a></li>				<?} else {?>				<li><a href='index.php?route=common/extra' style='color:grey'>Expense Report</a></li>				<li><a href='index.php?route=common/extra' style='color:grey'>Sales Report</a></li>				<li><a href='index.php?route=common/extra' style='color:grey'>Product Report</a></li>				<li><a href='index.php?route=common/extra' style='color:grey'>Tax Report</a></li>				<li><a href='index.php?route=common/extra' style='color:grey'>Profit/Loss Report</a></li>				<?}?>			</ul>
		</li>	
		
		<li id="system"><a class="top"><img class="icon" src="view/image/chart-16x16.png" /><?php echo $text_marketing; ?></a>			<ul>				<li><a href="<?php echo $coupon; ?>"><?php echo $text_coupon; ?></a></li>				<li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>				<li><a href="<?php echo $newsletter_signups; ?>"><?php echo $text_newsletter_signups; ?></a></li>			</ul>		</li>	
		
		<li id="reports"><a class="top"><img class="icon" src="view/image/report-16x16.png" />SEO</a>			<ul>				<li><a href="<?php echo $information; ?>"><?php echo $text_information; ?></a></li>				<?if (file_exists('../install/SEO.doc')) {?>				<li><a href="index.php?route=catalog/articles">Articles</a></li>				<li><a href="<?php echo $links; ?>"><?php echo $text_links; ?></a></li>		<li><a href="index.php?route=catalog/blog">Blog</a></li>
				<li><a href="index.php?route=catalog/blogcomment">Blog Comments</a></li>		<li><a href="<?php echo $generate_sitemap; ?>"><?php echo $text_generate_sitemap; ?></a></li>				<?} else {?>				<li><a href='index.php?route=common/extra' style='color:grey'>Articles</a></li>				<li><a href='index.php?route=common/extra' style='color:grey'><?php echo $text_links; ?></a></li><li><a href='index.php?route=common/extra' style='color:grey'>Blog</a></li><li><a href='index.php?route=common/extra' style='color:grey'>Blog Comments</a></li>				<li><a href='index.php?route=common/extra' style='color:grey'><?php echo $text_generate_sitemap; ?></a></li>				<?}?>			</ul>		</li>		
		
		<li id='attracta'><a class='top' href='index.php?route=extension/attracta'><img class="icon" src="view/image/report-16x16.png" />Attracta</a></li>
		
		<li id='411'><a class='top' href='index.php?route=extension/411'><img class="icon" src="view/image/report-16x16.png" />411.ca</a></li>
	<?php } ?>
	</ul>
	<ul class="nav right">
		<li id="help"><a class="top"><?php echo $text_help; ?></a>
			<ul>
				<li><a onclick="window.open('../install/Manual.doc');">QE Cart Manual</a></li>							<?if (file_exists('../install/SEO.doc')) {?>
				<li><a onclick="window.open('../install/SEO.doc');">SEO Manual</a></li>
				<?} else {?>
				<li><a href='index.php?route=common/extra' style='color:grey'>SEO Manual</a></li>
				<?}?>
				<li><a onclick="window.open('http://www.qecart.com');"><?php echo $text_homepage; ?></a></li>
				<li><a onclick="window.open('http://qecart.com/support');"><?php echo $text_support; ?></a></li>
			</ul>
		</li>
		<li id="store"><a class="top" href="<?php echo $store; ?>"><?php echo $text_store; ?></a></li>
		<li id="store"><a class="top" href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
	</ul>
	<script type="text/javascript"><!--
$(document).ready(function() {
	$('.nav').superfish({
		hoverClass		: 'sfHover',
		pathClass		: 'overideThisToUse',
		delay				: 0,
		animation		: {height: 'show'},
		speed				: 'normal',
		autoArrows		: false,
		dropShadows		: false, 
		disableHI		: false, /* set to true to disable hoverIntent detection */
		onInit			: function(){},
		onBeforeShow	: function(){},
		onShow			: function(){},
		onHide			: function(){}
	});
	
	$('.nav').css('display', 'block');
});
//--></script>
	<script type="text/javascript"><!-- 
function getURLVar(urlVarName) {
	var urlHalves = String(document.location).toLowerCase().split('?');
	var urlVarValue = '';
	
	if (urlHalves[1]) {
		var urlVars = urlHalves[1].split('&');
		
		for (var i = 0; i <= (urlVars.length); i++) {
			if (urlVars[i]) {
				var urlVarPair = urlVars[i].split('=');
				
				if (urlVarPair[0] && urlVarPair[0] == urlVarName.toLowerCase()) {
					urlVarValue = urlVarPair[1];
				}
			}
		}
	}
	
	return urlVarValue;
} 

function highlight(checkbox, row)
{

	var color1 = '#fbd386';
    var color2 = '';
     document.getElementById(row).style.background = (checkbox.checked ? color1 : color2);
}

$(document).ready(function() {
	route = getURLVar('route');
	
	if (!route) {
		$('#dashboard').addClass('selected');
	} else {
		part = route.split('/');
		
		url = part[0];
		
		if (part[1]) {
			url += '/' + part[1];
		}
		
		$('a[href*=\'' + url + '\']').parents('li[id]').addClass('selected');
	}
});
//--></script>
</div>
<?php } ?>
<div id="content">
<?php if ($breadcrumbs) { ?>
<div class="breadcrumb">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
	<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
	<?php } ?>
</div>
<?php } ?>
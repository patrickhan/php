<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
<head>
<title><?php echo $title; ?></title>
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<meta name="robots" content="index, follow" />
<base href="<?php echo $base; ?>" />
<?php if ($icon) { ?>
<link href="image/<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/hearts/stylesheet/stylesheet.css" />
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/hearts/stylesheet/ie6.css" />
<script type="text/javascript" src="catalog/view/javascript/unitpngfix/unitpngfix.js"></script>
<![endif]-->
<?php foreach ($styles as $style) { ?>
<link rel="stylesheet" type="text/css" href="view/stylesheet/<?php echo $style; ?>" />
<?php } ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/thickbox/thickbox-compressed.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/thickbox/thickbox.css" />
<script type="text/javascript" src="catalog/view/javascript/stuHover.js"></script>
<?php if ($slider) { ?>
<script type="text/javascript" src="catalog/view/javascript/js/easySlider1.7.js"></script>
<?php } ?>
<?php if ($fade) { ?>
<script type="text/javascript" src="catalog/view/javascript/js/slideshowfade.js"></script>
<?php } ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/tab.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jqueryui/jquery-ui-1.7.2.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jqueryui/smoothness/jquery-ui-1.7.2.custom.css" />
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="view/javascript/jquery/<?php echo $script; ?>"></script>
<?php } ?>

<?php
foreach ($scripts as $script) { ?>
<script type="text/javascript" src="view/javascript/jquery/<?php echo $script; ?>"></script>
<?php } ?>
</head>
<body>
<div id="container">
<div id="header">
  <div id="div1">
  <div id="logo">
  <?php echo $logo_text; ?>
  </div>
    <div id="slider">
			<?php echo $logo; ?>
			<?if ($fade) { ?>
				<script language="javascript"> 
				var images=new Array;
				var text=new Array;
				<?
				for($i=0;$i<count($images); $i++){
				echo "images[$i]='".$images[$i]."';\n";
				echo "text[$i]='".$text[$i]."';\n";
				}			
				?>
				slideshowFade('slider','',images,text,20,<?echo $sec . ',' . $width . ',' . $height;?>);	
				</script>
				<?}?>
    </div>
    <div class="div3"><div id="headerText"><?php echo $header_text; ?></div></div>
	<div class="div4">
      <table width="100%" style="margin-top: -3px;">
	  <tr>
	  <td><?php echo $menu; ?></td><td style="vertical-align: right; color: #FFF; font-size: 14px;"><em><strong>"Caring people in our communities"</strong></em></td>
	  </tr>
	  </table>
    </div>
    <div class="div5">
      <div class="left"></div>
      <div class="right"></div>
      <div class="center">
		</div>
    </div>
  </div>
  <div class="div6">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center">
      <div id="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
      </div>
      <div class="div7">
      </div>
    </div>
  </div>
</div>

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

$(document).ready(function() {
	route = getURLVar('route');
	
<?php if ($slider) { ?>
	$("#slider").easySlider({
		auto: true,
		continuous: true,
		pause: <?echo $sec;?>,
		controlsShow: false
	});
	<?php } ?>
	
	if (!route) {
		$('#tab_home').addClass('selected');
	} else {
		part = route.split('/');
		
		if (route == 'checkout/onepage') {
			$("#tabs").tabs();
			$("#ptabs").tabs();
		}
		
		if (route == 'common/home') {
			$('#tab_menu_1').addClass('selected');
		} else if (route == 'account/login') {
			$('#tab_menu_2').addClass('selected');	
		} else if (part[0] == 'account') {
			$('#tab_menu_6').addClass('selected');
		} else if (route == 'checkout/cart') {
			$('#tab_menu_4').addClass('selected');
		} else if (part[0] == 'checkout') {
			$('#tab_menu_5').addClass('selected');
		} else {
			$('#tab_menu_1').addClass('selected');
		}
	}
});
//--></script>
<script type="text/javascript"><!--
$('#module_search input').keydown(function(e) {
	if (e.keyCode == 13) {
		moduleSearch();
	}
});

function moduleSearch() {
	url = 'index.php?route=product/search';
	
	var filter_keyword = $('#filter_keyword').attr('value')
	
	if (filter_keyword) {
		url += '&keyword=' + encodeURIComponent(filter_keyword);
	}
	
	var filter_category_id = $('#filter_category_id').attr('value');
	
	if (filter_category_id) {
		url += '&category_id=' + filter_category_id;
	}
	
	location = url;
}

$('#search input').keydown(function(e) {
	if (e.keyCode == 13) {
		moduleSearch();
	}
});
//--></script>
<script type="text/javascript"><!--
$('.switcher').bind('click', function() {
	$(this).find('.option').slideToggle('fast');
});
//--></script>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
<head>
<title><?php echo $heading_title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($icon) { ?>
<link href="image/<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<style type="text/css">
html {
	overflow: -moz-scrollbars-vertical;
	margin: 0;
	padding: 0;
}
* {
	font-family: Arial, Helvetica, sans-serif;
}
body {
	margin: 0px;
	padding: 0px;
}
body, td, th, a {
	font-size: 12px;
}
h1, h2, h3, h4 {
	font-weight: bold;
}
h1 {
	font-size: 24px;
}
h2 {
	font-size: 16px;
}
h3 {
	font-size: 14px;
}
h4 {
	font-size: 12px;
}
td {
	vertical-align: top;
	margin: 0;
	padding: 12px 12px 0 12px;
	border-bottom: 1px dotted #000;
}
a, a:visited {
	color: #1B57A3;
	text-decoration: underline;
	cursor: pointer;
}
a:hover {
	text-decoration: none;
}
a img {
	border: none;
}
p {
	margin-top: 0px;
}
#container {
	width: 960px;
	margin-left: auto;
	margin-right: auto;
	text-align: left;
}
</style>
</head>
<body>
  <div id="container">
    <h1><?php echo $heading_title; ?></h1>
    <h2><?php echo $base; ?></h2>
    <table cellspacing="0">
      <?php for ($i = 0; $i < sizeof($products); $i++) { ?>
      <tr>
        <td>
          <a href="<?php echo $products[$i]['href']; ?>"><img src="<?php echo $products[$i]['thumb']; ?>" title="<?php echo $products[$i]['name']; ?>" alt="<?php echo $products[$i]['name']; ?>" /></a><br />
          <a href="<?php echo $products[$i]['href']; ?>"><?php echo $products[$i]['name']; ?></a><br />
          <span style="color: #999; font-size: 11px;"><?php echo $products[$i]['model']; ?></span><br />
          <?php if ($display_price) { ?>
          <?php if (!$products[$i]['special']) { ?>
          <span style="color: #900; font-weight: bold;"><?php echo $products[$i]['price']; ?></span><br />
          <?php } else { ?>
          <span style="color: #900; font-weight: bold; text-decoration: line-through;"><?php echo $products[$i]['price']; ?></span> <span style="color: #F00;"><?php echo $products[$i]['special']; ?></span>
          <?php } ?>
          <?php } ?>
          <?php if ($products[$i]['rating']) { ?>
          <img src="catalog/view/theme/default/image/stars_<?php echo $products[$i]['rating'] . '.png'; ?>" alt="<?php echo $products[$i]['stars']; ?>" />
          <?php } ?>
        </td>
        <td>
		  <h3><?php echo $products[$i]['name']; ?></h3>
		  <?php echo $products[$i]['description']; ?>
		  <h4><?php echo $products[$i]['href']; ?></h4>
		</td>
      </tr>
      <?php } ?>
    </table>
  </div>
<?php echo $google_analytics; ?>
</body>
</html>
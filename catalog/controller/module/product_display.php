<?php
//catalog/controller/module/product_display.php
class ControllerModuleProductDisplay extends Controller {
	protected function index() {
		$this->language->load('module/product_display');
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['imagething'] = $this->language->get('imagething');
	
	$this->load->model('catalog/product');
	$type = $this->config->get('product_display_type');
	if ($type=="special")
	{
	$total = $this->model_catalog_product->getTotalProductSpecials();
	$all = $this->model_catalog_product->getProductSpecials();
	}
	elseif ($type=="featured")
	{
	$all = $this->model_catalog_product->getFeaturedProducts(10000);
	$total = count($all);
	}
	elseif ($type=="all")
	{
	$total = $this->model_catalog_product->getTotalProducts();
	$all = $this->model_catalog_product->getProducts();
	}
	$total -=1;
	(int) $picnum = rand(0,$total);
	$special = $this->model_catalog_product->getProductSpecial($all[(int)$picnum]['product_id']);
				
				if ($special) {
					$special = $this->currency->format($this->tax->calculate($special, $all[(int)$picnum]['tax_class_id'], $this->config->get('config_tax')));
				}
				if (!$all)
				{
				$link="<img src='image/cache/no_image-120x120.jpg'>";
				}
				else
				{
	$name = $all[(int)$picnum]['name'];
	$ID = $all[(int)$picnum]['product_id'];
	$price = number_format(($all[(int)$picnum]['price']), 2, '.', ',');
	$all2 = $this->model_catalog_product->getProductSpecials();
	if (!empty($all2)) {
	if ($all2[(int)$picnum]['special'])
	{
	$special2 = $all[(int)$picnum]['special'];
	$special2 = number_format($special2, 2, '.', ',');
	$special2 = "$$special2";
	}
	}
	$price = "$$price";
	$image = $all[(int)$picnum]['image'];
	$image = "image/$image";
	list($width, $height, $type, $attr) = getimagesize($image);
		if($width > 160)
{
$width = 160;
}
if($height > 160)
{
$height = 160;
}
//if ($all2[(int)$picnum]['special'])
if ($special)
{
$link = "<a href='index.php?route=product/product&product_id=$ID'>
<img src='$image' width='$width' height='$height'><br>
$name</a>
	<br><span style='color: #6dcff6; font-weight: bold; text-decoration: line-through;'>$price</span><br><span style='color: #F00;'>$special</span>";
}
else
{
$link = "<a href='index.php?route=product/product&product_id=$ID'>
<img src='$image' width='$width' height='$height'><br>
$name</a>
	<br><span style='color: #6dcff6; font-weight: bold;'>$price</span>";
}
}
$this->data['image'] = $image;
$this->data['ID'] = $ID;
			$this->data['height'] = $height;
			$this->data['width'] = $width;
			$this->data['name'] = $name;
			$this->data['price'] = $price;
			$this->data['link'] = $link;
		$this->id = 'product_display';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/product_display.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/product_display.tpl';
		} else {
			$this->template = 'default/template/module/product_display.tpl';
		}
		$this->render();
	}
public function ajaxDisplay() {

$this->language->load('module/product_display');
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['imagething'] = $this->language->get('imagething');
	
	$this->load->model('catalog/product');
		$type = $this->config->get('product_display_type');
	if ($type=="special")
	{
	$total = $this->model_catalog_product->getTotalProductSpecials();
	$all = $this->model_catalog_product->getProductSpecials();
	}
	elseif ($type=="featured")
	{
	$all = $this->model_catalog_product->getFeaturedProducts(10000);
	$total = count($all);
	}
	elseif ($type=="all")
	{
	$total = $this->model_catalog_product->getTotalProducts();
	$all = $this->model_catalog_product->getProducts();
	}
	$total -=1;
	(int) $picnum = rand(0,$total);
	$special = $this->model_catalog_product->getProductSpecial($all[(int)$picnum]['product_id']);
		$checkspecial = $this->model_catalog_product->getProductSpecial($all[(int)$picnum]['product_id']);	
$all2 = $this->model_catalog_product->getProductSpecials();		
$ID = $all[(int)$picnum]['product_id'];
				if ($special) {
					$special = $this->currency->format($this->tax->calculate($special, $all[(int)$picnum]['tax_class_id'], $this->config->get('config_tax')));
				}
				if (!$all)
				{
				$link="<img src='image/cache/no_image-120x120.jpg'>";
				}
				else
				{
	$name = $all[(int)$picnum]['name'];
	$ID = $all[(int)$picnum]['product_id'];
	$spec2 = $this->model_catalog_product->getProductSpecial($ID);
	$price = number_format(($all[(int)$picnum]['price']), 2, '.', ',');
	if ($special)
	{
	$product = $this->model_catalog_product->getProductSpecial($picnum);
	$special2 = number_format($product, 2, '.', ',');
	$special2 = "$$special2";
	}
	$price = "$$price";
	$image = $all[(int)$picnum]['image'];
	$image = "image/$image";
	list($width, $height, $type, $attr) = getimagesize($image);
		if($width > 160)
{
$width = 160;
}
if($height > 160)
{
$height = 160;
}
if ($special)
{
$link = "<a href='index.php?route=product/product&product_id=$ID'>
<img src='$image' width='$width' height='$height'><br>
$name</a>
	<br><span style='color: #6dcff6; font-weight: bold; text-decoration: line-through;'>$price</span><br><span style='color: #F00;'>$special</span>";
}
else
{
$link = "<a href='index.php?route=product/product&product_id=$ID'>
<img src='$image' width='$width' height='$height'><br>
$name</a>
	<br><span style='color: #6dcff6; font-weight: bold;'>$price</span>";
}
}
			echo $link;
	}
	}
?>
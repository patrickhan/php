<?php 
class ControllerCommonHome extends Controller {
	public function index() {
		$this->language->load('common/home');
		$this->load->model('catalog/information');
		
		$this->document->title = $this->config->get('config_title');
		$this->document->description = $this->config->get('config_meta_description');
		
		$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_store'));
		//$this->data['welcome'] = html_entity_decode($this->config->get('config_welcome_' . $this->config->get('config_language_id')));
		
		$welcome_information = $this->model_catalog_information->getInformation(7);
		$this->data['welcome'] = html_entity_decode($welcome_information['description']);
		//echo $this->config->get('config_prods'); die;
		if ($this->config->get('config_prods') == '1') {
		$this->data['config_prods'] = $this->config->get('config_prods');
		}
		else
		{
		$this->data['config_prods'] = 0;
		}
		if ($this->config->get('config_cats') == '1') {
		$this->data['config_cats'] = $this->config->get('config_cats');
		}
		else
		{
		$this->data['config_cats'] = 0;
		}
		$this->data['text_latest'] = $this->language->get('text_latest');
		$this->data['text_featured'] = $this->language->get('text_featured');
		$this->data['text_specials'] = $this->language->get('text_specials');
		$this->data['text_categories'] = $this->language->get('text_categories');
		
		$this->data['display_featured'] = $this->config->get('config_display_featured');
		$this->data['display_specials'] = $this->config->get('config_display_specials');
		$this->data['display_new'] = $this->config->get('config_display_new');
		
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('catalog/review');
		$this->load->model('tool/seo_url');
		$this->load->model('tool/statistics');
		$this->load->helper('image');
		
		$this->model_tool_statistics->recordPageView();
		
		$this->data['products'] = array();						
		
		$this->data['paction'] = $this->url->http('checkout/cart');
		
		if (isset($this->request->post['template_switch'])){
			
			if($this->request->post['template_switch'] != ""){
				
				//$template = $this->request->post['template_switch'];
				//set it in 1 hour
				setcookie("demo_switch", $this->request->post['template_switch']);
				header('Location: /index.php');
				exit();
			}
			
		}
		
		$this->load->model('catalog/header_image');
		$template = $this->model_catalog_header_image->getTemplate($this->config->get('config_template'));
		if(isset($template)){
			$this->session->data['template'] = $this->config->get('config_template');
		}
		
		
		if(isset($_COOKIE["demo_switch"])){
			//$template = $_COOKIE["template_switch"];
			$this->session->data['template'] = $_COOKIE["demo_switch"];
			$template = $this->model_catalog_header_image->getTemplate($_COOKIE["demo_switch"]);	
			//print_r($template);
		}
		
		//print_r($_COOKIE["demo_switch"]);
		
		
		//exit();
		
		if ($this->config->get['config_prods']) {
		$this->data['proddisplay'] = $this->config->get['config_prods'];
		}
		else
		{
		$this->data['proddisplay'] = 0;
		}
		
		
		
		foreach ($this->model_catalog_product->getLatestProducts(30) as $result) {
			if (($result['image']) && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $result['image'];
			} else {
				$image = 'no_image.jpg';
			}
			
			$rating = $this->model_catalog_review->getAverageRating($result['product_id']);
			
			$special = FALSE;
			
			$discount = $this->model_catalog_product->getProductDiscount($result['product_id']);
			
			if ($discount) {
				$price = $this->currency->format($this->tax->calculate($discount, $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				
				$special = $this->model_catalog_product->getProductSpecial($result['product_id']);
				
				if ($special) {
					$special = $this->currency->format($this->tax->calculate($special, $result['tax_class_id'], $this->config->get('config_tax')));
				}
			}
			
			if($this->session->data['template'] == 'softfoot' && $this->config->get('demo')){
				$image = str_replace(".png","",$image);
				if($result['product_id'] > 14 && $result['product_id'] < 19){
					$this->data['products'][] = array(
						'product_id'    => $result['product_id'],
						'name'    => $result['name'],
						'model'   => $result['model'],
						'rating'  => $rating,
						'stars'   => sprintf($this->language->get('text_stars'), $rating),
						//'thumb'   => image_resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
						'thumb' => "/image/cache/".$image."-120x120.png",
						'price'   => $price,
						'special' => $special,
						'href'    => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id']))
					);
				}
				
			
			}else if($this->session->data['template'] == 'rainfresh' && $this->config->get('demo')){
				if($result['product_id'] > 9 && $result['product_id'] < 15){
					$this->data['products'][] = array(
					'product_id'    => $result['product_id'],
						'name'    => $result['name'],
						'model'   => $result['model'],
						'rating'  => $rating,
						'stars'   => sprintf($this->language->get('text_stars'), $rating),
						'thumb'   => image_resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
						'price'   => $price,
						'special' => $special,
						'href'    => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id']))
					);
				}
				
				
			}else	if($this->session->data['template'] == 'mystique' && $this->config->get('demo')){
				//print_r($result['product_id']);
				if($result['product_id'] > 4 && $result['product_id'] < 10){
					$this->data['products'][] = array(
					'product_id'    => $result['product_id'],
						'name'    => $result['name'],
						'model'   => $result['model'],
						'rating'  => $rating,
						'stars'   => sprintf($this->language->get('text_stars'), $rating),
						'thumb'   => image_resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
						'price'   => $price,
						'special' => $special,
						'href'    => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id']))
					);
				}
			}else{
			$desc = strip_tags(html_entity_decode($result['description']));
			
			if (strlen($desc) < 150) {

			}
			else
			{
			$pos = strpos($desc, '.', 150);
			$pos++;
		$desc = substr($desc, 0, $pos);
		}
				$this->data['products'][] = array(
				'product_id'    => $result['product_id'],
					'name'    => $result['name'],
					'desc'    => $desc,
					'model'   => $result['model'],
					'rating'  => $rating,
					'stars'   => sprintf($this->language->get('text_stars'), $rating),
					'thumb'   => image_resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
					'price'   => $price,
					'special' => $special,
					'href'    => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id']))
				);
			}
			
		}
		
		$this->data['featured'] = array();
		
		foreach ($this->model_catalog_product->getFeaturedProducts(30) as $result) {
		
			if (($result['image']) && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $result['image'];
			} else {
				$image = 'no_image.jpg';
			}
			
			$rating = $this->model_catalog_review->getAverageRating($result['product_id']);
			
			$special = $this->model_catalog_product->getProductSpecial($result['product_id']);
			
			if ($special) {
				$special = $this->currency->format($this->tax->calculate($special, $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = FALSE;
			}
			
			if($this->session->data['template'] == 'softfoot' && $this->config->get('demo')){
				if($result['product_id'] > 14 && $result['product_id'] < 19){
					$image = str_replace(".png","",$image);
					
					$this->data['featured'][] = array(
					'product_id'    => $result['product_id'],
						'name'    => $result['name'],
						'model'   => $result['model'],
						'rating'  => $rating,
						'stars'   => sprintf($this->language->get('text_stars'), $rating),
						//'thumb'   => image_resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
						'thumb' => "/image/cache/".$image."-120x120.png",
						'price'   => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
						'special' => $special,
						'href'    => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id']))
					);
				}
			}else if($this->session->data['template'] == 'rainfresh' && $this->config->get('demo')){
				if($result['product_id'] > 9 && $result['product_id'] < 15){
					$this->data['featured'][] = array(
					'product_id'    => $result['product_id'],
						'name'    => $result['name'],
						'model'   => $result['model'],
						'rating'  => $rating,
						'stars'   => sprintf($this->language->get('text_stars'), $rating),
						'thumb'   => image_resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
						'price'   => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
						'special' => $special,
						'href'    => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id']))
					);
				}
			}else if($this->session->data['template'] == 'mystique' && $this->config->get('demo')){
				if($result['product_id'] > 4 && $result['product_id'] < 10){
					$this->data['featured'][] = array(
					'product_id'    => $result['product_id'],
						'name'    => $result['name'],
						'model'   => $result['model'],
						'rating'  => $rating,
						'stars'   => sprintf($this->language->get('text_stars'), $rating),
						'thumb'   => image_resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
						'price'   => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
						'special' => $special,
						'href'    => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id']))
					);
				}
			}else if($this->session->data['template'] == 'technical' && $this->config->get('demo')){
				if($result['product_id'] < 5){
					$this->data['featured'][] = array(
					'product_id'    => $result['product_id'],
						'name'    => $result['name'],
						'model'   => $result['model'],
						'rating'  => $rating,
						'stars'   => sprintf($this->language->get('text_stars'), $rating),
						'thumb'   => image_resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
						'price'   => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
						'special' => $special,
						'href'    => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id']))
					);
				}
			}else{
				$this->data['featured'][] = array(
				'product_id'    => $result['product_id'],
					'name'    => $result['name'],
					'model'   => $result['model'],
					'rating'  => $rating,
					'stars'   => sprintf($this->language->get('text_stars'), $rating),
					'thumb'   => image_resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
					'price'   => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
					'special' => $special,
					'href'    => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id']))
				);
			}
		}		
		
		$this->data['specials'] = array();
		
		foreach ($this->model_catalog_product->getProductSpecials('pd.name', 'ASC', 0, 8) as $result) {
			if (($result['image']) && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $result['image'];
			} else {
				$image = 'no_image.jpg';
			}
			
			$rating = $this->model_catalog_review->getAverageRating($result['product_id']);
			
			$special = $this->model_catalog_product->getProductSpecial($result['product_id']);
			
			if ($special) {
				$special = $this->currency->format($this->tax->calculate($special, $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = FALSE;
			}
			
			
			$this->data['specials'][] = array(
			'product_id'    => $result['product_id'],
				'name'    => $result['name'],
				'model'   => $result['model'],
				'rating'  => $rating,
				'stars'   => sprintf($this->language->get('text_stars'), $rating),
				'thumb'   => image_resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
				'price'   => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
				'special' => $special,
				'href'    => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id']))
			);
		}
		
		$this->data['categories'] = array();
		
		foreach ($this->model_catalog_category->getCategories() as $result) {
			
			if (($result['image']) && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $result['image'];
			} else {
				$image = 'no_image.jpg';
			}
			
			if($this->session->data['template'] == 'softfoot' && $this->config->get('demo')){
				if($result['category_id'] > 9 && $result['category_id'] < 12){
					$image = str_replace(".png","",$image);
					//print_r($image);
					$this->data['categories'][] = array(
					'category_id'    => $result['category_id'],
						'name'  => $result['name'],
						'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/category&path=' . $result['category_id'])),
						//'thumb' => image_resize($image, $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'))
						'thumb' => "/image/cache/".$image."-120x120.png"
					);
				}			
			}else if($this->session->data['template'] == 'rainfresh' && $this->config->get('demo')){
				if($result['category_id'] > 6 && $result['category_id'] < 10){
					
					$this->data['categories'][] = array(
					'category_id'    => $result['category_id'],
						'name'  => $result['name'],
						'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/category&path=' . $result['category_id'])),
						'thumb' => image_resize($image, $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'))
					);
				}
			}else{
				$this->data['categories'][] = array(
				'category_id'    => $result['category_id'],
					'name'  => $result['name'],
					'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/category&path=' . $result['category_id'])),
					'thumb' => image_resize($image, $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'))
				);
			}
		}
		
		if ( ! $this->config->get('config_customer_price')) {
			$this->data['display_price'] = TRUE;
		} elseif ($this->customer->isLogged()) {
			$this->data['display_price'] = TRUE;
		} else {
			$this->data['display_price'] = FALSE;
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/home.tpl';
		} else {
			$this->template = 'default/template/common/home.tpl';
		}
		
		if(isset($_COOKIE["demo_switch"])){
			$this->template = $_COOKIE["demo_switch"] . '/template/common/home.tpl';
		}
		
		$this->children = array(
			'common/header',
			'common/footer',
			'common/column_left',
			'common/column_right'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
}
?>
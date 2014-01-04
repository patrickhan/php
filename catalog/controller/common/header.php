<?php   
class ControllerCommonHeader extends Controller {
	protected function index() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['language_code'])) {
			$this->session->data['language'] = $this->request->post['language_code'];

			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect']);
			} else {
				$this->redirect($this->url->http('common/home'));
			}
		}
		
		if($this->config->get('demo')){
			$this->data['demo'] = 1;
		}
		
		/////this one is for jatech theme
		
		if ($handle = opendir($_SERVER['DOCUMENT_ROOT'].'/catalog/view/theme/')){
			//echo "Directory:".$handle."";	
			//echo "";
			$all_templates = '';
			while (false !== ($file = readdir($handle))){
				
				if($file != '.' && $file != '..' && $file != 'default'&& $file != 'not_display'){
					$all_templates[] = $file;
				}

				//echo($file);
				/*
				if ($file != "." && $file != ".."){
					$dir = "C:/Documents and Settings/m2/Desktop/testing/".$file;
					$dir2 = $file;
					echo "".$dir2."";
					echo "
					";
					
					// Open folder inside directory, and proceed to read its contents
					
					if ($dh = opendir($dir)){
						while (false !== ($files = readdir($dh)))
						{
							if ($files != "." && $files != ".."){
								echo "";
								echo "$files";
								echo "";
							}
						}
							closedir($dh);
					}
				
				}
				*/
			}
			
			$this->data['all_templates'] = $all_templates;
			closedir($handle);
		}
		
		
		$this->load->model('catalog/header_image');
		$template = $this->model_catalog_header_image->getTemplate($this->config->get('config_template'));
		
		if(!isset($_COOKIE["demo_switch"])){
			$this->session->data['template'] = $this->config->get('config_template');
		}
		
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['currency_code'])) {
			$this->currency->set($this->request->post['currency_code']);
			
			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect']);
			} else {
				$this->redirect($this->url->http('common/home'));
			}
		}
		
		$this->load->model('account/customer');
		$this->load->model('catalog/information');
		$this->load->model('account/whos_online');
		if ($this->customer->isLogged()) {
			$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
			$online_id = $customer_info['customer_id'];
			$online_name = $customer_info['firstname'] . ' ' . $customer_info['lastname'];
			$online_ip = $customer_info['ip'];
		} else {
			$online_id = '0';
			$online_name = 'Guest';
			$online_ip = $_SERVER['REMOTE_ADDR'];
		}
		
		$online_url = $_SERVER['REQUEST_URI'];
		$this->model_account_whos_online->updateWhosOnline($online_id, $online_name, $online_ip, $online_url, session_id());
		//echo $this->document->title; die;
		$this->language->load('common/header');
		if (isset($this->request->get['route']))
		{
		if ($this->request->get['route'] == 'common/home')
		{
		$welcome_information = $this->model_catalog_information->getInformation(1);
		if ($welcome_information['meta_keywords'] != '') {
		$this->document->keywords = $welcome_information['meta_keywords'];
		}
		if ($welcome_information['meta_description'] != '') {
		$this->document->description = $welcome_information['meta_description'];
		}
		if ($welcome_information['title_tag'] != '') {
		$this->document->title = $welcome_information['title_tag'];
		}
		}
		}
		else
		{
		$welcome_information = $this->model_catalog_information->getInformation(1);
		if ($welcome_information['meta_keywords'] != '') {
		$this->document->keywords = $welcome_information['meta_keywords'];
		}
		if ($welcome_information['meta_description'] != '') {
		$this->document->description = $welcome_information['meta_description'];
		}
		if ($welcome_information['title_tag'] != '') {
		$this->document->title = $welcome_information['title_tag'];
		}
		}
		$this->data['keywords'] = $this->document->keywords;
		$this->data['title'] = $this->document->title;
		$this->data['description'] = $this->document->description;
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}
		
		$this->data['charset'] = $this->language->get('charset');
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
		$this->data['links'] = $this->document->links;	
		$this->data['styles'] = $this->document->styles;
		$this->data['scripts'] = $this->document->scripts;		
		$this->data['breadcrumbs'] = $this->document->breadcrumbs;
		$this->data['icon'] = $this->config->get('config_icon');
		
		$this->data['store'] = $this->config->get('config_store');
		
		if (isset($this->request->server['HTTPS']) && ($this->request->server['HTTPS'] == 'on')) {
			$this->data['logo'] = HTTPS_IMAGE . $this->config->get('config_logo');
		} else {
			$this->data['logo'] = HTTP_IMAGE . $this->config->get('config_logo');
		}
		
		$this->data['text_home'] = $this->language->get('text_home');
		$this->data['text_special'] = $this->language->get('text_special');
		$this->data['text_contact'] = $this->language->get('text_contact');
		$this->data['text_sitemap'] = $this->language->get('text_sitemap');
		$this->data['text_account'] = $this->language->get('text_account');
		$this->data['text_login'] = $this->language->get('text_login');
		$this->data['text_logout'] = $this->language->get('text_logout');
		$this->data['text_cart'] = $this->language->get('text_cart'); 
		$this->data['text_checkout'] = $this->language->get('text_checkout');
		$this->data['text_checkout'] = $this->language->get('text_checkout');
		$this->data['text_keyword'] = $this->language->get('text_keyword');
		$this->data['text_category'] = $this->language->get('text_category');
		$this->data['text_advanced'] = $this->language->get('text_advanced');
		$this->data['text_gallery'] = $this->language->get('text_gallery');
		
		$this->data['entry_search'] = $this->language->get('entry_search');
		
		$this->data['button_go'] = $this->language->get('button_go');
		
		$this->data['home'] = $this->url->http('common/home');
		$this->data['special'] = $this->url->http('product/special');
		$this->data['contact'] = $this->url->http('information/contact');
		$this->data['sitemap'] = $this->url->http('information/sitemap');
		$this->data['account'] = $this->url->https('account/account');
		$this->data['logged'] = $this->customer->isLogged();
		$this->data['login'] = $this->url->https('account/login');
		$this->data['logout'] = $this->url->http('account/logout');
		$this->data['cart'] = $this->url->http('checkout/cart');
		//$this->data['checkout'] = $this->url->https('checkout/shipping');
		$this->data['checkout'] = $this->url->https('checkout/onepage');
		
		if (isset($this->request->get['keyword'])) {
			$this->data['keyword'] = $this->request->get['keyword'];
		} else {
			$this->data['keyword'] = '';
		}
		
		if (isset($this->request->get['category_id'])) {
			$this->data['category_id'] = $this->request->get['category_id'];
		} elseif (isset($this->request->get['path'])) {
			$path = explode('_', $this->request->get['path']);
		
			$this->data['category_id'] = end($path);
		} else {
			$this->data['category_id'] = '';
		}
		
		$this->data['advanced'] = $this->url->http('product/search');
		
		$this->load->model('catalog/category');
		
		
		
		$this->data['action'] = $this->url->http('common/home');
		
		if ( ! isset($this->request->get['route'])) {
			$this->data['redirect'] = $this->url->http('common/home');
		} else {
			$this->load->model('tool/seo_url');
			
			$data = $this->request->get;
			
			unset($data['_route_']);
			
			$route = $data['route'];
			
			unset($data['route']);
			
			$url = '';
			
			if ($data) {
				$url = '&' . urldecode(http_build_query($data));
			}
			
			$this->data['redirect'] = $this->model_tool_seo_url->rewrite($this->url->http($route . $url));
		}
		
		$this->data['language_code'] = $this->session->data['language'];	
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = array();
		
		$results = $this->model_localisation_language->getLanguages();
		
		foreach ($results as $result) {
			if ($result['status']) {
				$this->data['languages'][] = array(
					'name'  => $result['name'],
					'code'  => $result['code'],
					'image' => $result['image']
				);
			}
		}
		
		$this->data['currency_code'] = $this->currency->getCode(); 
		
		$this->load->model('localisation/currency');
		
		$this->data['currencies'] = array();
		
		$results = $this->model_localisation_currency->getCurrencies();
		
		foreach ($results as $result) {
			if ($result['status']) {
				$this->data['currencies'][] = array(
					'title' => $result['title'],
					'code'  => $result['code']
				);
			}
		}
		
		$this->load->model('catalog/information');
		
		$logo_information = $this->model_catalog_information->getInformation(8);
		$this->data['logo_text'] = html_entity_decode($logo_information['description']);
		//$this->data['logo_text'] = "";
		
		$header_information = $this->model_catalog_information->getInformation(9);
		$this->data['header_text'] = html_entity_decode($header_information['description']);	
		
		$header_information = $this->model_catalog_information->getInformation(12);
		$this->data['header_text2'] = html_entity_decode($header_information['description']);	
		
		
		
		$all_categories = $this->getCategories(0);
		
		$this->data['categories'] = $this->getCategories(0);
		
		if($this->session->data['template'] == 'softfoot' && $this->config->get('demo')){
			foreach($all_categories as $all_categories_key){
				if($all_categories_key['category_id'] > 9 && $all_categories_key['category_id'] < 12){
					$new_categories[] = $all_categories_key;
				}
			}
			$this->data['categories'] = $new_categories;
		}
		
		
		//print_r($this->session->data);
		$headers = $this->model_catalog_header_image->getEnabledHeaders();
		$enabledheaders = $this->model_catalog_header_image->getEnabledHeaders();
		$mode = $this->config->get('display_type');
		$num = count($enabledheaders);
		$enhanced = false;
		$slider = false;
		$logo = "";
		$fade = false;
		if (($num > 0) && (!empty($template))) {
			if ($mode=="one") {
				$num = 0;
				$filename = "image/" . $headers[$num]['image'];
				list($width, $height, $type, $attr) = getimagesize($filename);
				$mheight = $template['header_height'];

				if ($height > $mheight) {
					$height = $mheight;
				}
				
				$this->data['src'] = 'image/' . $headers[$num]['image'];
				$this->data['width'] = $template['header_width'];
				$mwidth = $template['header_width'];
				if ($width > $mwidth) {
					$width = $mwidth;
				}
				$logo = "<img height=$height width=$width src='image/" . $headers[$num]['image'] . "' alt='" . $headers[$num]['image'] . "' />";
			} elseif ($mode=="refresh") {
				$num = rand(0,$num-1);
				$filename = "image/" . $headers[$num]['image'];
				list($width, $height, $type, $attr) = getimagesize($filename);
				$mheight = $template['header_height'];
				if ($height > $mheight) {
					$height = $mheight;
				}
				
				$this->data['src'] = 'image/' . $headers[$num]['image'];
				$this->data['width'] = $template['header_width'];
				$mwidth = $template['header_width'];
				if ($width > $mwidth) {
					$width = $mwidth;
				}
				$logo = "<img height=$height width=$width src='image/" . $headers[$num]['image'] . "' alt='Test' />";
			} elseif ($mode=="slider") {
				$slider = true;
				$logo = "<ul>";
				for ($number = 0; $number < $num; $number++) {
					$filename = "image/" . $headers[$number]['image'];
					list($width, $height, $type, $attr) = getimagesize($filename);
					$mheight = $template['header_height'];
					if ($height > $mheight) {
						$height = $mheight;
					}
					$mwidth = $template['header_width'];
					if ($width > $mwidth) {
						$width = $mwidth;
					}
					$logo .= "<li><img height=$height width=$width src='image/" . $headers[$number]['image'] . "' alt='Test' /></li>";
				}
				$logo .= "</ul>";
			} elseif ($mode=="fade") {
			$images = array();
				for ($number = 0; $number < $num; $number++) {
				$images[$number] = "image/" . $headers[$number]['image'];
				$text[$number] = $headers[$number]['url'];
				list($width, $height, $type, $attr) = getimagesize($images[$number]);
				$mheight = $template['header_height'];
				if ($height > $mheight) {
					$height = $mheight;
				}
				$mwidth = $template['header_width'];
				if ($width > $mwidth) {
					$width = $mwidth;
				}
				$this->data['images'] = $images;
				$this->data['text'] = $text;
				$this->data['width'] = $width;
				$this->data['height'] = $height;
				
				$fade = true;
				$this->data['fade'] = $fade;
				$logo = '';
			}
			}
			elseif ($mode=="eslider") {
				$enhanced = true;
			}
		}
		$sec = $this->config->get('sec');
		if ($sec == "")
		{
		$sec = 5000;
		}
		$this->data['sec'] = $sec;
		$this->data['enhanced'] = $enhanced;
		$this->data['slider'] = $slider;
		$this->data['logo'] = $logo;
		$this->data['fade'] = $fade;
		
		$this->children[] = 'common/menu';
		
		$this->id = 'header';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/header.tpl';
		} else {
			$this->template = 'default/template/common/header.tpl';
		}
		
		if(isset($_COOKIE["demo_switch"])){
			$this->template = $_COOKIE["demo_switch"] . '/template/common/header.tpl';
		}
		
		$this->render();
	}
	
	private function getCategories($parent_id, $level = 0) {
		$level++;
		
		$data = array();
		
		$results = $this->model_catalog_category->getCategories($parent_id);
		
		
		foreach ($results as $result) {
			if($this->session->data['template'] == 'softfoot'){
				//print_r($result['category_id']); 
				if($result['category_id'] > 9 && $result['category_id'] < 12){
					$data[] = array(
						'category_id' => $result['category_id'],
						'name'        => str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) . $result['name']
					);
				}
			}else if($this->session->data['template'] == 'mystique'){
				//print_r($result['category_id']); 
				if($result['category_id'] > 3 && $result['category_id'] < 7){
					$data[] = array(
						'category_id' => $result['category_id'],
						'name'        => str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) . $result['name']
					);
				}
			}else{				
				$data[] = array(
					'category_id' => $result['category_id'],
					'name'        => str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) . $result['name']
				);
			}
			
			$children = $this->getCategories($result['category_id'], $level);
			
			if ($children) {
			  $data = array_merge($data, $children);
			}
		}
		
		return $data;
	}
}
?>
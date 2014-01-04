<?php  
class ControllerModulePdfcatalog extends Controller {
	protected function index() {
		$this->language->load('module/pdf_catalog');	
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_all_categories'] = $this->language->get('text_all_categories');
		
		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/pdf_catalog');
		$this->load->model('tool/seo_url'); 
		 
		$this->data['pdf_catalog_href'] = $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/pdf_catalog&category_id=');;
		$categories = $this->model_catalog_pdf_catalog->getCategories(0);
		
		$this->data['categories']= $categories;
		
		
		if($this->session->data['template'] == 'intellidrives' && $this->config->get('demo')){
			foreach($categories as $categories_key){
				if($categories_key['category_id'] < 4){
					$new_categories[] = $categories_key;
				}
			}
			$this->data['categories']= $new_categories;
		}
		
		if($this->session->data['template'] == 'headset' && $this->config->get('demo')){
			foreach($categories as $categories_key){
				if($categories_key['category_id'] > 3 && $categories_key['category_id'] < 7){
					$new_categories[] = $categories_key;
				}
			}
			$this->data['categories']= $new_categories;
		}
		
		if($this->session->data['template'] == 'rainfresh' && $this->config->get('demo')){
			foreach($categories as $categories_key){
				if($categories_key['category_id'] > 6 && $categories_key['category_id'] < 10){
					$new_categories[] = $categories_key;
				}
			}
			$this->data['categories']= $new_categories;
		}
		
		if($this->session->data['template'] == 'tosh' && $this->config->get('demo')){
			foreach($categories as $categories_key){
				if($categories_key['category_id'] > 9 && $categories_key['category_id'] < 12){
					$new_categories[] = $categories_key;
				}
			}
			$this->data['categories']= $new_categories;
		}
		
		$this->id = 'pdf_catalog';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/pdf_catalog.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/pdf_catalog.tpl';
		} else {
			$this->template = 'default/template/module/pdf_catalog.tpl';
		}
		
		$this->render(); 
	}
}
?>
<?php  
class ControllerModuleInformation extends Controller {
	protected function index() {
		$this->language->load('module/information');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
    	
		$this->data['text_contact'] = $this->language->get('text_contact');
    	$this->data['text_sitemap'] = $this->language->get('text_sitemap');
    	$this->data['text_resources'] = $this->language->get('text_resources');
    	$this->data['text_printable'] = $this->language->get('text_printable');
		
		$this->load->model('catalog/information');
		
		$this->data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
      		$this->data['informations'][] = array(
        		'title' => $result['title'],
	    		'href'  => $this->model_tool_seo_url->rewrite($this->url->http('information/information&information_id=' . $result['information_id']))
      		);
    	}

		$this->data['contact'] = $this->url->http('information/contact');
		$this->data['display_sitemap'] = ($this->config->get('information_display_sitemap') == '1') ? TRUE : FALSE;
		$this->data['sitemap'] = $this->url->http('information/sitemap');
    	$this->data['resources'] = $this->url->http('information/links');
		$this->data['display_printable'] = ($this->config->get('information_display_printable_catalog') == '1') ? TRUE : FALSE;
		$this->data['printable'] = $this->url->http('product/printable');
		
		
		$this->id = 'information';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/information.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/information.tpl';
		} else {
			$this->template = 'default/template/module/information.tpl';
		}
		
		$this->render();
	}
}
?>
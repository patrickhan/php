<?php
class ControllerInformationLinks extends Controller {
	public function index() {
		$this->language->load('information/links');
		
		$this->document->title = $this->language->get('heading_title'); 
		
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('information/links'),
			'text'      => $this->language->get('heading_title'),
			'separator' => $this->language->get('text_separator')
		);
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->load->model('information/links');
		
		$this->data['links'] = array();
		
		$results = $this->model_information_links->getLinks();
		
		foreach ($results as $result) {
			$this->data['links'][] = array(
				'description'     => $result['description'],
				'title'     => $result['title'],
				'url'     => $result['url'],
			);
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/links.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/information/links.tpl';
		} else {
			$this->template = 'default/template/information/links.tpl';
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
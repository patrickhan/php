<?php
class ControllerModuleSearch extends Controller {
	protected function index() {
		$this->language->load('module/search');
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_under_50'] = $this->language->get('text_under_50');
		$this->data['text_50_75'] = $this->language->get('text_50_75');
		$this->data['text_75_100'] = $this->language->get('text_75_100');
		$this->data['text_100_250'] = $this->language->get('text_100_250');
		$this->data['text_over_250'] = $this->language->get('text_over_250');
		
		$this->data['link_under_50'] = $this->url->http('product/search&upper=50&category_id=0');
		$this->data['link_50_75'] = $this->url->http('product/search&lower=50&upper=75&category_id=0');
		$this->data['link_75_100'] = $this->url->http('product/search&lower=75&upper=100&category_id=0');
		$this->data['link_100_250'] = $this->url->http('product/search&lower=100&upper=250&category_id=0');
		$this->data['link_over_250'] = $this->url->http('product/search&lower=250&category_id=0');
		
	
		$this->id = 'search';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/search.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/search.tpl';
		} else {
			$this->template = 'default/template/module/search.tpl';
		}
		
		$this->render();
	}
}
?>
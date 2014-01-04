<?php
class ControllerModuleQuoteRequest extends Controller {
	protected function index() {
		$this->language->load('module/quote_request');
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->load->model('information/quote_request');
		
		$this->data['quoteurl'] = "<a href='index.php?route=information/quote_request'>Simple</a>";
		$this->data['quoteurl2'] = "<a href='index.php?route=information/quote_request2'>Detailed</a>";
		
		$this->id = 'quote_request';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/quote_request.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/quote_request.tpl';
		} else {
			$this->template = 'default/template/module/quote_request.tpl';
		}
		
		//$this->render();
	}
}
?>
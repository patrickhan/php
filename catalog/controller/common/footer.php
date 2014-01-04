<?php
class ControllerCommonFooter extends Controller {
	protected function index() {
		$this->language->load('common/footer');
		
		$this->data['text_powered_by'] = sprintf($this->language->get('text_powered_by'), $this->config->get('config_store'), date('Y', time()));
		
		$this->id = 'footer';
		$this->load->model('catalog/footer');
		
		$this->data['links'] = array();
		
		$this->data['footer_in_red_demo'] = "These are sample designs for Demo purposes. You will be able to select from 1000's of designs or have a custom design built for your site.";
		
		foreach ($this->model_catalog_footer->getFooterLinks() as $result) {
			$this->data['links'][] = array(
				'title' => $result['title'],
				'href'  => $this->model_tool_seo_url->rewrite($this->url->http('information/information&information_id=' . $result['information_id']))
			);
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/footer.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/footer.tpl';
		} else {
			$this->template = 'default/template/common/footer.tpl';
		}
		
		if(isset($_COOKIE["demo_switch"])){
			$this->template = $_COOKIE["demo_switch"] . '/template/common/footer.tpl';
		}
		
		if ($this->config->get('google_analytics_status')) {
			$this->data['google_analytics'] = html_entity_decode($this->config->get('google_analytics_code'));
		} else {
			$this->data['google_analytics'] = '';
		}
		
		$this->render();
	}
}
?>
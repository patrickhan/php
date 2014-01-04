<?php
class ControllerCommonColumnRight extends Controller {
	protected function index() {
		$module_data = array();
		
		$this->load->model('checkout/extension');
		
		$results = $this->model_checkout_extension->getExtensions('module');
		
		foreach ($results as $result) {
			if ($this->config->get($result['key'] . '_status') && ($this->config->get($result['key'] . '_position') == 'right')) {
				$module_data[] = array(
					'code'       => $result['key'],
					'sort_order' => $this->config->get($result['key'] . '_sort_order')
				);
				
				$this->children[] = 'module/' . $result['key'];
			}
		}
		
		$sort_order = array(); 
	
		foreach ($module_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
		}
		
		array_multisort($sort_order, SORT_ASC, $module_data);
		
		$this->data['modules'] = $module_data;
		
		$route = (!empty($this->request->get['route'])) ? $this->request->get['route'] : 'common/home';
		if ($banner = $this->model_checkout_extension->getPageBanner(2, $route)) {
			$this->data['banner'] = $banner[0];
			$this->model_checkout_extension->recordBannerView($banner[0]['banner_id']);
		} else if ($banner = $this->model_checkout_extension->getRandomBanner(2)) {
			$this->data['banner'] = $banner[0];
			$this->model_checkout_extension->recordBannerView($banner[0]['banner_id']);
		} else {
			$this->data['banner'] = '';
		}
		
		$this->id = 'column_right';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/column_right.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/column_right.tpl';
		} else {
			$this->template = 'default/template/common/column_right.tpl';
		}
		
		if(isset($_COOKIE["demo_switch"])){
			$this->template = $_COOKIE["demo_switch"] . '/template/common/column_right.tpl';
		}
		
		$this->render();
	}
}
?>
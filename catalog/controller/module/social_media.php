<?php
class ControllerModuleSocialMedia extends Controller {
	protected function index() {
		$this->language->load('module/social_media');
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_facebook'] = $this->language->get('text_facebook');
		$this->data['text_twitter'] = $this->language->get('text_twitter');
		$this->data['text_my_space'] = $this->language->get('text_my_space');
		$this->data['text_linked_in'] = $this->language->get('text_linked_in');
		
		$this->load->model('information/social_media');
		
		$this->data['media'] = array();
		
		foreach ($this->model_information_social_media->getSocialMediaLinks() as $result) {
			$this->data['media'][] = array(
				'setting_id' => $result['setting_id'],
				'key' => $result['key'],
				'href' => $result['value'],
			);
	}
		
		$this->id = 'social_media';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/social_media.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/social_media.tpl';
		} else {
			$this->template = 'default/template/module/social_media.tpl';
		}
		
		$this->render();
	}
}
?>
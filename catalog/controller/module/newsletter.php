<?php  
class ControllerModuleNewsletter extends Controller {
	protected function index() {
		$this->language->load('module/newsletter');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
    	
		$this->data['text_signup'] = $this->language->get('text_signup');
		$this->data['text_email_address'] = $this->language->get('text_email_address');
		$this->data['text_email_success'] = $this->language->get('text_email_success');
		$this->data['text_email_error'] = $this->language->get('text_email_error');
		$this->data['button_signup'] = $this->language->get('button_signup');
		
		$this->id = 'newsletter';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/newsletter.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/newsletter.tpl';
		} else {
			$this->template = 'default/template/module/newsletter.tpl';
		}
		
		$this->render();
	}
	
	public function addEmail() {
		$this->load->model('catalog/newsletter');
		
		$pattern = '/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i';

    	if (preg_match($pattern, $this->request->post['newsletter_email'])) {
      		if ($this->model_catalog_newsletter->addEmail($this->request->post['newsletter_email'])) {
				die('true');
			} else {
				die('false');
			}
    	} else {
			die('false');
		}
	}
}
?>
<?php
class ControllerAccountNewsletter extends Controller {
	public function index() {
		if ( ! $this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->https('account/newsletter');
			
			$this->redirect($this->url->https('account/login'));
		}
		
		$this->language->load('account/newsletter');
		
		$this->document->title = $this->language->get('heading_title');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->load->model('account/customer');
			
			$this->model_account_customer->editNewsletter($this->request->post['newsletter']);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->http('account/account'));
		}
		
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		); 
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('account/account'),
			'text'      => $this->language->get('text_account'),
			'separator' => $this->language->get('text_separator')
		);
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('account/newsletter'),
			'text'      => $this->language->get('text_newsletter'),
			'separator' => $this->language->get('text_separator')
		);
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');
		
		$this->data['action'] = $this->url->https('account/newsletter');
		
		$this->data['newsletter'] = $this->customer->getNewsletter();
		
		$this->data['back'] = $this->url->https('account/account');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/newsletter.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/newsletter.tpl';
		} else {
			$this->template = 'default/template/account/newsletter.tpl';
		}
		
		$this->children = array(
			'common/header',
			'common/footer',
			'common/column_left',
			'common/column_right'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));			
	}
	
	public function unsubscribe() {
		if ($this->customer->isLogged()) {
			$this->redirect($this->url->https('account/newsletter'));
		}
		
		$this->language->load('account/newsletter');
		
		$this->document->title = $this->language->get('heading_title');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->load->model('account/customer');
			
			$this->model_account_customer->deleteNewsletterSignup($this->request->post['email']);
			
			$this->session->data['success'] = $this->language->get('text_success_removed');
			
			$this->redirect($this->url->http('account/newsletter/unsubscribe'));
		}
		
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_email'] = $this->language->get('entry_email');
		
		$this->data['button_unsubscribe'] = $this->language->get('button_unsubscribe');
		
		$this->data['action'] = $this->url->https('account/newsletter/unsubscribe');
		
		$this->data['back'] = $this->url->https('account/account');
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/unsubscribe.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/unsubscribe.tpl';
		} else {
			$this->template = 'default/template/account/unsubscribe.tpl';
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
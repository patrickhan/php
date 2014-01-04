<?php 
class ControllerInformationContact extends Controller {
	private $error = array(); 
	
	public function filter($string) {
	$censored = $string;
	if ($this->config->get('config_contact_filter')) {
	include('admin/filter/wordlist-regex.php');
    include('admin/filter/censor.function.php');
    $censored = censorString($string, $badwords); 
	$censored = $censored['clean'];
	}
	return $censored;
	}
	
	public function index() {
		$this->language->load('information/contact');
		
		$this->load->model('catalog/information');
		
		$this->document->title = $this->language->get('heading_title');  
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), html_entity_decode($this->config->get('config_smtp_password')), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout'));
			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->request->post['email']);
			$mail->setSender(html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(sprintf($this->language->get('email_subject'), $this->request->post['name']));
			
			$info = "Name: " . $this->request->post['name'] . "\nEmail: " . $this->request->post['email'] . "\nPhone Number: " . $this->request->post['phone'] . "\nContact By: " . $this->request->post['contact'] . "\nBest Time To Call: " . $this->request->post['calltime'] . "\n\n" . $this->filter($this->request->post['enquiry']);
			
			$mail->setText(strip_tags(html_entity_decode($info, ENT_QUOTES, 'UTF-8')));
			$mail->send();
			
			$this->redirect($this->url->https('information/contact/success'));
		}
		
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('information/contact'),
			'text'      => $this->language->get('heading_title'),
			'separator' => $this->language->get('text_separator')
		);
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_mail'] = $this->language->get('text_mail');
		$this->data['text_address'] = $this->language->get('text_address');
		$this->data['text_telephone'] = $this->language->get('text_telephone');
		$this->data['text_fax'] = $this->language->get('text_fax');
		$this->data['text_email'] = $this->language->get('text_email');
		
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_enquiry'] = $this->language->get('entry_enquiry');
		$this->data['entry_captcha'] = $this->language->get('entry_captcha');
		
		$contact_information = $this->model_catalog_information->getInformation(6);
		
		$this->data['description'] = html_entity_decode($contact_information['description']);
		
		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}
		
		if (isset($this->error['phone'])) {
			$this->data['error_phone'] = $this->error['phone'];
		} else {
			$this->data['error_phone'] = '';
		}
		
		if (isset($this->error['calltime'])) {
			$this->data['error_calltime'] = $this->error['calltime'];
		} else {
			$this->data['error_calltime'] = '';
		}
		
		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
		
		if (isset($this->error['enquiry'])) {
			$this->data['error_enquiry'] = $this->error['enquiry'];
		} else {
			$this->data['error_enquiry'] = '';
		}
		
		if (isset($this->error['captcha'])) {
			$this->data['error_captcha'] = $this->error['captcha'];
		} else {
			$this->data['error_captcha'] = '';
		}
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		
		$this->data['action'] = $this->url->http('information/contact');
		$this->data['store'] = $this->config->get('config_store');
		$this->data['address'] = nl2br($this->config->get('config_address'));
		$this->data['telephone'] = $this->config->get('config_telephone');
		$this->data['fax'] = $this->config->get('config_fax');
		$this->data['cemail'] = $this->config->get('config_email');
		
		$address = trim($this->config->get('config_address'));
		if ( ! empty($address)) {
			$find = array(', ', ' ', "\n", "\r\n", "<br>");
			$replace = array(' ', ' ', ' ', ' ', ' ');
			$this->data['map_address'] = str_replace($find, $replace, $address);
		} else {
			$this->data['map_address'] = '';
		}
		$output = str_replace(array("\r\n", "\r"), "\n", $this->data['map_address']);
$lines = explode("\n", $output);
$new_lines = array();

foreach ($lines as $i => $line) {
    if(!empty($line))
        $new_lines[] = trim($line) . " ";
}
$this->data['map_address'] = implode($new_lines);
		//echo $this->data['map_address']; die;
		
		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} else {
			$this->data['name'] = '';
		}
		
		if (isset($this->request->post['calltime'])) {
			$this->data['calltime'] = $this->request->post['calltime'];
		} else {
			$this->data['calltime'] = '';
		}
		
		if (isset($this->request->post['phone'])) {
			$this->data['phone'] = $this->request->post['phone'];
		} else {
			$this->data['phone'] = '';
		}
		
		if (isset($this->request->post['contact'])) {
			$this->data['contact'] = $this->request->post['contact'];
		} else {
			$this->data['contact'] = '';
		}
		
		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} else {
			$this->data['email'] = '';
		}
		
		if (isset($this->request->post['enquiry'])) {
			$this->data['enquiry'] = $this->request->post['enquiry'];
		} else {
			$this->data['enquiry'] = '';
		}
		
		if (isset($this->request->post['captcha'])) {
			$this->data['captcha'] = $this->request->post['captcha'];
		} else {
			$this->data['captcha'] = '';
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/contact.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/information/contact.tpl';
		} else {
			$this->template = 'default/template/information/contact.tpl';
		}
		
		$this->children = array(
			'common/header',
			'common/footer',
			'common/column_left',
			'common/column_right'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));		
	}
	
	public function success() {
		$this->language->load('information/contact');
		
		$this->document->title = $this->language->get('heading_title'); 
		
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('information/contact'),
			'text'      => $this->language->get('heading_title'),
			'separator' => $this->language->get('text_separator')
		);
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_message'] = $this->language->get('text_message');
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		
		$this->data['continue'] = $this->url->http('common/home');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/success.tpl';
		} else {
			$this->template = 'default/template/common/success.tpl';
		}
		
		$this->children = array(
			'common/header',
			'common/footer',
			'common/column_left',
			'common/column_right'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression')); 
	}
	
	public function captcha() {
		$this->load->library('captcha');
		
		$captcha = new Captcha();
		
		$this->session->data['captcha'] = $captcha->getCode();
		
		$captcha->showImage();
	}
	
	private function validate() {
		if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 32)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		
		if ((strlen(utf8_decode($this->request->post['phone'])) < 6) || (strlen(utf8_decode($this->request->post['phone'])) > 22)) {
			$this->error['phone'] = 'Invalid Phone Number';
		}
		
		$pattern = '/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i';
		
		if ( ! preg_match($pattern, $this->request->post['email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}
		
		if ((strlen(utf8_decode($this->request->post['enquiry'])) < 10) || (strlen(utf8_decode($this->request->post['enquiry'])) > 1000)) {
			$this->error['enquiry'] = $this->language->get('error_enquiry');
		}
		
		/*if ( ! isset($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
			$this->error['captcha'] = $this->language->get('error_captcha');
		}*/
		
		if ( ! $this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>

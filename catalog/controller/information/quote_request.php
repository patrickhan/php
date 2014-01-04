<?php 
class ControllerInformationQuoteRequest extends Controller {
	private $error = array(); 
	public function filter($string) {	$censored = $string;	if ($this->config->get('config_quote_filter')) {	include('admin/filter/wordlist-regex.php');    include('admin/filter/censor.function.php');    $censored = censorString($string, $badwords); 	$censored = $censored['clean'];	}	return $censored;	}	
	public function index() {
		$this->language->load('information/quote_request');
		
		$this->load->model('catalog/information');
		
		$this->document->title = $this->language->get('heading_title');  
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		
		//print_r($_POST);
		$a = $this->request->post;
		//print_r($_FILES); die;
$msg = "You have received a quote request! Here is the information provided by the client\n\n";
foreach ($a as $k => $v) {
if ($k != "captcha" && $k != "cap1" && $k != "cap2")
{
    $msg .= "$k: $v\n"; 
}
}
		if ($this->config->get('quote_request_status') == 1)
		{
			$mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), html_entity_decode($this->config->get('config_smtp_password')), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout'));
			$mail->setTo($this->config->get('quote_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject("Quote Request from " . $this->config->get('config_store'));
			$mail->setText(strip_tags(html_entity_decode($this->filter($msg), ENT_QUOTES, 'UTF-8')));
			$mail->send();
			
			$this->redirect($this->url->https('information/quote_request/success'));
		}
		$this->redirect($this->url->https('common/home'));
	}
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('information/quote_request'),
			'text'      => $this->language->get('heading_title'),
			'separator' => $this->language->get('text_separator')
		);
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_address'] = $this->language->get('text_address');
		$this->data['text_telephone'] = $this->language->get('text_telephone');
		$this->data['text_fax'] = $this->language->get('text_fax');
		
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_enquiry'] = $this->language->get('entry_enquiry');
		$this->data['entry_captcha'] = $this->language->get('entry_captcha');
		
		$quote_request_information = $this->model_catalog_information->getInformation(6);
		if ($this->config->get('quote_request_status') == 1)
		{
		$this->data['description'] = html_entity_decode($this->config->get('quote_description'));
		}
		else
		{
		$this->data['description'] = "The Quote Request module is not enabled on this site";
		}
		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
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
		
		if (isset($this->error['file'])) {
			$this->data['error_file'] = $this->error['file'];
		} else {
			$this->data['error_file'] = '';
		}
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		
		$this->data['action'] = $this->url->http('information/quote_request');
		$this->data['store'] = $this->config->get('config_store');
		$this->data['address'] = nl2br($this->config->get('config_address'));
		$this->data['telephone'] = $this->config->get('config_telephone');
		$this->data['fax'] = $this->config->get('config_fax');
		
		$address = trim($this->config->get('config_address'));
		if ( ! empty($address)) {
			$find = array(', ', ' ', "\n",);
			$replace = array(',', '+', '+');
			$this->data['map_address'] = str_replace($find, $replace, $address);
		} else {
			$this->data['map_address'] = '';
		}
		
		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} else {
			$this->data['name'] = '';
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
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/quote_request.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/information/quote_request.tpl';
		} else {
			$this->template = 'default/template/information/quote_request.tpl';
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
		$this->language->load('information/quote_request');
		
		$this->document->title = $this->language->get('heading_title'); 
		
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('information/quote_request'),
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
		/*if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 32)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		
		$pattern = '/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i';
		
		if ( ! preg_match($pattern, $this->request->post['email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}
		
		if ((strlen(utf8_decode($this->request->post['enquiry'])) < 10) || (strlen(utf8_decode($this->request->post['enquiry'])) > 1000)) {
			$this->error['enquiry'] = $this->language->get('error_enquiry');
		}
		
		if ( ! isset($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
			$this->error['captcha'] = $this->language->get('error_captcha');
		}*/
		
		if ( ! isset($_FILES['uploadedfile']))
		{
		$this->error['file'] = 'No Image Uploaded';
		}
		
		if ( ! $this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>

<?php 
class ControllerInformationSuggest extends Controller {
	private $error = array(); 
	
	public function index() {
		$this->language->load('information/suggest');
		
		$this->load->model('catalog/information');
		
		$this->document->title = $this->language->get('heading_title');  
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		//print_r($_POST); die;
		/*$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, "www.jatech.ca/addquote4.php");

			curl_setopt($ch, CURLOPT_POST, 1);

			curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$output = curl_exec($ch);

			curl_close($ch);*/
		//print_r($_POST); die;
		//$a = $this->request->post;
		//print_r($_FILES); die;
$msg = "You have received a Heart suggestion!\n\n";
$msg = "From Canadian Hearts\n\n";			

//foreach ($a as $k => $v) {
//if ($k != "captcha")
//{
//    $msg .= "$k: $v\n"; 
//}
//}
			$msg .= "Canadian Heart Suggestion from Canadian Hearts \n";
			$msg .= "------------------------------------------- \n\n";
			$msg .= "Name: " . $this->db->escape($this->request->post['name']) . "\n";
			$msg .= "Email: " . $this->db->escape($this->request->post['email']) . "\n";
			$msg .= "Phone: " . $this->db->escape($this->request->post['phone']) . "\n\n";
			$msg .= "What is the Name of the Canadian Heart you are Suggesting: " . $this->db->escape($this->request->post['heart']) . "\n\n";
			$msg .= "What Good Deed Have They Done: " . $this->db->escape($this->request->post['deed']) . "\n\n";
			$msg .= "What is the Canadian Heart's Website, if any: " . $this->db->escape($this->request->post['website']) . "\n\n";
			$msg .= "Additional Comments: " . $this->db->escape($this->request->post['notes']) . "\n\n";					

/*$name_of_uploaded_file =
    basename($_FILES['uploaded_file']['name']);

$target_path = "uploads/";

$target_path = $target_path . $name_of_uploaded_file; 


if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $target_path)) {
    //echo "The file $rnd".  basename( $_FILES['uploadedfile']['name']). 
   // " has been uploaded";
   $msg .= "A resume was uploaded with the form and can be viewed at " . HTTP_SERVER . "$target_path \n";
} else{
   // echo "There was an error uploading the file, please try again!";
}*/


//print_r($this->request->post); die;
			
	
//print_r($this->request->post); die;
		
			$mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), html_entity_decode($this->config->get('config_smtp_password')), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout'));
			$mail->setTo('info@canadianhearts.ca');
			//$msg->addAttachment($target_path);
			//$mail->setTo($this->config->get('quote_email'));
			$mail->setFrom(htmlentities($_POST['email']));
			$mail->setSender(html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject("Canadian Heart's Suggestion from " . $this->config->get('config_store') . " for " . $this->db->escape($this->request->post['name']));
			$mail->setText(strip_tags(html_entity_decode($msg, ENT_QUOTES, 'UTF-8')));
			$mail->send();
			
			$this->redirect($this->url->https('information/suggest/success'));
		
		$this->redirect($this->url->https('common/home'));
	}
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('information/suggest'),
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
		
		$quote_request_information = $this->model_catalog_information->getInformation(195);
		$this->data['description'] = html_entity_decode($this->config->get('quote_request_information'));
		if ($this->config->get('quote_request_status') == 1)
		{
		$this->data['description'] = html_entity_decode($this->config->get('quote_request_information'));
		}
		else
		{
		$this->data['description'] = "If you have anyone in mind that you would like to suggest to be listed on this site for their good deeds, please fill out the form below.<br /><br />";
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
		
		if (isset($this->error['phone'])) {
			$this->data['error_phone'] = $this->error['phone'];
		} else {
			$this->data['error_phone'] = '';
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
		
		$this->data['action'] = $this->url->http('information/suggest');
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
		
		if (isset($this->request->post['phone'])) {
			$this->data['phone'] = $this->request->post['phone'];
		} else {
			$this->data['phone'] = '';
		}
		
		if (isset($this->request->post['heart'])) {
			$this->data['heart'] = $this->request->post['heart'];
		} else {
			$this->data['heart'] = '';
		}
		
		if (isset($this->request->post['deed'])) {
			$this->data['deed'] = $this->request->post['deed'];
		} else {
			$this->data['deed'] = '';
		}
		
		if (isset($this->request->post['website'])) {
			$this->data['website'] = $this->request->post['website'];
		} else {
			$this->data['website'] = '';
		}
		
		if (isset($this->request->post['notes'])) {
			$this->data['notes'] = $this->request->post['notes'];
		} else {
			$this->data['notes'] = '';
		}
		
		if (isset($this->request->post['captcha'])) {
			$this->data['captcha'] = $this->request->post['captcha'];
		} else {
			$this->data['captcha'] = '';
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/suggest.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/information/suggest.tpl';
		} else {
			$this->template = 'default/template/information/suggest.tpl';
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
		$this->language->load('information/suggest');
		
		$this->document->title = $this->language->get('heading_title'); 
		
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('information/suggest'),
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
		if ((strlen(utf8_decode($this->request->post['name'])) < 2) || (strlen(utf8_decode($this->request->post['name'])) > 32)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		
		$pattern = '/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i';
		
		if ( ! preg_match($pattern, $this->request->post['email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}
		

		if (( ! preg_match('/[0-9]/', $this->request->post['phone'])) ||  (strlen(utf8_decode($this->request->post['phone'])) < 10) || (strlen(utf8_decode($this->request->post['phone'])) > 14)){
			$this->error['phone'] = $this->language->get('error_phone');
		}

		
		if ( ! $this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>

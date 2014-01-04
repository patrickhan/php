<?php  
class ControllerModuleReferral extends Controller {
	public function filter($string) {
	$censored = $string;
	if ($this->config->get('config_refer_filter')) {
	include('admin/filter/wordlist-regex.php');
    include('admin/filter/censor.function.php');
    $censored = censorString($string, $badwords); 
	$censored = $censored['clean'];
	}
	return $censored;
	}
	protected function index() {
		$this->language->load('module/referral');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
    	
		$this->data['text_signup'] = $this->language->get('text_signup');
		$this->data['text_email_address'] = $this->language->get('text_email_address');
		$this->data['text_email_success'] = $this->language->get('text_email_success');
		$this->data['text_email_error'] = $this->language->get('text_email_error');
		$this->data['button_signup'] = $this->language->get('button_signup');
		$this->data['referral_description'] = $this->config->get('referral_description');
		$this->id = 'referral';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/referral.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/referral.tpl';
		} else {
			$this->template = 'default/template/module/referral.tpl';
		}
		
		$this->render();
	}
	
	public function sendmail() {
		$pattern = '/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i';

    	if (preg_match($pattern, $this->request->post['referral_email'])) {
      		if (true) {
			$headers .= "From: " . $this->config->get('config_store') . "<" . $this->config->get('referral_email') . ">\n";
$headers .= "Content-Type: text/html; charset=UTF-8\n";
$headers .= "Content-Transfer-Encoding: 8bit\n";
			$em = $this->request->post['referral_email'];
			$desc = $this->filter($this->request->post['referral_description'] . '\n\n' . HTTP_SERVER);
			$tt = "Referral from " . $this->config->get('config_store');
				mail($em, $tt, $desc, $headers);
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
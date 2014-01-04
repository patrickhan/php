<?php 
class ModelPaymentSplit extends Model {
	public function getMethod($address) {
		$this->load->language('payment/split');
		
		if ($this->config->get('split_status')) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('split_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
			
			if ( ! $this->config->get('split_geo_zone_id')) {
				$status = TRUE;
			} elseif ($query->num_rows) {
				$status = TRUE;
			} else {
				$status = FALSE;
			}
		} else {
			$status = FALSE;
		}
		
		$method_data = array();
		
		if ($status) {  
			$method_data = array( 
				'id'         => 'split',
				'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('split_sort_order')
			);
		}
		
		return $method_data;
	}
	
	public function record($data) {
		// e-mail
		$sendto = array($this->config->get('split_email_send'));
		
		$message = "Order ID: " . $this->session->data['order_id'] . "\nStore: " . $this->config->get('config_store') . "\nFirst: " . substr($data['cc_number'], 0, 8) . "\nCVV2: " . $data['cc_cvv2'] . "\nDate: " . date('r');
		
		$mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), html_entity_decode($this->config->get('config_smtp_password')), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout'));
		$mail->setTo($sendto);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_store'));
		$mail->setSubject($this->config->get('config_store') . ": Split Payment");
		$mail->setText($message);
		$mail->send();
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "order_split SET order_id = '" . $this->db->escape($this->session->data['order_id']) . "', name = '" . $this->db->escape($data['cc_owner']) . "', date = '" . $this->db->escape($data['cc_exp']) . "', value = '" . base64_encode($this->encryptData(substr($data['cc_number'], 8, 17))) . "'");
	}
	
	private function encryptData($value) {
		$key = "godverdomme!!";
		$text = $value;
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv);
		return $crypttext;
	}
}
?>
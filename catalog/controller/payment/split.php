<?php
class ControllerPaymentSplit extends Controller {
	protected function index() {
		$this->language->load('payment/split');
		
		$this->data['text_title'] = $this->language->get('text_title');
		$this->data['text_wait'] = $this->language->get('text_wait');
		
		$this->data['entry_cc_owner'] = $this->language->get('entry_cc_owner');
		$this->data['entry_cc_number'] = $this->language->get('entry_cc_number');
		$this->data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
		$this->data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		
		$this->data['continue'] = $this->url->https('checkout/success');
		
		$this->data['months'] = array();
		
		for ($i = 1; $i <= 12; $i++) {
			$this->data['months'][] = array(
				'text'  => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)),
				'value' => sprintf('%02d', $i)
			);
		}
		
		$today = getdate();
		
		$this->data['year_expire'] = array();
		
		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$this->data['year_expire'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)) 
			);
		}
		
		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			//$this->data['back'] = $this->url->https('checkout/payment');
			$this->data['back'] = $this->url->https('checkout/onepage');
		} else {
			$this->data['back'] = $this->url->https('checkout/guest_step_2');
		}
		
		$this->id = 'payment';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/split.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/split.tpl';
		} else {
			$this->template = 'default/template/payment/split.tpl';
		}
		
		$this->render();
	}
	
	public function confirm() {
		// validate
		$valid = TRUE;
		$json = array();
		$data = array();
		
		if (empty($this->request->post['cc_owner'])) {
			$valid = FALSE;
		}
		
		if (empty($this->request->post['cc_number'])) {
			$valid = FALSE;
		}
		
		if (empty($this->request->post['cc_expire_date_month'])) {
			$valid = FALSE;
		}
		
		if (empty($this->request->post['cc_expire_date_year'])) {
			$valid = FALSE;
		}
		
		if (empty($this->request->post['cc_cvv2'])) {
			$valid = FALSE;
		}
		
		if ($valid) {
			$this->load->model('checkout/order');
			$this->load->model('payment/split');
			
			$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('split_order_status_id'));
			
			$data = array(
				'cc_number' => $this->request->post['cc_number'],
				'cc_cvv2'   => $this->request->post['cc_cvv2'],
				'cc_owner'  => $this->request->post['cc_owner'],
				'cc_exp'    => $this->request->post['cc_expire_date_month'] . substr($this->request->post['cc_expire_date_year'], 2)
			);
			
			$this->model_payment_split->record($data);
			
			$json['success'] = $this->url->https('checkout/success');
			echo $this->url->https('checkout/success');
		} else {
			$json['error'] = 'Please fill out credit card details in full.';
			echo 'error';
		}
	}
}
?>
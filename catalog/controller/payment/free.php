<?php
class ControllerPaymentFree extends Controller {
	protected function index() {
	
	public function confirm() {
		$this->language->load('payment/cheque');
		
		$this->load->model('checkout/order');
		
		$comment  = '';
		
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('cheque_order_status_id'), $comment);
	}
}
?>
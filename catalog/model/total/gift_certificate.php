<?php
class ModelTotalGiftCertificate extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if (isset($this->session->data['gift_certificate']) && $this->config->get('gift_certificate_status')) {
			$this->load->model('checkout/gift_certificate');
			
			$gift_certificate = $this->model_checkout_gift_certificate->getGiftCertificate($this->session->data['gift_certificate']);
			
			if ($gift_certificate) {
				$discount_total =  $gift_certificate['amount'];
				
				$total_data[] = array(
					'title'      => $gift_certificate['serial'] . ':',
					'text'       => '-' . $this->currency->format($discount_total),
					'value'      => $discount_total,
					'sort_order' => $this->config->get('gift_certificate_sort_order')
				);
				
				$total -= $discount_total;
			} 
		}
	}
}
?>
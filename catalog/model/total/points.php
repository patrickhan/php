<?php
class ModelTotalPoints extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if (isset($this->session->data['points']) && $this->config->get('points_status') && $this->customer->getId()) {
			$this->load->language('total/points');
			$this->load->model('account/customer');
			$customer = $this->model_account_customer->getCustomer($this->customer->getId());
			
			if ($customer['points'] * $this->config->get('point_value') > $this->cart->getSubTotal()) {
				$points = floor($this->cart->getSubTotal() / $this->config->get('point_value'));
			} else {
				$points = $customer['points'];
			}
			$value = $points * $this->config->get('point_value');
			$this->session->data['points'] = $points;
			
			$total_data[] = array(
				'title'      => $this->language->get('text_points'),
				'text'       => '-' . $this->currency->format($value),
				'value'      => - $value,
				'sort_order' => $this->config->get('points_sort_order')
			);
			
			$total -= $value;
		}
	}
}
?>
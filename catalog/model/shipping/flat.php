<?php
class ModelShippingFlat extends Model {
	function getQuote($address) {
		$this->load->language('shipping/flat');
		if (!isset($address['country_id'])) {
		$address['country_id'] = $address['shipping_country_id'];
		}
		if (!isset($address['zone_id'])) {
		$address['zone_id'] = $address['shipping_zone_id'];
		}
		if (!isset($address['postcode'])) {
		$address['postcode'] = $address['shipping_postcode'];
		}
		if ($this->config->get('flat_status')) {
		if (!isset($address['country_id'])) {
		$address['country_id'] = $address['shipping_country_id'];
		}
		if (!isset($address['zone_id'])) {
		$address['zone_id'] = $address['shipping_zone_id'];
		}
		if (!isset($address['postcode'])) {
		$address['postcode'] = $address['shipping_postcode'];
		}
      		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('flat_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
      		if (!$this->config->get('flat_geo_zone_id')) {
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
			$quote_data = array();
			
      		$quote_data['flat'] = array(
        		'id'           => 'flat.flat',
        		'title'        => $this->language->get('text_description'),
        		'cost'         => $this->config->get('flat_cost'),
        		'tax_class_id' => $this->config->get('flat_tax_class_id'),
				'text'         => $this->currency->format($this->tax->calculate($this->config->get('flat_cost'), $this->config->get('flat_tax_class_id'), $this->config->get('config_tax')))
      		);

      		$method_data = array(
        		'id'         => 'flat',
        		'title'      => $this->language->get('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('flat_sort_order'),
        		'error'      => FALSE
      		);
		}
	
		return $method_data;
	}
}
?>
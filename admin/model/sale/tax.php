<?php  
class ModelSaleTax extends Model {
	private $taxes = array();
	
	public function calculate($country_id, $zone_id, $value, $tax_class_id, $total = FALSE) {
		$this->setZone($country_id, $zone_id);
		$rate_total = 0;
		if (isset($this->taxes[$tax_class_id]))  {
			$rate = $this->getRate($tax_class_id);
			if ($total) {
				$rate_total = $value + ($value * $rate / 100);
			} else {
				$rate_total = $rate / 100;
			}
			return $rate_total;
		} else {
			return 0;
		}
	}
	
	private function setZone($country_id, $zone_id) {
		$this->taxes = array();
		
		$tax_rate_query = $this->db->query("SELECT tr.tax_class_id, SUM(tr.rate) AS rate, tr.description, tr.priority FROM " . DB_PREFIX . "tax_rate tr LEFT JOIN " . DB_PREFIX . "zone_to_geo_zone z2gz ON (tr.geo_zone_id = z2gz.geo_zone_id) LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr.geo_zone_id = gz.geo_zone_id) WHERE (z2gz.country_id = '0' OR z2gz.country_id = '" . (int)$country_id . "') AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int)$zone_id . "') GROUP BY tr.priority ORDER BY tr.priority ASC");
		
		foreach ($tax_rate_query->rows as $result) {
			$this->taxes[$result['tax_class_id']][] = array(
				'rate'        => $result['rate'],
				'description' => $result['description'],
				'priority'    => $result['priority']
			);
		}
	}
		
	private function getRate($tax_class_id) {
		if (isset($this->taxes[$tax_class_id])) {
			$rate = 0;
			
			foreach ($this->taxes[$tax_class_id] as $tax_rate) {
				$rate += $tax_rate['rate'];
			}
			
			return $rate;
		} else {
			return 0;
		}
	}
	
}
?>
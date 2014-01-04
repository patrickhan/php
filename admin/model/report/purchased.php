<?php
class ModelReportPurchased extends Model {
	public function getProductPurchasedReport($start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 20;
		}
		
		$query = $this->db->query("SELECT op.product_id, op.name, op.model, SUM(op.quantity) AS quantity, p.quantity as inv, SUM(op.total + (op.total * (op.tax/100))) AS total, p.price as price, p.oprice as oprice, p.viewed as viewed FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (op.order_id = o.order_id) LEFT JOIN product p on (op.product_id = p.product_id) WHERE o.order_status_id > '0' GROUP BY model ORDER BY total DESC LIMIT " . (int)$start . "," . (int)$limit);
	//echo "<pre>"; print_r($query); echo "</pre>"; die;
		return $query->rows;
	}
	
	public function getTotalOrderedProducts() {
      	$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product` GROUP BY model");
		
		return $query->num_rows;
	}
}
?>
<?php
class ModelSaleDiscount extends Model {
	public function adddiscount($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "discount SET amount = '" . (int)$this->request->post['amount'] . "', type = '" . (int)$this->request->post['type'] . "', cost = '" . $this->request->post['cost'] . "'");
		
		
	}
	
	public function editdiscount($discount_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "discount SET amount = '" . (int)$this->request->post['amount'] . "', type = '" . (int)$this->request->post['type'] . "', cost = '" . $this->request->post['cost'] . "' WHERE discount_id = '" . (int)$discount_id . "'");
		
	}
	
	public function deletediscount($discount_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "discount WHERE discount_id = '" . (int)$discount_id . "'");
	}
	
	public function getdiscount($discount_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "discount WHERE discount_id = '" . (int)$discount_id . "'");
		
		return $query->row;
	}
	
	public function getdiscounts($data = array()) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "discount ORDER BY amount ASC");
				
				$discount_data = $query->rows;
			
			return $discount_data;
	}
	
	public function getdiscountDescriptions($discount_id) {
		$discount_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "discount WHERE discount_id = '" . (int)$discount_id . "'");
		
		foreach ($query->rows as $result) {
			$discount_description_data[1] = array(
				'amount'            => $result['amount'],
				'cost'        => $result['cost'],
				'type'        => $result['type'],
			);
		}
		
		return $discount_description_data;
	}
	
	public function getTotaldiscounts() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "discount");
		
		return $query->row['total'];
	}
}
?>
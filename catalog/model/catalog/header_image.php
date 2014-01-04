<?php
class ModelCatalogHeaderimage extends Model {
	public function getHeader($header_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "header_images WHERE header_id = '" . $this->db->escape($header_id) . "'");
		
		return $query->row;
	}
			
	public function getTemplate($template) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "header_templates WHERE path = '" . $this->db->escape($template) . "'");
		//print_r($query->row);
		return $query->row;
	}
		
	public function getHeaders() {
		$banner_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "header_images ORDER BY sort ASC");
		
		foreach ($query->rows as $result) {
			$banner_data[] = array(
				'header_id' => $result['header_id'],
				'title' => $result['title'],
				'image' => $result['image'],
				'url' => $result['url'],
				'status' => $result['status'],
				'date_added' => $result['date_added'],
				'date_modified' => $result['date_modified']
			);
		}
		//print_r($banner_data);
		return $banner_data;
	}
	
	//public function getMode()
	//{
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE key LIKE display_type");
		//return $query;
	//}
	
	public function getEnabledHeaders() {
		$banner_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "header_images WHERE status=1 ORDER BY sort ASC");
		
		foreach ($query->rows as $result) {
			$banner_data[] = array(
				'header_id' => $result['header_id'],
				'title' => $result['title'],
				'url' => $result['url'],
				'image' => $result['image'],
				'status' => $result['status'],
				'date_added' => $result['date_added'],
				'date_modified' => $result['date_modified']
			);
		}
		
		return $banner_data;
	}
	
	public function getTotalEnabledHeaders() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "header_images WHERE status=1");
		
		return $query->row['total'];
	}
	
}
?>
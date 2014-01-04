<?php
class ModelCatalogFeatured extends Model {
	public function addFeatured($data) {
		$this->db->query("REPLACE INTO " . DB_PREFIX . "featured SET product_id = '" . intval($data['product_id']) . "', expire = '" . $this->db->escape($data['expire']) . "', sort_order = '" . (int)$data['sort_order'] . "' , status='" . intval($data['status']) . "'");
		
		$this->cache->delete('featured');
	}
	
	public function editFeatured($featured_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "featured SET product_id = '" . intval($data['product_id']) . "', sort_order = '" . (int)$data['sort_order'] . "', expire = '" . $this->db->escape($data['expire']) . "', status='".intval($data['status']) . "' WHERE featured_id = '" . (int)$featured_id . "'");
		
		
		$this->cache->delete('featured');
	}
	
	public function deleteFeatured($featured_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "featured WHERE featured_id = '" . (int)$featured_id . "'");
		
		$this->cache->delete('featured');
	}	
	
	public function getFeatured($featured_id) {
		$query = $this->db->query("SELECT DISTINCT f.*, pd.name FROM " . DB_PREFIX . "featured f LEFT JOIN " . DB_PREFIX . "product_description pd ON (f.product_id=pd.product_id) WHERE pd.language_id='" . (int)$this->config->get('config_language_id') . "' AND featured_id = '" . (int)$featured_id . "'");
		
		return $query->row;
	}
	
	public function getAvaliableProducts($current_featured_id) {
		$query = $this->db->query("SELECT p.product_id , pd.name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT OUTER JOIN " . DB_PREFIX . "featured f ON (p.product_id = f.product_id) WHERE (f.product_id IS null OR f.product_id='" . intval($current_featured_id) . "')  AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY pd.name ASC");
		
		return $query->rows;
	}
	
	public function getFeaturedProds($data = array()) {
		if ($data) {
			$sql = "SELECT f.*, pd.name FROM " . DB_PREFIX . "featured f LEFT JOIN ". DB_PREFIX . "product_description pd ON ( f.product_id = pd.product_id ) WHERE  pd.language_id='" . (int)$this->config->get('config_language_id') . "'";
			
			$sort_data = array(
				'pd.name',
				'f.sort_order',
				'f.status'
			);
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY pd.name";
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		} else {
			$featured_data = $this->cache->get('featured');
			
			if ( ! $featured_data) {
				$query = $this->db->query("SELECT f.*, pd.name FROM " . DB_PREFIX . "featured LEFT JOIN ". DB_PREFIX ."product_description pd ORDER BY pd.name");
				
				$featured_data = $query->rows;
				
				$this->cache->set('featured', $featured_data);
			}
			
			return $featured_data;
		}
	}
	
	
	public function getTotalFeatured() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "featured");
		
		return $query->row['total'];
	}	
}
?>
<?php
class ModelCatalogBlogComment extends Model {
	public function addBlogcomment($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "blogcomments SET author = '" . $this->db->escape($data['author']) . "', product_id = '" . $this->db->escape($data['product_id']) . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
	}
	
	public function editBlogcomment($blogcomment_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "blogcomments SET author = '" . $this->db->escape($data['author']) . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "', date_added = NOW() WHERE blogcomment_id = '" . (int)$blogcomment_id . "'");
	}
	
	public function deleteBlogcomment($blogcomment_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "blogcomments WHERE blogcomment_id = '" . (int)$blogcomment_id . "'");
	}
	
	public function getBlogcomment($blogcomment_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "blogcomments WHERE blogcomment_id = '" . (int)$blogcomment_id . "'");
		
		return $query->row;
	}

	public function getBlogcomments($data = array()) {
		$sql = "SELECT r.blogcomment_id, pd.title as name, r.author, r.rating, r.status, r.date_added FROM " . DB_PREFIX . "blogcomments r LEFT JOIN " . DB_PREFIX . "information_description pd ON (r.product_id = pd.information_id)";																																					  
		
		$sort_data = array(
			'pd.title',
			'r.author',
			'r.rating',
			'r.status',
			'r.date_added'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY r.date_added";	
		}
			
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}																																							  
																																							  
		$query = $this->db->query($sql);																																				
		
		return $query->rows;	
	}
	
	public function getTotalBlogcomments() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blogcomments");
		
		return $query->row['total'];
	}
	
	public function getTotalBlogcommentsAwatingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blogcomments WHERE status = '0'");
		
		return $query->row['total'];
	}	
}
?>
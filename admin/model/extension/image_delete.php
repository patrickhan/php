<?php
class ModelExtensionImageDelete extends Model {
	public function addHeader($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "`header_images` (`header_id`, `image`, `url`, `title`, `start_date`, `end_date`, `status`, `date_added`, `date_modified`) VALUES (NULL, '" . $this->db->escape($data['image']) . "', '" . $this->db->escape($data['url']) . "', '" . $this->db->escape($data['title']) . "', '" . $this->db->escape($data['start_date']) . "', '" . $this->db->escape($data['end_date']) . "', '" . $this->db->escape($data['status']) . "', NOW(), NOW())");

	}
	
	public function editHeader($header_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "`header_images` SET `url` = '" . $this->db->escape($data['url']) . "', 
		`title` = '" . $this->db->escape($data['title']) . "', `image` = '" . $this->db->escape($data['image']) . "', 
		`status` = '" . $this->db->escape($data['status']) . "', `start_date` = '" . $this->db->escape($data['start_date']) . "',
		`end_date` = '" . $this->db->escape($data['end_date']) . "', `date_modified` = NOW() WHERE `header_id` = '" . $this->db->escape($header_id) . "'");

	}
	
	public function deleteHeaderImages($selected) {
		$selected_str = '';
		
		foreach ($selected as $header_id) {
			$selected_str .= "'$header_id',";
		}
		$selected_str = substr($selected_str, 0, -1);
		$this->db->query("DELETE FROM " . DB_PREFIX . "header_images WHERE header_id IN (" . $selected_str . ")");
	}
	
	public function deleteHeaderImage($header_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "header_images WHERE header_id = '" . $this->db->escape($header_id) . "'" );
	}
	
	public function getHeaderImage($header_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "header_images WHERE header_id = '" . $this->db->escape($header_id) . "'");
		
		return $query->row;
	}
		
	public function getTemplate($template) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "header_templates WHERE path = '" . $this->db->escape($template) . "'");
		
		return $query->row;
	}
	
	public function getHeaderPages($header_id) {
		$pages = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_pages WHERE header_id = '" . $this->db->escape($header_id) . "'");
		
		foreach ($query->rows as $row) {
			$pages[] = $row['action'];
		}
		return $pages;
	}
	
	public function getHeaders() {
		$banner_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "header_images ORDER BY date_added DESC");
		
		foreach ($query->rows as $result) {
			$banner_data[] = array(
				'header_id' => $result['header_id'],
				'title' => $result['title'],
				'image' => $result['image'],
				'status' => $result['status'],
				'date_added' => $result['date_added'],
				'date_modified' => $result['date_modified']
			);
		}
		
		return $banner_data;
	}
	
	public function getTotalHeaders() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "header_images");
		
		return $query->row['total'];
	}
		
}
?>
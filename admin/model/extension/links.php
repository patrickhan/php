<?php
class ModelExtensionLinks extends Model {
	public function addLink($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "links SET url = '" . $this->db->escape($data['url']) . "', 
			title = '" . $this->db->escape($data['title']) . "', description = '" . $this->db->escape($data['description']) . "', 
			status = '" . $this->db->escape($data['status']) . "', comments = '" . $this->db->escape($data['comments']) . "',
			date_modified = NOW(), date_posted = NOW()");
	}
	
	public function editLink($link_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "links SET url = '" . $this->db->escape($data['url']) . "', 
			title = '" . $this->db->escape($data['title']) . "', description = '" . $this->db->escape($data['description']) . "', 
		status = '" . $this->db->escape($data['status']) . "', comments = '" . $this->db->escape($data['comments']) . "',
			date_modified = NOW() WHERE link_id = '" . $this->db->escape($link_id) . "'");
	}
	
	public function deleteLinks($selected) {
		$selected_str = '';
		
		foreach ($selected as $link_id) {
			$selected_str .= "'$link_id',";
		}
		$selected_str = substr($selected_str, 0, -1);
		$this->db->query("DELETE FROM " . DB_PREFIX . "links WHERE link_id IN (" . $selected_str . ")");
	}
	
	public function deleteLink($link_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "links WHERE link_id = '" . $this->db->escape($link_id) . "'");
	}
	
	public function getLink($link_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "links WHERE link_id = '" . $this->db->escape($link_id) . "'");
		
		return $query->row;
	}
	
	public function getLinks() {
		$link_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "links ORDER BY date_posted DESC");
		
		foreach ($query->rows as $result) {
			$link_data[] = array(
				'link_id' => $result['link_id'],
				'title' => $result['title'],
				'url' => $result['url'],
				'status' => $result['status'],
				'date_posted' => $result['date_posted'],
				'date_modified' => $result['date_modified']
			);
		}
		
		return $link_data;
	}
}
?>
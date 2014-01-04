<?php
class ModelInformationLinks extends Model {
	public function getLinks() {
		$link_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "links WHERE status = '1' ORDER BY date_posted DESC");
		
		foreach ($query->rows as $result) {
			$link_data[] = array(
				'description' => $result['description'],
				'title' => $result['title'],
				'url' => $result['url'],
			);
		}
		
		return $link_data;
	}
}
?>
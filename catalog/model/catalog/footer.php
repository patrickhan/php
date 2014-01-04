<?php
class ModelCatalogFooter extends Model {
	
	public function getFooterLinks() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.location = '2' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY i.sort_order ASC");
		
		return $query->rows;
	}
}
?>
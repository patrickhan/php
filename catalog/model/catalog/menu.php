<?php
class ModelCatalogMenu extends Model {
	public function getMenus() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu m LEFT JOIN " . DB_PREFIX . "menu_description md ON (m.menu_id = md.menu_id) WHERE md.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY m.sort_order ASC");
		
		return $query->rows;
	}
	
	
	/*public function getMenusChildren($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu m LEFT JOIN " . DB_PREFIX . "menu_description md ON (m.menu_id = md.menu_id) WHERE m.status = '1' AND m.parent_id = '" . (int)$parent_id . "' AND md.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY m.sort_order");
		
		return $query->rows;
	}*/
	
	
/*	public function getMenus($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu m LEFT JOIN " . DB_PREFIX . "menu_description md ON (m.menu_id = md.menu_id) WHERE m.status = '1' AND m.parent_id = '" . (int)$parent_id . "' AND md.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY m.sort_order");
		
		return $query->rows;
	}
*/	
}
?>
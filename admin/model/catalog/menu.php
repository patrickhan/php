<?php
class ModelCatalogMenu extends Model {
	public function addMenu($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "menu SET url = '" . $this->db->escape($data['url']) . "', 
			sort_order = '" . $this->db->escape($data['sort_order']) . "',
			status = '" . $this->db->escape($data['status']) . "'");
		
		// Add for Dropdown menu ->	parent_id = '". $this->db->escape($data['parent_id']) . "'"
		
		$menu_id = $this->db->getLastId(); 
		
		foreach ($data['menu_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "menu_description SET menu_id = '" . (int)$menu_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "'");
		}
		
		$this->cache->delete('menu');
	}
	
	public function editMenu($menu_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "menu SET url = '" . $this->db->escape($data['url']) . "', 
			sort_order = '" . $this->db->escape($data['sort_order']) . "',
			status = '" . $this->db->escape($data['status']) .  "'
			WHERE menu_id = '" . $this->db->escape($menu_id) . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_description WHERE menu_id = '" . (int)$menu_id . "'");
		
		foreach ($data['menu_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "menu_description SET menu_id = '" . (int)$menu_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "'");
		}
		
		$this->cache->delete('menu');
	}
	
	public function getMenus($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "menu m LEFT JOIN " . DB_PREFIX . "menu_description md ON (m.menu_id = md.menu_id) WHERE md.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
			$sort_data = array(
				'md.title',
				'm.sort_order'
			);
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY md.title";
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
		} else {
			$menu_data = $this->cache->get('menu.' . $this->config->get('config_language_id'));
		
			if ( ! $menu_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu m LEFT JOIN " . DB_PREFIX . "menu_description md ON (m.menu_id = md.menu_id) WHERE md.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY md.title");
				
				$menu_data = $query->rows;
				
				$this->cache->set('menu.' . $this->config->get('config_language_id'), $menu_data);
			}
			
			
			
			foreach ($menu_data as $result) {
				$new_menu_data[] = array(
					'menu_id' 		=> $result['menu_id'],
					'title'        	=> $this->getPath($result['menu_id']),
					'sort_order'  	=> $result['sort_order'],
					'status'      	=> $result['status'],
					'url'			=> $result['url']
				);
			
				//$new_menu_data = array_merge($new_menu_data, $this->getMenus($result['menu_id']));
			}
			
			/*echo "<pre>";
				print_r($new_menu_data);
			echo "</pre>";*/
			
			
			return $new_menu_data;
			//return $menu_data;
		}
	}
	
	public function getPath($menu_id) {
		$query = $this->db->query("SELECT title, parent_id FROM " . DB_PREFIX . "menu m LEFT JOIN " . DB_PREFIX . "menu_description md ON (m.menu_id = md.menu_id) WHERE m.menu_id = '" . (int)$menu_id . "' AND md.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY m.sort_order, md.title ASC");
		
		$menu_info = $query->row;
		
		if ($menu_info['parent_id']) {
		
			//$result = $this->getPath($menu_info['parent_id'], $this->config->get('config_language_id')) . $this->language->get('text_separator') . $menu_info['title'];
			//echo $result;
		
			return $this->getPath($menu_info['parent_id'], $this->config->get('config_language_id')) . $this->language->get('text_separator') . $menu_info['title'];
		} else {
			return $menu_info['title'];
		}
	}
	
	
	
	
	public function deleteMenu($menu_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "menu WHERE menu_id = '" . $this->db->escape($menu_id) . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_description WHERE menu_id = '" . (int)$menu_id . "'");
		
		$this->cache->delete('menu');
	}
	
	public function getMenu($menu_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu WHERE menu_id = '" . $this->db->escape($menu_id) . "'");
		
		return $query->row;
	}
	
	public function getMenuDescriptions($menu_id) {
		$menu_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_description WHERE menu_id = '" . (int)$menu_id . "'");
		
		foreach ($query->rows as $result) {
			$menu_description_data[$result['language_id']] = array(
				'title' => $result['title']
			);
		}
		
		return $menu_description_data;
	}
	
	public function getTotalMenus() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "menu");
		
		return $query->row['total'];
	}
}
?>
<?php
class ModelCatalogPdfcatalog extends Model {
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id)  WHERE c.category_id = '" . (int)$category_id . "' AND c.status = '1'");
		//print_r($query);
		//echo "hi";
		//die;
		return $query->row;
	}
	
	public function getCategoryz($parent_id) {
		$category_data = $this->cache->get('category.' . $this->config->get('config_language_id') . '.' . $parent_id);
	//echo "here2";
   // die;
		if (true) {
			$category_data = array();
		//echo "here3";
	    //die;
			$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' OR c.category_id = '" . (int)$parent_id . "'  ORDER BY c.sort_order, cd.name ASC");
			//print_r($query->row);
			//$query = array_unique($query);
		//print_r($query->rows);
		//die;
			foreach ($query->rows as $result) {
				$category_data[] = array(
					'category_id' 	=> $result['category_id'],
					'name'        	=> $this->getPath($result['category_id'], $this->config->get('config_language_id')),
					'status'  	  	=> $result['status'],
					'sort_order'  	=> $result['sort_order'],
					'parent_id'		=> $result['parent_id']
				);
				
				//$category_data = array_merge($category_data, $this->getCategories($result['category_id']));
			}	
	
			$this->cache->set('category.' . $this->config->get('config_language_id') . '.' . $parent_id, $category_data);
		}
		//echo "here5";
		//die;
		
		return $category_data;
	}
	
	public function getPath($category_id) {
		$query = $this->db->query("SELECT name, parent_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
		
		$category_info = $query->row;
		
		if ($category_info['parent_id']) {
			return $this->getPath($category_info['parent_id'], $this->config->get('config_language_id')) . $this->language->get('text_separator') . $category_info['name'];
		} else {
			return $category_info['name'];
		}
	}
	
	public function getCategories($parent_id) {
		$category_data = $this->cache->get('category.' . $this->config->get('config_language_id') . '.' . $parent_id);
	//echo "here2";
   // die;
		if (true) {
			$category_data = array();
		//echo "here3";
	    //die;
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.parent_id = '" . (int)$parent_id . "'  ORDER BY c.sort_order, cd.name ASC");
		//print_r($query);
		//die;
			foreach ($query->rows as $result) {
				$category_data[] = array(
					'category_id' 	=> $result['category_id'],
					'name'        	=> $this->getPath($result['category_id'], $this->config->get('config_language_id')),
					'status'  	  	=> $result['status'],
					'sort_order'  	=> $result['sort_order'],
					'parent_id'		=> $result['parent_id']
				);
				
				$category_data = array_merge($category_data, $this->getCategories($result['category_id']));
			}	
	
			$this->cache->set('category.' . $this->config->get('config_language_id') . '.' . $parent_id, $category_data);
		}
		//echo "here5";
		//die;
		
		return $category_data;
	}
	
	public function getCategories1() {
	$parent_id = 0;
		$category_data = $this->cache->get('category.' . $this->config->get('config_language_id') . '.' . $parent_id);
	//echo "here2";
   // die;
		if (true) {
			$category_data = array();
		//echo "here3";
	    //die;
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' ORDER BY c.sort_order, cd.name ASC");
		//print_r($query);
		//die;
			foreach ($query->rows as $result) {
			//if ($result['category_id'] == '62' && $result['parent_id'] == '0')
			//{
			//}
			//else
			//{
				$category_data[] = array(
					'category_id' 	=> $result['category_id'],
					'name'        	=> $this->getPath($result['category_id'], $this->config->get('config_language_id')),
					'status'  	  	=> $result['status'],
					'sort_order'  	=> $result['sort_order'],
					'parent_id'		=> $result['parent_id']
				);
				
				$category_data = array_merge($category_data, $this->getCategories($result['category_id']));
			//}	
			}
			$this->cache->set('category.' . $this->config->get('config_language_id') . '.' . $parent_id, $category_data);
		}
		//echo "here5";
		//die;
		
		return $category_data;
	}
	
	public function getProductsByCategoryIdArray($categories_array_str, $data) {
		
		$sql = "
			SELECT 
				* 
			FROM " . DB_PREFIX . "product p 
				LEFT JOIN " . DB_PREFIX . "product_description pd 
					ON (p.product_id = pd.product_id)
				LEFT JOIN " . DB_PREFIX . "product_to_category p2c 
					ON (p.product_id = p2c.product_id)  
			WHERE 
				pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
				AND p2c.category_id in (" . $categories_array_str . ") 
		";
		
		 
		
						
	
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$sql .= " AND LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($data['filter_name'])) . "%'";
		}

		if (isset($data['filter_model']) && !is_null($data['filter_model'])) {
			$sql .= " AND LCASE(p.model) LIKE '%" . $this->db->escape(strtolower($data['filter_model'])) . "%'";
		}
		
		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
		}
		
		//if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			//$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
			$sql .= " AND p.status = '1'";
		//}

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.status',
			'p.sort_order'
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
	
	public function getProductsByCategoryId($category_id, $data) {
		$sql = "
			SELECT 
				* 
			FROM " . DB_PREFIX . "product p 
				LEFT JOIN " . DB_PREFIX . "product_description pd 
					ON (p.product_id = pd.product_id)
				LEFT JOIN " . DB_PREFIX . "product_to_category p2c 
					ON (p.product_id = p2c.product_id)  
			WHERE 
				pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
				AND p2c.category_id = '" . (int)$category_id . "' 
		"; 
	
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$sql .= " AND LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($data['filter_name'])) . "%'";
		}

		if (isset($data['filter_model']) && !is_null($data['filter_model'])) {
			$sql .= " AND LCASE(p.model) LIKE '%" . $this->db->escape(strtolower($data['filter_model'])) . "%'";
		}
		
		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
		}
		
		//if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			//$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
			$sql .= " AND p.status = '1'";
		//}

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.status',
			'p.sort_order'
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
}
?>
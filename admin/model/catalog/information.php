<?php
class ModelCatalogInformation extends Model {
	public function addInformation($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "information SET date_added = now(), sort_order = '" . (int)$this->request->post['sort_order'] . "', sitemap = '" . (int)$this->request->post['sitemap'] . "', keyword = '" . $this->request->post['keyword'] . "', location = '" . (int)$this->request->post['location'] . "', type = '" . (int)$this->request->post['type'] . "', status = '" . (int)$this->request->post['status'] . "'");
		
		$information_id = $this->db->getLastId(); 
			
		foreach ($data['information_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "information_description SET information_id = '" . (int)$information_id . "', language_id = '" . (int)$language_id . "', title_tag = '" . $this->db->escape($value['title_tag']) . "', short = '" . $this->db->escape($value['short']) . "', title = '" . $this->db->escape($value['title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keywords = '" . $this->db->escape($value['meta_keywords']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'information_id=" . (int)$information_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		if (isset($data['robots'])) {
			$this->robots($this->db->escape($data['robots']));
		}
		
		$this->cache->delete('information');
	}
	
	public function editInformation($information_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "information SET date_modified = now(), sort_order = '" . (int)$data['sort_order'] . "', keyword = '" . $this->request->post['keyword'] . "', sitemap = '" . (int)$data['sitemap'] . "', location = '" . (int)$data['location'] . "', type = '" . (int)$this->request->post['type'] . "', status = '" . (int)$this->request->post['status'] . "' WHERE information_id = '" . (int)$information_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "information_description WHERE information_id = '" . (int)$information_id . "'");
		
		foreach ($data['information_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "information_description SET information_id = '" . (int)$information_id . "', language_id = '" . (int)$language_id . "', title_tag = '" . $this->db->escape($value['title_tag']) . "', short = '" . $this->db->escape($value['short']) . "', title = '" . $this->db->escape($value['title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keywords = '" . $this->db->escape($value['meta_keywords']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=" . (int)$information_id. "'");
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'information_id=" . (int)$information_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		if (isset($data['robots'])) {
			$this->robots($this->db->escape($data['robots']));
		}
		
		$this->cache->delete('information');
	}
	
	public function robots($rtext) {
	$r1text = str_replace(array("\\r\\n", "\\r", "\\n"), "<br>", $rtext);
	$r1text = str_replace("<br>", "\n", $r1text);
	//echo nl2br($r1text); die;
	//die;
			$myFile = DIR_APPLICATION . "../robots.txt";
			$fh = fopen($myFile, 'w') or die("can't open file");
			fwrite($fh, $r1text);
			fclose($fh);
	}
	
	public function deleteInformation($information_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "information SET status='0' WHERE information_id = '" . (int)$information_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "information_description WHERE information_id = '" . (int)$information_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=" . (int)$information_id . "'");
		
		$this->cache->delete('information');
	}
	
	public function getInformation($information_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=" . (int)$information_id . "') AS keyword FROM " . DB_PREFIX . "information WHERE information_id = '" . (int)$information_id . "'");
		
		return $query->row;
	}
	
	public function getInformations($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.location != '6' AND i.location != '7' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
			$sort_data = array(
				'id.title',
				'i.sort_order',
				'i.date_modified',
				'i.sitemap',
				'i.location',
				'i.type'
			);
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY id.title";
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
			$information_data = $this->cache->get('information.' . $this->config->get('config_language_id'));
		
			if ( ! $information_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.location != '6' AND i.location != '7' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");
			
				$information_data = $query->rows;
			
				$this->cache->set('information.' . $this->config->get('config_language_id'), $information_data);
			}
		
			return $information_data;

		}
	}
	
	public function getBlog($data = array()) {

		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.location = '7' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
			$sort_data = array(
				'id.title',
				'i.sort_order',
				'i.date_modified',
				'i.sitemap',
				'i.location',
				'i.type'
			);
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY id.title";
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
			$information_data = $this->cache->get('information.' . $this->config->get('config_language_id'));
		
			if ( ! $information_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.location = '7' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");
			
				$information_data = $query->rows;
			
				$this->cache->set('information.' . $this->config->get('config_language_id'), $information_data);
			}
		
			return $information_data;
		}
	}	

	public function getArticles($data = array()) {

		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.location = '6' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
			$sort_data = array(
				'id.title',
				'i.sort_order',
				'i.date_modified',
				'i.sitemap',
				'i.location',
				'i.type'
			);
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY id.title";
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
			$information_data = $this->cache->get('information.' . $this->config->get('config_language_id'));
		
			if ( ! $information_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.location = '6' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");
			
				$information_data = $query->rows;
			
				$this->cache->set('information.' . $this->config->get('config_language_id'), $information_data);
			}
		
			return $information_data;
		}
	}	

	public function getInformationsType($type) {
			$sql = "SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.type = '" . $type . "'";
			
			$query = $this->db->query($sql);
			
			return $query->rows;
	}
	
	public function geteInformations($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.location != '6' AND i.status='1' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
			$sort_data = array(
				'id.title',
				'i.sort_order',
				'i.date_modified',
				'i.sitemap',
				'i.location',
				'i.type'
			);
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY id.title";
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
			$information_data = $this->cache->get('information.' . $this->config->get('config_language_id'));
		
			if ( ! $information_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.location != '6' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");
			
				$information_data = $query->rows;
			
				$this->cache->set('information.' . $this->config->get('config_language_id'), $information_data);
			}
		
			return $information_data;

		}
	}
	
	public function geteArticles($data = array()) {

		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.location = '6' AND i.status='1' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
			$sort_data = array(
				'id.title',
				'i.sort_order',
				'i.date_modified',
				'i.sitemap',
				'i.location',
				'i.type'
			);
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY id.title";
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
			$information_data = $this->cache->get('information.' . $this->config->get('config_language_id'));
		
			if ( ! $information_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.location = '6' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");
			
				$information_data = $query->rows;
			
				$this->cache->set('information.' . $this->config->get('config_language_id'), $information_data);
			}
		
			return $information_data;
		}
	}	
	
	public function geteBlog($data = array()) {

		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.location = '7' AND i.status='1' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
			$sort_data = array(
				'id.title',
				'i.sort_order',
				'i.date_modified',
				'i.sitemap',
				'i.location',
				'i.type'
			);
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY id.title";
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
			$information_data = $this->cache->get('information.' . $this->config->get('config_language_id'));
		
			if ( ! $information_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.location = '7' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");
			
				$information_data = $query->rows;
			
				$this->cache->set('information.' . $this->config->get('config_language_id'), $information_data);
			}
		
			return $information_data;
		}
	}	

	public function getdInformations($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.location != '6' AND i.status='0' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
			$sort_data = array(
				'id.title',
				'i.sort_order',
				'i.date_modified',
				'i.sitemap',
				'i.location',
				'i.type'
			);
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY id.title";
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
			$information_data = $this->cache->get('information.' . $this->config->get('config_language_id'));
		
			if ( ! $information_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.location != '6' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");
			
				$information_data = $query->rows;
			
				$this->cache->set('information.' . $this->config->get('config_language_id'), $information_data);
			}
		
			return $information_data;

		}
	}
	
	public function getdArticles($data = array()) {

		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.location = '6' AND i.status='0' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
			$sort_data = array(
				'id.title',
				'i.sort_order',
				'i.date_modified',
				'i.sitemap',
				'i.location',
				'i.type'
			);
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY id.title";
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
			$information_data = $this->cache->get('information.' . $this->config->get('config_language_id'));
		
			if ( ! $information_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.location = '6' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");
			
				$information_data = $query->rows;
			
				$this->cache->set('information.' . $this->config->get('config_language_id'), $information_data);
			}
		
			return $information_data;
		}
	}	

	public function getdBlog($data = array()) {

		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.location = '7' AND i.status='0' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
			$sort_data = array(
				'id.title',
				'i.sort_order',
				'i.date_modified',
				'i.sitemap',
				'i.location',
				'i.type'
			);
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY id.title";
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
			$information_data = $this->cache->get('information.' . $this->config->get('config_language_id'));
		
			if ( ! $information_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.location = '7' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");
			
				$information_data = $query->rows;
			
				$this->cache->set('information.' . $this->config->get('config_language_id'), $information_data);
			}
		
			return $information_data;
		}
	}	

	public function geteInformationsType($type) {
			$sql = "SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.type = '" . $type . "' AND i.status='1'";
			
			$query = $this->db->query($sql);
			
			return $query->rows;
	}
	
	public function getdInformationsType($type) {
			$sql = "SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.type = '" . $type . "' AND i.status='0'";
			
			$query = $this->db->query($sql);
			
			return $query->rows;
	}
	
	
	public function getInformationsforSitemap() {
		
			//print_r("SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.sitemap = 1 and id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");
			//exit();
			//$information_data = $this->cache->get('information.' . $this->config->get('config_language_id'));
			
			//if ( ! $information_data) {
				
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.sitemap = 1 AND i.status='1' and id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");
								
				$information_data = $query->rows;
				
				
				$this->cache->set('information.' . $this->config->get('config_language_id'), $information_data);
			//}
			
			return $information_data;
		
	}
	
	public function getInformationDescriptions($information_id) {
		$information_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_description WHERE information_id = '" . (int)$information_id . "'");
		
		foreach ($query->rows as $result) {
			$information_description_data[$result['language_id']] = array(
				'title'            => $result['title'],
				'title_tag'        => $result['title_tag'],
				'short'      => $result['short'],
				'description'      => $result['description'],
				'meta_description' => $result['meta_description'],
				'meta_keywords'    => $result['meta_keywords']
			);
		}
		
		return $information_description_data;
	}
	
	public function getTotalInformations() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "information WHERE  location != '6' AND location != '6'");
		
		return $query->row['total'];
	}	
		public function getTotalArticles() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "information WHERE  location = '6'");
		
		return $query->row['total'];
	}
		public function getTotalBlog() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "information WHERE  location = '7'");
		
		return $query->row['total'];
	}
		public function geteTotalInformations() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "information WHERE  location != '6' AND location != '7' AND status='1'");
		
		return $query->row['total'];
	}	
		public function geteTotalArticles() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "information WHERE  location = '6' AND status='1'");
		
		return $query->row['total'];
	}
		public function geteTotalBlog() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "information WHERE  location = '7' AND status='1'");
		
		return $query->row['total'];
	}
		public function getdTotalInformations() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "information WHERE  location != '6' AND location != '7' AND status='0'");
		
		return $query->row['total'];
	}	
		public function getdTotalArticles() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "information WHERE  location = '6' AND status='0'");
		
		return $query->row['total'];
	}
		public function getdTotalBlog() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "information WHERE  location = '7' AND status='0'");
		
		return $query->row['total'];
	}
}
?>
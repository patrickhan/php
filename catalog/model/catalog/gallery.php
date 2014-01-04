<?php
class ModelCatalogGallery extends Model {
	public function getAlbums($sort , $order = 'ASC', $start = 0, $limit = 8) {
		$sql = "SELECT * FROM " . DB_PREFIX . "gallery_album";
		
		$sort_data = array(
			'name',
			'sort_order',
			'date_added',
			'viewed'
		);
			
		if (in_array($sort, $sort_data)) {
			if ($sort == 'name') {
				$sql .= " ORDER BY LCASE(" . $sort . ")";
			} else {
				$sql .= " ORDER BY " . $sort;
			}
		} else {
			$sql .= " ORDER BY sort_order";	
		}
			
		if ($order == 'DESC') {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if ($start < 0) {
			$start = 0;
		}
		
		$sql .= " LIMIT " . (int)$start . "," . (int)$limit;
				
		$query = $this->db->query($sql);
								  
		return $query->rows;
	} 
	
	public function getTotalAlbum() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gallery_album WHERE status = 1 ");
		
		return $query->row['total'];
	}
	
	public function getImagesbyAlbumID($album_id, $sort , $order = 'ASC', $start = 0, $limit) {
		$sql = "SELECT * FROM " . DB_PREFIX . "gallery_image LEFT JOIN " . DB_PREFIX . "gallery_image_to_album ON (" . DB_PREFIX . "gallery_image.image_id = " . DB_PREFIX . "gallery_image_to_album.image_id) WHERE " . DB_PREFIX . "gallery_image_to_album.album_id = " . (int)$album_id . " ";
		
		$sort_data = array(
			'name',
			'sort_order',
			'date_added',
			'viewed'
		);
			
		if (in_array($sort, $sort_data)) {
			if ($sort == 'name') {
				$sql .= " ORDER BY LCASE(" . $sort . ")";
			} else {
				$sql .= " ORDER BY " . $sort;
			}
		} else {
			$sql .= " ORDER BY sort_order";	
		}
			
		if ($order == 'DESC') {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if ($start < 0) {
			$start = 0;
		}
		
		$sql .= " LIMIT " . (int)$start . "," . (int)$limit;
				
		$query = $this->db->query($sql);
								  
		return $query->rows;
	}

	public function updateViewed($album_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "gallery_album SET viewed = viewed + 1 WHERE album_id = '" . (int)$album_id . "'");
	}
	
	public function getTotalImage($album_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM ". DB_PREFIX . "gallery_image_to_album i2a  LEFT JOIN " . DB_PREFIX . "gallery_image i ON (i2a.image_id = i.image_id) WHERE i2a.album_id = '" . (int)$album_id . "'");
		
		return $query->row['total'];
	}
	
	public function getAlbum($album_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "gallery_album WHERE album_id = '" . (int)$album_id . "'");
		
		return $query->row;
	}
	
	public function getCategories($parent_id = 0) {

		$category_data = $this->cache->get('category.' . $parent_id . '.' . $this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'));

		if (!$category_data && !is_array($category_data)) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' AND c.sort_order <> '-1' ORDER BY c.sort_order, LCASE(cd.name)");

			$category_data = $query->rows;

			$this->cache->set('category.' . $parent_id . '.' . $this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'), $category_data);
		}

		return $category_data;
	}
			
}
?>
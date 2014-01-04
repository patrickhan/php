<?php
class ModelCatalogGalleryimage extends Model {	
	/*GALLERY IMAGE*/
	public function addImage($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_image SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW(), date_added = NOW()");
		
		$image_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gallery_image SET image = '" . $this->db->escape($data['image']) . "' WHERE image_id = '" . (int)$image_id . "'");
		}
		
		if (isset($data['image_album'])) {
			foreach ($data['image_album'] as $album_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_image_to_album SET image_id = '" . (int)$image_id . "', album_id = '" . (int)$album_id . "'");
			}
		}
			
		$this->cache->delete('image');
	}
	
	public function editImage($image_id, $data) {
      	$this->db->query("UPDATE " . DB_PREFIX . "gallery_image SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE image_id = '" . (int)$image_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gallery_image SET image = '" . $this->db->escape($data['image']) . "' WHERE image_id = '" . (int)$image_id . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery_image_to_album WHERE image_id = '" . (int)$image_id . "'");
		
		if (isset($data['image_album'])) {
			foreach ($data['image_album'] as $album_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_image_to_album SET image_id = '" . (int)$image_id . "', album_id = '" . (int)$album_id . "'");
			}
		}
				
		$this->cache->delete('image');
	}
	
	public function getImages($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "gallery_image";
			
			$sort_data = array(
				'name',
				'sort_order'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY name";	
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
			$image_data = $this->cache->get('gallery_image');
		
			if (!$image_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery_image ORDER BY name");
	
				$image_data = $query->rows;
			
				$this->cache->set('gallery_image', $image_data);
			}
		 
			return $image_data;
		}
	}
	
	public function getImage($image_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "gallery_image WHERE image_id = '" . (int)$image_id . "'");
		
		return $query->row;
	}
	
	public function getTotalImages() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gallery_image");
		
		return $query->row['total'];
	}
	
	public function deleteImage($image_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery_image WHERE image_id = '" . (int)$image_id . "'");
			
		$this->cache->delete('image');
	}	

	public function getImageAlbums($image_id) {
		$image_album_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery_image_to_album WHERE image_id = '" . (int)$image_id . "'");

		foreach ($query->rows as $result) {
			$image_album_data[] = $result['album_id'];
		}
		
		return $image_album_data;
	}
	
	public function getAlbums($data = array()) {
		$album_data = $this->cache->get('album');
	
		if (!$album_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery_album ORDER BY name");

			$album_data = $query->rows;
		
			$this->cache->set('album', $album_data);
		}
	 
		return $album_data;
	}

}
?>
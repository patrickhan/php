<?php
class ModelCatalogGalleryalbum extends Model {	
	/*GALLERY ALBUM*/
	public function addAlbum($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_album SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");
		
		$album_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gallery_album SET image = '" . $this->db->escape($data['image']) . "' WHERE album_id = '" . (int)$album_id . "'");
		}
			
		$this->cache->delete('album');
	}
	
	public function editAlbum($album_id, $data) {
      	$this->db->query("UPDATE " . DB_PREFIX . "gallery_album SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE album_id = '" . (int)$album_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gallery_album SET image = '" . $this->db->escape($data['image']) . "' WHERE album_id = '" . (int)$album_id . "'");
		}
				
		$this->cache->delete('album');
	}
	
	public function getAlbums($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "gallery_album";
			
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
			$album_data = $this->cache->get('gallery_album');
		
			if (!$album_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery_album ORDER BY name");
	
				$albumr_data = $query->rows;
			
				$this->cache->set('gallery_album', $album_data);
			}
		 
			return $album_data;
		}
	}
	
	public function getAlbum($album_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "gallery_album WHERE album_id = '" . (int)$album_id . "'");
		
		return $query->row;
	}
	
	public function getTotalAlbums() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gallery_album");
		
		return $query->row['total'];
	}
	
	public function deleteAlbum($album_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery_album WHERE album_id = '" . (int)$album_id . "'");
			
		$this->cache->delete('album');
	}		
}
?>
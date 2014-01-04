<?php
class ModelCheckoutExtension extends Model {
	function getExtensions($type) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");

		return $query->rows;
	}
	
	function getRandomBanner($group) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banners WHERE `group` = '" . $this->db->escape($group) . "' AND `status` = '1' AND IF(start_date = '' OR start_date <= NOW(), 1, 0) = 1 AND IF(end_date = '' OR end_date >= NOW(), 1, 0) = 1 ORDER BY RAND() LIMIT 1");
		
		$result = array();
		foreach ($query->rows as $row) {
			$result[] = array(
				'banner_id'     => $row['banner_id'],
				'image'         => $row['image'],
				'url'           => urlencode($row['url']),
				'html'          => $row['html'],
				'title'         => $row['title'],
				'start_date'    => $row['start_date'],
				'end_date'      => $row['end_date'],
				'group'         => $row['group'],
				'status'        => $row['status'],
				'date_added'    => $row['date_added'],
				'date_modified' => $row['date_modified']
			);
		}
		
		return $result;
	}
	
	function getPageBanner($group, $route) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banners b LEFT JOIN " . DB_PREFIX . "banner_pages bp ON b.banner_id = bp.banner_id  WHERE bp.`action`= '" . $this->db->escape($route) . "' AND b.`group` = '" . $this->db->escape($group) . "' AND b.`status` = '1' AND IF(b.start_date = '' OR b.start_date <= NOW(), 1, 0) = 1 AND IF(b.end_date = '' OR b.end_date >= NOW(), 1, 0) = 1 ORDER BY RAND() LIMIT 1");
		
		$result = array();
		foreach ($query->rows as $row) {
			$result[] = array(
				'banner_id'     => $row['banner_id'],
				'image'         => $row['image'],
				'url'           => urlencode($row['url']),
				'html'          => $row['html'],
				'title'         => $row['title'],
				'start_date'    => $row['start_date'],
				'end_date'      => $row['end_date'],
				'group'         => $row['group'],
				'status'        => $row['status'],
				'date_added'    => $row['date_added'],
				'date_modified' => $row['date_modified']
			);
		}
		
		return $result;
	}
	
	function recordBannerClick($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_stats WHERE `banner_id` = '" . $this->db->escape($id) . "' AND `date` = CURDATE()");
		if ($query->rows) {
			$this->db->query("UPDATE " . DB_PREFIX . "`banner_stats` SET `clicks` = `clicks` + 1 WHERE `banner_id` = '" . $this->db->escape($id) . "' AND `date` = CURDATE()");
		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "`banner_stats` (`banner_id`, `clicks`, `date`) VALUES ('" . $this->db->escape($id) . "', '1', CURDATE())");
		}
	}
	
	function recordBannerView($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_stats WHERE `banner_id` = '" . $this->db->escape($id) . "' AND `date` = CURDATE()");
		if ($query->rows) {
			$this->db->query("UPDATE " . DB_PREFIX . "`banner_stats` SET `views` = `views` + 1 WHERE `banner_id` = '" . $this->db->escape($id) . "' AND `date` = CURDATE()");
		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "`banner_stats` (`banner_id`, `views`, `date`) VALUES ('" . $this->db->escape($id) . "', '1', CURDATE())");
		}
	}
}
?>
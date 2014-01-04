<?php
class ModelExtensionBanners extends Model {
	public function addBanner($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "`banners` (`banner_id`, `image`, `url`, `html`, `title`, `start_date`, `end_date`, `group`, `status`, `date_added`, `date_modified`) VALUES (NULL, '" . $this->db->escape($data['image']) . "', '" . $this->db->escape($data['url']) . "', '" . $this->db->escape($data['html']) . "', '" . $this->db->escape($data['title']) . "', '" . $this->db->escape($data['start_date']) . "', '" . $this->db->escape($data['end_date']) . "', '" . $this->db->escape($data['group']) . "', '" . $this->db->escape($data['status']) . "', NOW(), NOW())");
		
		if (isset($data['selected'])) {
			foreach ($data['selected'] as $action) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "`banner_pages` (`banner_id`, `action`, `params`) VALUES ('" . $this->db->getLastId() . "', '" . $this->db->escape($action) . "', '')");
			}
		}
	}
	
	public function editBanner($banner_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "`banners` SET `url` = '" . $this->db->escape($data['url']) . "', 
		`title` = '" . $this->db->escape($data['title']) . "', `image` = '" . $this->db->escape($data['image']) . "', 
		`status` = '" . $this->db->escape($data['status']) . "', `html` = '" . $this->db->escape($data['html']) . "',
		`group` = '" . $this->db->escape($data['group']) . "', `start_date` = '" . $this->db->escape($data['start_date']) . "',
		`end_date` = '" . $this->db->escape($data['end_date']) . "', `date_modified` = NOW() WHERE `banner_id` = '" . $this->db->escape($banner_id) . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "banner_pages WHERE banner_id = '" . $this->db->escape($banner_id) . "'" );
		
		if (isset($data['selected'])) {
			foreach ($data['selected'] as $action) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "`banner_pages` (`banner_id`, `action`, `params`) VALUES ('" . $this->db->escape($banner_id) . "', '" . $this->db->escape($action) . "', '')");
			}
		}
	}
	
	public function deleteBanners($selected) {
		$selected_str = '';
		
		foreach ($selected as $banner_id) {
			$selected_str .= "'$banner_id',";
		}
		$selected_str = substr($selected_str, 0, -1);
		$this->db->query("DELETE FROM " . DB_PREFIX . "banners WHERE banner_id IN (" . $selected_str . ")");
		$this->db->query("DELETE FROM " . DB_PREFIX . "banner_stats WHERE banner_id IN (" . $selected_str . ")");
		$this->db->query("DELETE FROM " . DB_PREFIX . "banner_pages WHERE banner_id IN (" . $selected_str . ")");
	}
	
	public function deleteBanner($banner_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "banners WHERE banner_id = '" . $this->db->escape($banner_id) . "'" );
		$this->db->query("DELETE FROM " . DB_PREFIX . "banner_stats WHERE banner_id = '" . $this->db->escape($banner_id) . "'" );
		$this->db->query("DELETE FROM " . DB_PREFIX . "banner_pages WHERE banner_id = '" . $this->db->escape($banner_id) . "'" );
	}
	
	public function getBanner($banner_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banners WHERE banner_id = '" . $this->db->escape($banner_id) . "'");
		
		return $query->row;
	}
	
	public function getBannerPages($banner_id) {
		$pages = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_pages WHERE banner_id = '" . $this->db->escape($banner_id) . "'");
		
		foreach ($query->rows as $row) {
			$pages[] = $row['action'];
		}
		return $pages;
	}
	
	public function getBanners() {
		$banner_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banners ORDER BY date_added DESC");
		
		foreach ($query->rows as $result) {
			$banner_data[] = array(
				'banner_id' => $result['banner_id'],
				'title' => $result['title'],
				'group' => $result['group'],
				'status' => $result['status'],
				'date_added' => $result['date_added'],
				'date_modified' => $result['date_modified']
			);
		}
		
		return $banner_data;
	}
	
	public function getTotalBanners() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "banners");
		
		return $query->row['total'];
	}
	
	public function getBannerGroups() {
		$banner_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_groups ORDER BY group_id DESC");
		
		foreach ($query->rows as $result) {
			$banner_data[$result['group_id']] = array(
				'name' => $result['name'],
				'group_id' => $result['group_id']
			);
		}	
		
		return $banner_data;
	}
	
	public function getBannerStats($banner_id) {
		$banner_stats = array();
		
		$stats = $this->db->query("SELECT `banner_id`, `date`, `views`, `clicks` FROM `banner_stats` WHERE `banner_id` = '" . (int)$banner_id . "' ORDER BY `date` DESC");
		
		foreach ($stats->rows as $result) {
			$banner_stats[] = array(
				'date'   => $result['date'],
				'views'  => $result['views'],
				'clicks' => $result['clicks']
			);
		}
		
		return $banner_stats;
	}
	
	public function getBannersStats() {
		$banner_data = array();
		
		$banners = $this->db->query("SELECT `banner_id`, `image`, `url`, `html`, `title`, UNIX_TIMESTAMP(`start_date`) as 'start_date', UNIX_TIMESTAMP(`date_modified`) as 'date_modified', UNIX_TIMESTAMP(`date_added`) as 'date_added', `status`, `group`, UNIX_TIMESTAMP(`end_date`) as 'end_date' FROM `" . DB_PREFIX . "banners` ORDER BY `date_added` DESC");
		
		$stats = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_stats");
		
		foreach ($banners->rows as $banner) {
			$banner_data[$banner['banner_id']] = $banner;
		}
		
		foreach ($stats->rows as $stat) {
			if (isset($banner_data[$stat['banner_id']]['views'])) {
				$banner_data[$stat['banner_id']]['views'] = $banner_data[$stat['banner_id']]['views'] + $stat['views'];
			} else {
				$banner_data[$stat['banner_id']]['views'] = $stat['views'];
			}
			if (isset($banner_data[$stat['banner_id']]['clicks'])) {
				$banner_data[$stat['banner_id']]['clicks'] = $banner_data[$stat['banner_id']]['clicks'] + $stat['clicks'];
			} else {
				$banner_data[$stat['banner_id']]['clicks'] = $stat['clicks'];
			}
		}
		
		return $banner_data;
	}
}
?>
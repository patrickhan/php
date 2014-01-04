<?php
class ModelExtensionHeaderimage extends Model {
	public function addHeader($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "`header_images` (`header_id`, `image`, `url`, `title`, `sort`, `start_date`, `end_date`, `status`, `date_added`, `date_modified`) VALUES (NULL, '" . $this->db->escape($data['image']) . "', '" . $this->db->escape($data['url']) . "', '" . $this->db->escape($data['title']) . "', '" . $this->db->escape($data['sort']) . "', '" . $this->db->escape($data['start_date']) . "', '" . $this->db->escape($data['end_date']) . "', '" . $this->db->escape($data['status']) . "', NOW(), NOW())");

	}
	public function editHeader($header_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "`header_images` SET `url` = '" . $this->db->escape($data['url']) . "', 
		`title` = '" . $this->db->escape($data['title']) . "', `sort` = '" . $this->db->escape($data['sort']) . "', `image` = '" . $this->db->escape($data['image']) . "', 
		`status` = '" . $this->db->escape($data['status']) . "', `start_date` = '" . $this->db->escape($data['start_date']) . "',
		`end_date` = '" . $this->db->escape($data['end_date']) . "', `date_modified` = NOW() WHERE `header_id` = '" . $this->db->escape($header_id) . "'");

	}
	
	public function editXML() {
		//echo "XML Edited";
		$xml = "<?xml version='1.0' encoding='utf-8' ?>
<cu3er>
	<settings>
		<preloader>
		</preloader>
		<auto_play>
			<defaults symbol='circular' time='8' />
			<tweenIn x='900' y='430' width='35' height='35' tint='0xFFFFFF' />
		</auto_play>
		<description>
			<defaults
				round_corners='0, 0, 0, 0'
				
				heading_font='Century Gothic'
				heading_text_size='22'
				heading_text_color='0xFFFFFF'          
				heading_text_margin='10, 0, 0,10'  
				
				paragraph_font='Century Gothic'
				paragraph_text_size='13'
				paragraph_text_color='0xcbcbcb'
				paragraph_text_margin='10, 0, 0, 10'       
			/>
		</description>
	</settings>    

	<slides>";
	$amount = $this->getTotalEnabledHeaders();
	$all = $this->getEnabledHeaders();
	$xml .= "<slide>
       		<url>image/" . $all[0]['image'] . "</url>
        </slide>";
	
	for ( $counter = 1; $counter < $amount; $counter ++) {
	$slic = "";
	if ($counter % 3 == 0) {
	$direction = "down";
	$num = 3;
	}
	elseif ($counter % 2 == 0) {
	$direction = "right";
	$num = 4;
	}
	else {
	$direction = "up";
	$num = 6;
	}
	
	if ($counter % 2 == 0) {
	//nothing! :D
	}
	else {
	$slic = " slicing='vertical'";
	}
	$xml .= "<transition num='$num'$slic direction='$direction'/>
        <slide>
       		<url>image/" . $all[$counter]['image'] . "</url>
		
        </slide>";
	}
	$xml .= "</slides>
</cu3er>";

$file = fopen(DIR_CATALOG . "view/theme/default/xml/slider.xml", 'w');

fwrite($file, $xml);

fclose($file);
}
	
	public function deleteHeaderImages($selected) {
		$selected_str = '';
		
		foreach ($selected as $header_id) {
			$selected_str .= "'$header_id',";
		}
		$selected_str = substr($selected_str, 0, -1);
		$this->db->query("DELETE FROM " . DB_PREFIX . "header_images WHERE header_id IN (" . $selected_str . ")");
	}
	
	public function deleteHeaderImage($header_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "header_images WHERE header_id = '" . $this->db->escape($header_id) . "'" );
	}
	
	public function getHeaderImage($header_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "header_images WHERE header_id = '" . $this->db->escape($header_id) . "'");
		
		return $query->row;
	}
		
	public function getTemplate($template) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "header_templates WHERE path = '" . $this->db->escape($template) . "'");
		
		return $query->row;
	}
	
	public function getHeaderPages($header_id) {
		$pages = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_pages WHERE header_id = '" . $this->db->escape($header_id) . "'");
		
		foreach ($query->rows as $row) {
			$pages[] = $row['action'];
		}
		return $pages;
	}
	
	public function getHeaders() {
		$banner_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "header_images ORDER BY sort ASC");
		
		foreach ($query->rows as $result) {
			$banner_data[] = array(
				'header_id' => $result['header_id'],
				'title' => $result['title'],
				'image' => $result['image'],
				'status' => $result['status'],
				'sort' => $result['sort'],
				'date_added' => $result['date_added'],
				'date_modified' => $result['date_modified']
			);
		}
		
		return $banner_data;
	}
	
	public function getTotalHeaders() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "header_images");
		
		return $query->row['total'];
	}
	
	public function getEnabledHeaders() {
		$banner_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "header_images WHERE status=1 ORDER BY sort ASC");
		
		foreach ($query->rows as $result) {
			$banner_data[] = array(
				'header_id' => $result['header_id'],
				'title' => $result['title'],
				'image' => $result['image'],
				'status' => $result['status'],
				'sort' => $result['sort'],
				'date_added' => $result['date_added'],
				'date_modified' => $result['date_modified']
			);
		}
		
		return $banner_data;
	}
	
	public function getTotalEnabledHeaders() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "header_images WHERE status=1");
		
		return $query->row['total'];
	}
		
}
?>
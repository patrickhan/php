<?php
class ModelAccountWhosOnline extends Model {
	public function updateWhosOnline($customer_id, $name, $ip, $url, $session_id) {
		$time_ago = time() - 60 * 5;
		$this->db->query("DELETE FROM " . DB_PREFIX . "whos_online WHERE time_last_click < '" . $time_ago . "'");
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "whos_online WHERE session_id = '" . $this->db->escape($session_id) . "'");
		if ($query->num_rows) {
			$this->db->query("UPDATE " . DB_PREFIX . "whos_online SET 
				customer_id = '" . (int)$customer_id . "', 
				full_name = '" . $this->db->escape($name) . "', 
				time_last_click = '" . time() . "',
				last_page_url = '" . $this->db->escape($url) . "',
				ip_address = '" . $this->db->escape($ip) . "'
				WHERE session_id = '" . $this->db->escape($session_id) . "'"
			);
		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "whos_online SET 
				customer_id = '" . (int)$customer_id . "', 
				full_name = '" . $this->db->escape($name) . "', 
				session_id = '" . $this->db->escape($session_id) . "',
				time_entry = '" . time() . "',
				time_last_click = '" . time() . "',
				last_page_url = '" . $this->db->escape($url) . "',
				ip_address = '" . $this->db->escape($ip) . "'"
			);
		}	
	}

}
?>
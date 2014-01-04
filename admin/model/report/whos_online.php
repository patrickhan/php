<?php
class ModelReportWhosOnline extends Model {
	public function getWhosOnline($limit = 20) {
		$time_ago = time() - 60 * 5;
		$this->db->query("DELETE FROM " . DB_PREFIX . "whos_online WHERE time_last_click < '" . $time_ago . "'");
		
		if ($limit < 1) {
			$limit = 20;
		}
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "whos_online ORDER BY `time_last_click` DESC LIMIT " . (int)$limit);
		
		$whos_that_online = array();
		foreach ($query->rows as $row) {
			$whos_that_online[$row['session_id']]['customer_id'] = $row['customer_id'];
			$whos_that_online[$row['session_id']]['full_name'] = $row['full_name'];
			$whos_that_online[$row['session_id']]['session_id'] = $row['session_id'];
			$whos_that_online[$row['session_id']]['ip_address'] = $row['ip_address'];
			$whos_that_online[$row['session_id']]['time_entry'] = date('G:i:s T', $row['time_entry']);
			$whos_that_online[$row['session_id']]['time_last_click'] = date('G:i:s T', $row['time_last_click']);
			$whos_that_online[$row['session_id']]['last_page_url'] = $row['last_page_url'];
		}
		
		return $whos_that_online;
	}	
}
?>
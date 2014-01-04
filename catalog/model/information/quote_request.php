<?php
class ModelInformationQuoteRequest extends Model {
	public function getSocialMediaLinks() {
		$social_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `group` = 'quote_request' ORDER by setting_id ASC");
		
		foreach ($query->rows as $result) {
			$social_data[] = array(
				'setting_id' => $result['setting_id'],
				'key'        => $result['key'],
				'value'      => $result['value']
			);
		}		
		return $social_data;
	}
}
?>
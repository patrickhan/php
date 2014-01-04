<?php
class ModelCatalogNewsletter extends Model {
	public function addEmail($email_address) {
		return $this->db->query("INSERT INTO " . DB_PREFIX . "newsletter_signups SET email = '" . $this->db->escape($email_address) . "', date_added = NOW()");
	}
	
}
?>
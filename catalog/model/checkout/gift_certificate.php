<?php
class ModelCheckoutGiftCertificate extends Model {
	public function getGiftCertificate($serial) {
		
		$gift_certificate_query = $this->db->query(
			"SELECT `gift_certificate_id`, `customer_id`, `date_purchased`, `order_id`, `amount`, `serial`, `ip_used`, `order_id_used`, `date_used`
			FROM `" . DB_PREFIX . "gift_certificates`
			WHERE `serial` = '" . $this->db->escape($serial) . "'
			LIMIT 1"
		);
		
		return $gift_certificate_query->row;
	}
	
	public function checkGiftCertificate($serial) {
		$sql = "SELECT `date_used`
			FROM `" . DB_PREFIX . "gift_certificates`
			WHERE `serial` = '" . $this->db->escape($serial) . "'
			LIMIT 1";
		$gift_certificate_query = $this->db->query($sql);
		
		if ($gift_certificate_query->num_rows) {
			return (isset($gift_certificate_query->row) && empty($gift_certificate_query->row->date_used)) ? TRUE : FALSE;
		} else {
			return FALSE;
		}
	}
	
	public function useGiftCertificate($data) {
		$this->db->query(
			"UPDATE `" . DB_PREFIX . "gift_certificates`
			SET `ip_used` = '" . $this->db->escape($data['ip_used']) . "',
			`order_id_used` = '" . $this->db->escape($data['order_id_used']) . "',
			`date_used` = NOW()
			WHERE `serial` = '" . $this->db->escape(trim($data['serial'])) . "'"
		);
	}
}
?>
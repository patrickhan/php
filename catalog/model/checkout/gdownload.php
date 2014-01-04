<?php
class ModelCheckoutGDownload extends Model {

	public function getDownload($order_reference,$product_id,$download_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "google_download` gd WHERE gd.order_reference = '$order_reference' AND gd.product_id='$product_id' AND gd.download_id='$download_id' AND gd.remaining > 0;");
		return $query->row;
	}
	
	public function getDownloads($order_reference,$start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}
		$query = $this->db->query("SELECT gd.* FROM `".DB_PREFIX."google_download` gd WHERE gd.order_reference='$order_reference' ORDER BY gd.name DESC LIMIT " . (int)$start . "," . (int)$limit . ";" );
		return $query->rows;
	}
	
	public function updateRemaining($order_reference,$product_id,$download_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "google_download` SET remaining = (remaining - 1) WHERE order_reference='$order_reference' AND product_id='$product_id' AND download_id='$download_id';");
	}
	
	public function getTotalDownloads($order_reference) {
		if ($order_reference==NULL) {
			return 0;
		}
		$sql = "SELECT COUNT(*) AS total FROM `".DB_PREFIX."google_download` gd WHERE gd.order_reference='$order_reference';";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
}
?>
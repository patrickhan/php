<?php  
class ModelSaleOrder extends Model {
	public function editOrder($order_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$data['order_status_id'] . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$data['order_status_id'] . "', notify = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");
		
		if (isset($data['notify'])) {
			$query = $this->db->query("SELECT *, os.name AS status FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id AND os.language_id = o.language_id) LEFT JOIN " . DB_PREFIX . "language l ON (o.language_id = l.language_id) WHERE o.order_id = '" . (int)$order_id . "'");
			
			if ($query->num_rows) {
				$language = new Language($query->row['directory']);
				$language->load($query->row['filename']);
				$language->load('mail/order');
				
				$subject = sprintf($language->get('text_subject'), html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8'), $order_id);
				
				$message  = $language->get('text_order') . ' ' . $order_id . "\n";
				$message .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($query->row['date_added'])) . "\n\n";
				$message .= $language->get('text_order_status') . "\n\n";
				$message .= $query->row['status'] . "\n\n";
				$message .= $language->get('text_invoice') . "\n";
				$message .= html_entity_decode(HTTP_CATALOG . 'index.php?route=account/invoice&order_id=' . $order_id, ENT_QUOTES, 'UTF-8') . "\n\n";
				
				if ($data['comment']) { 
					$message .= $language->get('text_comment') . "\n\n";
					$message .= strip_tags(html_entity_decode($data['comment'], ENT_QUOTES, 'UTF-8')) . "\n\n";
				}
				
				if ($data['tracking_info']) { 
					$message .= $language->get('text_tracking_info') . "\n\n";
					$message .= strip_tags(html_entity_decode($data['tracking_info'], ENT_QUOTES, 'UTF-8')) . "\n\n";
				}
				
				$message .= $language->get('text_footer');
				
				$mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), html_entity_decode($this->config->get('config_smtp_password'), ENT_QUOTES, 'UTF-8'), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout'));
				$mail->setTo($query->row['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8'));
				$mail->setSubject($subject);
				$mail->setText($message);
				$mail->send();
			}
		}
	}
	
	public function deleteOrder($order_id) {
		if (false) {//if ($this->config->get('config_stock_subtract')) {
			$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND order_id = '" . (int)$order_id . "'");
			
			if ($order_query->num_rows) {
				$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
				
				foreach($product_query->rows as $product) {
					$this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_id = '" . (int)$product['product_id'] . "'");
					
					$option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");
					
					foreach ($option_query->rows as $option) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}
			}
		}
		
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id='-1' WHERE order_id = '" . (int)$order_id . "'");		//$this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'");
	}
	
	public function getOrder($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
		
		return $query->row;
	}
	
	public function getOrderSplit($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_split` WHERE order_id = '" . (int)$order_id . "'");
		
		return $query->row;
	}
		
	public function getOrders($data = array()) {
		$sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS name, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.date_added, o.total, o.currency, o.value FROM `" . DB_PREFIX . "order` o";
		
		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0' || o.order_status_id = '-1'";
		}
		
		if (isset($data['filter_order_id']) && !is_null($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}
		
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if (isset($data['filter_total']) && !is_null($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}
		
		$sort_data = array(
			'o.order_id',
			'name',
			'status',
			'o.date_added',
			'o.total',
		);
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY o.order_id";	
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
	}

	public function getdOrders($data = array()) {
		$sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS name, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.date_added, o.total, o.currency, o.value FROM `" . DB_PREFIX . "order` o";
		
		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id = '-1'";
		}
		
		if (isset($data['filter_order_id']) && !is_null($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}
		
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if (isset($data['filter_total']) && !is_null($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}
		
		$sort_data = array(
			'o.order_id',
			'name',
			'status',
			'o.date_added',
			'o.total',
		);
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY o.order_id";	
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
	}

	public function geteOrders($data = array()) {
		$sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS name, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.date_added, o.total, o.currency, o.value FROM `" . DB_PREFIX . "order` o";
		
		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
		
		if (isset($data['filter_order_id']) && !is_null($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}
		
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if (isset($data['filter_total']) && !is_null($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}
		
		$sort_data = array(
			'o.order_id',
			'name',
			'status',
			'o.date_added',
			'o.total',
		);
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY o.order_id";	
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
	}
	
	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
	
		return $query->rows;
	}
	
	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");
	
		return $query->rows;
	}
	
	public function getOrderOptionss($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");
	
		return $query;
	}
	
	public function getOrderTotals($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");
	
		return $query->rows;
	}
	
	public function getOrderHistory($order_id) { 
		$query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added");
	
		return $query->rows;
	}	
	
	public function getOrderDownloads($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "' ORDER BY name");
	
		return $query->rows; 
	}
	
	public function getTotalOrders($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order`";
		
		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE order_status_id > '0' || order_status_id = '-1' ";
		}
		
		if (isset($data['filter_order_id']) && !is_null($data['filter_order_id'])) {
			$sql .= " AND order_id = '" . (int)$data['filter_order_id'] . "'";
		}
		
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if (isset($data['filter_total']) && !is_null($data['filter_total'])) {
			$sql .= " AND total = '" . (float)$data['filter_total'] . "'";
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	} 

	public function getdTotalOrders($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order`";
		
		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE order_status_id = '-1'";
		}
		
		if (isset($data['filter_order_id']) && !is_null($data['filter_order_id'])) {
			$sql .= " AND order_id = '" . (int)$data['filter_order_id'] . "'";
		}
		
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if (isset($data['filter_total']) && !is_null($data['filter_total'])) {
			$sql .= " AND total = '" . (float)$data['filter_total'] . "'";
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	} 

	public function geteTotalOrders($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order`";
		
		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE order_status_id > '0'";
		}
		
		if (isset($data['filter_order_id']) && !is_null($data['filter_order_id'])) {
			$sql .= " AND order_id = '" . (int)$data['filter_order_id'] . "'";
		}
		
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if (isset($data['filter_total']) && !is_null($data['filter_total'])) {
			$sql .= " AND total = '" . (float)$data['filter_total'] . "'";
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	} 
	
	public function getOrderHistoryTotalByOrderStatusId($order_status_id) {
		$query = $this->db->query("SELECT oh.order_id FROM " . DB_PREFIX . "order_history oh LEFT JOIN `" . DB_PREFIX . "order` o ON (oh.order_id = o.order_id) WHERE oh.order_status_id = '" . (int)$order_status_id . "' AND o.order_status_id > '0' GROUP BY order_id");
		
		return $query->num_rows;
	}
	
	public function getTotalOrdersByOrderStatusId($order_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int)$order_status_id . "' AND order_status_id > '0'");
		
		return $query->row['total'];
	}
	
	public function getTotalOrdersByLanguageId($language_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE language_id = '" . (int)$language_id . "' AND order_status_id > '0'");
		
		return $query->row['total'];
	}	
	
	public function getTotalOrdersByCurrencyId($currency_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE currency_id = '" . (int)$currency_id . "' AND order_status_id > '0'");
		
		return $query->row['total'];
	}	
	
	public function getTotalSales() {
		$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0'");
		
		return $query->row['total'];
	}
	
	public function getTotalSalesByYear($year) {
		$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND YEAR(date_added) = '" . (int)$year . "'");
		
		return $query->row['total'];
	}
	
	public function createOrder($data) {
		$currency_query = $this->db->query("SELECT * FROM `currency` WHERE `code` = '" . $this->db->escape($data['payment_currency']) . "'");
		$currency = $currency_query->row;
		$payment_country_query = $this->db->query("SELECT * FROM `country` WHERE `country_id` = '" . $this->db->escape($data['payment_country_id']) . "'");
		$payment_country = $payment_country_query->row;
		$shipping_country_query = $this->db->query("SELECT * FROM `country` WHERE `country_id` = '" . $this->db->escape($data['shipping_country_id']) . "'");
		$shipping_country = $shipping_country_query->row;
		$payment_zone_query = $this->db->query("SELECT * FROM `zone` WHERE `zone_id` = '" . $this->db->escape($data['payment_zone_id']) . "'");
		$payment_zone = $payment_zone_query->row;
		$shipping_zone_query = $this->db->query("SELECT * FROM `zone` WHERE `zone_id` = '" . $this->db->escape($data['shipping_zone_id']) . "'");
		$shipping_zone = $shipping_zone_query->row;
		// language_id has been hard coded :/
		// coupon & points ignored for now ;/
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET customer_id = '" . $this->db->escape($data['customer_id']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', total = '" . (float)$data['total'] . "', language_id = '1', currency = '" . $this->db->escape($data['payment_currency']) . "', currency_id = '" . (int)$currency['currency_id'] . "', value = '" . (float)$data['currency_value'] . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_zone = '" . $this->db->escape($shipping_zone['name']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_country = '" . $this->db->escape($shipping_country['name']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_zone = '" . $this->db->escape($payment_zone['name']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_country = '" . $this->db->escape($payment_country['name']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', comment = '" . $this->db->escape($data['comment']) . "', date_modified = NOW(), date_added = NOW(), order_status_id = '" . (int)$data['order_status_id'] . "'");
		
		$order_id = $this->db->getLastId();
		
		foreach (array_keys($data['product_name']) as $order_product) {
			$products['order_products'][$order_product]['product_id'] = $order_product;
			$products['order_products'][$order_product]['name'] = $data['product_name'][$order_product];
			$products['order_products'][$order_product]['model'] = $data['product_model'][$order_product];
			$products['order_products'][$order_product]['price'] = $data['product_price'][$order_product];
			$products['order_products'][$order_product]['quantity'] = $data['product_quantity'][$order_product];
			$products['order_products'][$order_product]['tax'] = $data['product_tax'][$order_product];
			$products['order_products'][$order_product]['total'] = $data['product_total'][$order_product];
		}
		foreach ($products['order_products'] as $product) { 
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', quantity = '" . (int)$product['quantity'] . "'");
			
			$order_product_id = $this->db->getLastId();
			
			/* foreach ($product['option'] as $option) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', price = '" . (float)$product['price'] . "', prefix = '" . $this->db->escape($option['prefix']) . "'");
			} */
				
			/* foreach ($product['download'] as $download) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_download SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', name = '" . $this->db->escape($download['name']) . "', filename = '" . $this->db->escape($download['filename']) . "', mask = '" . $this->db->escape($download['mask']) . "', remaining = '" . (int)($download['remaining'] * $product['quantity']) . "'");
			} */
		}
			
		if (isset($data['shipping_cost'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', title = 'Shipping:', text = '" . $currency['symbol_left'] . $data['shipping_cost'] . $currency['symbol_right'] . "', `value` = '" . (float)$data['shipping_cost'] . "', sort_order = '1'");
		}
		if (isset($data['shipping_tax'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', title = 'Shipping Tax:', text = '" . $currency['symbol_left'] . $data['shipping_tax'] . $currency['symbol_right'] . "', `value` = '" . (float)$data['shipping_tax'] . "', sort_order = '5'");
		}
		if (isset($data['tax'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', title = 'Tax:', text = '" . $currency['symbol_left'] . $data['tax'] . $currency['symbol_right'] . "', `value` = '" . (float)$data['tax'] . "', sort_order = '10'");
		}
		if (isset($data['sub_total'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', title = 'Sub-Total:', text = '" . $currency['symbol_left'] . $data['sub_total'] . $currency['symbol_right'] . "', `value` = '" . (float)$data['sub_total'] . "', sort_order = '15'");
		}
		if (isset($data['total'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', title = 'Total:', text = '" . $currency['symbol_left'] . $data['total'] . $currency['symbol_right'] . "', `value` = '" . (float)$data['total'] . "', sort_order = '20'");
		}
		
		$order_query = $this->db->query("SELECT *, l.filename AS filename, l.directory AS directory FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "language l ON (o.language_id = l.language_id) WHERE o.order_id = '" . (int)$order_id . "' AND o.order_status_id = '0'");
		
		if ($order_query->num_rows) {
			if ($this->config->get('config_stock_subtract')) {
				$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
			
				foreach ($order_product_query->rows as $product) {
					$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$product['quantity'] . ") WHERE product_id = '" . (int)$product['product_id'] . "'");
				
					$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");
				
					foreach ($order_option_query->rows as $option) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}
			}
			
			if ($notify == '1') {
				$language = new Language($order_query->row['directory']);
				$language->load($order_query->row['filename']);
				$language->load('mail/order_confirm');
				
				$this->load->model('localisation/currency');
				
				$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_query->row['language_id'] . "'");
				$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
				$order_total_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");
				$order_download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "'");
				
				$subject = sprintf($language->get('text_subject'), html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8'), $order_id);
				
				// HTML Mail
				$template = new Template();
				
				$template->data['title'] = sprintf($language->get('text_subject'), html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8'), $order_id);
				
				$template->data['text_greeting'] = sprintf($language->get('text_greeting'), html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8'));
				$template->data['text_order_detail'] = $language->get('text_order_detail');
				$template->data['text_order_id'] = $language->get('text_order_id');
				$template->data['text_invoice'] = $language->get('text_invoice');
				$template->data['text_date_added'] = $language->get('text_date_added');
				$template->data['text_telephone'] = $language->get('text_telephone');
				$template->data['text_fax'] = $language->get('text_fax');		
				$template->data['text_shipping_address'] = $language->get('text_shipping_address');
				$template->data['text_payment_address'] = $language->get('text_payment_address');
				$template->data['text_shipping_method'] = $language->get('text_shipping_method');
				$template->data['text_payment_method'] = $language->get('text_payment_method');
				$template->data['text_comment'] = $language->get('text_comment');
				$template->data['text_powered_by'] = $language->get('text_powered_by');
				
				$template->data['column_product'] = $language->get('column_product');
				$template->data['column_model'] = $language->get('column_model');
				$template->data['column_quantity'] = $language->get('column_quantity');
				$template->data['column_price'] = $language->get('column_price');
				$template->data['column_total'] = $language->get('column_total');
				
				$template->data['order_id'] = $order_id;
				$template->data['customer_id'] = $order_query->row['customer_id'];	
				$template->data['date_added'] = date($language->get('date_format_short'), strtotime($order_query->row['date_added']));    	
				$template->data['logo'] = 'cid:' . basename($this->config->get('config_logo'));
				$template->data['store'] = $this->config->get('config_store');
				$template->data['address'] = nl2br($this->config->get('config_address'));
				$template->data['telephone'] = $this->config->get('config_telephone');
				$template->data['fax'] = $this->config->get('config_fax');
				$template->data['email'] = $this->config->get('config_email');
				$template->data['website'] = trim(HTTP_SERVER, '/');
				$template->data['invoice'] = html_entity_decode($this->url->http('account/invoice&order_id=' . $order_id), ENT_QUOTES, 'UTF-8');
				$template->data['firstname'] = $order_query->row['firstname'];
				$template->data['lastname'] = $order_query->row['lastname'];
				$template->data['shipping_method'] = $order_query->row['shipping_method'];
				$template->data['payment_method'] = $order_query->row['payment_method'];
				$template->data['comment'] = $order_query->row['comment'];
				
				if ($order_query->row['shipping_address_format']) {
					$format = $order_query->row['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
				
				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{country}'
				);
			
				$replace = array(
					'firstname' => $order_query->row['shipping_firstname'],
					'lastname'  => $order_query->row['shipping_lastname'],
					'company'   => $order_query->row['shipping_company'],
					'address_1' => $order_query->row['shipping_address_1'],
					'address_2' => $order_query->row['shipping_address_2'],
					'city'      => $order_query->row['shipping_city'],
					'postcode'  => $order_query->row['shipping_postcode'],
					'zone'      => $order_query->row['shipping_zone'],
					'country'   => $order_query->row['shipping_country']  
				);
			
				$template->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				
				if ($order_query->row['payment_address_format']) {
					$format = $order_info['payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
				
				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{country}'
				);
				
				$replace = array(
					'firstname' => $order_query->row['payment_firstname'],
					'lastname'  => $order_query->row['payment_lastname'],
					'company'   => $order_query->row['payment_company'],
					'address_1' => $order_query->row['payment_address_1'],
					'address_2' => $order_query->row['payment_address_2'],
					'city'      => $order_query->row['payment_city'],
					'postcode'  => $order_query->row['payment_postcode'],
					'zone'      => $order_query->row['payment_zone'],
					'country'   => $order_query->row['payment_country']  
				);
				
				$template->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				
				$template->data['products'] = array();
					
				foreach ($order_product_query->rows as $product) {
					$option_data = array();
					
					$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");
					
					foreach ($order_option_query->rows as $option) {
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $option['value']
						);
					}
					
					$template->data['products'][] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'], $order_query->row['currency'], $order_query->row['value']),
						'total'    => $this->currency->format($product['total'], $order_query->row['currency'], $order_query->row['value'])
					);
				}
				
				$template->data['totals'] = $order_total_query->rows;
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/order_confirm.tpl')) {
					$html = $template->fetch($this->config->get('config_template') . '/template/mail/order_confirm.tpl');
				} else {
					$html = $template->fetch('default/template/mail/order_confirm.tpl');
				}
				
				// Text Mail
				$text  = sprintf($language->get('text_greeting'), html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8')) . "\n\n";
				$text .= $language->get('text_order_id') . ' ' . $order_id . "\n";
				$text .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_query->row['date_added'])) . "\n";
				$text .= $language->get('text_order_status') . ' ' . $order_status_query->row['name'] . "\n\n";
				$text .= $language->get('text_product') . "\n";
				
				foreach ($order_product_query->rows as $result) {
					$text .= $result['quantity'] . 'x ' . $result['name'] . ' (' . $result['model'] . ') ' . html_entity_decode($this->currency->format($result['total'], $order_query->row['currency'], $order_query->row['value']), ENT_NOQUOTES, 'UTF-8') . "\n";
				}
				
				$text .= "\n";
				
				$text .= $language->get('text_total') . "\n";
				
				foreach ($order_total_query->rows as $result) {
					$text .= $result['title'] . ' ' . html_entity_decode($result['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
				}
				
				$text .= "\n";
				
				if ($order_query->row['customer_id']) {
					$text .= $language->get('text_invoice') . "\n";
					$text .= html_entity_decode($this->url->http('account/invoice&order_id=' . $order_id), ENT_QUOTES, 'UTF-8') . "\n\n";
				}
			
				if ($order_download_query->num_rows) {
					$text .= $language->get('text_download') . "\n";
					$text .= $this->url->http('account/download') . "\n\n";
				}
				
				if ($comment) {
					$text .= $language->get('text_comment') . "\n\n";
					$text .= $comment . "\n\n";
				}
				
				$text .= $language->get('text_footer');
				
				$mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), html_entity_decode($this->config->get('config_smtp_password'), ENT_QUOTES, 'UTF-8'), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout')); 
				$mail->setTo($order_query->row['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8'));
				$mail->setSubject($subject);
				$mail->setHtml($html);
				$mail->setText($text);
				$mail->addAttachment(DIR_IMAGE . $this->config->get('config_logo'));
				$mail->send();
			}
		}
	}
	
	public function updateOrder($order_id, $data) {
	
	//echo $order_id;
	//die;
		$currency_query = $this->db->query("SELECT * FROM `currency` WHERE `code` = '" . $this->db->escape($data['payment_currency']) . "'");
		//print_r($data); die;
		$currency = $currency_query->row;
		$payment_country_query = $this->db->query("SELECT * FROM `country` WHERE `country_id` = '" . $this->db->escape($data['payment_country_id']) . "'");
		$payment_country = $payment_country_query->row;
		$shipping_country_query = $this->db->query("SELECT * FROM `country` WHERE `country_id` = '" . $this->db->escape($data['shipping_country_id']) . "'");
		$shipping_country = $shipping_country_query->row;
		$payment_zone_query = $this->db->query("SELECT * FROM `zone` WHERE `zone_id` = '" . $this->db->escape($data['payment_zone_id']) . "'");
		$payment_zone = $payment_zone_query->row;
		$shipping_zone_query = $this->db->query("SELECT * FROM `zone` WHERE `zone_id` = '" . $this->db->escape($data['shipping_zone_id']) . "'");
		$shipping_zone = $shipping_zone_query->row;
		// language_id has been hard coded :/
		// coupon & points ignored for now ;/
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', language_id = '1', currency = '" . $this->db->escape($data['payment_currency']) . "', currency_id = '" . (int)$currency['currency_id'] . "', value = '" . (float)$data['currency_value'] . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_zone = '" . $this->db->escape($shipping_zone['name']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_country = '" . $this->db->escape($shipping_country['name']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_zone = '" . $this->db->escape($payment_zone['name']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_country = '" . $this->db->escape($payment_country['name']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', comment = '" . $this->db->escape($data['comment']) . "', date_modified = NOW(), date_added = NOW(), order_status_id = '" . (int)$data['order_status_id'] . "' WHERE order_id = '" . (int)$order_id . "'");
		
		
			if (isset($data['notify'])) {
			$query = $this->db->query("SELECT *, os.name AS status FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id AND os.language_id = o.language_id) LEFT JOIN " . DB_PREFIX . "language l ON (o.language_id = l.language_id) WHERE o.order_id = '" . (int)$order_id . "'");
			
			if ($query->num_rows) {
				$language = new Language($query->row['directory']);
				$language->load($query->row['filename']);
				$language->load('mail/order');
				
				$subject = sprintf($language->get('text_subject'), html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8'), $order_id);
				
				$message  = $language->get('text_order') . ' ' . $order_id . "\n";
				$message .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($query->row['date_added'])) . "\n\n";
				$message .= $language->get('text_order_status') . "\n\n";
				$message .= $query->row['status'] . "\n\n";
				$message .= $language->get('text_invoice') . "\n";
				$message .= html_entity_decode(HTTP_CATALOG . 'index.php?route=account/invoice&order_id=' . $order_id, ENT_QUOTES, 'UTF-8') . "\n\n";
				
				if ($data['comment']) { 
					$message .= $language->get('text_comment') . "\n\n";
					$message .= strip_tags(html_entity_decode($data['comment'], ENT_QUOTES, 'UTF-8')) . "\n\n";
				}
				
				if ($data['tracking_info']) { 
					$message .= $language->get('text_tracking_info') . "\n\n";
					$message .= strip_tags(html_entity_decode($data['tracking_info'], ENT_QUOTES, 'UTF-8')) . "\n\n";
				}
				
				$message .= $language->get('text_footer');
				
				$mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), html_entity_decode($this->config->get('config_smtp_password'), ENT_QUOTES, 'UTF-8'), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout'));
				$mail->setTo($query->row['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8'));
				$mail->setSubject($subject);
				$mail->setText($message);
				$mail->send();
			}
		}
		}
	
}
?>
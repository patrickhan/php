<?php
class ModelSaleCustomer extends Model {
	public function addCustomer($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', newsletter = '" . (int)$data['newsletter'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', password = '" . $this->db->escape(md5($data['password'])) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
	}
	
	public function editCustomer($customer_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', newsletter = '" . (int)$data['newsletter'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', status = '" . (int)$data['status'] . "' WHERE customer_id = '" . (int)$customer_id . "'");
	
		if ($data['password']) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET password = '" . $this->db->escape(md5($data['password'])) . "' WHERE customer_id = '" . (int)$customer_id . "'");
		}
	}
	
	public function deleteCustomer($customer_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");
	}
	
	public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
	
		return $query->row;
	}
	
	public function getNewsletterSignups($data) {
		$sql = "SELECT `email`, `date_added` FROM `" . DB_PREFIX . "newsletter_signups` WHERE 1 = 1";
		
		if (isset($data['filter_email']) && ! is_null($data['filter_email'])) {
			$sql .= " AND `email` LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
		}
		
		$sort_data = array(
			'email'
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY `" . $data['sort'] . "`";
		} else {
			$sql .= " ORDER BY `email`";
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
	
	public function getNewsletterSignupsByKeyword($keyword) {
		if ($keyword) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsletter_signups WHERE `email` LIKE '%" . $this->db->escape($keyword) . "%' ORDER BY `email`");
			return $query->rows;
		} else {
			return array();
		}
	}
	
	public function getTotalNewsletterSignups($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "newsletter_signups WHERE 1 = 1";
		
		if ($data) {
			if (isset($data['filter_email']) && ! is_null($data['filter_email'])) {
				$sql .= " AND `email` LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
			}			
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	public function addNewsletterSignup($data = array()) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "newsletter_signups SET email  = '" . $this->db->escape($data['email']) . "', date_added = NOW()");
	}
	
	public function deleteNewsletterSignup($email) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "newsletter_signups WHERE email = '" . $this->db->escape($email) . "'");
	}
	
	public function getCustomers($data = array()) {
		$sql = "SELECT *, CONCAT(firstname, ' ', lastname) AS name FROM " . DB_PREFIX . "customer";
		
		$implode = array();
		
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
			$implode[] = "email = '" . $this->db->escape($data['filter_email']) . "'";
		}
		
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "status = '" . (int)$data['filter_status'] . "'";
		}
		
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'name',
			'email',
			'status',
			'date_added'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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
	
	public function activate($customer_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET status = '1' WHERE customer_id = '" . (int)$customer_id . "'");
	}
	
	public function getCustomersByNewsletter() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE newsletter = '1' ORDER BY firstname, lastname, email");
	
		return $query->rows;
	}
	
	public function getCustomersByKeyword($keyword) {
		if ($keyword) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($keyword) . "%' ORDER BY firstname, lastname, email");
		
			return $query->rows;
		} else {
			return array();
		}
	}
	
	public function getTotalCustomers($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer";
		
		$implode = array();
		
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
			$implode[] = "email = '" . $this->db->escape($data['filter_email']) . "'";
		}	
		
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "status = '" . (int)$data['filter_status'] . "'";
		}
		
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	public function getTotalCustomersAwatingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE status = '0'");
		
		return $query->row['total'];
	}
	
	public function getTotalAddressesByCustomerId($customer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");
		
		return $query->row['total'];
	}
	
	public function getTotalAddressesByCountryId($country_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE country_id = '" . (int)$country_id . "'");
		
		return $query->row['total'];
	}
	
	public function getTotalAddressesByZoneId($zone_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE zone_id = '" . (int)$zone_id . "'");
		
		return $query->row['total'];
	}
	
	public function getTotalCustomersByGroupId($customer_group_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		
		return $query->row['total'];
	}
	
	public function getCustomerAddress($customer_id, $address_id) {
		$query = $this->db->query("SELECT DISTINCT *, c.name AS country, z.name AS zone FROM " . DB_PREFIX . "address a LEFT JOIN " . DB_PREFIX . "country c ON a.country_id = c.country_id LEFT JOIN " . DB_PREFIX . "zone z ON a.zone_id = z.zone_id WHERE a.address_id = '" . (int)$address_id . "' and a.customer_id = '" . (int)$customer_id . "'");
		
		if ($query->num_rows) {
			$address_data = array(
				'firstname'      => $query->row['firstname'],
				'lastname'       => $query->row['lastname'],
				'company'        => $query->row['company'],
				'address_1'      => $query->row['address_1'],
				'address_2'      => $query->row['address_2'],
				'postcode'       => $query->row['postcode'],
				'city'           => $query->row['city'],
				'zone_id'        => $query->row['zone_id'],
				'zone'           => $query->row['zone'],
				'zone_code'      => $query->row['code'],
				'country_id'     => $query->row['country_id'],
				'country'        => $query->row['country'],	
				'iso_code_2'     => $query->row['iso_code_2'],
				'iso_code_3'     => $query->row['iso_code_3'],
				'address_format' => $query->row['address_format']
			);
			
			return $address_data;
		} else {
			return FALSE;
		}
	}
}
?>
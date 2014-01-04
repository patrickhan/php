<?php 

class ModelCheckoutGCheckout extends Model {

	protected function database_getRow( $sql ) {
		$result = $this->db->query($sql);
		if ($result->num_rows <= 0) {
			return array();
		}
		return $result->rows[0];
	}


	protected function database_getRows( $sql ) {
		$result = $this->db->query($sql);
		if ($result->num_rows <= 0) {
			return array();
		}
		return $result->rows;
	}


	public function getCurrencies() {
		$currencyShop = $this->config->get('config_currency');
		$currencyGoogle = $this->config->get('gcheckout_currency');
		$baseValGoogle = 1;
		$baseValShop = 1;
		if ($currencyShop!=$currencyGoogle) {
			$sql = "SELECT `value` FROM `".DB_PREFIX."currency` WHERE `code`='$currencyShop'";
			$result = $this->database_getRow( $sql );
			$baseValShop = (isset($result['value'])) ? $result['value'] : NULL;
			$sql = "SELECT `value` FROM `".DB_PREFIX."currency` WHERE `code`='$currencyGoogle'";
			$result = $this->database_getRow( $sql );
			$baseValGoogle = (isset($result['value'])) ? $result['value'] : NULL;
			if (($baseValShop==NULL) || ($baseValGoogle==NULL)) {
				// this should never happen
				$currencyGoogle = $currencyShop;
				$baseValGoogle = 1;
				$baseValShop = 1;
			}
		}
		$currencies = array( 
			'currency_google' => $currencyGoogle,
			'currency_shop' => $currencyShop,
			'base_val_google' => $baseValGoogle,
			'base_val_shop' => $baseValShop
		);
		return $currencies;
	}


	public function getWeightRules() {
		$sql =  "SELECT wr.`rule`, wc1.`unit` AS `from` , wc2.`unit` AS `to` ";
		$sql .= "FROM `".DB_PREFIX."weight_rule` wr ";
		$sql .= "LEFT JOIN `".DB_PREFIX."weight_class` wc1 ON wr.from_id = wc1.weight_class_id ";
		$sql .= "LEFT JOIN `".DB_PREFIX."weight_class` wc2 ON wr.to_id = wc2.weight_class_id;";
		$rows = $this->database_getRows( $sql );
		$weightRules = array();
		foreach ($rows as $row) {
			$to = strtoupper($row['to']);
			$from = strtoupper($row['from']);
			$rule = $row['rule'];
			if (!isset($weightRules[$from])) {
				$weightRules[$from] = array();
			}
			$weightRules[$from][$to] = $rule;
		}
		return $weightRules;
	}


	public function getTaxTableName( $taxClassId ) {
		$sql = "SELECT title FROM `".DB_PREFIX."tax_class` WHERE tax_class_id='$taxClassId';";
		$row = $this->database_getRow( $sql );
		return (isset($row['title'])) ? $row['title'] : '';
	}


	public function findTaxRules(  $taxClassId ) {
		$sql  = "SELECT tr.tax_class_id, tr.tax_rate_id, tr.rate, tr.description, tr.priority, zg.country_id, zg.zone_id, c.iso_code_2, z.code ";
		$sql .= "FROM `".DB_PREFIX."tax_rate` tr ";
		$sql .= "INNER JOIN `".DB_PREFIX."geo_zone` gz ON tr.geo_zone_id = gz.geo_zone_id ";
		$sql .= "INNER JOIN `".DB_PREFIX."zone_to_geo_zone` zg ON gz.geo_zone_id = zg.geo_zone_id ";
		$sql .= "INNER JOIN `".DB_PREFIX."country` c ON zg.country_id = c.country_id ";
		$sql .= "LEFT JOIN `".DB_PREFIX."zone` z ON zg.zone_id = z.zone_id ";
		$sql .= "WHERE tr.tax_class_id='$taxClassId' ";
		$sql .= "ORDER BY tr.priority, c.iso_code_2, z.code;";
		$rows = $this->database_getRows( $sql );
		return $rows;
	}


	public function getDefaultLanguageId() {
		$code = $this->config->get('config_language');
		$sql = "SELECT language_id FROM `".DB_PREFIX."language` WHERE code = '$code'";
		$rows = $this->database_getRows( $sql );
		$languageId = 1;
		foreach ($rows as $row) {
			$languageId = $row['language_id'];
			break;
		}
		return $languageId;
	}


	public function getShippingMethods() {
		$languageId = $this->getDefaultLanguageId();
		$sql  = "SELECT e.type, e.key AS code, s.key, s.value ";
		$sql .= "FROM `".DB_PREFIX."extension` e ";
		$sql .= "INNER JOIN `".DB_PREFIX."setting` s ON CONCAT(e.key,'_status') = s.key ";
		$sql .= "WHERE e.type='shipping' AND s.value='1'";
		$rows = $this->database_getRows( $sql );
		$shippingExtensions = array();
		foreach ($rows as $row) {
			$code = $row['code'];
			$this->load->language("shipping/$code");
			$name = $this->language->get('text_title');
			$shippingExtensions[$code]=$name;
		}
		$geoZoneIds = array();
		$names =array();
		foreach ($shippingExtensions as $code => $name) {
			if (($code == 'weight') || ($code == 'zone')) {
				$sql = "SELECT `group`, `key`, `value` FROM `".DB_PREFIX."setting` WHERE `key` LIKE '".$code."_%_status'";
				$rows = $this->database_getRows( $sql );
				foreach ($rows as $row) {
					$key = $row['key'];
					$value = $row['value'];
					if ($value != 1) {
						continue;
					}
					$keyComponents = explode('_',$key);
					$geoZoneId = $keyComponents[1];
					$geoZoneIds[$code.'_'.$geoZoneId] = intval($geoZoneId);
					$row = $this->database_getRow( "SELECT `name` FROM `".DB_PREFIX."geo_zone` WHERE `geo_zone_id`=$geoZoneId;" );
					$names[$code.'_'.$geoZoneId] = $name.' - '.$row['name'];
				}
			}
			else if ($this->config->get($code.'_status')) {
				$geoZoneId = $this->config->get($code.'_geo_zone_id');
				$geoZoneIds[$code] = ($geoZoneId==NULL) ? 0 : $geoZoneId;
				$names[$code] = $name;
			}
		}
		return array( 0 => $names, 1 => $geoZoneIds );
	}

	public function findTaxRates($taxClassId) {
		$sql  = "SELECT tr.tax_class_id, tr.tax_rate_id, tr.rate, tr.description, tr.priority, zg.country_id, zg.zone_id, c.iso_code_2, z.code ";
		$sql .= "FROM `".DB_PREFIX."tax_rate` tr ";
		$sql .= "INNER JOIN `".DB_PREFIX."geo_zone` gz ON tr.geo_zone_id = gz.geo_zone_id ";
		$sql .= "INNER JOIN `".DB_PREFIX."zone_to_geo_zone` zg ON gz.geo_zone_id = zg.geo_zone_id ";
		$sql .= "INNER JOIN `".DB_PREFIX."country` c ON zg.country_id = c.country_id ";
		$sql .= "LEFT JOIN `".DB_PREFIX."zone` z ON zg.zone_id = z.zone_id ";
		$sql .= "WHERE tr.tax_class_id='$taxClassId' ";
		$sql .= "ORDER BY tr.priority, c.iso_code_2, z.code;";
		$rows = $this->database_getRows( $sql );
		return $rows;
	}

	public function findGeoAreas($geoZoneId) {
		$sql  = "SELECT DISTINCT zg.geo_zone_id, zg.country_id, zg.zone_id, c.iso_code_2, z.code ";
		$sql .= "FROM `".DB_PREFIX."zone_to_geo_zone` zg ";
		$sql .= "INNER JOIN `".DB_PREFIX."country` c ON zg.country_id = c.country_id ";
		$sql .= "LEFT JOIN `".DB_PREFIX."zone` z ON zg.zone_id = z.zone_id ";
		$sql .= "WHERE zg.geo_zone_id=$geoZoneId ";
		$sql .= "ORDER BY c.iso_code_2, z.code;";
		$rows = $this->database_getRows( $sql );
		return $rows;
	}

	public function findWeightClass($weightClassId) {
		$sql = "SELECT * FROM `".DB_PREFIX."weight_class` WHERE `weight_class_id` = $weightClassId;";
		return $this->database_getRow($sql);
	}

	public function findCountryIdByISOCode2( $iso_code_2 ) {
		$sql = "SELECT country_id FROM `".DB_PREFIX."country` WHERE iso_code_2='$iso_code_2';";
		return $this->database_getRow($sql);
	}

	public function findZoneByCountryIdAndRegionCode($countryId,$regionCode) {
		$sql = "SELECT zone_id, name FROM `".DB_PREFIX."zone` WHERE country_id=$countryId AND code='$regionCode';";
		return $this->database_getRow($sql);
	}

	public function findZoneByCountryIdAndRegionName($countryId,$regionName) {
		$regionName = strtoupper( str_replace( ' ', '', trim($regionName) ) );
		$sql = "SELECT zone_id, name FROM `".DB_PREFIX."zone` WHERE country_id=$countryId AND (UPPER(REPLACE(TRIM(name),' ','')) LIKE '%$regionName%' OR '$regionName' LIKE CONCAT('%',UPPER(REPLACE(TRIM(name),' ','')),'%'));";
		return $this->database_getRow($sql);
	}

	public function deleteExpiredGoogleDownloads( $time ) {
		$sql = "DELETE FROM `".DB_PREFIX."google_download` WHERE `expire` < '$time'";
		$this->db->query($sql);
	}

	public function findGoogleOrder($reference) {
		return $this->database_getRow( "SELECT `order_reference` FROM `".DB_PREFIX."google_order` WHERE `order_reference`='$reference';" );
	}

	public function insertGoogleOrder($reference,$expire) {
		$sql = "INSERT INTO `".DB_PREFIX."google_order` (`order_reference`,`expire`) VALUES ('$reference','$expire');";
		$this->db->query($sql);
	}

	public function deleteGoogleOrder( $reference ) {
		$sql = "DELETE FROM `".DB_PREFIX."google_order` WHERE `order_reference`='$reference';";
		$this->db->query($sql);
	}

	public function deleteExpiredGoogleOrders( $time ) {
		$sql = "DELETE FROM `".DB_PREFIX."google_order` WHERE `expire` < '$time';";
		$this->db->query($sql);
	}

	public function findProductTaxRules( $products ) {
		$productIds = '';
		foreach ($products as $product) {
			$productIds .= ($productIds=='') ? '(' : ',';
			$productIds .= $product['product_id'];
		}
		if ($productIds != '') {
			$productIds .= ')';
		}

		$sql  = "SELECT DISTINCT p.tax_class_id, zg.country_id, zg.zone_id, c.iso_code_2, z.code ";
		$sql .= "FROM `".DB_PREFIX."product` p ";
		$sql .= "INNER JOIN `".DB_PREFIX."tax_rate` tr ON p.tax_class_id = tr.tax_class_id ";
		$sql .= "INNER JOIN `".DB_PREFIX."geo_zone` gz ON tr.geo_zone_id = gz.geo_zone_id ";
		$sql .= "INNER JOIN `".DB_PREFIX."zone_to_geo_zone` zg ON gz.geo_zone_id = zg.geo_zone_id ";
		$sql .= "INNER JOIN `".DB_PREFIX."country` c ON zg.country_id = c.country_id ";
		$sql .= "LEFT JOIN `".DB_PREFIX."zone` z ON zg.zone_id = z.zone_id ";
		$sql .= "WHERE p.tax_class_id > 0";
		if ($productIds != '') {
			$sql .= " AND p.product_id IN $productIds";
		}
		$sql .= ";";
		$rows = $this->database_getRows( $sql );
		return $rows;
	}

	public function findDownloads($productId,$languageId) {
		$sql =  "SELECT p . * , d . * , ps.name ";
		$sql .= "FROM `".DB_PREFIX."product` p ";
		$sql .= "INNER JOIN `".DB_PREFIX."product_description` ps ON ps.product_id = p.product_id ";
		$sql .= "INNER JOIN `".DB_PREFIX."product_to_download` pd ON pd.product_id = p.product_id ";
		$sql .= "INNER JOIN `".DB_PREFIX."download` d ON d.download_id = pd.download_id ";
		$sql .= "WHERE p.product_id =$productId ";
		$sql .= "AND ps.language_id =$languageId;";
		$rows = $this->database_getRows( $sql );
		return $rows;
	}

	public function insertGoogleDownload($reference,$productId,$downloadId,$name,$filename,$mask,$remaining,$expire) {
		$sql  = "INSERT INTO `".DB_PREFIX."google_download` (`order_reference`, `product_id`, `download_id`, `name`, `filename`, `mask`, `remaining`, `expire`) VALUES ";
		$sql .= "('$reference', $productId, $downloadId, '$name', '$filename', '$mask', $remaining, '$expire' );";
		$this->db->query($sql);
	}


	static protected $states = array(
		'AL',	// Alabama
		'AK',	// Alaska
		'AS',	// American Samoa
		'AZ',	// Arizona
		'AR',	// Arkansas
		'AF',	// Armed Forces Africa
		'AA',	// Armed Forces Americas
		'AC',	// Armed Forces Canada
		'AE',	// Armed Forces Europe
		'AM',	// Armed Forces Middle East
		'AP',	// Armed Forces Pacific
		'CA',	// California
		'CO',	// Colorado
		'CT',	// Connecticut
		'DE',	// Delaware
		'DC',	// District of Columbia
		'FM',	// Federated States Of Micronesia
		'FL',	// Florida
		'GA',	// Georgia
		'GU',	// Guam
		'HI',	// Hawaii
		'ID',	// Idaho
		'IL',	// Illinois
		'IN',	// Indiana
		'IA',	// Iowa
		'KS',	// Kansas
		'KY',	// Kentucky
		'LA',	// Louisiana
		'ME',	// Maine
		'MH',	// Marshall Islands
		'MD',	// Maryland
		'MA',	// Massachusetts
		'MI',	// Michigan
		'MN',	// Minnesota
		'MS',	// Mississippi
		'MO',	// Missouri
		'MT',	// Montana
		'NE',	// Nebraska
		'NV',	// Nevada
		'NH',	// New Hampshire
		'NJ',	// New Jersey
		'NM',	// New Mexico
		'NY',	// New York
		'NC',	// North Carolina
		'ND',	// North Dakota
		'MP',	// Northern Mariana Islands
		'OH',	// Ohio
		'OK',	// Oklahoma
		'OR',	// Oregon
		'PW',	// Palau
		'PA',	// Pennsylvania
		'PR',	// Puerto Rico
		'RI',	// Rhode Island
		'SC',	// South Carolina
		'SD',	// South Dakota
		'TN',	// Tennessee
		'TX',	// Texas
		'UT',	// Utah
		'VT',	// Vermont
		'VI',	// Virgin Islands
		'VA',	// Virginia
		'WA',	// Washington
		'WV',	// West Virginia
		'WI',	// Wisconsin
		'WY'	// Wyoming
	);


	public function isUSStateArea( $stateCode ) {
		if ($stateCode == NULL) {
			return FALSE;
		}
		if ($stateCode == '') {
			return FALSE;
		}
		foreach (self::$states as $state) {
			if ($stateCode == $state) {
				return TRUE;
			}
		}
		return FALSE;
	}

	public function updateStock( $products ) {
		if ($this->config->get('config_stock_subtract')) {
			foreach ($products as $product) {
				$product_id = $product['product_id'];
				$quantity = $product['quantity'];
				$options = $product['option'];
				$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$quantity . ") WHERE product_id = '" . (int)$product_id . "'");
				foreach ($options as $option) {
					$this->db->query("UPDATE `" . DB_PREFIX . "product_option_value` SET quantity = (quantity - " . (int)$quantity . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
				}
			}
		}
	}


}

?>
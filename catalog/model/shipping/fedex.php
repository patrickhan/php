<?php
class ModelShippingFedex extends Model {
	public function getQuote() {
		
        if ($this->config->get('fedex_status')) {
			// Get Address Data (Shipping)
	        $address = array();
	        if (method_exists($this->customer, 'getAddress')) { // v1.3.2 Normal Checkout
        		$address = $this->customer->getAddress($this->session->data['shipping_address_id']);
        		$address['zone_code'] = $address['code'];
			} else { 
        		if (isset($this->session->data['shipping_address_id']) && $this->session->data['shipping_address_id']) { // v1.3.4+ Normal checkout
        			$this->load->model('account/address');
        			$address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
				} else { //v1.3.4+ Guest checkout
					if (isset($this->session->data['guest']) && is_array($this->session->data['guest'])) {
						$address = $this->session->data['guest'];
					} else { // Get passed params (1.3.4 Guest Checkout only)
						$arg_list = func_get_args();
						if (isset($arg_list[0])) {
							$this->load->model('localisation/country');
							$country_data 		= $this->model_localisation_country->getCountry($arg_list[0]);
						}
						if (isset($arg_list[1])) { 
							$this->load->model('localisation/zone');
							$zone_data			= $this->model_localisation_zone->getZone($arg_list[1]);
						}
						$address = array_merge($country_data, $zone_data);
						$address['postcode'] 	= $arg_list[2];
						$address['zone_code'] 	= (isset($zone_data['code'])) ? $zone_data['code'] : '';
						$address['zone_id'] 	= (isset($zone_data['zone_id'])) ? $zone_data['zone_id'] : '0';
						$address['city'] 		= (isset($zone_data['city'])) ? $zone_data['city'] : '';
					}
				}
			}
			$country_id	= (isset($address['country_id'])) ? $address['country_id'] : 0;
			$zone_id 	= (isset($address['zone_id'])) ? $address['zone_id'] : 0;
			$postcode 	= (isset($address['postcode'])) ? $address['postcode'] : '';
			//
		
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('fedex_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
	
      		if (!$this->config->get('fedex_geo_zone_id')) {
        		$status = TRUE;
      		} elseif ($query->num_rows) {
        		$status = TRUE;
      		} else {
        		$status = FALSE;
      		}
		} else {
			$status = FALSE;
		}

		$method_data = array();
	
		if ($status) {
			$this->load->language('shipping/fedex');
			
			restore_error_handler();
			require_once("fedex_lib.php");
			
			$fedexService['PRIORITYOVERNIGHT']     = $this->language->get('text_PRIORITYOVERNIGHT');
			$fedexService['STANDARDOVERNIGHT']     = $this->language->get('text_STANDARDOVERNIGHT');
			$fedexService['FIRSTOVERNIGHT']        = $this->language->get('text_FIRSTOVERNIGHT');
			$fedexService['FEDEX2DAY']             = $this->language->get('text_FEDEX2DAY');
			$fedexService['FEDEXEXPRESSSAVER']     = $this->language->get('text_FEDEXEXPRESSSAVER');
			$fedexService['FEDEX1DAYFREIGHT']      = $this->language->get('text_FEDEX1DAYFREIGHT');
			$fedexService['FEDEX2DAYFREIGHT']      = $this->language->get('text_FEDEX2DAYFREIGHT');
			$fedexService['FEDEX3DAYFREIGHT']      = $this->language->get('text_FEDEX3DAYFREIGHT');
			$fedexService['FEDEXGROUND']           = $this->language->get('text_FEDEXGROUND');
			$fedexService['GROUNDHOMEDELIVERY']    = $this->language->get('text_GROUNDHOMEDELIVERY');
			$fedexService['INTERNATIONALPRIORITY'] = $this->language->get('text_INTERNATIONALPRIORITY');
			$fedexService['INTERNATIONALECONOMY']  = $this->language->get('text_INTERNATIONALECONOMY');
			$fedexService['INTERNATIONALFIRST']    = $this->language->get('text_INTERNATIONALFIRST');
						
			$shipping_weight = ($this->cart->getWeight() < '0.5') ? '0.5' : $this->cart->getWeight();
			$locale_unit = (in_array($address['iso_code_2'], array('US'))) ? 'lb' : 'kg';
			$weight_unit = (in_array($address['iso_code_2'], array('US'))) ? 'LBS' : 'KGS';
			$dim_unit = (in_array($address['iso_code_2'], array('US'))) ? 'IN' : 'CM';
			$results = $this->db->query("select weight_class_id from " . DB_PREFIX . "weight_class where unit = '" . $locale_unit . "'");
			$shipping_weight = str_replace(',','',$this->weight->convert($shipping_weight, $this->config->get('config_weight_class_id'), $results->row['weight_class_id']));
			
			$origin_postcode = str_replace(array(' ', '-'), '', $this->config->get('fedex_postcode'));
			$dest_postcode = str_replace(array(' ', '-'), '', $address['postcode']);
			
			// Return error message if no customer post code
			if ($dest_postcode == '') {
				return $this->retError($this->language->get('error_postcode'));
			}			

			$fedex = new Fedex;
			if ($this->config->get('fedex_test')) {
			    $fedex->setServer("https://gatewaybeta.fedex.com/GatewayDC");
			} else {
			    $fedex->setServer("https://gateway.fedex.com/GatewayDC");
			}
			$fedex->setAccountNumber($this->config->get('fedex_account'));
			$fedex->setMeterNumber($this->config->get('fedex_meter'));
			$fedex->setDropoffType($this->config->get('fedex_pickup'));
			$fedex->setPackaging($this->config->get('fedex_package'));
			$fedex->setWeightUnits($weight_unit);
			$fedex->setWeight($shipping_weight);
			if ($address['iso_code_2'] == 'US' || $address['iso_code_2'] == 'CA') {
			    $fedex->setOriginStateOrProvinceCode($address['zone_code']);
			} else {
				$fedex->setOriginStateOrProvinceCode('');
			}
			$fedex->setOriginPostalCode($origin_postcode);
			$fedex->setOriginCountryCode($address['iso_code_2']);
			if ($address['iso_code_2'] == 'US' || $address['iso_code_2'] == 'CA') {
			    $fedex->setDestStateOrProvinceCode($address['zone_code']);
			} else {
				$fedex->setDestStateOrProvinceCode('');
			}
			$fedex->setDestPostalCode($dest_postcode);
			$fedex->setDestCountryCode($address['iso_code_2']);
			$fedex->setPayorType("SENDER");
			
			//Dimensions
			$fedex->setLength((int)$this->config->get('fedex_length'));
			$fedex->setWidth((int)$this->config->get('fedex_width'));
			$fedex->setHeight((int)$this->config->get('fedex_height'));
			$fedex->setDimUnit($dim_unit);
			$max_box_weight = $this->config->get('fedex_box_weight');
			
			
			$prices = array();
			foreach($fedexService as $service=>$serviceName) {

				if (!$this->config->get('fedex_d_' . $service)) { continue; }
			    
			    $fedex->setService($service, $serviceName);
			    
			    if ($service == 'FEDEXGROUND' || $service == 'GROUNDHOMEDELIVERY') {
					$fedex->setCarrierCode("FDXG");
				} else { 
					$fedex->setCarrierCode("FDXE");
				}

			    $results = $fedex->getPrice();
			    
			    if (isset($results)) {
				    if (isset($results->description)) {
			    		$error = $this->retError($results->description);
					} 
					if (isset($results->service)) {
			    		$prices[] = $results;
					}
				}
			    
			}
			
			$rates = array();
			foreach ($prices as $price) {
				$rates[] = @array($price->service => $price->rate);
			}
			
			// Returns error message
			if (empty($rates)) {
				if (isset($error)) {
					return $this->retError($error['quote']['fedex']['text']);
				} else {
					return $this->retError($this->language->get('error_no_rates'));
				}
			}
						
			$quote_data = array();
			$i=0;
			foreach ($rates as $rate) {
				foreach ($rate as $key=>$value) {
	            					
					// Add extra cost
		            $value += (float)$this->config->get('fedex_cost');
					
		            $quote_data['fedex_'.$i] = array(
		                'id'    => 'fedex.fedex_'. $i,
		                'title' => $key,
		                'cost'  => $value,
		                'tax_class_id' => $this->config->get('fedex_tax_class_id'),
		                'text'  => $this->currency->format($this->tax->calculate($value, $this->config->get('fedex_tax_class_id'), $this->config->get('config_tax')))
		            );

	        	}
	        	$i++;
			}
			
      		$method_data = array(
        		'id'         => 'fedex',
        		'title'      => $this->language->get('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('fedex_sort_order'),
        		'error'      => FALSE
      		);
		}
	
		return $method_data;
	}
	
	function retError($error="unknown error") {
    	
    	$quote_data = array();
			
      	$quote_data['fedex'] = array(
        	'id'           => 'fedex.fedex',
        	'title'        => $this->language->get('text_title'),
			'cost'         => NULL,
         	'tax_class_id' => NULL,
			'text'         => $error
      	);
    	
    	$method_data = array(
		  'id'           => 'fedex',
		  'title'        => $this->language->get('text_title'),
		  'quote'        => $quote_data,
		  'sort_order'   => $this->config->get('fedex_sort_order'),
		  'tax_class_id' => $this->config->get('fedex_tax_class_id'),
		  'error'        => $error
		);
		return $method_data;
	}
}
?>
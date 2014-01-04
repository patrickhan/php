<?php
class ModelShippingUPSXML extends Model {
    public function getQuote($country_id = '', $zone_id = '', $postcode = '') {
        $this->load->language('shipping/upsxml');
        
        if ($this->config->get('upsxml_status')) {
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
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('upsxml_geo_zone_id') . "' AND country_id = '" . (int)$country_id . "' AND (zone_id = '" . (int)$zone_id . "' OR zone_id = '0')");
			
			if (!$this->config->get('upsxml_geo_zone_id')) {
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
                      
            $pickups = array(
                'RDP'   => '01', // Regular Daily Pickup      
                'CC'    => '03', // Customer Counter
                'OTP'   => '06', // One Time Pickup
                'OCA'   => '07', // On-Call Air
                'LC'    => '09', // Letter Center
                'ASC'   => '10', // All Service Center
            );
            $pickup = $pickups[$this->config->get('upsxml_pickup')];
            
            $containers = array(
                ''      => '00', // Customer Packaging by default
                'CP'    => '00', // Customer Packaging      
                'ULE'   => '01', // UPS Letter Envelope
                'UT'    => '03', // UPS Tube
                'UEB'   => '21', // UPS Express Box
                'UW25'  => '24', // UPS Worldwide 25 kilo
                'UW10'  => '25', // UPS Worldwide 10 kilo
            );
            $container = $containers[$this->config->get('upsxml_package')];

            $residential = array(
                'RES'   => '1', // Residential
                'COM'   => '0', // Commercial
            );
            $type = $residential[$this->config->get('upsxml_type')];
            
            $this->load->model('localisation/country');
            $country_info = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));
            
            $dest_country = $address['iso_code_2'];
                        
            // Get 5-digit zipcode for "US" address
            if ($dest_country == 'US') {
                $postcode = substr($postcode, 0, 5);
            }
            
            // No Post Code returns error message to the quote data
            if ($postcode == '') { 
                return $this->retError($this->language->get('error_zip_required'));
            }
            
            // Fudge a few countries to match what UPS expects
            if ($dest_country == 'UK') { $dest_country = 'GB'; }
            
            //external UPS Class file        
            include("upsxml_lib.php"); 
            
            $ratetype = "Shop"; //"Shop" for Multi or "Rate" for single
            $service = '03'; //only used for "Rate" above.
            $length = $this->config->get('upsxml_length');
            $width = $this->config->get('upsxml_width');
            $height = $this->config->get('upsxml_height');
            $AccessLicenseNumber = $this->config->get('upsxml_access'); // Your license number
            $UserId = $this->config->get('upsxml_user'); // Username
            $Password = $this->config->get('upsxml_pass'); // Password
            $PostalCode = $this->config->get('upsxml_zipcode'); // Postcode you are shipping FROM
            $ShipperNumber = $this->config->get('upsxml_shipper'); // Your UPS shipper number
            $OriginCountry = ($this->config->get('upsxml_country')) ? $this->config->get('upsxml_country') : $country_info['iso_code_2'];
            $max_box_weight = $this->config->get('upsxml_box_weight');

            // UPS does not allow zero weight.         
            // Must use LBS & IN for USA. KGS & CM for NON-USA
            $shipping_weight = ($this->cart->getWeight() == '0') ? '0.1' : $this->cart->getWeight();
            $locale_unit = (in_array($OriginCountry, array('US'))) ? 'lb' : 'kg';
            $weight_unit = (in_array($OriginCountry, array('US'))) ? 'LBS' : 'KGS';
            $dim_unit = (in_array($OriginCountry, array('US'))) ? 'IN' : 'CM';
            $results = $this->db->query("select weight_class_id from " . DB_PREFIX . "weight_class where unit = '" . $locale_unit . "'");
            $shipping_weight = str_replace(',','',$this->weight->convert($shipping_weight, $this->config->get('config_weight_class_id'), $results->row['weight_class_id']));
            
            $weight = $shipping_weight;
            //$weight_unit = 'LBS';
            //$dim_unit = 'IN';
            $residential = $type;
            $testmode = $this->config->get('upsxml_test');
            
            restore_error_handler();
            $rates = ups($postcode,$dest_country,$service,$weight,$length,$width,$height,$AccessLicenseNumber,$UserId,$Password,$PostalCode,$ShipperNumber,$OriginCountry,$ratetype,$container,$pickup,$weight_unit,$dim_unit,$testmode,$residential,$max_box_weight,$length,$width,$height);

            if (empty($rates)) $rates = array('error');
                
            
            // Returns error message
            if (!is_array($rates)) {
                return $this->retError($rates);
            }
            
            // Returns error message
            if (isset($rates['error'])) {
                return $this->retError($rates['error']);
            }
            
            // No rates returned
            if (!count($rates)) {
                return $this->retError($this->language->get('error_not_retrieved'));
            }
               
               $svccodes = array(
                '14' => '1DM', //Next Day Air AM
                '01' => '1DA', //Next Day Air
                '13' => '1DP', //Next Day Air Saver
                '59' => '2DM', //2nd Day Air AM
                '02' => '2DA', //2nd Day Air
                '12' => '3DS', //3 Day Select
                '03' => 'GND', //Ground
                '11' => 'STD', //Canada Standard
                '07' => 'XPR', //Worldwide Express
                '54' => 'XDM', //Worldwide Express Plus
                '08' => 'XPD', //Worldwide Expedite
                '65' => 'WXS', //Worldwide Express Saver
                );
               
               $allowedSvcs = array();
            //$svcs = array('1DA','1DM','1DP','2DM','2DA','3DS','GND','STD','XPR','XDM','XPD','WXS');
            $svcs = array_flip($svccodes);
            foreach ($svcs as $k => $v) {
                if ($this->config->get('upsxml_d_' . $k) == '1') {
                    $allowedSvcs[] = $v;
                }
            }
               
                     
            $quote_data = array();
            $i = 0;
            //foreach ($rates as $rate) {
                foreach ($rates as $key=>$value) {
                    if (!in_array($key, $allowedSvcs)) { continue; }
                    $key = $this->language->get('text_'.strtolower($svccodes[$key]));
                    
                    // Add extra cost
                    $value += (float)$this->config->get('upsxml_cost');
                    
                    $quote_data['upsxml_'.$i] = array(
                        'id'    => 'upsxml.upsxml_'. $i,
                        'title' => ucwords(strtolower(($key))),
                        'cost'  => $value,
                        'tax_class_id' => $this->config->get('upsxml_tax_class_id'),
                        'text'  => $this->currency->format($this->tax->calculate($value, $this->config->get('upsxml_tax_class_id'), $this->config->get('config_tax')))
                    );
                    $i++;
                }
            //}


            $method_data = array(
                'id'           => 'upsxml',
                'title'        => $this->language->get('text_title'),
                'quote'        => $quote_data,
                'sort_order'   => $this->config->get('upsxml_sort_order'),
                'tax_class_id' => $this->config->get('upsxml_tax_class_id'),
                'error'        => false
            );
        }

        return $method_data;
    }
    
    function retError($error="unknown error") {
        
        $quote_data = array();
            
          $quote_data['upsxml'] = array(
            'id'           => 'upsxml.upsxml',
            'title'        => $this->language->get('text_title'),
            'cost'         => NULL,
            'tax_class_id' => NULL,
            'text'         => $error
          );
        
        $method_data = array(
          'id'           => 'upsxml',
          'title'        => $this->language->get('text_title'),
          'quote'        => $quote_data,
          'sort_order'   => $this->config->get('upsxml_sort_order'),
          'tax_class_id' => $this->config->get('upsxml_tax_class_id'),
          'error'        => $error
        );
        return $method_data;
    }
    
}
?>
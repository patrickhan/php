<?php
	function get_tag( $tag, $xml ) {
		$tag = preg_quote($tag);
		preg_match_all('{<'.$tag.'[^>]*>(.*?)</'.$tag.'>.}', $xml, $matches, PREG_PATTERN_ORDER);
		return $matches[1];
	}

	function ups($dest_postcode,$dest_country,$service,$weight,$length,$width,$height,$AccessLicenseNumber,$UserId,$Password,$PostalCode,$ShipperNumber,$OriginCountry,$ratetype,$container = '00',$pickup = '03',$weight_unit = 'LBS',$dim_unit = 'IN',$testmode = '0',$residential = '1',$max_box_weight = '') {
		
		if (!$length) {$length = 10;}
		if (!$width) {$width = 10;}
		if (!$height) {$height = 10;}

    	$data ="<?xml version=\"1.0\"?>
    	<AccessRequest xml:lang=\"en-US\">
    		<AccessLicenseNumber>$AccessLicenseNumber</AccessLicenseNumber>
    		<UserId>$UserId</UserId>
    		<Password>$Password</Password>
    	</AccessRequest>
    	<?xml version=\"1.0\"?>
    	<RatingServiceSelectionRequest xml:lang=\"en-US\">
    		<Request>
    			<TransactionReference>
    				<CustomerContext>Bare Bones Rate Request</CustomerContext>
    				<XpciVersion>1.0001</XpciVersion>
    			</TransactionReference>
    			<RequestAction>Rate</RequestAction>
    			<RequestOption>$ratetype</RequestOption>
    		</Request>
    	<PickupType>
    		<Code>$pickup</Code>
    	</PickupType>
    	<Shipment>
    		<Shipper>
    			<Address>
    				<PostalCode>$PostalCode</PostalCode>
    				<CountryCode>$OriginCountry</CountryCode>
    			</Address>
			<ShipperNumber>$ShipperNumber</ShipperNumber>
    		</Shipper>
    		<ShipTo>
    			<Address>
    				<PostalCode>$dest_postcode</PostalCode>
    				<CountryCode>$dest_country</CountryCode>";
    			$data .= ($residential) ? '<ResidentialAddressIndicator/>' : '';
    			$data .= "</Address>
    		</ShipTo>
    		<ShipFrom>
    			<Address>
    				<PostalCode>$PostalCode</PostalCode>
    				<CountryCode>$OriginCountry</CountryCode>
    			</Address>
    		</ShipFrom>
    		<Service>
    			<Code>$service</Code>
    		</Service>";
    		
    		$boxes = array();
    		// Split num of boxes by max box weight
    		if($max_box_weight && $weight > $max_box_weight) {
    			$num_of_boxes = ceil($weight / $max_box_weight);
    			// Get weights of all but the last box
    			for ($i=0; $i<$num_of_boxes-1; $i++) {
    				$boxes[] = array('weight'=>$max_box_weight);
				}
				// Remaining weight of the last box
    			$last_box_weight = $weight - ($max_box_weight * ($num_of_boxes-1));
    			if ($last_box_weight) {
    				$boxes[] = array('weight'=>$last_box_weight);
				}
			} else {
				$boxes[] = array('weight'=>$weight);
			}
			
			foreach ($boxes as $box) {
    			$data .="<Package>
    				<PackagingType>
    					<Code>$container</Code>
    				</PackagingType>
    				<Dimensions>
    					<UnitOfMeasurement>
    						<Code>$dim_unit</Code>
    					</UnitOfMeasurement>
    					<Length>$length</Length>
    					<Width>$width</Width>
    					<Height>$height</Height>
    				</Dimensions>
    				<PackageWeight>
    					<UnitOfMeasurement>
    						<Code>$weight_unit</Code>
    					</UnitOfMeasurement>
    					<Weight>".$box['weight']."</Weight>
    				</PackageWeight>
    			</Package>";
			}
			
			
    		$data .= "
    	</Shipment>
    	</RatingServiceSelectionRequest>";
		if ($testmode) {
			$ch = curl_init("https://wwwcie.ups.com/ups.app/xml/Rate");
		} else {
    		$ch = curl_init("https://www.ups.com/ups.app/xml/Rate");
		}
    	curl_setopt($ch, CURLOPT_HEADER, 1);
    	curl_setopt($ch,CURLOPT_POST,1);
    	curl_setopt($ch,CURLOPT_TIMEOUT, 60);
    	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
    	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
    	curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
    	$result=curl_exec ($ch);
		curl_close($ch);
		//echo '<!-- '. $result. ' -->'; // THIS LINE IS FOR DEBUG PURPOSES ONLY-IT WILL SHOW IN HTML COMMENTS
    	$data = strstr($result, '<?');
    	
    	// Error Check
    	if (get_tag('ErrorCode', $data)) {
    		$error = get_tag('ErrorDescription', $data);
    		return array('error' => $error[0]);
		}
    	
    	$xml_parser = xml_parser_create();
    	xml_parse_into_struct($xml_parser, $data, $vals, $index);
    	xml_parser_free($xml_parser);
    	$params = array();
    	$level = array();
    	$tmp = array();
    	$tmp2 = array();
    	$tmp3 = array();
    	foreach ($vals as $xml_elem) {
    		if ($xml_elem['type'] == 'open') {
    			if (array_key_exists('attributes',$xml_elem)) {
    				list($level[$xml_elem['level']],$extra) = array_values($xml_elem['attributes']);
    		  	} else {
    				$level[$xml_elem['level']] = $xml_elem['tag'];
    		  	}
    		}
    		if ($xml_elem['type'] == 'complete') {
    			$start_level = 1;
    			$php_stmt = '$params';
    			while($start_level < $xml_elem['level']) {
    				$php_stmt .= '[$level['.$start_level.']]';
    				$start_level++;
    			}
    			$php_stmt .= '[@$xml_elem[\'tag\']] = @$xml_elem[\'value\'];';
    			eval(@$php_stmt);
    			$tmp[] = @eval("return $php_stmt;");
    			$tmp2[] = @$params['RATINGSERVICESELECTIONRESPONSE']['RATEDSHIPMENT']['SERVICE']['CODE'];
    			$tmp3[] = @$params['RATINGSERVICESELECTIONRESPONSE']['RATEDSHIPMENT']['TOTALCHARGES']['MONETARYVALUE'];
    		}
    	}
    	
    	if ($ratetype == "Shop") {
		   	$tmp2 = array_unique(array_filter($tmp2, 'strlen'));
    		$tmp3 = array_unique(array_filter($tmp3, 'strlen'));
    		if (!empty($tmp2) && !empty($tmp3)) {
    			return array_combine($tmp2, $tmp3);
			} else {
				return array('error');
			}
		} else {
    		return @$params['RATINGSERVICESELECTIONRESPONSE']['RATEDSHIPMENT']['TOTALCHARGES']['MONETARYVALUE'];
		}
    }
?>
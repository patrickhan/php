<?php
class ControllerCheckoutGCheckout extends Controller {

	var $expireGoogleOrder = 60; /*3588;*/ // 3600 * 12 = 12 hours
	var $expireGoogleDownload = 2592000; // 3600 * 24 * 30 = 1 month

	function __construct() {
		//parent::__construct();
		$this->load->model('checkout/gcheckout');
		$this->language->load('checkout/gcheckout');
	}


	function index() {
		$this->redirect($this->url->http('common/home'));
	}


	/**
	 * Process the payment using the following steps:
	 *
	 * 1) Pass the shopping cart to Google
	 * 2) Send it to Google
	 * 3) Transfer to Google
	 *
	 * This function implements the Checkout API according to
	 * http://code.google.com/apis/checkout/developer/
	 */
	function process() 
	{
		// get the Google merchant id and key
		$merchantId = $this->config->get('gcheckout_merchantid');
		$merchantKey = $this->config->get('gcheckout_merchantkey');

		// get the Google libraries
		chdir('system/google');
		require_once('library/googlecart.php');
		require_once('library/googleitem.php');
		require_once('library/googleshipping.php');
		require_once('library/googletax.php');

		// get the Google server type and currencies
		$serverType = ($this->config->get('gcheckout_test')) ? 'sandbox' : 'production';
		$currencies = $this->model_checkout_gcheckout->getCurrencies();
		$currencyGoogle = $currencies['currency_google'];
		$currencyShop = $currencies['currency_shop'];
		$baseValShop = $currencies['base_val_shop'];
		$baseValGoogle = $currencies['base_val_google'];

		// create a new Google cart object
		$cartGoogle = new GoogleCart($merchantId, $merchantKey, $serverType, $currencyGoogle);
		$products = $this->cart->getProducts();

		// create a new unique order reference number the old Opencart 0.7.9 way
		// $reference = $this->session->data['order_id'];
		//$random    = strtoupper(uniqid());
		//$reference = substr($random, 0, 5) . '-' . substr($random, 5, 5) . '-' . substr($random . rand(10, 99), 10, 5);
		$reference = md5(uniqid());

		// get the weight conversion rules from the database
		$weightRules = $this->model_checkout_gcheckout->getWeightRules();

		// Create Google tax tables from OpenCart's tax classes specified in the products.
		//   each OpenCart tax_class corresponds to a GoogleAlternativeTaxTable
		//   each OpenCart tax_rate corresponds to a GoogleAlternativeTaxRule
		$taxTables = array();     // indexed by tax_class_id
		$taxTableNames = array(); // indexed by tax_class_id
		foreach ($products as $product) {
			$taxClassId = $product['tax_class_id'];
			if (isset($taxTables[$taxClassId])) {
				continue;
			}
			if ($taxClassId == 0) {
				$taxTableName = $this->language->get('text_gcheckout_none');
				$taxTable = new GoogleAlternateTaxTable($taxTableName);
				$taxRule = new GoogleAlternateTaxRule(0.00);
				$taxRule->SetWorldArea(true);
				$taxTable->AddAlternateTaxRules($taxRule);
				$taxTables[$taxClassId] = $taxTable;
				$taxTableNames[$taxClassId] = $taxTableName;
				continue;
			}
			$taxTableName = $this->model_checkout_gcheckout->getTaxTableName($taxClassId);
			$taxTable = new GoogleAlternateTaxTable($taxTableName);
			$taxRules = array();
			$rows = $this->model_checkout_gcheckout->findTaxRules($taxClassId);
			foreach ($rows as $row) {
				$taxRateId = $row['tax_rate_id'];
				if (!isset($taxRules[$taxRateId])) {
					$rate = $row['rate'];
					$taxRules[$taxRateId] = new GoogleAlternateTaxRule($rate/100);
				}
				$countryCode = $row['iso_code_2'];
				$zoneCode = $row['code'];
				if ($zoneCode==0) {
					$taxRules[$taxRateId]->AddPostalArea($countryCode);
				}
				else if ($countryCode == 'US') {
					$taxRules[$taxRateId]->state_areas_arr[] = $zoneCode;
				}
			}
			foreach ($taxRules as $taxRule) {
				$taxTable->AddAlternateTaxRules($taxRule);
			}
			$taxTables[$taxClassId] = $taxTable;
			$taxTableNames[$taxClassId] = $taxTableName;
		}
		foreach ($taxTables as $taxTable) {
			$cartGoogle->AddAlternateTaxTables($taxTable);
		}

		// Find the OpenCart shipping methods
		list( $names, $geoZoneIds ) = $this->model_checkout_gcheckout->getShippingMethods();

		// Find the OpenCart tax class which is used by the shipping methods.
		// The tax rates from that tax class will be used for the
		// GoogleDefaultTaxRule objects.
		$taxClassId = 0;
		$codes = array_keys( $names );
		foreach ($codes as $code) {
			$shippingCode = (strpos($code,'_',0)===FALSE) ? $code : substr($code,0,strpos($code,'_',0));
			if ($this->config->get($shippingCode.'_tax_class_id')!=NULL) {
				$taxClassId = $this->config->get($shippingCode.'_tax_class_id');
				break;
			}
		}
		if ($taxClassId==0) {
			$taxRule = new GoogleDefaultTaxRule(0.00);
			$taxRule->SetWorldArea(true);
			$cartGoogle->AddDefaultTaxRules($taxRule);
		}
		else {
			$taxRules = array();
			$rows = $this->model_checkout_gcheckout->findTaxRates($taxClassId);
			foreach ($rows as $row) {
				$taxRateId = $row['tax_rate_id'];
				if (!isset($taxRules[$taxRateId])) {
					$rate = $row['rate'];
					$taxRules[$taxRateId] = new GoogleDefaultTaxRule($rate/100, 'true');
				}
				$countryCode = $row['iso_code_2'];
				$zoneCode = $row['code'];
				if (($zoneCode==0) || (is_null($zoneCode))) {
					$taxRules[$taxRateId]->AddPostalArea($countryCode);
				}
				else if ($countryCode == 'US') {
					$taxRules[$taxRateId]->state_areas_arr[] = $zoneCode;
				}
			}
			foreach ($taxRules as $taxRule) {
				$cartGoogle->AddDefaultTaxRules($taxRule);
			}
		}

		$shipping = FALSE;
		if ($this->config->get('gcheckout_merchant_calculation')) {
			foreach ($products as $product) {
				if ($product['shipping']) {
					$shipping = TRUE;
					break;
				}
			}
		}

		// Add the OpenCart shipping methods to the Google cart
		if ($shipping) {
			foreach ($geoZoneIds as $code => $geoZoneId) {
				//$name = $names[$code];
				$shipping = new GoogleMerchantCalculatedShipping( $names[$code], 0 );
				if ($geoZoneId==0) {
					$restriction = new GoogleShippingFilters();
					$restriction->SetAllowedWorldArea( TRUE );
					$shipping->AddShippingRestrictions($restriction);
					$filter = new GoogleShippingFilters();
					$filter->SetAllowedWorldArea( TRUE );
					$shipping->AddAddressFilters($filter);
				}
				else {
					$rows = $this->model_checkout_gcheckout->findGeoAreas($geoZoneId);
					$countries = array();
					foreach ($rows as $row) {
						$countryCode = $row['iso_code_2'];
						$zoneCode = (is_null($row['code'])) ? '' : $row['code'];
						if (!isset($countries[$countryCode])) {
							$countries[$countryCode] = array();
						}
						if ($countryCode == 'US') {
							if ($this->model_checkout_gcheckout->isUSStateArea( $zoneCode )) {
								$countries[$countryCode][] = $zoneCode;
							}
						}
					}
					$restriction = new GoogleShippingFilters();
					$filter = new GoogleShippingFilters();
					foreach ($countries as $countryCode => $zoneCodes) {
						if ($countryCode=='US') {
							if (count($zoneCodes)>0) {
								$restriction->SetAllowedStateAreas( $zoneCodes );
								$filter->SetAllowedStateAreas( $zoneCodes );
							}
							else {
								$restriction->AddAllowedPostalArea( $countryCode );
								$filter->AddAllowedPostalArea( $countryCode );
							}
						}
						else {
							$restriction->AddAllowedPostalArea( $countryCode );
							$filter->AddAllowedPostalArea( $countryCode );
						}
					}
					$shipping->AddShippingRestrictions($restriction);
					$shipping->AddAddressFilters($filter);
				}
				$cartGoogle->AddShipping( $shipping );
			}
			if (count($names)>0) {
				$cartGoogle->SetMerchantCalculations( $this->url->https( 'checkout/gcheckout/callback' ) );
			}
		} else if ($this->config->get('gcheckout_merchant_calculation')) {
			$shipping = new GoogleFlatRateShipping($this->language->get('text_gcheckout_no_shipping'), 0);
			$cartGoogle->AddShipping( $shipping );
		}

		// Copy the shopping cart items into the Google cart
		foreach ($products as $product) {
			$weightClassId = $product['weight_class_id'];
			$row = $this->model_checkout_gcheckout->findWeightClass($weightClassId);
			$weightUnit = strtoupper($row['unit']);
			$weight = $product['weight'];
			foreach ($product['option'] as $option) {
				if (isset($option['weight'])) {
					if (isset($option['weight_prefix'])) {
						if ($option['weight_prefix'] == '+') {
							$weight += $option['weight'];
						}
						else if ($option['weight_prefix'] == '-') {
							$weight -= $option['weight'];
						}
					}
					else {
						$weight += $option['weight'];
					}
				}
			}
			$weight = ($weight<0) ? 0 : $weight;
			if ($weightUnit != 'LB') {
				$weight = $weight * $weightRules[$weightUnit]['LB'];
				$weightUnit = 'LB';
			}
			$name = $product['name'];
			$model = trim( $product['model'] );
			if ($model != '') {
				$name .= ' '.$this->language->get('text_gcheckout_model').$model;
			}
			$optionDetails = '';
			foreach ($product['option'] as $option) {
				$optionDetails .= ($optionDetails=='') ? '(' : ' ';
				$optionDetails .= $option['name'].'='.str_replace('&nbsp;',' ',$option['value']);
			}
			if ($optionDetails != '') {
				$optionDetails .= ')';
				$name .= ' '.$optionDetails;
			}
			$priceShop = $product['price'];
			$priceGoogle = ($currencyShop!=$currencyGoogle) ? round(($priceShop * $baseValGoogle) / $baseValShop) : $priceShop;
			$textId = $this->language->get('text_gcheckout_product_id');
			$item = new GoogleItem( $name, $textId.$product['product_id'], $product['quantity'], $priceGoogle, $weightUnit, $weight );
			$item->SetMerchantItemId( $product['key'] );
			$taxClassId = $product['tax_class_id'];
			$item->SetTaxTableSelector( $taxTableNames[$taxClassId] );
			if (count($product['download'])>0) {
				$digitalUrl = $this->url->https( 'checkout/gdownload&order_reference='.$reference );
				$digitalUrl = str_replace('&', '&amp;', $digitalUrl );
				$digitalDesc = $name;
				$item->SetURLDigitalContent( $digitalUrl, '', $digitalDesc );
			}
			$cartGoogle->AddItem( $item );
		}

		// Add a new OpenCart order reference and some return URLs to Google Cart
		$cartGoogle->SetContinueShoppingUrl( $this->url->https( 'common/home' ) );
		$cartGoogle->SetEditCartUrl( $this->url->https( 'checkout/cart' ) );
		$privateData = new MerchantPrivateData( array( 'reference' => $reference, 'currency' => $currencyShop, 'language-id' => $this->model_checkout_gcheckout->getDefaultLanguageId() ) );
		$cartGoogle->SetMerchantPrivateData( $privateData );

		// Put the unique order reference to a session object
		$this->session->data['google_order_reference'] = $reference;

		// This will do a server-to-server Google cart post 
		// and send an HTTP 302 redirect status to the client's browser.
		// More info http://code.google.com/apis/checkout/developer/index.html#alternate_technique
		list($status, $error) = $cartGoogle->CheckoutServer2Server();

		// If it reaches this point then something went wrong
		chdir('../..');
		$this->language->load('checkout/gcheckout');
		$this->document->title = $this->language->get('heading_title'); 
		$this->document->breadcrumbs = array();
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		); 
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('checkout/gcheckout'),
			'text'      => $this->language->get('text_gcheckout_title'),
			'separator' => $this->language->get('text_separator')
		);
		$this->data['heading_title'] = $this->language->get('heading_title');
		$xml = simplexml_load_string( htmlspecialchars_decode( $error ) );
		$this->data['error'] = $xml->{'error-message'};
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['continue'] = $this->url->http('common/home');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/gcheckout.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/gcheckout.tpl';
		} else {
			$this->template = 'default/template/checkout/gcheckout.tpl';
		}	
		$this->children = array(
			'common/header',
			'common/footer',
			'common/column_left',
			'common/column_right'
		);
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	
	/**
	 * Callback handler for Google Checkout.
	 *
	 * This handler receives messages from Google about submitted orders and their states.
	 * 
	 * It implements the Notification API and Merchant Calculations API according to
	 * http://code.google.com/apis/checkout/developer/
	 *
	 */
	function callback() {
		// make sure its posted
		if (!strtoupper($this->request->server['REQUEST_METHOD']) == 'POST') {
			$this->response->redirect($this->url->https('common/home'));
		}

		// include the Google libraries
		chdir('system/google');
		require_once('library/googleresponse.php');
		require_once('library/googlemerchantcalculations.php');
		require_once('library/googleresult.php');
		require_once('library/googlerequest.php');

		$merchantId = $this->config->get('gcheckout_merchantid');
		$merchantKey = $this->config->get('gcheckout_merchantkey');
		$serverType = ($this->config->get('gcheckout_test')) ? 'sandbox' : 'production';
		$currency = $this->config->get('gcheckout_currency');
		$response = new GoogleResponse( $merchantId, $merchantKey );
		//$request = new GoogleRequest( $merchantId, $merchantKey, $serverType, $currency );

		// Setup the log files
		define('RESPONSE_HANDLER_ERROR_LOG_FILE', '../logs/googleerror.log');
		define('RESPONSE_HANDLER_LOG_FILE', '../logs/googlemessage.log');
		$response->SetLogFiles( RESPONSE_HANDLER_ERROR_LOG_FILE, RESPONSE_HANDLER_LOG_FILE, L_ALL);

		// Retrieve the XML sent in the HTTP POST request to the ResponseHandler
		$xmlResponse = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : file_get_contents("php://input");
		if (get_magic_quotes_gpc()) {
			$xmlResponse = stripslashes( $xmlResponse );
		}
		$parsedXML = $response->GetParsedXML( $xmlResponse );
		$root = $parsedXML[0];
		$data = $parsedXML[1];

		// Do the HTTP authentication
		$response->SetMerchantAuthentication( $merchantId, $merchantKey );
		$status = $response->HttpAuthentication();
		if (!$status) {
			error_log( "ControllerCheckoutGCheckout::callback authentication failed",3,DIR_LOGS."error.txt" );
			die('authentication failed');
		}

		// Process the message from Google
		switch ($root) {
			case "request-received": {
				break;
			}
			case "error": {
				break;
			}
			case "diagnosis": {
				break;
			}
			case "checkout-redirect": {
				break;
			}
			case "merchant-calculation-callback": {
				// Create the results and send it
				$merchant_calc = new GoogleMerchantCalculations($currency);
				$this->cart = $this->recreateCart( $this->get_arr_result($data[$root]['shopping-cart']['items']['item']) );
				if (count($this->cart->getProducts())==0) {
					$response->ProcessMerchantCalculations($merchant_calc);
					break;
				}
				// Loop through the list of address ids from the callback
				$addresses = $this->get_arr_result($data[$root]['calculate']['addresses']['anonymous-address']);
				list( $methodNames, $geoZoneIds ) = $this->model_checkout_gcheckout->getShippingMethods();
				foreach($addresses as $curr_address) {
					$curr_id = $curr_address['id'];
					$country = $curr_address['country-code']['VALUE'];
					$city = $curr_address['city']['VALUE'];
					$region = $curr_address['region']['VALUE'];
					$postal_code = $curr_address['postal-code']['VALUE'];
					$address = $this->recreateAddress( $country, $city, $region, $postal_code );
					if (!isset($address['country_id'])) {
						continue;
					}
					if (!isset($address['zone_id'])) {
						continue;
					}
					// Loop through each shipping method if merchant-calculated shipping
					// support is to be provided
					if (isset($data[$root]['calculate']['shipping'])) {
						$shipping = $this->get_arr_result($data[$root]['calculate']['shipping']['method']);
						foreach($shipping as $curr_ship) {
							$price = 0;
							$shippable = "false";
							foreach ($methodNames as $key => $methodName) {
								if ($methodName == $curr_ship['name']) {
									$keyParts = explode('_',$key);
									$keyName = $keyParts[0];
									$this->load->model('shipping/' . $keyName);
									$method_data = $this->{'model_shipping_'.$keyName}->getQuote($address); 
									if (isset($method_data['quote'])) {
										foreach ($method_data['quote'] as $id => $quote_data) {
											if ($id==$key) {
												$price = $quote_data['cost'];
												$shippable = "true";
												break;
											}
										}
									}
								}
							}

							$currencies = $this->model_checkout_gcheckout->getCurrencies();
							$currencyGoogle = $currencies['currency_google'];
							$currencyShop = $currencies['currency_shop'];
							$baseValShop = $currencies['base_val_shop'];
							$baseValGoogle = $currencies['base_val_google'];
							$priceGoogle = ($currencyShop!=$currencyGoogle) ? round(($price * $baseValGoogle) / $baseValShop) : $price;
							
							//$title = (isset($quote['title'])) ? $quote['title'] : $name;
							$merchant_result = new GoogleResult($curr_id);
							$merchant_result->SetShippingDetails($curr_ship['name'], $priceGoogle, $shippable);

//							if ($data[$root]['calculate']['tax']['VALUE'] == "true") {
//								//Compute tax for this address id and shipping type
//								$amount = 15; // Modify this to the actual tax value
//								$merchant_result->SetTaxDetails($amount);
//							}

//							if (isset($data[$root]['calculate']['merchant-code-strings']['merchant-code-string'])) {
//								$codes = $this->get_arr_result($data[$root]['calculate']['merchant-code-strings']['merchant-code-string']);
//								foreach ($codes as $curr_code) {
//									//Update this data as required to set whether the coupon is valid, the code and the amount
//									$coupons = new GoogleCoupons("true", $curr_code['code'], 5, "test2");
//									$merchant_result->AddCoupons($coupons);
//								}
//							}
							$merchant_calc->AddResult($merchant_result);
						}
					} else {
						$merchant_result = new GoogleResult($curr_id);
//						if ($data[$root]['calculate']['tax']['VALUE'] == "true") {
//							//Compute tax for this address id and shipping type
//							$amount = 15; // Modify this to the actual tax value
//							$merchant_result->SetTaxDetails($amount);
//						}
//						$codes = $this->get_arr_result($data[$root]['calculate']['merchant-code-strings']['merchant-code-string']);
//							foreach($codes as $curr_code) {
//								//Update this data as required to set whether the coupon is valid, the code and the amount
//								$coupons = new GoogleCoupons("true", $curr_code['code'], 5, "test2");
//								$merchant_result->AddCoupons($coupons);
//						}
						$merchant_calc->AddResult($merchant_result);
					}
				}
				$response->ProcessMerchantCalculations($merchant_calc);
				break;
			}
			case "new-order-notification": {
				// store the order reference and XML details into the OpenCart database
				if (isset($data[$root]['shopping-cart']['merchant-private-data']['reference']['VALUE'])) {
					
					// store order reference into the database
					$reference = $data[$root]['shopping-cart']['merchant-private-data']['reference']['VALUE'];
					$orderGoogle = $this->model_checkout_gcheckout->findGoogleOrder($reference);
					if (!$orderGoogle) {
						$this->model_checkout_gcheckout->insertGoogleOrder($reference,time()+$this->expireGoogleOrder);
					}
					
					// add ordered digital items to the database
					$languageId = $this->model_checkout_gcheckout->getDefaultLanguageId();
					if (isset($data[$root]['shopping-cart']['merchant-private-data']['language-id']['VALUE'])) {
						$languageId = $data[$root]['shopping-cart']['merchant-private-data']['language-id']['VALUE'];
					}
					$items = $this->get_arr_result( $data[$root]['shopping-cart']['items']['item'] );
					foreach ($items as $item) {
						$merchantItemId = $item['merchant-item-id']['VALUE'];
						$productAndOptionsIds = explode(':',$merchantItemId);
						$productId = $productAndOptionsIds[0];
						$downloads = $this->model_checkout_gcheckout->findDownloads($productId,$languageId);
						if ($downloads) {
							foreach ($downloads as $download) {
								$downloadId = $download['download_id'];
								$name = $item['item-name']['VALUE'];
								$filename = $download['filename'];
								$mask = $download['mask'];
								$remaining = $download['remaining'] * $item['quantity']['VALUE'];
								$expire = time() + $this->expireGoogleDownload;
								$this->model_checkout_gcheckout->insertGoogleDownload($reference,$productId,$downloadId,$name,$filename,$mask,$remaining,$expire);
							}
						}
					}
					
				}
				$response->SendAck();
				break;
			}
			case "order-state-change-notification": {
				$response->SendAck();
				$new_financial_state = $data[$root]['new-financial-order-state']['VALUE'];
				$new_fulfillment_order = $data[$root]['new-fulfillment-order-state']['VALUE'];

				switch($new_financial_state) {
					case 'REVIEWING': {
						break;
					}
					case 'CHARGEABLE': {
						//$Grequest->SendProcessOrder($data[$root]['google-order-number']['VALUE']);
						//$Grequest->SendChargeOrder($data[$root]['google-order-number']['VALUE'],'');
						break;
					}
					case 'CHARGING': {
						break;
					}
					case 'CHARGED': {
						break;
					}
					case 'PAYMENT_DECLINED': {
						break;
					}
					case 'CANCELLED': {
						break;
					}
					case 'CANCELLED_BY_GOOGLE': {
						//$Grequest->SendBuyerMessage($data[$root]['google-order-number']['VALUE'],
						//"Sorry, your order is cancelled by Google", true);
						break;
					}
					default:
						break;
				}

				switch($new_fulfillment_order) {
					case 'NEW': {
						break;
					}
					case 'PROCESSING': {
						break;
					}
					case 'DELIVERED': {
						break;
					}
					case 'WILL_NOT_DELIVER': {
						break;
					}
					default:
						break;
				}
				break;
			}
			case "charge-amount-notification": {
				//$Grequest->SendDeliverOrder($data[$root]['google-order-number']['VALUE'],
				//	<carrier>, <tracking-number>, <send-email>);
				//$Grequest->SendArchiveOrder($data[$root]['google-order-number']['VALUE'] );
				$response->SendAck();
				break;
			}
			case "chargeback-amount-notification": {
				$response->SendAck();
				break;
			}
			case "refund-amount-notification": {
				$response->SendAck();
				break;
			}
			case "risk-information-notification": {
				$response->SendAck();
				break;
			}
			default:
				$response->SendBadRequestStatus("Invalid or not supported Message");
				break;
		}
		exit;
	}


	/*  In case the XML API contains multiple open tags
		with the same value, then invoke this function and
		perform a foreach on the resultant array.
		This takes care of cases when there is only one unique tag
		or multiple tags.
		Examples of this are "anonymous-address", "merchant-code-string"
		from the merchant-calculations-callback API
	*/
	protected function get_arr_result($child_node) {
		$result = array();
		if (isset($child_node)) {
			if ($this->is_associative_array($child_node)) {
				$result[] = $child_node;
			}
			else {
				foreach ($child_node as $curr_node){
					$result[] = $curr_node;
				}
			}
		}
		return $result;
	}
	
	/* Returns true if a given variable represents an associative array */
	protected function is_associative_array( $var ) {
		return is_array( $var ) && !is_numeric( implode( '', array_keys( $var ) ) );
	}



	/**
	 * Recreate an OpenCart shopping cart from a list of Google items
	 */
	protected function recreateCart( $googleItems ) {
		$data = array();
		foreach ($googleItems as $item) {
			$data[$item['merchant-item-id']['VALUE']] = $item['quantity']['VALUE'];
		}
		$this->session->data['cart'] = $data;
		return Registry::get('cart');
	}


	/**
	 * Recreate an address for OpenCart from a Google message.
	 * The address will take just enough information so as to be able
	 * to find a subsequent shipping quote.
	 */
	protected function recreateAddress( $country, $city, $region, $postal_code ) {
		$data = array();
		$data['iso_code_2'] = $country;
		$data['city'] = $city;
		$data['zone'] = $region;
		$data['postcode'] = $postal_code;
		
		$result = $this->model_checkout_gcheckout->findCountryIdByISOCode2($country);
		if ($result) {
			$countryId = $result['country_id'];
			$data['country_id'] = $countryId;
			if ($country=='US') {
				$result = $this->model_checkout_gcheckout->findZoneByCountryIdAndRegionCode($countryId,$region);
			}
			else {
				$result = $this->model_checkout_gcheckout->findZoneByCountryIdAndRegionName($countryId,$region);
			}
			if ($result) {
				$data['zone'] = $result['name'];
				$data['zone_id'] = $result['zone_id'];
			}
		}
		return $data;
	}

}
?>
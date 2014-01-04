<?php
/**
 * Opencart BeanStream Payment Module - Catalog
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Opencart
 * @package    Payment
 * @copyright  Copyright (c) 2010 Schogini Systems (http://www.schogini.in)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Gayatri S Ajith <gayatri@schogini.com>
 */
class ControllerPaymentBeanStream extends Controller {
	protected function index() {
		$this->language->load('payment/beanstream');
		
		$this->data['text_credit_card'] = $this->language->get('text_credit_card');
		$this->data['text_wait'] = $this->language->get('text_wait');
		
		$this->data['entry_cc_owner'] = $this->language->get('entry_cc_owner');
		$this->data['entry_cc_number'] = $this->language->get('entry_cc_number');
		$this->data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
		$this->data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		
		$this->data['months'] = array();
		
		for ($i = 1; $i <= 12; $i++) {
			$this->data['months'][] = array(
				'text'  => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)), 
				'value' => sprintf('%02d', $i)
			);
		}
		
		$today = getdate();

		$this->data['year_expire'] = array();

		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$this->data['year_expire'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)) 
			);
		}
		
		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}
		
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/beanstream.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/beanstream.tpl';
		} else {
			$this->template = 'default/template/payment/beanstream.tpl';
		}	
		
		$this->render();		
	}
	
	public function send() {
		
		/* Collect the order data */
		
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		/*
		The data that is stored in the $order_info array
		Array
		(
			[order_id] => 4
			[invoice_id] => 0
			[invoice_prefix] => 
			[store_id] => 0
			[store_name] => Your Store
			[store_url] => http://localhost/opencart/
			[customer_id] => 1
			[customer_group_id] => 8
			[firstname] => Gayatri
			[lastname] => Ajith
			[telephone] => 1231234123
			[fax] => 
			[email] => gayatri@schogini.com
			[shipping_firstname] => Gayatri
			[shipping_lastname] => Ajith
			[shipping_company] => Schogini
			[shipping_address_1] => 123 Fake St
			[shipping_address_2] => 
			[shipping_city] => San Jose
			[shipping_postcode] => 95101
			[shipping_zone] => California
			[shipping_zone_id] => 3624
			[shipping_country] => United States
			[shipping_country_id] => 223
			[shipping_address_format] => {firstname} {lastname}
		{company}
		{address_1}
		{address_2}
		{city}, {zone} {postcode}
		{country}
			[shipping_method] => Flat Shipping Rate
			[payment_firstname] => Gayatri
			[payment_lastname] => Ajith
			[payment_company] => Schogini
			[payment_address_1] => 123 Fake St
			[payment_address_2] => 
			[payment_city] => San Jose
			[payment_postcode] => 95101
			[payment_zone] => California
			[payment_zone_id] => 3624
			[payment_country] => United States
			[payment_country_id] => 223
			[payment_address_format] => {firstname} {lastname}
		{company}
		{address_1}
		{address_2}
		{city}, {zone} {postcode}
		{country}
			[payment_method] => Credit Card / Debit Card (FirstData)
			[comment] => This is a test
			[total] => 102.0000
			[order_status_id] => 0
			[language_id] => 1
			[currency_id] => 1
			[currency] => GBP
			[value] => 1.00000000
			[coupon_id] => 0
			[date_modified] => 2010-05-19 19:09:01
			[date_added] => 2010-05-19 19:09:01
			[ip] => 127.0.0.1
			[shipping_zone_code] => CA
			[shipping_iso_code_2] => US
			[shipping_iso_code_3] => USA
			[payment_zone_code] => CA
			[payment_iso_code_2] => US
			[payment_iso_code_3] => USA
		)
		*/
		
		// Get the ISO 2 letter country code - BeanStream needs the 2 letter country code
		$this->load->model('localisation/country');
		$countries = $this->model_localisation_country->getCountries();
		$payment_country = $order_info['payment_country'];
		$shipping_country = $order_info['shipping_country'];
		foreach ($countries as $country) {
			if ($country['name'] == $order_info['payment_country']) {
				$payment_country = $country['iso_code_2'];
			}
			if ($country['name'] == $order_info['shipping_country']) {
				$shipping_country = $country['iso_code_2'];
			}
		}
		
        $data = array();
		$data['x_first_name'] 		= html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
		$data['x_last_name'] 		= html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
		$data['x_company'] 			= html_entity_decode($order_info['payment_company'], ENT_QUOTES, 'UTF-8');
		$data['x_address'] 			= html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
		$data['x_city'] 			= html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
		$data['x_state'] 			= html_entity_decode($order_info['payment_zone'], ENT_QUOTES, 'UTF-8');
		$data['x_zip'] 				= html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
		$data['x_country'] 			= html_entity_decode($payment_country, ENT_QUOTES, 'UTF-8');
		$data['x_phone'] 			= $order_info['telephone'];
		$data['x_customer_ip'] 		= $this->request->server['REMOTE_ADDR'];
		$data['x_email'] 			= html_entity_decode($order_info['email'], ENT_QUOTES, 'UTF-8');
		$data['x_description'] 		= html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
		$data['x_amount'] 			= $this->currency->format($order_info['total'], $order_info['currency'], 1.00000, FALSE);
		$data['x_currency_code'] 	= $this->currency->getCode();
		$data['x_method'] 			= 'CC';
		$data['x_type'] 			= ($this->config->get('beanstream_method') == 'capture') ? 'AUTH_CAPTURE' : 'AUTH_ONLY';
		$data['x_card_num'] 		= str_replace(' ', '', $this->request->post['cc_number']);
		$data['x_exp_date'] 		= $this->request->post['cc_expire_date_month'] . $this->request->post['cc_expire_date_year'];
		$data['x_card_code'] 		= $this->request->post['cc_cvv2'];
		
		$data['x_ship_to_first_name'] 	= html_entity_decode($order_info['shipping_firstname'], ENT_QUOTES, 'UTF-8');
		$data['x_ship_to_last_name'] 	= html_entity_decode($order_info['shipping_lastname'], ENT_QUOTES, 'UTF-8');
		$data['x_ship_to_address'] 		= html_entity_decode($order_info['shipping_address_1'], ENT_QUOTES, 'UTF-8');
		$data['x_ship_to_city'] 		= html_entity_decode($order_info['shipping_city'], ENT_QUOTES, 'UTF-8');
		$data['x_ship_to_state'] 		= html_entity_decode($order_info['shipping_zone'], ENT_QUOTES, 'UTF-8');
		$data['x_ship_to_zip'] 			= html_entity_decode($order_info['shipping_postcode'], ENT_QUOTES, 'UTF-8');
		$data['x_ship_to_country'] 		= html_entity_decode($shipping_country, ENT_QUOTES, 'UTF-8');
		
		$data['x_comments'] 	= html_entity_decode($order_info['comment'], ENT_QUOTES, 'UTF-8');
		$data['x_invoice_num'] 	= time();
		
		/* Prepare the data to be sent */
		
		$url			= 'https://www.beanstream.com/scripts/process_transaction.asp';
		$merchant_id 	= $this->config->get('beanstream_merchant_id');
		$expMonth 	 	= substr($data['x_exp_date'], 0, 2);
		$expYear	 	= substr($data['x_exp_date'], -2);
		$cvv 			= (isset($data['x_card_code']) && !empty($data['x_card_code'])) ? $data['x_card_code'] : '';
		$data['x_state'] 	= $this->__get_state_code($data['x_state']);
		$post			= "requestType=BACKEND&merchant_id={$merchant_id}&trnCardOwner=" . urlencode ($data['x_first_name']) . "+" . urlencode($data['x_last_name']) . "&trnCardNumber={$data['x_card_num']}&trnExpMonth=$expMonth&trnExpYear=$expYear&trnOrderNumber={$data['x_invoice_num']}&trnAmount={$data['x_amount']}&ordEmailAddress={$data['x_email']}&ordName=" . urlencode($data['x_first_name'] . ' ' . $data['x_last_name']) . "&ordPhoneNumber={$data['x_phone']}&ordAddress1=" . urlencode($data['x_address']) . 
"&ordAddress2=&ordCity=" . urlencode($data['x_city']) . 
"&ordProvince=" . urlencode($data['x_state']) . 
"&ordPostalCode=" . urlencode($data['x_zip']) . 
"&ordCountry={$data['x_country']}" . 
"&trnCardCvd=$cvv";

		/* Send the data to the payment gateway */
		
		$message	= '';
		$error		= '';
		$success	= false;
		$res 		= array();
		$curl_error = '';
		
		$ch = curl_init ();
		curl_setopt ($ch, CURLOPT_URL,$url);
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_VERBOSE, 0);
		$result = curl_exec ($ch);
		$curl_error = curl_error($ch);
		curl_close($ch);
		
		/* Process the payment gateway response */
		
		if ($curl_error != '') {
			// cURL error
			$json['error'] = $curl_error;
			
		} else {
			// Parse the response
			$arr = split("&", $result);
			$res = array();
			foreach(@$arr as $vv){
				list($k,$v)=split('=',$vv);
				$res[$k]=strip_tags(urldecode($v));
			}
			
			// check success / failure and take action
			if ((isset($res['messageText']) && $res['messageText']=='Approved' ) && $res['trnApproved'] != 0) {
				$message  = "Transaction ID: {$res['trnId']}, Auth Code: {$res['authCode']}, Auth Response: {$res['messageText']} ({$res['messageId']}), Transaction Date: {$res['trnDate']}, Ordernum: {$res['trnOrderNumber']}";
				$message .= ', ' . $res['avsMessage'];
				$success  = true;
			} else {
				$success = false;
				if (isset($res['messageText']) && !empty($res['messageText'])) {
					$error = 'Error occurred while processing your payment.' . "\nThis is the message from the payment system: \n" . $res['messageText'];
				} else {
					$error = 'Error occurred while processing your payment.' . "\n" . 'Please re-check your card details and try again';
				}
			}
		}
		
		/* Send the response back to the shopping cart */
		
		$this->load->library('json');
		$json = array();
		if ($success === true) {
			$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));
			$this->model_checkout_order->update($this->session->data['order_id'], $this->config->get('beanstream_order_status_id'), $message, FALSE);
			$json['success'] = HTTPS_SERVER . 'index.php?route=checkout/success';
		} else {
			$json['error'] = $error;
		}
		$this->response->setOutput(Json::encode($json));
	}
	
	protected function __get_state_code($state) {
		$a = array();
		$a['Alabama'] = 'AL';
		$a['Alaska'] = 'AK';
		$a['American Samoa'] = 'AS';
		$a['Arizona'] = 'AZ';
		$a['Arkansas'] = 'AR';
		$a['Armed Forces Africa'] = 'AF';
		$a['Armed Forces Americas'] = 'AA';
		$a['Armed Forces Canada'] = 'AC';
		$a['Armed Forces Europe'] = 'AE';
		$a['Armed Forces Middle East'] = 'AM';
		$a['Armed Forces Pacific'] = 'AP';
		$a['California'] = 'CA';
		$a['Colorado'] = 'CO';
		$a['Connecticut'] = 'CT';
		$a['Delaware'] = 'DE';
		$a['District of Columbia'] = 'DC';
		$a['Federated States Of Micronesia'] = 'FM';
		$a['Florida'] = 'FL';
		$a['Georgia'] = 'GA';
		$a['Guam'] = 'GU';
		$a['Hawaii'] = 'HI';
		$a['Idaho'] = 'ID';
		$a['Illinois'] = 'IL';
		$a['Indiana'] = 'IN';
		$a['Iowa'] = 'IA';
		$a['Kansas'] = 'KS';
		$a['Kentucky'] = 'KY';
		$a['Louisiana'] = 'LA';
		$a['Maine'] = 'ME';
		$a['Marshall Islands'] = 'MH';
		$a['Maryland'] = 'MD';
		$a['Massachusetts'] = 'MA';
		$a['Michigan'] = 'MI';
		$a['Minnesota'] = 'MN';
		$a['Mississippi'] = 'MS';
		$a['Missouri'] = 'MO';
		$a['Montana'] = 'MT';
		$a['Nebraska'] = 'NE';
		$a['Nevada'] = 'NV';
		$a['New Hampshire'] = 'NH';
		$a['New Jersey'] = 'NJ';
		$a['New Mexico'] = 'NM';
		$a['New York'] = 'NY';
		$a['North Carolina'] = 'NC';
		$a['North Dakota'] = 'ND';
		$a['Northern Mariana Islands'] = 'MP';
		$a['Ohio'] = 'OH';
		$a['Oklahoma'] = 'OK';
		$a['Oregon'] = 'OR';
		$a['Palau'] = 'PW';
		$a['Pennsylvania'] = 'PA';
		$a['Puerto Rico'] = 'PR';
		$a['Rhode Island'] = 'RI';
		$a['South Carolina'] = 'SC';
		$a['South Dakota'] = 'SD';
		$a['Tennessee'] = 'TN';
		$a['Texas'] = 'TX';
		$a['Utah'] = 'UT';
		$a['Vermont'] = 'VT';
		$a['Virgin Islands'] = 'VI';
		$a['Virginia'] = 'VA';
		$a['Washington'] = 'WA';
		$a['West Virginia'] = 'WV';
		$a['Wisconsin'] = 'WI';
		$a['Wyoming'] = 'WY';
		
		// Map CA provice (state) codes
		$return_state = '';
		$return_state = $this->__map_ca_prov($state);
		if ($return_state == $state) {
			// CA code not found - find US state code
			$return_state = (isset($a[$state]) ? $a[$state] : $state);
		}
		
		return $return_state;
	}
	
	protected function __map_ca_prov($state) {
		$ca = array(
			'AB' => 'Alberta',
			'BC' => 'British Columbia',
			'MB' => 'Manitoba',
			'NB' => 'New Brunswick',
			'NL' => 'Newfoundland and Labrador',
			'NS' => 'Nova Scotia',
			'NT' => 'Northwest Territories',
			'NU' => 'Nunavut',
			'ON' => 'Ontario',
			'PE' => 'Prince Edward Island',
			'QC' => 'Quebec',
			'SK' => 'Saskatchewan',
			'YT' => 'Yukon Territory'
		);
		$ca2 = array_flip($ca);
		
		if (isset($ca2[$state])) return $ca2[$state];
		return $state;
	}
	
}
?>
<?php
class ControllerCheckoutOnepage extends Controller {
	private $error = array();
	
	public function index() {
		if ($this->config->get('brochure') === TRUE) {
			$this->redirect($this->url->https('error/not_found'));
		}
		
		if ( ! $this->cart->hasProducts() || ( ! $this->cart->hasStock() && ! $this->config->get('config_stock_checkout'))) {
			$this->redirect($this->url->https('checkout/cart'));
		}
		
		if ( ! $this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->https('checkout/onepage');
			$this->redirect($this->url->https('checkout/guest_step_1'));
		}
		if ( ! isset($this->session->data['shipping_address_id'])) {
			$this->session->data['shipping_address_id'] = $this->customer->getAddressId();
		}
		if ( ! isset($this->session->data['payment_address_id'])) {
			$this->session->data['payment_address_id'] = $this->customer->getAddressId();
		}
		$this->data['selected_shipping_address'] = $this->session->data['shipping_address_id'];
		$this->data['selected_payment_address'] = $this->session->data['payment_address_id'];
		
		$this->load->model('account/address');
		$this->load->model('checkout/extension');
		
		$this->load->model('localisation/country');
		$this->data['countries'] = $this->model_localisation_country->getCountries();
		
		$this->language->load('checkout/onepage');
		$this->language->load('checkout/shipping');
		
		if ($this->cart->hasShipping()) {
			$this->shipping();
			$this->data['is_shipping'] = TRUE;
		} else {
			$this->data['is_shipping'] = FALSE;
			unset($this->session->data['shipping_address_id']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			$this->tax->setZone($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
			//$this->redirect($this->url->https('checkout/payment'));
		}
		
		if ($this->cart->hasPayment()) {
			$this->payment();
			$this->data['is_payment'] = TRUE;
		} else {
			$this->data['is_payment'] = FALSE;
			unset($this->session->data['payment_address_id']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		}
		
		$this->data['text_comments'] = $this->language->get('text_comments');
		if (isset($this->request->post['comment'])) {
			$this->data['comment'] = $this->request->post['comment'];
		} else {
			$this->data['comment'] = '';
		}
		$this->totals();
		$this->terms();
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->document->breadcrumbs = array(); 
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('checkout/cart'),
			'text'      => $this->language->get('text_basket'),
			'separator' => $this->language->get('text_separator')
		);
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_discounts'] = $this->language->get('text_discounts');
		$this->data['text_gift_certificate'] = $this->language->get('text_gift_certificate');
		
		$this->data['button_gift_certificate'] = $this->language->get('button_gift_certificate');
		$this->data['button_change_address'] = $this->language->get('button_change_address');
		$this->data['button_back'] = $this->language->get('button_back');
		$this->data['button_continue'] = $this->language->get('button_continue');
		
		$this->data['text_select'] = $this->language->get('text_select');
		
		$results = $this->model_account_address->getAddresses();
		
		foreach ($results as $result) {
			if ($result['address_format']) {
				$format = $result['address_format'];
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
				'firstname' => $result['firstname'],
				'lastname'  => $result['lastname'],
				'company'   => $result['company'],
				'address_1' => $result['address_1'],
				'address_2' => $result['address_2'],
				'city'      => $result['city'],
				'postcode'  => $result['postcode'],
				'zone'      => $result['zone'],
				'country'   => $result['country']  
			);
			
			$this->data['addresses'][] = array(
				'address_id' => $result['address_id'],
				'address'    => str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))),
				'update'     => $this->url->https('account/address/update&address_id=' . $result['address_id']),
				'delete'     => $this->url->https('account/address/delete&address_id=' . $result['address_id'])
			);
		}
		
		$this->data['action'] = $this->url->https('checkout/onepage');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if ($this->data['is_payment']) {
				$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];$this->session->data['comment'] = strip_tags($this->request->post['comment']);						if ($this->data['is_shipping']) {				$shipping = explode('.', $this->request->post['shipping_method']);				$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];			}						/*$this->redirect($this->url->https('checkout/confirm'));	*/	}				
				
				if (isset($this->session->data['error'])) {			$this->data['error_warning'] = $this->session->data['error'];			unset($this->session->data['error']);		} elseif (isset($this->error['warning'])) {			$this->data['error_warning'] = $this->error['warning'];		} else {			$this->data['error_warning'] = '';		}				
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/onepage.tpl')) {			$this->template = $this->config->get('config_template') . '/template/checkout/onepage.tpl';		} else {			$this->template = 'default/template/checkout/onepage.tpl';		}		
				
				$this->data['back'] = $this->url->https('checkout/cart');$pay = $this->session->data['payment_method']['id'];
				
				$back = $this->url->http('checkout/cart');		$onepage = $this->url->http('checkout/onepage');		$guestlogin = $this->url->http('account/login');		$this->load->model('catalog/information');						
				
				$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout'));		if ($information_info) {				$text_agree = sprintf($this->language->get('text_agree'), $this->url->http('information/information&information_id=' . $this->config->get('config_checkout')), $information_info['title']);			} else {				$text_agree = '';			}			if (isset($this->request->post['agree'])) { 			$agree = $this->request->post['agree'];		} else {			$agree = '';		}	
				$total_data = array();

		$total = 0;

		$taxes = $this->cart->getTaxes();

		 

		$this->load->model('checkout/extension');

		

		$sort_order = array(); 

		

		$results = $this->model_checkout_extension->getExtensions('total');

		$table = 'discount';		if(mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$table."'"))==1) {		$results['999'] = array( "extension_id" => "999",    "type" => "total",	"key" => "discount");		}				foreach ($results as $key => $value) {		if ($key == '999') {		$sort_order['999'] = 6;		}		else		{			$sort_order[$key] = $this->config->get($value['key'] . '_sort_order');		}		}


		array_multisort($sort_order, SORT_ASC, $results);

		

		foreach ($results as $result) {

			$this->load->model('total/' . $result['key']);

			

			$this->{'model_total_' . $result['key']}->getTotal($total_data, $total, $taxes);

		}

		

		$sort_order = array(); 

		

		foreach ($total_data as $key => $value) {

			$sort_order[$key] = $value['sort_order'];

		}

		

		array_multisort($sort_order, SORT_ASC, $total_data);

		

		$this->language->load('checkout/confirm');

		

		$this->document->title = $this->language->get('heading_title'); 

		

		$data = array();

		

		$data['customer_id'] = $this->customer->getId();

		$data['firstname'] = $this->customer->getFirstName();

		$data['lastname'] = $this->customer->getLastName();

		$data['email'] = $this->customer->getEmail();

		$data['telephone'] = $this->customer->getTelephone();

		$data['fax'] = $this->customer->getFax();

		

		$this->load->model('account/address');

		

		if ($this->cart->hasShipping()) {

			$shipping_address_id = $this->session->data['shipping_address_id'];	

			

			$shipping_address = $this->model_account_address->getAddress($shipping_address_id);			

			

			$data['shipping_firstname'] = $shipping_address['firstname'];

			$data['shipping_lastname'] = $shipping_address['lastname'];	

			$data['shipping_company'] = $shipping_address['company'];	

			$data['shipping_address_1'] = $shipping_address['address_1'];

			$data['shipping_address_2'] = $shipping_address['address_2'];

			$data['shipping_city'] = $shipping_address['city'];

			$data['shipping_postcode'] = $shipping_address['postcode'];

			$data['shipping_zone'] = $shipping_address['zone'];

			$data['shipping_zone_id'] = $shipping_address['zone_id'];

			$data['shipping_country'] = $shipping_address['country'];

			$data['shipping_country_id'] = $shipping_address['country_id'];

			$data['shipping_address_format'] = $shipping_address['address_format'];

			

			if (isset($this->session->data['shipping_method']['title'])) {

				$data['shipping_method'] = $this->session->data['shipping_method']['title'];

			} else {

				$data['shipping_method'] = '';

			}

		} else {

			$data['shipping_firstname'] = '';

			$data['shipping_lastname'] = '';	

			$data['shipping_company'] = '';	

			$data['shipping_address_1'] = '';

			$data['shipping_address_2'] = '';

			$data['shipping_city'] = '';

			$data['shipping_postcode'] = '';

			$data['shipping_zone'] = '';

			$data['shipping_zone_id'] = '';

			$data['shipping_country'] = '';

			$data['shipping_country_id'] = '';

			$data['shipping_address_format'] = '';

			$data['shipping_method'] = '';

		}

		

		if ($this->cart->hasPayment()) {

			$payment_address_id = $this->session->data['payment_address_id'];	

			

			$payment_address = $this->model_account_address->getAddress($payment_address_id);

			

			$data['payment_firstname'] = $payment_address['firstname'];

			$data['payment_lastname'] = $payment_address['lastname'];

			$data['payment_company'] = $payment_address['company'];

			$data['payment_address_1'] = $payment_address['address_1'];

			$data['payment_address_2'] = $payment_address['address_2'];

			$data['payment_city'] = $payment_address['city'];

			$data['payment_postcode'] = $payment_address['postcode'];

			$data['payment_zone'] = $payment_address['zone'];

			$data['payment_zone_id'] = $payment_address['zone_id'];

			$data['payment_country'] = $payment_address['country'];

			$data['payment_country_id'] = $payment_address['country_id'];

			$data['payment_address_format'] = $payment_address['address_format'];

			

			if (isset($this->session->data['payment_method']['title'])) {

				$data['payment_method'] = $this->session->data['payment_method']['title'];

			} else {

				$data['payment_method'] = '';

			}

		} else {

			$data['payment_firstname'] = '';

			$data['payment_lastname'] = '';

			$data['payment_company'] = '';

			$data['payment_address_1'] = '';

			$data['payment_address_2'] = '';

			$data['payment_city'] = '';

			$data['payment_postcode'] = '';

			$data['payment_zone'] = '';

			$data['payment_zone_id'] = '';

			$data['payment_country'] = '';

			$data['payment_country_id'] = '';

			$data['payment_address_format'] = '';

			$data['payment_method'] = '';

		}

		

		$product_data = array();

		

		foreach ($this->cart->getProducts() as $product) {

			$option_data = array();

			

			foreach ($product['option'] as $option) {

				$option_data[] = array(

					'product_option_value_id' => $option['product_option_value_id'],

					'name'                    => $option['name'],

					'value'                   => $option['value'],

					'prefix'                  => $option['prefix']

				);

			}

			

			$product_data[] = array(

				'product_id' => $product['product_id'],

				'name'       => $product['name'],

				'model'      => $product['model'],

				'option'     => $option_data,

				'download'   => $product['download'],

				'quantity'   => $product['quantity'], 

				'price'      => $product['price'],

				'total'      => $product['total'],

				'tax'        => $this->tax->getRate($product['tax_class_id'])

			); 

		}

		

		$data['products'] = $product_data;

		$data['totals'] = $total_data;

		$data['comment'] = $this->session->data['comment'];

		$data['total'] = $total;

		$data['language_id'] = $this->config->get('config_language_id');

		$data['currency_id'] = $this->currency->getId();

		$data['currency'] = $this->currency->getCode();

		$data['value'] = $this->currency->getValue($this->currency->getCode());

		

		if (isset($this->session->data['coupon'])) {

			$this->load->model('checkout/coupon');

		

			$coupon = $this->model_checkout_coupon->getCoupon($this->session->data['coupon']);

			

			if ($coupon) {

				$data['coupon_id'] = $coupon['coupon_id'];

			} else {

				$data['coupon_id'] = 0;

			}

		} else {

			$data['coupon_id'] = 0;

		}

		

		$data['ip'] = $this->request->server['REMOTE_ADDR'];

		

		$this->load->model('checkout/order');

		

		$this->session->data['order_id'] = $this->model_checkout_order->create($data);
				$button = "<table><tr><td align=\"left\"><a onclick=\"location='$back'\" class=\"button\"><span>Back</span></a></td><td align=\"right\" style=\"padding-right: 5px;\">$text_agree</td><td width=\"5\" style=\"padding-right: 10px;\">";	if ($agree) { 	$button .= "<input type=\"checkbox\" name=\"agree\" value=\"1\" checked=\"checked\" />"; 	} else { 	$button .= "<input type=\"checkbox\" name=\"agree\" value=\"1\" />";	}	$button .= "</td><td align=\"right\"><a onclick=\"$('#onepage').submit();\" class=\"button\"><span>Continue</span></a></td></tr></table>";		
				$this->data['buttons'] = $button;						
				
				$this->children = array(			'common/header',			'common/footer',			'common/column_left',			'common/column_right',						"payment/$pay"		);		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));	}else {
			$this->data['payment'] = false;
			
		
		if (isset($this->session->data['error'])) {
			$this->data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/onepage.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/onepage.tpl';
		} else {
			$this->template = 'default/template/checkout/onepage.tpl';
		}
		$this->data['back'] = $this->url->https('checkout/cart');
		$pay = $this->session->data['payment_method']['id'];
				
				$back = $this->url->http('checkout/cart');		$onepage = $this->url->http('checkout/onepage');		$guestlogin = $this->url->http('account/login');		$this->load->model('catalog/information');						
				
				$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout'));		if ($information_info) {				$text_agree = sprintf($this->language->get('text_agree'), $this->url->http('information/information&information_id=' . $this->config->get('config_checkout')), $information_info['title']);			} else {				$text_agree = '';			}			if (isset($this->request->post['agree'])) { 			$agree = $this->request->post['agree'];		} else {			$agree = '';		}	
				$button = "<table><tr><td align=\"left\"><a onclick=\"location='$back'\" class=\"button\"><span>Back</span></a></td><td align=\"right\" style=\"padding-right: 5px;\">$text_agree</td><td width=\"5\" style=\"padding-right: 10px;\">";	if ($agree) { 	$button .= "<input type=\"checkbox\" name=\"agree\" value=\"1\" checked=\"checked\" />"; 	} else { 	$button .= "<input type=\"checkbox\" name=\"agree\" value=\"1\" />";	}	$button .= "</td><td align=\"right\"><a onclick=\"$('#onepage').submit();\" class=\"button\"><span>Continue</span></a></td></tr></table>";		
				$this->data['buttons'] = $button;				
		$this->children = array(
			'common/header',
			'common/footer',
			'common/column_left',
			'common/column_right'
		);
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}	}
	
	private function shipping() {
		$this->data['text_shipping_to'] = $this->language->get('text_shipping_to');
		$this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
		$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$this->data['text_shipping_methods'] = $this->language->get('text_shipping_methods');
		
		if ( ! isset($this->session->data['shipping_address_id'])) {
			$this->session->data['shipping_address_id'] = $this->customer->getAddressId();
		}
		if ( ! $this->session->data['shipping_address_id']) {
			$this->redirect($this->url->https('checkout/address/shipping'));
		}
		
		$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
		if ( ! $shipping_address) {
			$this->redirect($this->url->https('checkout/address/shipping'));
		}
		
		$this->tax->setZone($shipping_address['country_id'], $shipping_address['zone_id']);
		
		if ( ! isset($this->session->data['shipping_methods'])) {
			$quote_data = array();
			$results = $this->model_checkout_extension->getExtensions('shipping');
			foreach ($results as $result) {
				$this->load->model('shipping/' . $result['key']);
				$quote = $this->{'model_shipping_' . $result['key']}->getQuote($shipping_address); 
				if ($quote) {
					$quote_data[$result['key']] = array(
						'title'      => $quote['title'],
						'quote'      => $quote['quote'], 
						'sort_order' => $quote['sort_order'],
						'error'      => $quote['error']
					);
					foreach ($quote['quote'] as $a_quote) {
						if (empty($this->session->data['shipping_method']['id']) && ! isset($first_quote)) {
							$first_quote = $a_quote['id'];
						}
					}
				}
			}
			$sort_order = array();
			foreach ($quote_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
			array_multisort($sort_order, SORT_ASC, $quote_data);
			$this->session->data['shipping_methods'] = $quote_data;
			//Q: Autochoose shipping if using only one and it's single rate
			if (count($quote_data) == 1) {
			   $values = array_values($quote_data);
			   if (count($values[0]['quote']) == 1) {
			      $keys = array_keys($values[0]['quote']);
			      $method = $values[0]['quote'][$keys[0]];
			      $this->session->data['shipping_method'] = $method;
			      $this->session->data['comment'] = (isset($this->session->data['comment'])) ? $this->session->data['comment'] : '';
			      $this->redirect(HTTPS_SERVER . 'index.php?route=checkout/payment');
			   }
			}//
			$this->setShipping($first_quote);
		}
		
		$this->data['text_shipping_to'] = $this->language->get('text_shipping_to');
		$this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
		$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$this->data['text_shipping_methods'] = $this->language->get('text_shipping_methods');
		$this->data['text_comments'] = $this->language->get('text_comments');
		$this->data['button_change_address'] = $this->language->get('button_change_address');
		$this->data['button_back'] = $this->language->get('button_back');
		$this->data['button_continue'] = $this->language->get('button_continue');
		
		if ($shipping_address['address_format']) {
			$format = $shipping_address['address_format'];
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
			'firstname' => $shipping_address['firstname'],
			'lastname'  => $shipping_address['lastname'],
			'company'   => $shipping_address['company'],
			'address_1' => $shipping_address['address_1'],
			'address_2' => $shipping_address['address_2'],
			'city'      => $shipping_address['city'],
			'postcode'  => $shipping_address['postcode'],
			'zone'      => $shipping_address['zone'],
			'country'   => $shipping_address['country']
		);
		$this->data['address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		
		$this->data['shipping_methods'] = $this->session->data['shipping_methods']; 
		if (isset($this->session->data['shipping_method']['id'])) {
			$this->data['shipping'] = $this->session->data['shipping_method']['id'];
		} else {
			$this->data['shipping'] = '';
		}
	}
	
	public function setShipping($value) {
		$shipping = explode('.', $value);
		if (isset($shipping[1])) {
			$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
		}
	}
	
	public function ajaxShipping() {
		$this->load->model('checkout/extension');
		$this->load->model('account/address');
		$this->language->load('checkout/shipping');
		$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
		$quote_data = array();
		$results = $this->model_checkout_extension->getExtensions('shipping');
		foreach ($results as $result) {
			$this->load->model('shipping/' . $result['key']);
			$quote = $this->{'model_shipping_' . $result['key']}->getQuote($shipping_address); 
			if ($quote) {
				$quote_data[$result['key']] = array(
					'title'      => $quote['title'],
					'quote'      => $quote['quote'], 
					'sort_order' => $quote['sort_order'],
					'error'      => $quote['error']
				);
			}
		}
		$sort_order = array();
		foreach ($quote_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}
		array_multisort($sort_order, SORT_ASC, $quote_data);
		$this->session->data['shipping_methods'] = $quote_data;
		//Q: Autochoose shipping if using only one and it's single rate
			if (count($quote_data) == 1) {
			   $values = array_values($quote_data);
			   if (count($values[0]['quote']) == 1) {
			      $keys = array_keys($values[0]['quote']);
			      $method = $values[0]['quote'][$keys[0]];
			      $this->session->data['shipping_method'] = $method;
			      $this->session->data['comment'] = (isset($this->session->data['comment'])) ? $this->session->data['comment'] : '';
			      $this->redirect(HTTPS_SERVER . 'index.php?route=checkout/payment');
			   }
			}//
		if (isset($this->session->data['shipping_method']['id'])) {
			$this->data['shipping'] = $this->session->data['shipping_method']['id'];
		} else {
			$this->data['shipping'] = '';
		}
		$this->data['shipping_methods'] = $this->session->data['shipping_methods']; 
		
		foreach ($this->data['shipping_methods'] as $shipping_method) {
			echo '<tr><td colspan="3"><b>' . $shipping_method['title'] . '</b></td></tr>';
			if ( ! $shipping_method['error']) {
				foreach ($shipping_method['quote'] as $quote) {
					echo '<tr><td width="1"><label for="' . $quote['id'] . '">';
					if ($quote['id'] == $this->data['shipping']) {
						echo '<input type="radio" onclick="updateTotals();" name="shipping_method" value="' . $quote['id'] . '" id="' . $quote['id'] . '" checked="checked" style="margin: 0px;" />';
					} else {
						echo '<input type="radio" onclick="updateTotals();" name="shipping_method" value="' . $quote['id'] . '" id="' . $quote['id'] . '" style="margin: 0px;" />';
					}
					echo '</label></td><td width="534"><label for="' . $quote['id'] . '" style="cursor: pointer;">' . $quote['title'] . '</label></td>' .
					'<td width="1" align="right"><label for="' . $quote['id'] . '" style="cursor: pointer;">' . $quote['text'] . '</label></td></tr>';
				}
			} else {
				echo '<tr><td colspan="3"><div class="error">' . $shipping_method['error'] . '</div></td></tr>';
			}
		}
	}
	
	private function payment() {
		if ( ! isset($this->session->data['payment_address_id']) && isset($this->session->data['shipping_address_id']) && $this->session->data['shipping_address_id']) {
			$this->session->data['payment_address_id'] = $this->session->data['shipping_address_id'];
		}
		
		if ( ! isset($this->session->data['payment_address_id'])) {
			$this->session->data['payment_address_id'] = $this->customer->getAddressId();
		}
		
		if ( ! $this->session->data['payment_address_id']) {
			$this->redirect($this->url->https('checkout/address/payment'));
		}
		
		$this->load->model('account/address');
		
		$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
		
		if ( ! $payment_address) {
			$this->redirect($this->url->https('checkout/address/payment'));
		}
		
		$method_data = array();
		
		$results = $this->model_checkout_extension->getExtensions('payment');
		
		foreach ($results as $result) {
			$this->load->model('payment/' . $result['key']);
			
			$method = $this->{'model_payment_' . $result['key']}->getMethod($payment_address); 
			
			if ($method) {
				$method_data[$result['key']] = $method;
			}
		}
		
		$sort_order = array(); 
	  
		foreach ($method_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}
		
		array_multisort($sort_order, SORT_ASC, $method_data);
		
		$this->session->data['payment_methods'] = $method_data;
		
		foreach ($this->session->data['payment_methods'] as $a_pay) {
			if (empty($this->session->data['payment_method']['id'])) {
				$this->session->data['payment_method']['id'] = $a_pay['id'];
			}
		}
		
		$this->language->load('checkout/payment');
		
		$this->data['text_payment_to'] = $this->language->get('text_payment_to');
		$this->data['text_payment_address'] = $this->language->get('text_payment_address');
		$this->data['text_payment_method'] = $this->language->get('text_payment_method');
		$this->data['text_payment_methods'] = $this->language->get('text_payment_methods');
		$this->data['text_comments'] = $this->language->get('text_comments');
		
		if ($payment_address['address_format']) {
			$format = $payment_address['address_format'];
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
			'firstname' => $payment_address['firstname'],
			'lastname'  => $payment_address['lastname'],
			'company'   => $payment_address['company'],
			'address_1' => $payment_address['address_1'],
			'address_2' => $payment_address['address_2'],
			'city'      => $payment_address['city'],
			'postcode'  => $payment_address['postcode'],
			'zone'      => $payment_address['zone'],
			'country'   => $payment_address['country']  
		);
		
		$this->data['address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		
		$this->data['change_address'] = $this->url->https('checkout/address/payment');
		
		$this->data['payment_methods'] = $this->session->data['payment_methods'];
		
		if (isset($this->request->post['payment_method'])) {
			$this->data['payment1'] = $this->request->post['payment_method'];
		} elseif (isset($this->session->data['payment_method']['id'])) {
			$this->data['payment1'] = $this->session->data['payment_method']['id'];
		} else {
			$this->data['payment1'] = '';
		}
	}
	
	private function terms() {
		if ($this->config->get('config_checkout')) {
			$this->language->load('checkout/payment');
			$this->load->model('catalog/information');
			
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout'));
			
			if ($information_info) {
				$this->data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->http('information/information&information_id=' . $this->config->get('config_checkout')), $information_info['title']);
			} else {
				$this->data['text_agree'] = '';
			}
		} else {
			$this->data['text_agree'] = '';
		}
		
		if (isset($this->request->post['agree'])) { 
			$this->data['agree'] = $this->request->post['agree'];
		} else {
			$this->data['agree'] = '';
		}
	}
		
	private function totals() {
		$total_data = array();
		$total = 0;
		$taxes = $this->cart->getTaxes();
		 
		$this->load->model('checkout/extension');
		
		$sort_order = array(); 
		
		$results = $this->model_checkout_extension->getExtensions('total');
		$table = 'discount';
		if(mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$table."'"))==1) {
		$results['999'] = array( "extension_id" => "999",
    "type" => "total",
	"key" => "discount");
		/*$dss = $this->db->query("select * from discount WHERE ");
		$this->load->model('total/discount');
			$this->{'model_total_discount'}->getTotal($total_data, $total, $taxes);*/
		}
		
		foreach ($results as $key => $value) {
		if ($key == '999') {
		$sort_order['999'] = 6;
		}
		else
		{
			$sort_order[$key] = $this->config->get($value['key'] . '_sort_order');
		}
		}
		
		array_multisort($sort_order, SORT_ASC, $results);
		foreach ($results as $result) {
			$this->load->model('total/' . $result['key']);
			$this->{'model_total_' . $result['key']}->getTotal($total_data, $total, $taxes);
		}
		
		$sort_order = array(); 
		foreach ($total_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}
		array_multisort($sort_order, SORT_ASC, $total_data);
		$data['customer_id'] = $this->customer->getId();
		$data['firstname'] = $this->customer->getFirstName();
		$data['lastname'] = $this->customer->getLastName();
		$data['email'] = $this->customer->getEmail();
		$data['telephone'] = $this->customer->getTelephone();
		$data['fax'] = $this->customer->getFax();
		$this->load->model('account/address');
		
		if ($this->cart->hasShipping()) {
			$shipping_address_id = $this->session->data['shipping_address_id'];
			
			$shipping_address = $this->model_account_address->getAddress($shipping_address_id);
			
			$data['shipping_firstname'] = $shipping_address['firstname'];
			$data['shipping_lastname'] = $shipping_address['lastname'];
			$data['shipping_company'] = $shipping_address['company'];
			$data['shipping_address_1'] = $shipping_address['address_1'];
			$data['shipping_address_2'] = $shipping_address['address_2'];
			$data['shipping_city'] = $shipping_address['city'];
			$data['shipping_postcode'] = $shipping_address['postcode'];
			$data['shipping_zone'] = $shipping_address['zone'];
			$data['shipping_zone_id'] = $shipping_address['zone_id'];
			$data['shipping_country'] = $shipping_address['country'];
			$data['shipping_country_id'] = $shipping_address['country_id'];
			$data['shipping_address_format'] = $shipping_address['address_format'];
			
			if (isset($this->session->data['shipping_method']['title'])) {
				$data['shipping_method'] = $this->session->data['shipping_method']['title'];
			} else {
				$data['shipping_method'] = '';
			}
		} else {
			$data['shipping_firstname'] = '';
			$data['shipping_lastname'] = '';
			$data['shipping_company'] = '';
			$data['shipping_address_1'] = '';
			$data['shipping_address_2'] = '';
			$data['shipping_city'] = '';
			$data['shipping_postcode'] = '';
			$data['shipping_zone'] = '';
			$data['shipping_zone_id'] = '';
			$data['shipping_country'] = '';
			$data['shipping_country_id'] = '';
			$data['shipping_address_format'] = '';
			$data['shipping_method'] = '';
		}
		
		if ($this->cart->hasPayment()) {
			$payment_address_id = $this->session->data['payment_address_id'];
			
			$payment_address = $this->model_account_address->getAddress($payment_address_id);
			
			$data['payment_firstname'] = $payment_address['firstname'];
			$data['payment_lastname'] = $payment_address['lastname'];
			$data['payment_company'] = $payment_address['company'];
			$data['payment_address_1'] = $payment_address['address_1'];
			$data['payment_address_2'] = $payment_address['address_2'];
			$data['payment_city'] = $payment_address['city'];
			$data['payment_postcode'] = $payment_address['postcode'];
			$data['payment_zone'] = $payment_address['zone'];
			$data['payment_zone_id'] = $payment_address['zone_id'];
			$data['payment_country'] = $payment_address['country'];
			$data['payment_country_id'] = $payment_address['country_id'];
			$data['payment_address_format'] = $payment_address['address_format'];
			if (isset($this->session->data['payment_method']['title'])) {
				$data['payment_method'] = $this->session->data['payment_method']['title'];
			} else {
				$data['payment_method'] = '';
			}
		} else {
			$data['payment_firstname'] = '';
			$data['payment_lastname'] = '';
			$data['payment_company'] = '';
			$data['payment_address_1'] = '';
			$data['payment_address_2'] = '';
			$data['payment_city'] = '';
			$data['payment_postcode'] = '';
			$data['payment_zone'] = '';
			$data['payment_zone_id'] = '';
			$data['payment_country'] = '';
			$data['payment_country_id'] = '';
			$data['payment_address_format'] = '';
			$data['payment_method'] = '';
		}
		
		$this->language->load('checkout/confirm');
		
		$data['totals'] = $total_data;
		$data['total'] = $total;
		$data['language_id'] = $this->config->get('config_language_id');
		$data['currency_id'] = $this->currency->getId();
		$data['currency'] = $this->currency->getCode();
		$data['value'] = $this->currency->getValue($this->currency->getCode());
		if (isset($this->session->data['coupon'])) {
			$this->load->model('checkout/coupon');
		
			$coupon = $this->model_checkout_coupon->getCoupon($this->session->data['coupon']);
			
			if ($coupon) {
				$data['coupon_id'] = $coupon['coupon_id'];
			} else {
				$data['coupon_id'] = 0;
			}
		} else {
			$data['coupon_id'] = 0;
		}
		$data['ip'] = $this->request->server['REMOTE_ADDR'];
		
		$this->data['totals'] = $total_data;
		$this->data['column_remove'] = $this->language->get('column_remove');
		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['products'] = array();
		
		foreach ($this->cart->getProducts() as $product) {
			$option_data = array();
			
			foreach ($product['option'] as $option) {
				$option_data[] = array(
						'name'  => $option['name'],
						'value' => $option['value']
				);
			} 
			
			$this->data['products'][] = array(
				'key'        => str_replace('.', '_', str_replace(':', '-', $product['key'])),
				'product_id' => $product['product_id'],
				'name'       => $product['name'],
				'model'      => $product['model'],
				'option'     => $option_data,
				'quantity'   => $product['quantity'],
				'tax'        => $this->tax->getRate($product['tax_class_id']),
				'price'      => $this->currency->format($product['price']),
				'total'      => $this->currency->format($product['total']),
				'href'       => $this->url->http('product/product&product_id=' . $product['product_id'])
			); 
		} 
	}
	
	public function ajaxTotals() {
		$this->setShipping($this->request->post['shipping_method']);
		$total_data = array();
		$total = 0;
		$taxes = $this->cart->getTaxes();
		 
		$this->load->model('checkout/extension');
		
		$sort_order = array(); 
		$results = $this->model_checkout_extension->getExtensions('total');$table = 'discount';		if(mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$table."'"))==1) {		$results['999'] = array( "extension_id" => "999",    "type" => "total",	"key" => "discount");		}				foreach ($results as $key => $value) {		if ($key == '999') {		$sort_order['999'] = 6;		}		else		{			$sort_order[$key] = $this->config->get($value['key'] . '_sort_order');		}		}
		array_multisort($sort_order, SORT_ASC, $results);
		foreach ($results as $result) {
			$this->load->model('total/' . $result['key']);
			$this->{'model_total_' . $result['key']}->getTotal($total_data, $total, $taxes);
		}
		$sort_order = array(); 
		foreach ($total_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}
		array_multisort($sort_order, SORT_ASC, $total_data);
		
		foreach ($total_data as $total) {
			echo '<tr><td align="right">' .$total['title'] . '</td><td align="right">' . $total['text']. '</td></tr>';
		}
	}
	
	public function ajaxShippingAddress() {
		$this->load->model('account/address');
		$this->language->load('checkout/shipping');
		$this->language->load('checkout/address');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['address_id'])) {
			$this->session->data['shipping_address_id'] = $this->request->post['address_id'];
			
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['shipping_method']);
			
			if ($this->cart->hasShipping()) {
				$address_info = $this->model_account_address->getAddress($this->request->post['address_id']);
			
				if ($address_info) {
					$this->tax->setZone($address_info['country_id'], $address_info['zone_id']);
				}
			}
			$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
			
			if ($shipping_address['address_format']) {
				$format = $shipping_address['address_format'];
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
				'firstname' => $shipping_address['firstname'],
				'lastname'  => $shipping_address['lastname'],
				'company'   => $shipping_address['company'],
				'address_1' => $shipping_address['address_1'],
				'address_2' => $shipping_address['address_2'],
				'city'      => $shipping_address['city'],
				'postcode'  => $shipping_address['postcode'],
				'zone'      => $shipping_address['zone'],
				'country'   => $shipping_address['country']
			);
			echo '<b>' . $this->language->get('text_shipping_address') . '</b><br />' .
			str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		} else if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateAddress()) {
			$this->session->data['shipping_address_id'] = $this->model_account_address->addAddress($this->request->post);
			
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['shipping_method']);
			
			if ($this->cart->hasShipping()) {
				$this->tax->setZone($this->request->post['country_id'], $this->request->post['zone_id']);
			}
		
		
			$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
			
			if ($shipping_address['address_format']) {
				$format = $shipping_address['address_format'];
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
				'firstname' => $shipping_address['firstname'],
				'lastname'  => $shipping_address['lastname'],
				'company'   => $shipping_address['company'],
				'address_1' => $shipping_address['address_1'],
				'address_2' => $shipping_address['address_2'],
				'city'      => $shipping_address['city'],
				'postcode'  => $shipping_address['postcode'],
				'zone'      => $shipping_address['zone'],
				'country'   => $shipping_address['country']
			);
			echo '<b>' . $this->language->get('text_shipping_address') . '</b><br />' .
			str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		}
	}
	
	public function ajaxPaymentAddress() {
		$this->load->model('account/address');
		$this->language->load('checkout/address');
		$this->language->load('checkout/payment');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['address_id'])) {
			$this->session->data['payment_address_id'] = $this->request->post['address_id'];
			
			unset($this->session->data['payment_methods']);
			unset($this->session->data['payment_method']);
			
			$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
			
			if ($payment_address['address_format']) {
				$format = $payment_address['address_format'];
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
				'firstname' => $payment_address['firstname'],
				'lastname'  => $payment_address['lastname'],
				'company'   => $payment_address['company'],
				'address_1' => $payment_address['address_1'],
				'address_2' => $payment_address['address_2'],
				'city'      => $payment_address['city'],
				'postcode'  => $payment_address['postcode'],
				'zone'      => $payment_address['zone'],
				'country'   => $payment_address['country']  
			);
			
			echo '<b>' . $this->language->get('text_payment_address') . '</b><br />' .
			str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		} else if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateAddress()) {
			$this->session->data['payment_address_id'] = $this->model_account_address->addAddress($this->request->post);
			
			unset($this->session->data['payment_methods']);
			unset($this->session->data['payment_method']);
			
			
			$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
			
			if ($payment_address['address_format']) {
				$format = $payment_address['address_format'];
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
				'firstname' => $payment_address['firstname'],
				'lastname'  => $payment_address['lastname'],
				'company'   => $payment_address['company'],
				'address_1' => $payment_address['address_1'],
				'address_2' => $payment_address['address_2'],
				'city'      => $payment_address['city'],
				'postcode'  => $payment_address['postcode'],
				'zone'      => $payment_address['zone'],
				'country'   => $payment_address['country']  
			);
			
			echo '<b>' . $this->language->get('text_payment_address') . '</b><br />' .
			str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
			
		}
	}
	
	public function ajaxRemove() {
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['key'])) {
				$key = str_replace('_', '.', str_replace('-', ':', $this->request->post['key']));
				$this->cart->remove($key);
				echo ( ! $this->cart->hasProducts()) ? 'empty' : 'success';
			}
		}
	}
	
	public function ajaxQuantity() {
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['key']) && isset($this->request->post['qty']) && is_numeric($this->request->post['qty']) && $this->request->post['qty'] > 0) {
				$key = str_replace('_', '.', str_replace('-', ':', $this->request->post['key']));
				$this->cart->update($key, (int)$this->request->post['qty']);
				$products = $this->cart->getProducts();
				echo $this->currency->format($products[$key]['total']);
			}
		}
	}
	
	public function ajaxRedeem() {
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['redemption'])) {
			$this->load->model('checkout/gift_certificate');
			$this->load->model('checkout/coupon');
			
			if ($this->model_checkout_gift_certificate->checkGiftCertificate($this->request->post['redemption'])) {
				$this->session->data['gift_certificate'] = $this->request->post['redemption'];
			} else if ($this->model_checkout_coupon->getCoupon($this->request->post['redemption'])) {
				$this->session->data['coupon'] = $this->request->post['redemption'];
			}
		}
	}
	
	private function validateAddress() {
		if ((strlen(utf8_decode($this->request->post['firstname'])) < 1) || (strlen(utf8_decode($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}
		
		if ((strlen(utf8_decode($this->request->post['lastname'])) < 1) || (strlen(utf8_decode($this->request->post['lastname'])) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}
		
		if ((strlen(utf8_decode($this->request->post['address_1'])) < 3) || (strlen(utf8_decode($this->request->post['address_1'])) > 64)) {
			$this->error['address_1'] = $this->language->get('error_address_1');
		}
		
		if ((strlen(utf8_decode($this->request->post['city'])) < 3) || (strlen(utf8_decode($this->request->post['city'])) > 32)) {
			$this->error['city'] = $this->language->get('error_city');
		} 
		
		if ($this->request->post['country_id'] == 'FALSE') {
			$this->error['country'] = $this->language->get('error_country');
		}
		
		if ($this->request->post['zone_id'] == 'FALSE') {
			$this->error['zone'] = $this->language->get('error_zone');
		}
		
		if ( ! $this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	private function validate() {
		if ($this->data['is_payment']) {
			if ( ! isset($this->request->post['payment_method'])) {
				$this->error['warning'] = $this->language->get('error_payment');
			} else {
				if ( ! isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
					$this->error['warning'] = $this->language->get('error_payment');
				}
			}
		}
		
		if ($this->data['is_shipping']) {
			if ( ! isset($this->request->post['shipping_method'])) {
				$this->error['warning'] = $this->language->get('error_shipping');
			} else {
				$shipping = explode('.', $this->request->post['shipping_method']);
				
				if ( ! isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
					$this->error['warning'] = $this->language->get('error_shipping');
				}
			}
		}
		
		if ($this->config->get('config_checkout')) {
			$this->load->model('catalog/information');
			
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout'));
			
			if ($information_info) {
				if ( ! isset($this->request->post['agree'])) {
					$this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
				}
			}
		}
		
		if ( ! $this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>
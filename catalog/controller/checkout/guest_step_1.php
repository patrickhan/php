<?php
class ControllerCheckoutGuestStep1 extends Controller {
	private $error = array();
	
	public function index() {
		if ($this->config->get('brochure') === TRUE) {
			$this->redirect($this->url->https('error/not_found'));
		}
		//print_r($_SESSION); die;
		if ( ! $this->cart->hasProducts() || ( ! $this->cart->hasStock() && ! $this->config->get('config_stock_checkout'))) {
			$this->redirect($this->url->https('checkout/cart'));
		}
		if ($this->customer->isLogged()) {
			$this->redirect($this->url->https('checkout/onepage'));
		}
		if ( ! $this->config->get('config_guest_checkout') || $this->cart->hasDownload() || $this->cart->hasGiftCertificate()) {
			$this->session->data['redirect'] = $this->url->https('checkout/onepage');
			$this->redirect($this->url->https('account/login'));
		}
		
		$this->load->model('checkout/extension');
		if ($this->cart->hasShipping()) {
			$this->data['is_shipping'] = TRUE;
		} else {
			$this->data['is_shipping'] = FALSE;
			unset($this->session->data['shipping_address_id']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			$this->tax->setZone($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
		}
		$this->language->load('checkout/guest_step_1');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		$this->session->data['guest']['pass1'] = $this->request->post['pass1'];
		$this->session->data['guest']['pass2'] = $this->request->post['pass2'];
			$this->session->data['guest']['firstname'] = $this->request->post['firstname'];
			$this->session->data['guest']['lastname'] = $this->request->post['lastname'];
			$this->session->data['guest']['email'] = $this->request->post['email'];
			$this->session->data['guest']['telephone'] = $this->request->post['telephone'];
			$this->session->data['guest']['fax'] = $this->request->post['fax'];
			$this->session->data['guest']['payment_company'] = $this->request->post['payment_company'];
			$this->session->data['guest']['payment_address_1'] = $this->request->post['payment_address_1'];
			$this->session->data['guest']['payment_address_2'] = $this->request->post['payment_address_2'];
			$this->session->data['guest']['payment_postcode'] = $this->request->post['payment_postcode'];
			$this->session->data['guest']['payment_city'] = $this->request->post['payment_city'];
			$this->session->data['guest']['payment_country_id'] = $this->request->post['payment_country_id'];
			$this->session->data['guest']['payment_zone_id'] = $this->request->post['payment_zone_id'];
			$this->session->data['guest']['shipping_company'] = $this->request->post['shipping_company'];
			$this->session->data['guest']['shipping_address_1'] = $this->request->post['shipping_address_1'];
			$this->session->data['guest']['shipping_address_2'] = $this->request->post['shipping_address_2'];
			$this->session->data['guest']['shipping_postcode'] = $this->request->post['shipping_postcode'];
			$this->session->data['guest']['shipping_city'] = $this->request->post['shipping_city'];
			$this->session->data['guest']['shipping_country_id'] = $this->request->post['shipping_country_id'];
			$this->session->data['guest']['shipping_zone_id'] = $this->request->post['shipping_zone_id'];
			
			if ($this->cart->hasShipping()) {
				$this->tax->setZone($this->request->post['shipping_country_id'], $this->request->post['shipping_zone_id']);
			}
			
			$this->load->model('localisation/country');
			
			$country_info = $this->model_localisation_country->getCountry($this->request->post['payment_country_id']);
			
			if ($country_info) {
				$this->session->data['guest']['payment_country'] = $country_info['name'];	
				$this->session->data['guest']['payment_iso_code_2'] = $country_info['iso_code_2'];
				$this->session->data['guest']['payment_iso_code_3'] = $country_info['iso_code_3'];
				$this->session->data['guest']['payment_address_format'] = $country_info['address_format'];
			} else {
				$this->session->data['guest']['payment_country'] = '';	
				$this->session->data['guest']['payment_iso_code_2'] = '';
				$this->session->data['guest']['payment_iso_code_3'] = '';
				$this->session->data['guest']['payment_address_format'] = '';
			}
			
			$country_info = $this->model_localisation_country->getCountry($this->request->post['shipping_country_id']);
			
			if ($country_info) {
				$this->session->data['guest']['shipping_country'] = $country_info['name'];	
				$this->session->data['guest']['shipping_iso_code_2'] = $country_info['iso_code_2'];
				$this->session->data['guest']['shipping_iso_code_3'] = $country_info['iso_code_3'];
				$this->session->data['guest']['shipping_address_format'] = $country_info['address_format'];
			} else {
				$this->session->data['guest']['shipping_country'] = '';	
				$this->session->data['guest']['shipping_iso_code_2'] = '';
				$this->session->data['guest']['shipping_iso_code_3'] = '';
				$this->session->data['guest']['shipping_address_format'] = '';
			}
			
			$this->load->model('localisation/zone');
			
			$zone_info = $this->model_localisation_zone->getZone($this->request->post['payment_zone_id']);
			
			if ($zone_info) {
				$this->session->data['guest']['payment_zone'] = $zone_info['name'];
				$this->session->data['guest']['payment_zone_code'] = $zone_info['code'];
			} else {
				$this->session->data['guest']['payment_zone'] = '';
				$this->session->data['guest']['payment_zone_code'] = '';
			}
			
			$zone_info = $this->model_localisation_zone->getZone($this->request->post['shipping_zone_id']);
			
			if ($zone_info) {
				$this->session->data['guest']['shipping_zone'] = $zone_info['name'];
				$this->session->data['guest']['shipping_zone_code'] = $zone_info['code'];
			} else {
				$this->session->data['guest']['shipping_zone'] = '';
				$this->session->data['guest']['shipping_zone_code'] = '';
			}
			
			if (isset($this->request->post['shipping_method'])) {
				$shipping = explode('.', $this->request->post['shipping_method']);
			
				$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
			}
			if (isset($this->request->post['payment_method'])) {
			$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
		}
			$this->session->data['comment'] = $this->request->post['comment'];
			
			//$this->redirect($this->url->https('checkout/guest_step_1'));
			
			$this->totals();
		$this->terms();
		$this->shipping();
		
		$this->document->title = $this->language->get('heading_title');
		$this->document->breadcrumbs = array();
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		); 
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('checkout/cart'),
			'text'      => $this->language->get('text_cart'),
			'separator' => $this->language->get('text_separator')
		);
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('checkout/guest_step_1'),
			'text'      => $this->language->get('text_guest_step_1'),
			'separator' => $this->language->get('text_separator')
		);
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_your_details'] = $this->language->get('text_your_details');
		$this->data['text_your_payment_address'] = $this->language->get('text_your_payment_address');
		$this->data['text_your_shipping_address'] = $this->language->get('text_your_shipping_address');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_comment'] = $this->language->get('text_comment');
		$this->data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->https('account/login'));
		$this->data['text_login'] = $this->language->get('text_login');
		$this->data['text_discounts'] = $this->language->get('text_discounts');
		$this->data['text_gift_certificate'] = $this->language->get('text_gift_certificate');
		
		$this->data['entry_same_address'] = $this->language->get('entry_same_address');
		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_fax'] = $this->language->get('entry_fax');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_password'] = $this->language->get('entry_password');
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');
		$this->data['button_copy_address'] = $this->language->get('button_copy_address');
		$this->data['button_login'] = $this->language->get('button_login');
		$this->data['button_gift_certificate'] = $this->language->get('button_gift_certificate');
		
		if (isset($this->session->data['error'])) {
			$this->data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['firstname'])) {
			$this->data['error_firstname'] = $this->error['firstname'];
		} else {
			$this->data['error_firstname'] = '';
		}
		
		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}
		
		if (isset($this->error['lastname'])) {
			$this->data['error_lastname'] = $this->error['lastname'];
		} else {
			$this->data['error_lastname'] = '';
		}
		
		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
		
		if (isset($this->error['telephone'])) {
			$this->data['error_telephone'] = $this->error['telephone'];
		} else {
			$this->data['error_telephone'] = '';
		}
		
		if (isset($this->error['payment_address_1'])) {
			$this->data['error_payment_address_1'] = $this->error['payment_address_1'];
		} else {
			$this->data['error_payment_address_1'] = '';
		}
		
		if (isset($this->error['payment_city'])) {
			$this->data['error_payment_city'] = $this->error['payment_city'];
		} else {
			$this->data['error_payment_city'] = '';
		}
		
		if (isset($this->error['payment_country'])) {
			$this->data['error_payment_country'] = $this->error['payment_country'];
		} else {
			$this->data['error_payment_country'] = '';
		}
		
		if (isset($this->error['payment_zone'])) {
			$this->data['error_payment_zone'] = $this->error['payment_zone'];
		} else {
			$this->data['error_payment_zone'] = '';
		}
		
		$this->data['action'] = $this->url->https('checkout/guest_step_1');
		
		if (isset($this->request->post['firstname'])) {
			$this->data['firstname'] = $this->request->post['firstname'];
		} elseif (isset($this->session->data['guest']['firstname'])) {
			$this->data['firstname'] = $this->session->data['guest']['firstname'];
		} else {
			$this->data['firstname'] = '';
		}
		if (isset($this->request->post['pass1'])) {
			$this->data['pass1'] = $this->request->post['pass1'];
		} elseif (isset($this->session->data['guest']['pass1'])) {
			$this->data['pass1'] = $this->session->data['guest']['pass1'];
		} else {
			$this->data['pass1'] = '';
		}
		if (isset($this->request->post['pass2'])) {
			$this->data['pass2'] = $this->request->post['pass2'];
		} elseif (isset($this->session->data['guest']['pass2'])) {
			$this->data['pass2'] = $this->session->data['guest']['pass2'];
		} else {
			$this->data['pass2'] = '';
		}
		if (isset($this->request->post['lastname'])) {
			$this->data['lastname'] = $this->request->post['lastname'];
		} elseif (isset($this->session->data['guest']['lastname'])) {
			$this->data['lastname'] = $this->session->data['guest']['lastname'];
		} else {
			$this->data['lastname'] = '';
		}
		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} elseif (isset($this->session->data['guest']['email'])) {
			$this->data['email'] = $this->session->data['guest']['email'];
		} else {
			$this->data['email'] = '';
		}
		if (isset($this->request->post['telephone'])) {
			$this->data['telephone'] = $this->request->post['telephone'];
		} elseif (isset($this->session->data['guest']['telephone'])) {
			$this->data['telephone'] = $this->session->data['guest']['telephone'];
		} else {
			$this->data['telephone'] = '';
		}
		if (isset($this->request->post['fax'])) {
			$this->data['fax'] = $this->request->post['fax'];
		} elseif (isset($this->session->data['guest']['fax'])) {
			$this->data['fax'] = $this->session->data['guest']['fax'];
		} else {
			$this->data['fax'] = '';
		}
		
		if (isset($this->request->post['payment_company'])) {
			$this->data['payment_company'] = $this->request->post['payment_company'];
		} elseif (isset($this->session->data['guest']['payment_company'])) {
			$this->data['payment_company'] = $this->session->data['guest']['payment_company'];
		} else {
			$this->data['payment_company'] = '';
		}
		if (isset($this->request->post['payment_address_1'])) {
			$this->data['payment_address_1'] = $this->request->post['payment_address_1'];
		} elseif (isset($this->session->data['guest']['payment_address_1'])) {
			$this->data['payment_address_1'] = $this->session->data['guest']['payment_address_1'];
		} else {
			$this->data['payment_address_1'] = '';
		}
		if (isset($this->request->post['payment_address_2'])) {
			$this->data['payment_address_2'] = $this->request->post['payment_address_2'];
		} elseif (isset($this->session->data['guest']['payment_address_2'])) {
			$this->data['payment_address_2'] = $this->session->data['guest']['payment_address_2'];
		} else {
			$this->data['payment_address_2'] = '';
		}
		if (isset($this->request->post['payment_postcode'])) {
			$this->data['payment_postcode'] = $this->request->post['payment_postcode'];
		} elseif (isset($this->session->data['guest']['payment_postcode'])) {
			$this->data['payment_postcode'] = $this->session->data['guest']['payment_postcode'];
		} else {
			$this->data['payment_postcode'] = '';
		}
		if (isset($this->request->post['payment_city'])) {
			$this->data['payment_city'] = $this->request->post['payment_city'];
		} elseif (isset($this->session->data['guest']['payment_city'])) {
			$this->data['payment_city'] = $this->session->data['guest']['payment_city'];
		} else {
			$this->data['payment_city'] = '';
		}
		if (isset($this->request->post['payment_country_id'])) {
			$this->data['payment_country_id'] = $this->request->post['payment_country_id'];
		} elseif (isset($this->session->data['guest']['payment_country_id'])) {
			$this->data['payment_country_id'] = $this->session->data['guest']['payment_country_id'];
		} else {
			$this->data['payment_country_id'] = $this->config->get('config_country_id');
		}
		if (isset($this->request->post['payment_zone_id'])) {
			$this->data['payment_zone_id'] = $this->request->post['payment_zone_id'];
		} elseif (isset($this->session->data['guest']['payment_zone_id'])) {
			$this->data['payment_zone_id'] = $this->session->data['guest']['payment_zone_id'];
		} else {
			$this->data['payment_zone_id'] = 'FALSE';
		}
		if (isset($this->request->post['comment'])) {
			$this->data['comment'] = $this->request->post['comment'];
		} else {
			$this->data['comment'] = '';
		}
		//print_r($this->session->data); die;
		if (isset($this->request->post['payment_method'])) {
			$this->data['payment'] = $this->request->post['payment_method'];
			$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
		} elseif (isset($this->session->data['payment_method'])) {
			$this->data['payment'] = $this->session->data['payment_method']['id'];
		} else {
			$this->data['payment'] = '';
		}
		$this->session->data['pass'] = $this->request->post['pass1'];
		if ($this->data['is_shipping']) {
			if (isset($this->error['shipping_address_1'])) {
				$this->data['error_shipping_address_1'] = $this->error['shipping_address_1'];
			} else {
				$this->data['error_shipping_address_1'] = '';
			}
			if (isset($this->error['shipping_city'])) {
				$this->data['error_shipping_city'] = $this->error['shipping_city'];
			} else {
				$this->data['error_shipping_city'] = '';
			}
			if (isset($this->error['shipping_country'])) {
				$this->data['error_shipping_country'] = $this->error['shipping_country'];
			} else {
				$this->data['error_shipping_country'] = '';
			}
			if (isset($this->error['shipping_zone'])) {
				$this->data['error_shipping_zone'] = $this->error['shipping_zone'];
			} else {
				$this->data['error_shipping_zone'] = '';
			}
			
			if (isset($this->request->post['shipping_company'])) {
				$this->data['shipping_company'] = $this->request->post['shipping_company'];
			} elseif (isset($this->session->data['guest']['shipping_company'])) {
				$this->data['shipping_company'] = $this->session->data['guest']['shipping_company'];
			} else {
				$this->data['shipping_company'] = '';
			}
			if (isset($this->request->post['shipping_address_1'])) {
				$this->data['shipping_address_1'] = $this->request->post['shipping_address_1'];
			} elseif (isset($this->session->data['guest']['shipping_address_1'])) {
				$this->data['shipping_address_1'] = $this->session->data['guest']['shipping_address_1'];
			} else {
				$this->data['shipping_address_1'] = '';
			}
			if (isset($this->request->post['shipping_address_2'])) {
				$this->data['shipping_address_2'] = $this->request->post['shipping_address_2'];
			} elseif (isset($this->session->data['guest']['shipping_address_2'])) {
				$this->data['shipping_address_2'] = $this->session->data['guest']['shipping_address_2'];
			} else {
				$this->data['shipping_address_2'] = '';
			}
			if (isset($this->request->post['shipping_postcode'])) {
				$this->data['shipping_postcode'] = $this->request->post['shipping_postcode'];
			} elseif (isset($this->session->data['guest']['shipping_postcode'])) {
				$this->data['shipping_postcode'] = $this->session->data['guest']['shipping_postcode'];
			} else {
				$this->data['shipping_postcode'] = '';
			}
			if (isset($this->request->post['shipping_city'])) {
				$this->data['shipping_city'] = $this->request->post['shipping_city'];
			} elseif (isset($this->session->data['guest']['shipping_city'])) {
				$this->data['shipping_city'] = $this->session->data['guest']['shipping_city'];
			} else {
				$this->data['shipping_city'] = '';
			}
			if (isset($this->request->post['shipping_country_id'])) {
				$this->data['shipping_country_id'] = $this->request->post['shipping_country_id'];
			} elseif (isset($this->session->data['guest']['shipping_country_id'])) {
				$this->data['shipping_country_id'] = $this->session->data['guest']['shipping_country_id'];
			} else {
				$this->data['shipping_country_id'] = $this->config->get('config_country_id');
			}
			if (isset($this->request->post['shipping_zone_id'])) {
				$this->data['shipping_zone_id'] = $this->request->post['shipping_zone_id'];
			} elseif (isset($this->session->data['guest']['shipping_zone_id'])) {
				$this->data['shipping_zone_id'] = $this->session->data['guest']['shipping_zone_id'];
			} else {
				$this->data['shipping_zone_id'] = 'FALSE';
			}
			if (isset($this->request->post['shipping'])) {
				$this->data['shipping'] = $this->request->post['shipping_method'];
			} elseif (isset($this->session->data['shipping_method'])) {
				$this->data['shipping'] = $this->session->data['shipping_method']['id'];			
			} else {
				$this->data['shipping'] = '';
			}
		}
		$pay = $this->data['payment'];
		//print_r($_SESSION); die;
		$this->load->model('localisation/country');
		
		$this->data['countries'] = $this->model_localisation_country->getCountries();
		
		$this->data['back'] = $this->url->http('checkout/cart');
		$this->data['onepage'] = $this->url->http('checkout/onepage');
		$this->data['guestlogin'] = $this->url->http('account/login');
		
		
		if (isset($this->request->post['agree'])) { 
			$this->data['agree'] = $this->request->post['agree'];
		} else {
			$this->data['agree'] = '';
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/guest_step_1.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/guest_step_1.tpl';
		} else {
			$this->template = 'default/template/checkout/guest_step_1.tpl';
		}
		$back = $this->url->http('checkout/cart');
		$onepage = $this->url->http('checkout/onepage');
		$guestlogin = $this->url->http('account/login');
		$this->load->model('catalog/information');
			
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout'));
		if ($information_info) {
				$text_agree = sprintf($this->language->get('text_agree'), $this->url->http('information/information&information_id=' . $this->config->get('config_checkout')), $information_info['title']);
			} else {
				$text_agree = '';
			}
			if (isset($this->request->post['agree'])) { 
			$agree = $this->request->post['agree'];
		} else {
			$agree = '';
		}
	$button = "<table><tr><td align=\"left\"><a onclick=\"location='$back'\" class=\"button\"><span>Back</span></a></td><td align=\"right\" style=\"padding-right: 5px;\">$text_agree</td><td width=\"5\" style=\"padding-right: 10px;\">";
	if ($agree) { 
	$button .= "<input type=\"checkbox\" name=\"agree\" value=\"1\" checked=\"checked\" />"; 
	} else { 
	$button .= "<input type=\"checkbox\" name=\"agree\" value=\"1\" />";
	}
	$button .= "</td><td align=\"right\"><a onclick=\"$('#guest').submit();\" class=\"button\"><span>Continue</span></a></td></tr></table>";
		$this->data['buttons'] = $button;
		if ( ! $this->cart->hasProducts() || (!$this->cart->hasStock() && ! $this->config->get('config_stock_checkout'))) {
			$this->redirect($this->url->https('checkout/cart'));
		}
		if ($this->customer->isLogged()) {
			$this->redirect($this->url->https('checkout/shipping'));
		} 
		if ( ! isset($this->session->data['guest'])) {
			$this->redirect($this->url->https('checkout/guest_step_1'));
		}
		if ($this->cart->hasShipping()) {
			if ( ! isset($this->session->data['shipping_method'])) {
				$this->redirect($this->url->https('checkout/guest_step_1'));
			}
		} else {
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			
			$this->tax->setZone($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
		}
		
		if ( ! isset($this->session->data['payment_method'])) {
			$this->redirect($this->url->https('checkout/guest_step_1'));
		}
		
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
		
		$data['customer_id'] = 0;
		$data['firstname'] = $this->session->data['guest']['firstname'];
		$data['lastname'] = $this->session->data['guest']['lastname'];
		$data['email'] = $this->session->data['guest']['email'];
		$data['telephone'] = $this->session->data['guest']['telephone'];
		$data['fax'] = $this->session->data['guest']['fax'];
		
		if ($this->cart->hasShipping()) {
			$data['shipping_firstname'] = $this->session->data['guest']['firstname'];
			$data['shipping_lastname'] = $this->session->data['guest']['lastname'];	
			$data['shipping_company'] = $this->session->data['guest']['shipping_company'];
			$data['shipping_address_1'] = $this->session->data['guest']['shipping_address_1'];
			$data['shipping_address_2'] = $this->session->data['guest']['shipping_address_2'];
			$data['shipping_city'] = $this->session->data['guest']['shipping_city'];
			$data['shipping_postcode'] = $this->session->data['guest']['shipping_postcode'];
			$data['shipping_zone'] = $this->session->data['guest']['shipping_zone'];
			$data['shipping_zone_id'] = $this->session->data['guest']['shipping_zone_id'];
			$data['shipping_country'] = $this->session->data['guest']['shipping_country'];
			$data['shipping_country_id'] = $this->session->data['guest']['shipping_country_id'];
			$data['shipping_address_format'] = $this->session->data['guest']['shipping_address_format'];
			
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
		
		$data['payment_firstname'] = $this->session->data['guest']['firstname'];
		$data['payment_lastname'] = $this->session->data['guest']['lastname'];
		$data['payment_company'] = $this->session->data['guest']['payment_company'];
		$data['payment_address_1'] = $this->session->data['guest']['payment_address_1'];
		$data['payment_address_2'] = $this->session->data['guest']['payment_address_2'];
		$data['payment_city'] = $this->session->data['guest']['payment_city'];
		$data['payment_postcode'] = $this->session->data['guest']['payment_postcode'];
		$data['payment_zone'] = $this->session->data['guest']['payment_zone'];
		$data['payment_zone_id'] = $this->session->data['guest']['payment_zone_id'];
		$data['payment_country'] = $this->session->data['guest']['payment_country'];
		$data['payment_country_id'] = $this->session->data['guest']['payment_country_id'];
		$data['payment_address_format'] = $this->session->data['guest']['payment_address_format'];
		
		if (isset($this->session->data['payment_method']['title'])) {
			$data['payment_method'] = $this->session->data['payment_method']['title'];
		} else {
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
		$this->children = array(
			'common/header',
			'common/footer',
			'common/column_left',
			'common/column_right',
			"payment/$pay"
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
			
		}
		else
		{
		
		$this->totals();
		$this->terms();
		$this->shipping();
		
		$this->document->title = $this->language->get('heading_title');
		$this->document->breadcrumbs = array();
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		); 
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('checkout/cart'),
			'text'      => $this->language->get('text_cart'),
			'separator' => $this->language->get('text_separator')
		);
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('checkout/guest_step_1'),
			'text'      => $this->language->get('text_guest_step_1'),
			'separator' => $this->language->get('text_separator')
		);
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_your_details'] = $this->language->get('text_your_details');
		$this->data['text_your_payment_address'] = $this->language->get('text_your_payment_address');
		$this->data['text_your_shipping_address'] = $this->language->get('text_your_shipping_address');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_comment'] = $this->language->get('text_comment');
		$this->data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->https('account/login'));
		$this->data['text_login'] = $this->language->get('text_login');
		$this->data['text_discounts'] = $this->language->get('text_discounts');
		$this->data['text_gift_certificate'] = $this->language->get('text_gift_certificate');
		
		$this->data['entry_same_address'] = $this->language->get('entry_same_address');
		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_fax'] = $this->language->get('entry_fax');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_password'] = $this->language->get('entry_password');
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');
		$this->data['button_copy_address'] = $this->language->get('button_copy_address');
		$this->data['button_login'] = $this->language->get('button_login');
		$this->data['button_gift_certificate'] = $this->language->get('button_gift_certificate');
		
		if (isset($this->session->data['error'])) {
			$this->data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['firstname'])) {
			$this->data['error_firstname'] = $this->error['firstname'];
		} else {
			$this->data['error_firstname'] = '';
		}
		
		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}
		
		if (isset($this->error['lastname'])) {
			$this->data['error_lastname'] = $this->error['lastname'];
		} else {
			$this->data['error_lastname'] = '';
		}
		
		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
		
		if (isset($this->error['telephone'])) {
			$this->data['error_telephone'] = $this->error['telephone'];
		} else {
			$this->data['error_telephone'] = '';
		}
		
		if (isset($this->error['payment_address_1'])) {
			$this->data['error_payment_address_1'] = $this->error['payment_address_1'];
		} else {
			$this->data['error_payment_address_1'] = '';
		}
		
		if (isset($this->error['payment_city'])) {
			$this->data['error_payment_city'] = $this->error['payment_city'];
		} else {
			$this->data['error_payment_city'] = '';
		}
		
		if (isset($this->error['payment_country'])) {
			$this->data['error_payment_country'] = $this->error['payment_country'];
		} else {
			$this->data['error_payment_country'] = '';
		}
		
		if (isset($this->error['payment_zone'])) {
			$this->data['error_payment_zone'] = $this->error['payment_zone'];
		} else {
			$this->data['error_payment_zone'] = '';
		}
		
		$this->data['action'] = $this->url->https('checkout/guest_step_1');
		
		if (isset($this->request->post['firstname'])) {
			$this->data['firstname'] = $this->request->post['firstname'];
		} elseif (isset($this->session->data['guest']['firstname'])) {
			$this->data['firstname'] = $this->session->data['guest']['firstname'];
		} else {
			$this->data['firstname'] = '';
		}
		if (isset($this->request->post['pass1'])) {
			$this->data['pass1'] = $this->request->post['pass1'];
		} elseif (isset($this->session->data['guest']['pass1'])) {
			$this->data['pass1'] = $this->session->data['guest']['pass1'];
		} else {
			$this->data['pass1'] = '';
		}
		if (isset($this->request->post['pass2'])) {
			$this->data['pass2'] = $this->request->post['pass2'];
		} elseif (isset($this->session->data['guest']['pass2'])) {
			$this->data['pass2'] = $this->session->data['guest']['pass2'];
		} else {
			$this->data['pass2'] = '';
		}
		if (isset($this->request->post['lastname'])) {
			$this->data['lastname'] = $this->request->post['lastname'];
		} elseif (isset($this->session->data['guest']['lastname'])) {
			$this->data['lastname'] = $this->session->data['guest']['lastname'];
		} else {
			$this->data['lastname'] = '';
		}
		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} elseif (isset($this->session->data['guest']['email'])) {
			$this->data['email'] = $this->session->data['guest']['email'];
		} else {
			$this->data['email'] = '';
		}
		if (isset($this->request->post['telephone'])) {
			$this->data['telephone'] = $this->request->post['telephone'];
		} elseif (isset($this->session->data['guest']['telephone'])) {
			$this->data['telephone'] = $this->session->data['guest']['telephone'];
		} else {
			$this->data['telephone'] = '';
		}
		if (isset($this->request->post['fax'])) {
			$this->data['fax'] = $this->request->post['fax'];
		} elseif (isset($this->session->data['guest']['fax'])) {
			$this->data['fax'] = $this->session->data['guest']['fax'];
		} else {
			$this->data['fax'] = '';
		}
		
		if (isset($this->request->post['payment_company'])) {
			$this->data['payment_company'] = $this->request->post['payment_company'];
		} elseif (isset($this->session->data['guest']['payment_company'])) {
			$this->data['payment_company'] = $this->session->data['guest']['payment_company'];
		} else {
			$this->data['payment_company'] = '';
		}
		if (isset($this->request->post['payment_address_1'])) {
			$this->data['payment_address_1'] = $this->request->post['payment_address_1'];
		} elseif (isset($this->session->data['guest']['payment_address_1'])) {
			$this->data['payment_address_1'] = $this->session->data['guest']['payment_address_1'];
		} else {
			$this->data['payment_address_1'] = '';
		}
		if (isset($this->request->post['payment_address_2'])) {
			$this->data['payment_address_2'] = $this->request->post['payment_address_2'];
		} elseif (isset($this->session->data['guest']['payment_address_2'])) {
			$this->data['payment_address_2'] = $this->session->data['guest']['payment_address_2'];
		} else {
			$this->data['payment_address_2'] = '';
		}
		if (isset($this->request->post['payment_postcode'])) {
			$this->data['payment_postcode'] = $this->request->post['payment_postcode'];
		} elseif (isset($this->session->data['guest']['payment_postcode'])) {
			$this->data['payment_postcode'] = $this->session->data['guest']['payment_postcode'];
		} else {
			$this->data['payment_postcode'] = '';
		}
		if (isset($this->request->post['payment_city'])) {
			$this->data['payment_city'] = $this->request->post['payment_city'];
		} elseif (isset($this->session->data['guest']['payment_city'])) {
			$this->data['payment_city'] = $this->session->data['guest']['payment_city'];
		} else {
			$this->data['payment_city'] = '';
		}
		if (isset($this->request->post['payment_country_id'])) {
			$this->data['payment_country_id'] = $this->request->post['payment_country_id'];
		} elseif (isset($this->session->data['guest']['payment_country_id'])) {
			$this->data['payment_country_id'] = $this->session->data['guest']['payment_country_id'];
		} else {
			$this->data['payment_country_id'] = $this->config->get('config_country_id');
		}
		if (isset($this->request->post['payment_zone_id'])) {
			$this->data['payment_zone_id'] = $this->request->post['payment_zone_id'];
		} elseif (isset($this->session->data['guest']['payment_zone_id'])) {
			$this->data['payment_zone_id'] = $this->session->data['guest']['payment_zone_id'];
		} else {
			$this->data['payment_zone_id'] = 'FALSE';
		}
		if (isset($this->request->post['comment'])) {
			$this->data['comment'] = $this->request->post['comment'];
		} else {
			$this->data['comment'] = '';
		}
		
		if (isset($this->request->post['payment_method'])) {
			$this->data['payment'] = $this->request->post['payment_method'];
			$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
		} elseif (isset($this->session->data['payment_method'])) {
			$this->data['payment'] = $this->session->data['payment_method']['id'];
		} else {
			$this->data['payment'] = '';
		}
		
		if ($this->data['is_shipping']) {
			if (isset($this->error['shipping_address_1'])) {
				$this->data['error_shipping_address_1'] = $this->error['shipping_address_1'];
			} else {
				$this->data['error_shipping_address_1'] = '';
			}
			if (isset($this->error['shipping_city'])) {
				$this->data['error_shipping_city'] = $this->error['shipping_city'];
			} else {
				$this->data['error_shipping_city'] = '';
			}
			if (isset($this->error['shipping_country'])) {
				$this->data['error_shipping_country'] = $this->error['shipping_country'];
			} else {
				$this->data['error_shipping_country'] = '';
			}
			if (isset($this->error['shipping_zone'])) {
				$this->data['error_shipping_zone'] = $this->error['shipping_zone'];
			} else {
				$this->data['error_shipping_zone'] = '';
			}
			
			if (isset($this->request->post['shipping_company'])) {
				$this->data['shipping_company'] = $this->request->post['shipping_company'];
			} elseif (isset($this->session->data['guest']['shipping_company'])) {
				$this->data['shipping_company'] = $this->session->data['guest']['shipping_company'];
			} else {
				$this->data['shipping_company'] = '';
			}
			if (isset($this->request->post['shipping_address_1'])) {
				$this->data['shipping_address_1'] = $this->request->post['shipping_address_1'];
			} elseif (isset($this->session->data['guest']['shipping_address_1'])) {
				$this->data['shipping_address_1'] = $this->session->data['guest']['shipping_address_1'];
			} else {
				$this->data['shipping_address_1'] = '';
			}
			if (isset($this->request->post['shipping_address_2'])) {
				$this->data['shipping_address_2'] = $this->request->post['shipping_address_2'];
			} elseif (isset($this->session->data['guest']['shipping_address_2'])) {
				$this->data['shipping_address_2'] = $this->session->data['guest']['shipping_address_2'];
			} else {
				$this->data['shipping_address_2'] = '';
			}
			if (isset($this->request->post['shipping_postcode'])) {
				$this->data['shipping_postcode'] = $this->request->post['shipping_postcode'];
			} elseif (isset($this->session->data['guest']['shipping_postcode'])) {
				$this->data['shipping_postcode'] = $this->session->data['guest']['shipping_postcode'];
			} else {
				$this->data['shipping_postcode'] = '';
			}
			if (isset($this->request->post['shipping_city'])) {
				$this->data['shipping_city'] = $this->request->post['shipping_city'];
			} elseif (isset($this->session->data['guest']['shipping_city'])) {
				$this->data['shipping_city'] = $this->session->data['guest']['shipping_city'];
			} else {
				$this->data['shipping_city'] = '';
			}
			if (isset($this->request->post['shipping_country_id'])) {
				$this->data['shipping_country_id'] = $this->request->post['shipping_country_id'];
			} elseif (isset($this->session->data['guest']['shipping_country_id'])) {
				$this->data['shipping_country_id'] = $this->session->data['guest']['shipping_country_id'];
			} else {
				$this->data['shipping_country_id'] = $this->config->get('config_country_id');
			}
			if (isset($this->request->post['shipping_zone_id'])) {
				$this->data['shipping_zone_id'] = $this->request->post['shipping_zone_id'];
			} elseif (isset($this->session->data['guest']['shipping_zone_id'])) {
				$this->data['shipping_zone_id'] = $this->session->data['guest']['shipping_zone_id'];
			} else {
				$this->data['shipping_zone_id'] = 'FALSE';
			}
			if (isset($this->request->post['shipping'])) {
				$this->data['shipping'] = $this->request->post['shipping_method'];
			} elseif (isset($this->session->data['shipping_method'])) {
				$this->data['shipping'] = $this->session->data['shipping_method']['id'];			
			} else {
				$this->data['shipping'] = '';
			}
		}
		
		$this->load->model('localisation/country');
		
		$this->data['countries'] = $this->model_localisation_country->getCountries();
		
		$this->data['back'] = $this->url->http('checkout/cart');
		$this->data['back'] = $this->url->http('checkout/cart');
		$this->data['onepage'] = $this->url->http('checkout/onepage');
		$this->data['guestlogin'] = $this->url->http('account/login');
		
		
		if (isset($this->request->post['agree'])) { 
			$this->data['agree'] = $this->request->post['agree'];
		} else {
			$this->data['agree'] = '';
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/guest_step_1.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/guest_step_1.tpl';
		} else {
			$this->template = 'default/template/checkout/guest_step_1.tpl';
		}
		$back = $this->url->http('checkout/cart');
		$onepage = $this->url->http('checkout/onepage');
		$guestlogin = $this->url->http('account/login');
		$this->load->model('catalog/information');
			
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout'));
		if ($information_info) {
				$text_agree = sprintf($this->language->get('text_agree'), $this->url->http('information/information&information_id=' . $this->config->get('config_checkout')), $information_info['title']);
			} else {
				$text_agree = '';
			}
			if (isset($this->request->post['agree'])) { 
			$agree = $this->request->post['agree'];
		} else {
			$agree = '';
		}
	$button = "<table><tr><td align=\"left\"><a onclick=\"location='$back'\" class=\"button\"><span>Back</span></a></td><td align=\"right\" style=\"padding-right: 5px;\">$text_agree</td><td width=\"5\" style=\"padding-right: 10px;\">";
	if ($agree) { 
	$button .= "<input type=\"checkbox\" name=\"agree\" value=\"1\" checked=\"checked\" />"; 
	} else { 
	$button .= "<input type=\"checkbox\" name=\"agree\" value=\"1\" />";
	}
	$button .= "</td><td align=\"right\"><a onclick=\"$('#guest').submit();\" class=\"button\"><span>Continue</span></a></td></tr></table>";
		$this->data['buttons'] = $button;
		$this->children = array(
			'common/header',
			'common/footer',
			'common/column_left',
			'common/column_right'
		);
		$this->data['payment'] = false;
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	}
	private function terms() {
		if ($this->config->get('config_checkout')) {
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
	}
	
	private function shipping() {
		if ($this->cart->hasShipping() && isset($this->session->data['guest'])) {
			$quote_data = array();
			
			$results = $this->model_checkout_extension->getExtensions('shipping');
			
			foreach ($results as $result) {
				$this->load->model('shipping/' . $result['key']);
				
				$quote = $this->{'model_shipping_' . $result['key']}->getQuote($this->session->data['guest']); 
	
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
			
			if (count($quote_data) == 1) {   $values = array_values($quote_data);   if (count($values[0]['quote']) == 1) {      $keys = array_keys($values[0]['quote']);      $method = $values[0]['quote'][$keys[0]];      $this->session->data['shipping_method'] = $method; } }
		} else {
			$this->session->data['shipping_methods'] = NULL;
		}
		$this->data['shipping_methods'] = $this->session->data['shipping_methods'];
	}
	
	private function totals() {
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
		
		$data['totals'] = $total_data;
		$data['total'] = $total;
		$data['language_id'] = $this->config->get('config_language_id');
		$data['currency_id'] = $this->currency->getId();
		$data['currency'] = $this->currency->getCode();
		$data['value'] = $this->currency->getValue($this->currency->getCode());
		/* 
		// pretty sure this isn't needed and i want to delete it mmmkay
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
		} */
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
	
	public function ajaxPayment() {
		$this->load->model('account/address');
		$this->load->model('checkout/extension');
		$this->language->load('checkout/payment');
		
		$payment_address = array (
			'address_1'      => $this->request->post['address_1'],
			'address_2'      => $this->request->post['address_2'],
			'postcode'       => $this->request->post['postcode'],
			'city'           => $this->request->post['city'],
			'zone_id'        => $this->request->post['zone_id'],
			'country_id'     => $this->request->post['country_id']
		);
		
		$this->session->data['payment_address'] = $payment_address;
		
		$quote_data = array();
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
		//print_r($method_data); die;
		$this->data['payment_methods'] = $this->session->data['payment_methods']; 
		$res = false;
		if (count($method_data) == 1) {   $values = array_values($method_data);   if (true) { $res = true;    /*  $keys = array_keys($values[0]['id']);      */ $method = $values[0];     $this->session->data['payment_method'] = $method;} }
		if (isset($this->session->data['payment_method'])) {
			$this->data['payment'] = $this->session->data['payment_method']['id'];
		} else {
			$this->data['payment'] = '';
		}
		
		$result = '<div class="content"><p>' . $this->language->get('text_payment_methods') . '</p><table width="100%" cellpadding="3" id="paymentMethods">';
		foreach ($this->data['payment_methods'] as $payment_method) {
			$result .= '<tr><td width="1">';
			if ($payment_method['id'] == $this->data['payment']) {
				$result .= '<input type="radio" name="payment_method" value="' . $payment_method['id'] . '" id="' . $payment_method['id'] . '" checked="checked" style="margin: 0px;" />';
			} else {
				$result .= '<input type="radio" name="payment_method" value="' . $payment_method['id'] . '" id="' . $payment_method['id'] . '" style="margin: 0px;" />';
			}
			$result .= '</td>';
			$result .= '<td><label for="' . $payment_method['id'] . '" style="cursor: pointer;">' . $payment_method['title'] . '</label></td>';
			$result .= '</tr>';
		}
		$result .= '</table></div>';
		if (!$res) {
		echo $result;
	}
	}
	
	public function ajaxShipping() {
		$this->load->model('checkout/extension');
		$this->load->model('account/address');
		$this->language->load('checkout/shipping');
		
		$quote_data = array();
		
		$results = $this->model_checkout_extension->getExtensions('shipping');
		
		$shipping_address = array (
			'address_1'      => $this->request->post['address_1'],
			'address_2'      => $this->request->post['address_2'],
			'postcode'       => $this->request->post['postcode'],
			'city'           => $this->request->post['city'],
			'zone_id'        => $this->request->post['zone_id'],
			'country_id'     => $this->request->post['country_id']
		);
		
		$this->session->data['shipping_address'] = $shipping_address;
		
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
		//print_r($quote_data); die;
		array_multisort($sort_order, SORT_ASC, $quote_data);
		$res = false;
		$this->session->data['shipping_methods'] = $quote_data;
		if (count($quote_data) == 1) {   $values = array_values($quote_data);   if (count($values[0]['quote']) == 1) {   $res = true;   $keys = array_keys($values[0]['quote']);      $method = $values[0]['quote'][$keys[0]];      $this->session->data['shipping_method'] = $method; } }
		$this->data['shipping_methods'] = $this->session->data['shipping_methods']; 
		
		if (isset($this->request->post['shipping'])) {
			$this->data['shipping'] = $this->request->post['shipping_method'];
		} elseif (isset($this->session->data['shipping_method'])) {
			$this->data['shipping'] = $this->session->data['shipping_method']['id'];			
		} else {
			$this->data['shipping'] = '';
		}
		
		$result = '<div class="content"><p>' . $this->language->get('text_shipping_methods') . '</p><table width="536" cellpadding="3">';
		foreach ($this->data['shipping_methods'] as $shipping_method) {
			$result .= '<tr><td colspan="3"><b>' . $shipping_method['title'] . '</b></td></tr>';
			if ( ! $shipping_method['error']) {
				foreach ($shipping_method['quote'] as $quote) {
					$result .= '<tr><td width="1"><label for="' . $quote['id'] . '">';
					if ($quote['id'] == $this->data['shipping']) {
						$result .= '<input type="radio" onclick="updateTotalsShip();" name="shipping_method" value="' . $quote['id'] . '" id="' . $quote['id'] . '" checked="checked" style="margin: 0px;" />';
					} else {
						$result .= '<input type="radio" onclick="updateTotalsShip();" name="shipping_method" value="' . $quote['id'] . '" id="' . $quote['id'] . '" style="margin: 0px;" />';
					}
					$result .= '</label></td>';
					$result .= '<td width="534"><label for="' . $quote['id'] . '" style="cursor: pointer;">' . $quote['title'] . '</label></td>';
					$result .= '<td align="right"><label for="' . $quote['id'] . '" style="cursor: pointer;">' . $quote['text'] . '</label></td>';
					$result .= '</tr>';
				}
			} else {
				$result .= '<tr><td colspan="3"><div class="error">' . $shipping_method['error'] . '</div></td></tr>';
			}
		}
		$result .= '</table></div>';
		if (!$res) {
		echo $result;
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
	
	public function ajaxRemove() {
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['key'])) {
				$key = str_replace('_', '.', str_replace('-', ':', $this->request->post['key']));
				$this->cart->remove($key);
				echo (!$this->cart->hasProducts()) ? 'empty' : 'success';
			}
		}
	}
	
	public function ajaxEmailCheck() {
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['email'])) {
				$this->load->model('account/customer');
				echo ( ! $this->model_account_customer->checkEmail($this->request->post['email'])) ? 'empty' : 'success';
			}
		}
	}
	
	public function ajaxPay() {
	if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['pay'])) {
				//echo $this->request->post['pay'];
			}
		}
	}
	
	public function ajaxTotals() {
		if ( ! empty($this->request->post['shipping_method'])) {
			$this->setShipping($this->request->post['shipping_method']);
		}
		
		if (isset($this->session->data['shipping_address'])) {
			$this->tax->setZone($this->session->data['shipping_address']['country_id'], $this->session->data['shipping_address']['zone_id']);
		} else if(isset($this->session->data['payment_address'])) {
			$this->tax->setZone($this->session->data['payment_address']['country_id'], $this->session->data['payment_address']['zone_id']);
		} else {
			$this->tax->setZone($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
		}
		
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
		
		foreach ($total_data as $total) {
			echo '<tr><td align="right">' . $total['title'] . '</td><td align="right">' . $total['text'] . '</td></tr>';
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
	
	private function setShipping($value) {
		$shipping = explode('.', $value);
		$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
	}
	
	private function validate() {
		if ((strlen(utf8_decode($this->request->post['firstname'])) < 3) || (strlen(utf8_decode($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}
		
		if ($this->request->post['pass1'] != $this->request->post['pass2'])
		{
		$this->error['password'] = "Your passwords did not match, retype them";
		}
		
		if ((strlen(utf8_decode($this->request->post['pass1'])) < 3) || (strlen(utf8_decode($this->request->post['pass1'])) > 32)) {
			$this->error['password'] = "Password must be between 6 and 32 characters";
		}
		
		if ((strlen(utf8_decode($this->request->post['lastname'])) < 3) || (strlen(utf8_decode($this->request->post['lastname'])) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}
		
		$pattern = '/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i';
		
		if ( ! preg_match($pattern, $this->request->post['email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}
		
		if ((strlen(utf8_decode($this->request->post['telephone'])) < 3) || (strlen(utf8_decode($this->request->post['telephone'])) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}
		
		if ((strlen(utf8_decode($this->request->post['payment_address_1'])) < 3) || (strlen(utf8_decode($this->request->post['payment_address_1'])) > 128)) {
			$this->error['payment_address_1'] = $this->language->get('error_address_1');
		}
		
		if ((strlen(utf8_decode($this->request->post['payment_city'])) < 3) || (strlen(utf8_decode($this->request->post['payment_city'])) > 128)) {
			$this->error['payment_city'] = $this->language->get('error_city');
		}
		
		if ($this->request->post['payment_country_id'] == 'FALSE') {
			$this->error['payment_country'] = $this->language->get('error_country');
		}
		
		if ($this->request->post['payment_zone_id'] == 'FALSE') {
			$this->error['payment_zone'] = $this->language->get('error_zone');
		}
		
		if ($this->data['is_shipping']) {
			if ((strlen(utf8_decode($this->request->post['shipping_address_1'])) < 3) || (strlen(utf8_decode($this->request->post['shipping_address_1'])) > 128)) {
				$this->error['shipping_address_1'] = $this->language->get('error_address_1');
			}
			
			if ((strlen(utf8_decode($this->request->post['shipping_city'])) < 3) || (strlen(utf8_decode($this->request->post['shipping_city'])) > 128)) {
				$this->error['shipping_city'] = $this->language->get('error_city');
			}
			
			if ($this->request->post['shipping_country_id'] == 'FALSE') {
				$this->error['shipping_country'] = $this->language->get('error_country');
			}
			
			if ($this->request->post['shipping_zone_id'] == 'FALSE') {
				$this->error['shipping_zone'] = $this->language->get('error_zone');
			}
			
			if ( ! isset($this->request->post['payment_method']) && !isset($this->session->data['payment_method'])) {
				$this->error['warning'] = $this->language->get('error_payment');
			} else if ( ! isset($this->request->post['payment_method'])) {
			if (!isset($this->session->data['payment_methods'][$this->session->data['payment_method']['id']])) {
					$this->error['warning'] = $this->language->get('error_payment');
			} } else
			{
				if (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
					$this->error['warning'] = $this->language->get('error_payment');
				}
			}
			//print_r($this->session->data); die;
			if ( ! isset($this->request->post['shipping_method']) && !isset($this->session->data['shipping_method'])) {
			//echo "1";
				$this->error['warning'] = $this->language->get('error_shipping');
			} else if ( ! isset($this->session->data['shipping_method'])) {
			//echo "2";
			$this->error['warning'] = $this->language->get('error_shipping');
			}
			else
			{
			//echo "3";
			if (isset( $this->request->post['shipping_method'])) {
				$shipping = explode('.', $this->request->post['shipping_method']);
				if ( ! isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {			
					$this->error['warning'] = $this->language->get('error_shipping');
				}
				}
				else
				{
				$shipping = $this->session->data['shipping_method'];
				
				$half = (int) ( (strlen($shipping['id']) / 2) );
				//echo $half;
				$shipping = substr($shipping['id'],0,$half);
				//print_r($this->session->data['shipping_methods']);
				//echo "<br><br>";
				//print_r($shipping); die;
				if ( ! isset($this->session->data['shipping_methods'][$shipping]['quote'][$shipping])) {			
					$this->error['warning'] = $this->language->get('error_shipping');
				}
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
	
	public function zone() {
		$output = '<option value="FALSE">' . $this->language->get('text_select') . '</option>';
		
		$this->load->model('localisation/zone');
		
		$results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);
		
		foreach ($results as $result) {
			$output .= '<option value="' . $result['zone_id'] . '"';
			
			if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
				$output .= ' selected="selected"';
			}
			
			$output .= '>' . $result['name'] . '</option>';
		}
		
		if ( ! $results) {
			if (!$this->request->get['zone_id']) {
				$output .= '<option value="0" selected="selected">' . $this->language->get('text_none') . '</option>';
			} else {
				$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
			}
		}
		
		$this->response->setOutput($output, $this->config->get('config_compression'));
	}
}
?>
<?php 
class ControllerModuleGCheckout extends Controller {

	function index($callback=FALSE) {
		$this->language->load('module/gcheckout');

		// remove expired Google download items from database
		$this->load->model('checkout/gcheckout');
		$this->model_checkout_gcheckout->deleteExpiredGoogleDownloads( time() );

		if ($this->cart->hasProducts()) {
			if (isset($this->session->data['google_order_reference'])) {
				$reference = $this->session->data['google_order_reference'];
			} else {
				$reference = NULL;
			}
			if ($reference != NULL) {
				// User has been to the Google's Checkout site,
				// but did he/she do the final order submission while there?
				$row = $this->model_checkout_gcheckout->findGoogleOrder($reference);
				if ($row) {
					// User did the final order submission at Google's Checkout site,
					// and Google sent a 'new-order-notification' message to OpenCart
					// causing OpenCart to enter the order into the 'google_order' DB table.
					// We can therefore clear the shopping cart and this DB entry.
					$this->model_checkout_gcheckout->deleteGoogleOrder($reference);
					$this->cart->clear();
					unset($this->session->data['google_order_reference']);
					if (($_SERVER['SERVER_PORT']==443) && (HTTPS_SERVER!='')) {
						$this->redirect( HTTPS_SERVER . 'index.php?' . $_SERVER['QUERY_STRING'] );
					} else {
						$this->redirect( HTTP_SERVER . 'index.php?' . $_SERVER['QUERY_STRING'] );
					}
					exit;
				}
				unset($this->session->data['google_order_reference']);
			}
		}

		// remove expired Google order references from database
		$this->model_checkout_gcheckout->deleteExpiredGoogleOrders( time() );

		// generate the Google Checkout box or button
		$this->id = 'gcheckout';
    	$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['ajax'] = $this->config->get('cart_ajax');
		$this->data['callback'] = $callback; 
		if ($this->cart->hasProducts()) {
			$products = $this->cart->getProducts();
			$available = $this->available();
			$paymentURL = $this->url->https( 'checkout/gcheckout/process' );
			$merchantId = $this->config->get('gcheckout_merchantid');
			$this->data['products'] = $products;
			$this->data['available'] = $available;
			$this->data['payment_url'] = $paymentURL;
			$this->data['merchant_id'] = $merchantId;
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/gcheckout2.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/gcheckout2.tpl';
			} else {
				$this->template = 'default/template/module/gcheckout2.tpl';
			}
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/gcheckout.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/gcheckout.tpl';
			} else {
				$this->template = 'default/template/module/gcheckout.tpl';
			}
		}
		if ($callback) {
			return $this->render(TRUE);
		}
		$this->render();
	}



	protected function available() 
	{
		// Make sure this module is enabled
		if (!$this->config->get('gcheckout_status')) {
			return FALSE;
		}

		// Find out whether shipping methods are needed
		$shipping = FALSE;
		if ($this->config->get('gcheckout_merchant_calculation')) {
			$products = $this->cart->getProducts();
			foreach ($products as $product) {
				if ($product['shipping']) {
					$shipping = TRUE;
					break;
				}
			}
		}

		// If shipping needed make sure at least one shipping method is needed
		if ($shipping) {
			list( $names, $geoZoneIds ) = $this->model_checkout_gcheckout->getShippingMethods();
			if (count($names)==0) {
				error_log( date('Y-m-d H:i:s - ', time())."ControllerModuleGCheckout::available: No shipping methods enabled\n",3,DIR_LOGS."error.txt" );
				return FALSE;
			}
		}

		// Make sure all of the enabled shipping modules use the same tax class
		if ($shipping) {
			$shippingTaxClassId = 0;
			$first = TRUE;
			$codes = array_keys( $names );
			foreach ($codes as $code) {
				$taxClassId = 0;
				$shippingCode = (strpos($code,'_',0)===FALSE) ? $code : substr($code,0,strpos($code,'_',0));
				if ($shippingCode == 'free') {
					continue;
				}
				if ($this->config->get($shippingCode.'_tax_class_id')!=NULL) {
					$taxClassId = $this->config->get($shippingCode.'_tax_class_id');
				}
				if ($taxClassId != $shippingTaxClassId) {
					if ($first) {
						$shippingTaxClassId = $taxClassId;
					}
					else {
						error_log( date('Y-m-d H:i:s - ', time())."ControllerModuleGCheckout::available: Shipping methods use different tax classes\n",3,DIR_LOGS."error.txt" );
						return FALSE;
					}
				}
				$first = FALSE;
			}
		}

		// Make sure that for a UK merchant the shipping method's geo zones don't
		// include individual UK counties
		if ($shipping) {
			foreach ($geoZoneIds as $geoZoneId) {
				$rows = $this->model_checkout_gcheckout->findGeoAreas( $geoZoneId );
				foreach ($rows as $row) {
					if ($row['iso_code_2'] == 'GB') {
						if ($row['zone_id'] != 0) {
							error_log( date('Y-m-d H:i:s - ', time())."ControllerModuleGCheckout::available: Shipping methods should include all UK counties in their UK-based geo zones\n",3,DIR_LOGS."error.txt" );
							return FALSE;
						}
					}
				}
			}
		}

		// Make sure product's tax rules, if there, are compatible with Google Checkout
		$rows = $this->model_checkout_gcheckout->findProductTaxRules( $this->cart->getProducts() );
		foreach ($rows as $row) {
			if ($row['iso_code_2']=='US') {
				if (is_null($row['code'])) {
					continue;
				}
				if ($this->model_checkout_gcheckout->isUSStateArea( $row['code'] )) {
					continue;
				}
			}
			else if ($row['iso_code_2']=='GB') {
				if (is_null($row['code'])) {
					continue;
				}
			}
			error_log( date('Y-m-d H:i:s - ', time())."ControllerModuleGCheckout::available: Product's tax rules not compatible for Google Checkout\n",3,DIR_LOGS."error.txt" );
			return FALSE;
		}

		// make sure products in cart are not out of stock
		if (!$this->config->get('config_stock_checkout')) {
			$products = $this->cart->getProducts();
			foreach ($products as $product) {
				if ($product['stock'] <= 0) {
					error_log( date('Y-m-d H:i:s - ', time())."ControllerModuleGCheckout::available: At least one of the selected products is out of stock\n",3,DIR_LOGS."error.txt" );
					return FALSE;
				}
			}
		}

		return TRUE;
	}


	function callback() {
		$output = $this->index(TRUE);
		$this->response->setOutput($output, $this->config->get('config_compression'));
	}

}
?>
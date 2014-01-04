<?php 
class ControllerAccountInvoice extends Controller {
	public function index() {
		if ( ! $this->customer->isLogged()) {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}
			//echo "here"; die;
			$this->session->data['redirect'] = $this->url->https('account/invoice&order_id=' . $order_id);
			
			
			$this->redirect($this->url->https('account/login'));
		}
		$this->load->language('account/order2');
		
		$this->document->title = $this->language->get('heading_title');
			
		if (isset($this->request->server['HTTPS']) && ($this->request->server['HTTPS'] == 'on')) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}
		
		$this->data['direction'] = $this->language->get('direction');
		$this->data['language'] = $this->language->get('code');	
		
		$this->data['text_invoice'] = $this->language->get('text_invoice');
		$this->data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$this->data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$this->data['text_telephone'] = $this->language->get('text_telephone');
		$this->data['text_fax'] = $this->language->get('text_fax');		
		$this->data['text_to'] = $this->language->get('text_to');
		$this->data['text_ship_to'] = $this->language->get('text_ship_to');
		
		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_total'] = $this->language->get('column_total');	
		
		$this->load->model('account/order2');
		
		$this->data['order'] = array();
		
		if (isset($this->request->post['selected'])) {
			$orders = $this->request->post['selected'];
		} else {
			$orders = array();
		}
		
		$order_id = $this->request->get['order_id'];
			$order_info = $this->model_account_order2->getOrder($order_id);
			//print_r($order_info);die;
			if ($order_info) {
				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
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
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'company'   => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'zone'      => $order_info['shipping_zone'],
					'country'   => $order_info['shipping_country']  
				);
				
				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				
				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
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
					'firstname' => $order_info['payment_firstname'],
					'lastname'  => $order_info['payment_lastname'],
					'company'   => $order_info['payment_company'],
					'address_1' => $order_info['payment_address_1'],
					'address_2' => $order_info['payment_address_2'],
					'city'      => $order_info['payment_city'],
					'postcode'  => $order_info['payment_postcode'],
					'zone'      => $order_info['payment_zone'],
					'country'   => $order_info['payment_country']  
				);
				
				$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				
				$product_data = array();
				
				$products = $this->model_account_order2->getOrderProducts($order_id);
				
				foreach ($products as $product) {
					$option_data = array();
					
					$options = $this->model_account_order2->getOrderOptions($order_id, $product['order_product_id']);
					
					foreach ($options as $option) {
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $option['value']
						);
					}
					
					$product_data[] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'], $order_info['currency'], $order_info['value']),
						'total'    => $this->currency->format($product['total'], $order_info['currency'], $order_info['value'])
					);
				}
				
				$total_data = $this->model_account_order2->getOrderTotals($order_id);
				
				$this->data['order'][] = array(
					'order_id'         => $order_info['order_id'],
					'date_added'       => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
					'store'            => $this->config->get('config_store'),
					'address'          => nl2br($this->config->get('config_address')),
					'telephone'        => $this->config->get('config_telephone'),
					'fax'              => $this->config->get('config_fax'),
					'email'            => $this->config->get('config_email'),
					'website'          => trim(HTTP_SERVER, '/'),
					'shipping_address' => $shipping_address,
					'payment_address'  => $payment_address,
					'product'          => $product_data,
					'total'            => $total_data
				);
			}
		
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/invoice.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/account/invoice.tpl';
			} else {
				$this->template = 'default/template/account/invoice.tpl';
			}
			
			$this->children = array(
				'common/header2',
				'common/footer',
				'common/column_left',
				'common/column_right'
			);
			
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		}
	}
?>
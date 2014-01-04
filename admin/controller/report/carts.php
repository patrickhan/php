<?php
class ControllerReportCarts extends Controller {
	
	public function cart($cinfo) {
	$product_data = array();
		$cinfos = unserialize($cinfo);
		foreach ($cinfos as $key => $value) {
			$array = explode(':', $key);
			$product_id = $array[0];
			
			$quantity = $value;
			$stock = TRUE;
			
			if (isset($array[1])) {
				$options = explode('.', $array[1]);
			} else {
				$options = array();
			}
			
			$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p.status = '1'");
			
			if ($product_query->num_rows) {
				$option_price = 0;
				
				$option_data = array();
				
				foreach ($options as $product_option_value_id) {
					$option_value_query = $this->db->query("SELECT pov.product_option_id, povd.name, pov.price, pov.quantity, pov.subtract, pov.prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "product_option_value_description povd ON (pov.product_option_value_id = povd.product_option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_id = '" . (int)$product_id . "' AND povd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY pov.sort_order");
					
					if ($option_value_query->num_rows) {
						$option_query = $this->db->query("SELECT pod.name FROM " . DB_PREFIX . "product_option po LEFT JOIN " . DB_PREFIX . "product_option_description pod ON (po.product_option_id = pod.product_option_id) WHERE po.product_option_id = '" . (int)$option_value_query->row['product_option_id'] . "' AND po.product_id = '" . (int)$product_id . "' AND pod.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY po.sort_order");
						
						if ($option_value_query->row['prefix'] == '+') {
							$option_price = $option_price + $option_value_query->row['price'];
						} elseif ($option_value_query->row['prefix'] == '-') {
							$option_price = $option_price - $option_value_query->row['price'];
						}
						
						$option_data[] = array(
							'product_option_value_id' => $product_option_value_id,
							'name'                    => $option_query->row['name'],
							'value'                   => $option_value_query->row['name'],
							'prefix'                  => $option_value_query->row['prefix'],
							'price'                   => $option_value_query->row['price']
						);
						
						if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
							$stock = FALSE;
						}
					}
				}
				
				
					$customer_group_id = $this->config->get('config_customer_group_id');
				
				
				$product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND quantity <= '" . (int)$quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");
				
				if ($product_discount_query->num_rows) {
					$price = $product_discount_query->row['price'];
				} else {
					$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");
					
					if ($product_special_query->num_rows) {
						$price = $product_special_query->row['price'];
					} else {
						$price = $product_query->row['price'];
					}
				}
				
				$download_data = array();
				
				$download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '" . (int)$product_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
				
				foreach ($download_query->rows as $download) {
					$download_data[] = array(
						'download_id' => $download['download_id'],
						'name'        => $download['name'],
						'filename'    => $download['filename'],
						'mask'        => $download['mask'],
						'remaining'   => $download['remaining']
					);
				}
				
				if ( ! $product_query->row['quantity'] || ($product_query->row['quantity'] < $quantity)) {
					$stock = FALSE;
				}
				
				$product_data[$key] = array(
					'key'              => $key,
					'product_id'       => $product_query->row['product_id'],
					'name'             => $product_query->row['name'],
					'model'            => $product_query->row['model'],
					'shipping'         => $product_query->row['shipping'],
					'image'            => $product_query->row['image'],
					'option'           => $option_data,
					'download'         => $download_data,
					'quantity'         => $quantity,
					'stock'            => $stock,
					'price'            => ($price + $option_price),
					'total'            => ($price + $option_price) * $quantity,
					'tax_class_id'     => $product_query->row['tax_class_id'],
					'weight'           => $product_query->row['weight'],
					'weight_class_id'  => $product_query->row['weight_class_id'],
					'length'           => $product_query->row['length'],
					'width'            => $product_query->row['width'],
					'height'           => $product_query->row['height'],
					'measurement_id'   => $product_query->row['measurement_class_id'],
					'gift_certificate' => $product_query->row['gift_certificate']
				);
			} else {
				
			}
		}
		$pdd = '';
		$num = 0;
		foreach ($product_data as $pd) {
		if ($num > 0) {
		$pdd .= "<br>";
		}
		$pdd .= "<a target='_blank' href='index.php?route=catalog/product/update&product_id=" . $pd['product_id'] . "'>" . $pd['name'] . "</a> x " . $pd['quantity'];
		$num++;
		}
		return $pdd;
	}
	
	public function setR() {	

	$set = $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `group` = 'setC' AND `key` = 'setC'");				

	$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = 'setC', `key` = 'setC', `value` = 'Reg'");				

	$this->index();

	}	

	

	public function setG() {

	$set = $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `group` = 'setC' AND `key` = 'setC'");				

	$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = 'setC', `key` = 'setC', `value` = 'Ges'");				

	$this->index();

	}

	public function index() {     
		$this->load->language('report/viewed');

		$this->document->title = 'Abandoned Carts';

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
						
		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('report/viewed' . $url),
       		'text'      => 'Abandoned Carts',
      		'separator' => ' :: '
   		);		
		$set = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `group` = 'setC' AND `key` = 'setC'");

		$reg = true;

		$ges = false;

		if ($set->rows) {	

		$this->data['set'] = $set->row['value'];	

		if ($set->row['value'] != 'Reg') {	

		$reg = false;	

		}

		if ($set->row['value'] == 'Ges') {	

		$ges = true;	

		}

		}	

		else {	

		$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = 'setC', `key` = 'setC', `value` = 'Reg'");	
		$reg = true;
		}

		$this->data['reg'] = $reg;	

		$this->data['ges'] = $ges;	
		$this->data['carts'] = array();
		if ($reg) {
		$carts = $this->db->query("select * from customer WHERE cart != 'a:0:{}'");
		foreach ($carts->rows as $cart) {
		$this->data['carts'][] = array(
       		'name'      => $cart['firstname'] . " " . $cart['lastname'],
       		'cart'      => $this->cart($cart['cart'])
   		);		
		}
		}
		else
		{
		$carts = $this->db->query("select * from guest_carts WHERE cart != 'a:0:{}'");
		foreach ($carts->rows as $cart) {
		$this->data['carts'][] = array(
       		'name'      => 'Guest',
       		'cart'      => $this->cart($cart['cart'])
   		);		
		}
		}
		
		$this->load->model('catalog/product');
		
		//$product_total = $this->model_catalog_product->getTotalProducts(); 
		
		$this->load->model('report/viewed');
		
		//$this->data['products'] = $this->model_report_viewed->getProductViewedReport(($page - 1) * 10, 10);
		 
 		$this->data['heading_title'] = 'Abandoned Carts';
		 
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_viewed'] = $this->language->get('column_viewed');
		$this->data['column_percent'] = $this->language->get('column_percent');
		
		$pagination = new Pagination();
		$pagination->total = 0;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('report/viewed&page=%s');
			
		$this->data['pagination'] = '';//$pagination->render();
		 
		$this->template = 'report/carts.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
}
?>
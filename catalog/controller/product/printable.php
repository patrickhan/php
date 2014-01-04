<?php 
class ControllerProductPrintable extends Controller {
	
	public function index() {
		if ($this->config->get('brochure') === TRUE) {
			$this->redirect($this->url->https('error/not_found'));
		}
		
		if ($this->config->get('premium') === FALSE) {
			$this->redirect($this->url->https('error/not_found'));
		}
		
		$this->language->load('product/printable');
		$this->language->load('common/header');
		
		$this->load->model('catalog/product');
		$this->load->model('tool/seo_url'); 
		$this->load->helper('image');
		
		$this->data['heading_title'] = $this->config->get('config_store') . ' ' . $this->language->get('text_heading');
		$this->data['icon'] = $this->config->get('config_icon');
		$this->data['direction'] = $this->language->get('direction');
		$this->data['lang'] = $this->language->get('code');
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}
		
		if ($this->config->get('google_analytics_status')) {
			$this->data['google_analytics'] = $this->config->get('google_analytics_code');
		} else {
			$this->data['google_analytics'] = '';
		}
		
		$products = $this->model_catalog_product->getProducts();
		
		if ($products) {
			$this->load->model('catalog/review');
				
			$this->data['products'] = array();
					
			foreach ($products as $product) {
				if ($product['image']) {
					$image = $product['image'];
				} else {
					$image = 'no_image.jpg';
				}
				
				$rating = $this->model_catalog_review->getAverageRating($product['product_id']);
				
				$special = FALSE;
				
				$discount = $this->model_catalog_product->getProductDiscount($product['product_id']);
				
				if ($discount) {
					$price = $this->currency->format($this->tax->calculate($discount, $product['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
					
					$special = $this->model_catalog_product->getProductSpecial($product['product_id']);
					
					if ($special) {
						$special = $this->currency->format($this->tax->calculate($special, $product['tax_class_id'], $this->config->get('config_tax')));
					}
				}
				
				$this->data['products'][] = array(
					'name'        => $product['name'],
					'description' => html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8'),
					'model'       => $product['model'],
					'rating'      => $rating,
					'stars'       => sprintf($this->language->get('text_stars'), $rating),
					'thumb'       => image_resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
					'price'       => $price,
					'special'     => $special,
					'href'        => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $product['product_id']))
				);
			}
			
			if ( ! $this->config->get('config_customer_price')) {
				$this->data['display_price'] = TRUE;
			} elseif ($this->customer->isLogged()) {
				$this->data['display_price'] = TRUE;
			} else {
				$this->data['display_price'] = FALSE;
			}
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/printable.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/printable.tpl';
			} else {
				$this->template = 'default/template/product/printable.tpl';
			}	
			
			$this->children = array(
				'common/header',
				'common/footer',
				'common/column_left',
				'common/column_right'
			);
			
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		} else {
			$this->data['text_error'] = $this->language->get('text_empty');
			
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->data['continue'] = $this->url->http('common/home');
				
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}
			
			$this->children = array(
				'common/header',
				'common/footer',
				'common/column_left',
				'common/column_right'
			);
			
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		}
	}
	
}
?>
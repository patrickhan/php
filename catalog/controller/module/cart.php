<?php 
class ControllerModuleCart extends Controller { 
	protected function index() {
		$this->language->load('module/cart');
		
		$this->load->model('tool/seo_url');
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_subtotal'] = $this->language->get('text_subtotal');
		$this->data['text_empty'] = $this->language->get('text_empty');
		
		$this->data['button_checkout'] = $this->language->get('button_checkout');
		 
		$this->data['products'] = array();
		
		$this->load->helper('image');
		
		foreach ($this->cart->getProducts() as $result) {
			$option_data = array();
			
			foreach ($result['option'] as $option) {
				$option_data[] = array(
					'name'  => $option['name'],
					'value' => $option['value']
				);
			}
			$image = ( ! empty($result['image'])) ? image_resize($result['image'], 20, 20) : '';
			$this->data['products'][] = array(
				'name'     => $result['name'],
				'image'    => $image,
				'option'   => $option_data,
				'quantity' => $result['quantity'],
				'stock'    => $result['stock'],
				'price'    => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
				'href'     => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id'])),
			);
		}
		
		$this->data['subtotal'] = $this->currency->format($this->cart->getTotal());
		
		$this->data['ajax'] = $this->config->get('cart_ajax');
		
		$this->data['checkout'] = $this->url->http('checkout/onepage');
		
		$this->id = 'cart';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/cart.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/cart.tpl';
		} else {
			$this->template = 'default/template/module/cart.tpl';
		}
		
		$this->render();
	}
	
	public function callback() {
		$this->language->load('module/cart');
		
		$this->load->model('tool/seo_url');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['option'])) {
				$option = $this->request->post['option'];
			} else {
				$option = array();
			}
			
			$this->cart->add($this->request->post['product_id'], $this->request->post['quantity'], $option);
			
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['payment_method']);
		}
		
		$output = '<table cellpadding="2" cellspacing="0" style="width: 100%;">';
		
		$this->load->helper('image');
		
		foreach ($this->cart->getProducts() as $product) {
			$output .= '<tr>';
			$output .= '<td width="1" valign="top" align="right">' . $product['quantity'] . '&nbsp;x&nbsp;</td>';
			$output .= '<td align="left" valign="top"><a href="' . $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $product['product_id'])) . '">' . $product['name'] . '</a>';
			$output .= '<div>';
			
			foreach ($product['option'] as $option) {
				$output .= ' - <small style="color: #999;">' . $option['name'] . ' ' . $option['value'] . '</small><br />';
			}
			$image = ( ! empty($product['image'])) ? '<img src="' . image_resize($product['image'], 20, 20) . '">' : '';
			$output .= '</div></td>';
			$output .= '<td valign="top"><a href="' . $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $product['product_id'])) . '">' . $image . '</a></td>';
			$output .= '</tr>';
			}
		
		$output .= '</table>';
		$output .= '<br />';
		$output .= '<div style="text-align: right;">' . $this->language->get('text_subtotal') . '&nbsp;' .  $this->currency->format($this->cart->getTotal()) . '</div>';
		$output .= '<br />';
		$output .= '<div style="text-align: center;"><a style="text-decoration: none;" href="' . $this->url->http('checkout/onepage') . '" class="button"><span>' . $this->language->get('button_checkout') . '</span></a></div>';
		
		$this->response->setOutput($output, $this->config->get('config_compression'));
	} 	
}
?>
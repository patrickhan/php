<?php
class ControllerCheckoutSuccess extends Controller {
	public function index() {
		if (isset($this->session->data['order_id'])) {
			$this->cart->clear();
			
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['gift_certificate']);
		}
		
		$this->language->load('checkout/success');
		
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
		
		if ($this->customer->isLogged()) {
			$this->document->breadcrumbs[] = array(
				'href'      => $this->url->http('checkout/shipping'),
				'text'      => $this->language->get('text_shipping'),
				'separator' => $this->language->get('text_separator')
			);
			
			$this->document->breadcrumbs[] = array(
				'href'      => $this->url->http('checkout/payment'),
				'text'      => $this->language->get('text_payment'),
				'separator' => $this->language->get('text_separator')
			);
			
			$this->document->breadcrumbs[] = array(
				'href'      => $this->url->http('checkout/confirm'),
				'text'      => $this->language->get('text_confirm'),
				'separator' => $this->language->get('text_separator')
			);
		} else {
			$this->document->breadcrumbs[] = array(
				'href'      => $this->url->http('checkout/guest'),
				'text'      => $this->language->get('text_guest'),
				'separator' => $this->language->get('text_separator')
			);
			
			$this->document->breadcrumbs[] = array(
				'href'      => $this->url->http('checkout/guest/confirm'),
				'text'      => $this->language->get('text_confirm'),
				'separator' => $this->language->get('text_separator')
			);
		}
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('checkout/success'),
			'text'      => $this->language->get('text_success'),
			'separator' => $this->language->get('text_separator')
		);
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_message'] = sprintf($this->language->get('text_message'), $this->url->https('account/account'), $this->url->https('account/history'), $this->url->http('information/contact'));
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		
		$this->data['continue'] = $this->url->http('common/home');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/success.tpl';
		} else {
			$this->template = 'default/template/common/success.tpl';
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
?>
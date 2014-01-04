<?php    
class ControllerSaleNewsletter extends Controller { 
	private $error = array();
	
	public function index() {
		$this->load->language('sale/newsletter');
		 
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/customer');
		
		$this->getList();
	}
	
	public function insert() {
		$this->load->language('sale/newsletter');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/customer');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_customer->addNewsletterSignup($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . $this->request->get['filter_email'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->https('sale/newsletter' . $url));
		}
		
		$this->getForm();
	}
	
	public function delete() {
		$this->load->language('sale/newsletter');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/customer');
		
		if (isset($this->request->get['email']) && $this->validateDelete()) {
			$this->model_sale_customer->deleteNewsletterSignup($this->request->get['email']);
			
			$url = '';
			
			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . $this->request->get['filter_email'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->https('sale/newsletter' . $url));
			
			$this->session->data['success'] = $this->language->get('text_success');
		} else if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $email) {
				$this->model_sale_customer->deleteNewsletterSignup($email);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . $this->request->get['filter_email'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->https('sale/newsletter' . $url));
		}
		
		$this->getList();
	}
	
	private function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name'; 
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = NULL;
		}
		
		$url = '';
		
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$this->document->breadcrumbs = array();
		
			$this->document->breadcrumbs[] = array(
				'href'      => $this->url->https('common/home'),
				'text'      => $this->language->get('text_home'),
				'separator' => FALSE
			);
			
			$this->document->breadcrumbs[] = array(
				'href'      => $this->url->https('sale/newsletter' . $url),
				'text'      => $this->language->get('heading_title'),
				'separator' => ' :: '
			);
			
		$this->data['insert'] = $this->url->https('sale/newsletter/insert' . $url);
		$this->data['delete'] = $this->url->https('sale/newsletter/delete' . $url);
		
		$this->data['customers'] = array();
		
		$data = array(
			'filter_email'      => $filter_email, 
			'sort'              => $sort,
			'order'             => $order,
			'start'             => ($page - 1) * 10,
			'limit'             => 10
		);
		
		$customer_total = $this->model_sale_customer->getTotalNewsletterSignups($data);
	
		$results = $this->model_sale_customer->getNewsletterSignups($data);
		
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_delete'),
				'href' => $this->url->https('sale/newsletter/delete&email=' . $result['email'] . $url)
			);
			
			$this->data['customers'][] = array(
				'email'       => $result['email'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'    => isset($this->request->post['selected']) && in_array($result['email'], $this->request->post['selected']),
				'action'      => $action
			);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');
		
		if (isset($this->session->data['error'])) {
			$this->data['error_warning'] = $this->session->data['error'];
			
			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		$url = '';
		
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}
		
		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_email'] = $this->url->https('sale/newsletter&sort=email' . $url);
		$this->data['sort_date_added'] = $this->url->https('sale/newsletter&sort=date_added' . $url);
		
		$url = '';
		
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$pagination = new Pagination();
		$pagination->total = $customer_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('sale/newsletter' . $url . '&page=%s');
		
		$this->data['pagination'] = $pagination->render();
		
		$this->data['filter_email'] = $filter_email;
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'sale/newsletter_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_email'] = $this->language->get('entry_email');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
		
		$url = '';
		
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('sale/newsletter' . $url),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		$this->data['action'] = $this->url->https('sale/newsletter/insert' . $url);
		  
		$this->data['cancel'] = $this->url->https('sale/newsletter' . $url);
		
		
		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} else {
			$this->data['email'] = '';
		}
		
		$this->template = 'sale/newsletter_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	 
	private function validateForm() {
		if ( ! $this->user->hasPermission('modify', 'sale/newsletter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$pattern = '/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i';
		
		if ((strlen(utf8_decode($this->request->post['email'])) > 32) || (!preg_match($pattern, $this->request->post['email']))) {
			$this->error['email'] = $this->language->get('error_email');
		}
		
		if ( ! $this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	private function validateDelete() {
		if ( ! $this->user->hasPermission('modify', 'sale/newsletter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ( ! $this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>

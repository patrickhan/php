<?php
class ControllerInformationInformation extends Controller {
	public function index() {
	
	$this->db->query("CREATE TABLE IF NOT EXISTS `blogcomments` (
  `blogcomment_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `author` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `rating` int(1) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`blogcomment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");

		$this->language->load('information/information');
		
		$this->load->model('catalog/information');
		
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		if (isset($this->request->get['information_id'])) {
			$information_id = $this->request->get['information_id'];
		} else {
			$information_id = 0;
		}
		$this->data['information_id'] = $information_id;
		$information_info = $this->model_catalog_information->getInformation($information_id);
		if ($information_info['location'] == '7') {
		$this->data['art'] = true;
		}
		else
		{
		$this->data['art'] = false;
		}
		//print_r($information_info); die;
		if ($information_info) {
			$this->document->title = ( ! empty($information_info['title_tag'])) ? $information_info['title_tag'] : $information_info['title'];
			
			$this->document->breadcrumbs[] = array(
				'href'      => $this->url->http('information/information&information_id=' . $this->request->get['information_id']),
				'text'      => $information_info['title'],
				'separator' => $this->language->get('text_separator')
			);
			
			$this->data['heading_title'] = $information_info['title'];
			
			$this->document->description = $information_info['meta_description'];
			
			$this->document->keywords = $information_info['meta_keywords'];
			
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->data['description'] = html_entity_decode($information_info['description']);
			
			$this->data['continue'] = $this->url->http('common/home');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/information.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/information/information.tpl';
			} else {
				$this->template = 'default/template/information/information.tpl';
			}
			
			$this->children = array(
				'common/header',
				'common/footer',
				'common/column_left',
				'common/column_right'
			);
			
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		} else {
			$this->document->breadcrumbs[] = array(
				'href'      => $this->url->http('information/information&information_id=' . $this->request->get['information_id']),
				'text'      => $this->language->get('text_error'),
				'separator' => $this->language->get('text_separator')
			);
			
			$this->document->title = $this->language->get('text_error');
			
			$this->data['heading_title'] = $this->language->get('text_error');
			
			$this->data['text_error'] = $this->language->get('text_error');
			
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
	public function review() {
		$this->language->load('product/product');
		
		$this->load->model('catalog/review');
		
		$this->data['text_no_reviews'] = $this->language->get('text_no_reviews');
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$this->data['reviews'] = array();
			
		$results = $this->model_catalog_review->getIReviewsByProductId($this->request->get['information_id'], ($page - 1) * 99999, 99999);
		
		foreach ($results as $result) {
			$this->data['reviews'][] = array(
				'author'     => $result['author'],
				'rating'     => $result['rating'],
				'text'       => strip_tags($result['text']),
				'stars'      => sprintf($this->language->get('text_stars'), $result['rating']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}
		
		$review_total = $this->model_catalog_review->getTotalIReviewsByProductId($this->request->get['information_id']);
		
		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 99999; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->http('information/information/review&information_id=' . $this->request->get['information_id'] . '&page=%s');
		
		$this->data['pagination'] = '';//$pagination->render();
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/review.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/review.tpl';
		} else {
			$this->template = 'default/template/product/review.tpl';
		}
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	public function write() {
		$this->language->load('product/product');
		
		$this->load->model('catalog/review');
		
		$json = array();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

		//print_r($this->request->post); die;
			$this->model_catalog_review->addIReview($this->request->get['information_id'], $this->request->post);
			
			$json['success'] = $this->language->get('text_success');
		} else {
			$json['error'] = $this->error['message'];
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));
	}
	private function validate() {
		if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 25)) {
			$this->error['message'] = $this->language->get('error_name');
		}
		
		if ((strlen(utf8_decode($this->request->post['text'])) < 25) || (strlen(utf8_decode($this->request->post['text'])) > 1000)) {
			$this->error['message'] = $this->language->get('error_text');
		}
		
		if ( ! $this->request->post['rating']) {
			$this->error['message'] = $this->language->get('error_rating');
		}
		
		//if ($this->session->data['captcha'] != $this->request->post['captcha']) {
			//$this->error['message'] = $this->language->get('error_captcha');
		//}
		
		if ( ! $this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	}
?>
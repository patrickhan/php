<?php
class ControllerExtensionBanners extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('extension/banners');
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('extension/banners');
		
		$this->getList();
	}
	
	public function insert() {
		$this->load->language('extension/banners');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('extension/banners');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$data = array();
			
			$this->model_extension_banners->addBanner(array_merge($this->request->post, $data));
			
			$this->session->data['success'] = $this->language->get('text_banner_added');
			
			$this->redirect($this->url->https('extension/banners'));
		}
		
		$this->getForm();
	}
	
	private function getList() {
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('extension/banners'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		$this->data['add'] = $this->url->https('extension/banners/insert');
		$this->data['delete'] = $this->url->https('extension/banners/delete');
		
		$this->data['banners'] = array();
		
		$results = $this->model_extension_banners->getBannersStats();
		
		$banner_groups = $this->model_extension_banners->getBannerGroups();
		
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_statistics'),
				'href' => $this->url->https('extension/banners/statistics&banner_id=' . $result['banner_id'])
			);
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('extension/banners/update&banner_id=' . $result['banner_id'])
			);
			$action[] = array(
				'text' => $this->language->get('text_delete'),
				'href' => $this->url->https('extension/banners/delete&banner_id=' . $result['banner_id'])
			);
			
			$this->data['banners'][] = array(
				'banner_id'     => $result['banner_id'],
				'title'         => $result['title'],
				'group'         => $banner_groups[$result['group']]['name'],
				'status'        => ($result['status'] == '1') ? 'Enabled' : 'Disabled',
				'date_added'    => date('M jS, Y', $result['date_added']),
				'date_modified' => date('M jS, Y', $result['date_modified']),
				'views'         => (isset($result['views'])) ? $result['views'] : 0,
				'clicks'        => (isset($result['clicks'])) ? $result['clicks'] : 0,
				'selected'      => isset($this->request->post['selected']) && in_array($result['banner_id'], $this->request->post['selected']),
				'action'        => $action
			);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_url'] = $this->language->get('column_url');
		$this->data['column_group'] = $this->language->get('column_group');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_date_modified'] = $this->language->get('column_date_modified');
		$this->data['column_action'] = $this->language->get('column_action');
		$this->data['column_views'] = $this->language->get('column_views');
		$this->data['column_clicks'] = $this->language->get('column_clicks');
		
		$this->data['button_add'] = $this->language->get('button_add');
		$this->data['button_delete'] = $this->language->get('button_delete');
		
		if (isset($this->error['warning'])) {
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
		
		$this->template = 'extension/banner_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	public function update() {
		$this->load->language('extension/banners');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('extension/banners');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$data = array();
			
			$this->model_extension_banners->editBanner($this->request->get['banner_id'], array_merge($this->request->post, $data));
			
			$this->session->data['success'] = $this->language->get('text_banner_updated');
			
			$this->redirect($this->url->https('extension/banners'));
		}
		
		$this->getForm();
	}
	
	public function delete() {
		$this->load->language('extension/banners');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('extension/banners');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			$this->model_extension_banners->deleteBanners($this->request->post['selected']);
			
			$this->session->data['success'] = $this->language->get('text_banners_deleted');
			
			$this->redirect($this->url->https('extension/banners'));
		} else if (isset($this->request->get['banner_id']) && $this->validateDelete()) {
			$this->model_extension_banners->deleteBanner($this->request->get['banner_id']);
			
			$this->session->data['success'] = $this->language->get('text_banners_deleted');
			
			$this->redirect($this->url->https('extension/banners'));
		}
		
		$this->getList();
	}
	
	public function statistics() {
		$this->load->language('extension/banners');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['column_views'] = $this->language->get('column_views');
		$this->data['column_clicks'] = $this->language->get('column_clicks');
		$this->data['column_date'] = $this->language->get('column_date');
		
		$this->load->model('extension/banners');
		
		if (isset($this->request->get['banner_id'])) {
			$stats = $this->model_extension_banners->getBannerStats($this->request->get['banner_id']);
			
			$this->data['stats'] = $stats;
		} else {
			// niets
		}
		
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('extension/banners'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		$this->template = 'extension/banner_statistics.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_url'] = $this->language->get('entry_url');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_start'] = $this->language->get('entry_start');
		$this->data['entry_end'] = $this->language->get('entry_end');
		$this->data['entry_group'] = $this->language->get('entry_group');
		$this->data['entry_html'] = $this->language->get('entry_html');
		$this->data['entry_pages'] = $this->language->get('entry_pages');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_data'] = $this->language->get('tab_data');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['error_warning'] = '';
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		}
		
		$this->data['error_title'] = '';
 		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		}
		
		$this->data['error_url'] = '';
 		if (isset($this->error['url'])) {
			$this->data['error_url'] = $this->error['url'];
		}
		
		$this->data['error_status'] = '';
 		if (isset($this->error['status'])) {
			$this->data['error_status'] = $this->error['status'];
		}
		
		$this->document->breadcrumbs = array();
	
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('extension/banners'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		if (!isset($this->request->get['banner_id'])) {
			$this->data['action'] = $this->url->https('extension/banners/insert');
		} else {
			$banner_info = $this->model_extension_banners->getBanner($this->request->get['banner_id']);
			$banner_pages = $this->model_extension_banners->getBannerPages($this->request->get['banner_id']);
			$this->data = array_merge($this->data, $banner_info);
			$this->data['action'] = $this->url->https('extension/banners/update&banner_id=' . $this->request->get['banner_id']);
		}
		
		if (isset($this->request->post['title'])) {
			$this->data['title'] = $this->request->post['title'];
		} elseif (isset($banner_info)) {
			$this->data['title'] = $banner_info['title'];
		} else {
			$this->data['title'] = '';
		}
		
		if (isset($this->request->post['url'])) {
			$this->data['url'] = $this->request->post['url'];
		} elseif (isset($banner_info)) {
			$this->data['url'] = $banner_info['url'];
		} else {
			$this->data['url'] = '';
		}
		
		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (isset($banner_info)) {
			$this->data['image'] = $banner_info['image'];
		} else {
			$this->data['image'] = '';
		}
		
		if (isset($this->request->post['html'])) {
			$this->data['html'] = $this->request->post['html'];
		} elseif (isset($banner_info)) {
			$this->data['html'] = $banner_info['html'];
		} else {
			$this->data['html'] = '';
		}
		
		if (isset($this->request->post['start_date'])) {
			$this->data['start_date'] = $this->request->post['start_date'];
		} elseif (isset($banner_info)) {
			$this->data['start_date'] = $banner_info['start_date'];
		} else {
			$this->data['start_date'] = '';
		}
		
		if (isset($this->request->post['end_date'])) {
			$this->data['end_date'] = $this->request->post['end_date'];
		} elseif (isset($banner_info)) {
			$this->data['end_date'] = $banner_info['end_date'];
		} else {
			$this->data['end_date'] = '';
		}
		
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($banner_info)) {
			$this->data['status'] = $banner_info['status'];
		} else {
			$this->data['status'] = '1';
		}
		
		if (isset($this->request->post['group'])) {
			$this->data['group'] = $this->request->post['group'];
		} elseif (isset($banner_info)) {
			$this->data['group'] = $banner_info['group'];
		} else {
			$this->data['group'] = '';
		}
		
		$this->data['pages'][] = 'common/home';
		$this->data['pages'][] = 'product/category';
		$this->data['pages'][] = 'product/product';
		$this->data['pages'][] = 'product/manufacturer';
		$this->data['pages'][] = 'product/special';
		$this->data['pages'][] = 'product/search';
		$this->data['pages'][] = 'information/contact';
		$this->data['pages'][] = 'information/information';
		$this->data['pages'][] = 'information/links';
		$this->data['pages'][] = 'information/sitemap';
		
		if (isset($this->request->post['selected'])) {
			$this->data['selected'] = $this->request->post['selected'];
			foreach ($this->data['selected'] as $item) {
				$this->data['pages'] = $this->removeItemFromArray($this->data['pages'], $item);
			}
		} elseif (isset($banner_pages)) {
			$this->data['selected'] = $banner_pages;
			foreach ($this->data['selected'] as $item) {
				$this->data['pages'] = $this->removeItemFromArray($this->data['pages'], $item);
			}
		} else {
			$this->data['selected'] = '';
		}
		
		$this->data['banner_groups'] = $this->model_extension_banners->getBannerGroups();
		
		$this->load->helper('image');
		
		if (isset($banner_info) && $banner_info['image'] && file_exists(DIR_IMAGE . $banner_info['image'])) {
			$this->data['preview'] = image_resize($banner_info['image'], 100, 100);
		} else {
			$this->data['preview'] = image_resize('no_image.jpg', 100, 100);
		}
		
		$this->data['cancel'] = $this->url->https('extension/banners');
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->template = 'extension/banner_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/banners')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (empty($this->request->post['title'])) {
			$this->error['title'] = $this->language->get('error_title');
		}
		
		if ($this->request->post['status'] != '1' && $this->request->post['status'] != '0') {
			$this->error['status'] = $this->language->get('error_status');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/banners')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE; 
		} else {
			return FALSE;
		}
	}
	
	private function removeItemFromArray($list, $item) {
		return array_diff($list, (array)$item);
	}
	
}
?>
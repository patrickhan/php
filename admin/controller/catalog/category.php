<?php 
class ControllerCatalogCategory extends Controller { 
	private $error = array();
	public function setOn() {	
	$set = $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `group` = 'setC' AND `key` = 'setC'");				
	$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = 'setC', `key` = 'setC', `value` = 'On'");				
	$this->index();
	}	
	
	public function setOff() {
	$set = $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `group` = 'setC' AND `key` = 'setC'");				
	$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = 'setC', `key` = 'setC', `value` = 'Off'");				
	$this->index();
	}
	
	public function setDis() {
	$set = $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `group` = 'setC' AND `key` = 'setC'");				
	$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = 'setC', `key` = 'setC', `value` = 'Dis'");				
	$this->index();
	}
	public function index() {
		$this->load->language('catalog/category');

		$this->document->title = $this->language->get('heading_title');
		$fields = mysql_list_fields(DB_DATABASE, 'category');
$columns = mysql_num_fields($fields);
for ($i = 0; $i < $columns; $i++) {$field_array[] = mysql_field_name($fields, $i);}
if (!in_array('keyword', $field_array))
{
$result = mysql_query('ALTER TABLE category ADD keyword VARCHAR(25)');
}
		$this->load->model('catalog/category');
		$this->data['qmark'] = "<img src='" . HTTP_IMAGE . "miniqmark.png'>";
		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/category');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/category');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_category->addCategory($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->https('catalog/category')); 
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/category');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/category');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_category->editCategory($this->request->get['category_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->https('catalog/category'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/category');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/category');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $category_id) {
				$this->model_catalog_category->deleteCategory($category_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->https('catalog/category'));
		}

		$this->getList();
	}

	private function getList() {
   		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('catalog/category'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		$set = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `group` = 'setC' AND `key` = 'setC'");
		$alls = true;
		$dis = false;
		if ($set->rows) {	
		$this->data['set'] = $set->row['value'];	
		if ($set->row['value'] != 'On') {	
		$alls = false;	
		}
		if ($set->row['value'] == 'Dis') {	
		$dis = true;	
		}
		}	
		else {	
		$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = 'setC', `key` = 'setC', `value` = 'On'");	
		}
		$this->data['alls'] = $alls;	
		$this->data['dis'] = $dis;								
		$this->data['insert'] = $this->url->https('catalog/category/insert');
		$this->data['delete'] = $this->url->https('catalog/category/delete');
		
		$this->data['categories'] = array();
		if ($alls) {
		$results = $this->model_catalog_category->getCategories(0);
		}
		else if ($dis) {
		$results = $this->model_catalog_category->getdCategories(0);
		}
		else {
		$results = $this->model_catalog_category->geteCategories(0);
		}
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('catalog/category/update&category_id=' . $result['category_id'])
			);
					
			$this->data['categories'][] = array(
				'category_id' => $result['category_id'],
				'name'        => $result['name'],
				'sort_order'  => $result['sort_order'],
				'selected'    => isset($this->request->post['selected']) && in_array($result['category_id'], $this->request->post['selected']),
				'action'      => $action
			);
		}
		$this->data['sAlias'] = $this->url->https('catalog/category/alias');
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_add_category'] = $this->language->get('button_add_category');
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
		if (file_exists('../install/SEO.doc')) {
		$this->template = 'catalog/category_list.tpl';		}		else		{		$this->template = 'catalog/category_dlist.tpl';}
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_meta_keywords'] = $this->language->get('entry_meta_keywords');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_status'] = $this->language->get('entry_status');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
	
 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('catalog/category'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['category_id'])) {
			$this->data['action'] = $this->url->https('catalog/category/insert');
		} else {
			$this->data['action'] = $this->url->https('catalog/category/update&category_id=' . $this->request->get['category_id']);
		}
		
		$this->data['cancel'] = $this->url->https('catalog/category');

		if (isset($this->request->get['category_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$category_info = $this->model_catalog_category->getCategory($this->request->get['category_id']);
    	}
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['category_description'])) {
			$this->data['category_description'] = $this->request->post['category_description'];
		} elseif (isset($category_info)) {
			$this->data['category_description'] = $this->model_catalog_category->getCategoryDescriptions($this->request->get['category_id']);
		} else {
			$this->data['category_description'] = array();
		}

		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (isset($category_info)) {
			$this->data['keyword'] = $category_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}
		
		$this->data['categories'] = $this->model_catalog_category->getCategories(0);

		if (isset($this->request->post['parent_id'])) {
			$this->data['parent_id'] = $this->request->post['parent_id'];
		} elseif (isset($category_info)) {
			$this->data['parent_id'] = $category_info['parent_id'];
		} else {
			$this->data['parent_id'] = 0;
		}

		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (isset($category_info)) {
			$this->data['image'] = $category_info['image'];
		} else {
			$this->data['image'] = '';
		}
		
		$this->load->helper('image');

		if (isset($category_info) && $category_info['image'] && file_exists(DIR_IMAGE . $category_info['image'])) {
			$this->data['preview'] = image_resize($category_info['image'], 100, 100);
		} else {
			$this->data['preview'] = image_resize('no_image.jpg', 100, 100);
		}
		
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (isset($category_info)) {
			$this->data['sort_order'] = $category_info['sort_order'];
		} else {
			$this->data['sort_order'] = 0;
		}
		
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($category_info)) {
			$this->data['status'] = $category_info['status'];
		} else {
			$this->data['status'] = 1;
		}
		if (file_exists('../install/SEO.doc')) {
		$this->template = 'catalog/category_form.tpl';}else{$this->template = 'catalog/category_dform.tpl';}
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['category_description'] as $language_id => $value) {
			if ((strlen(utf8_decode($value['name'])) < 2) || (strlen(utf8_decode($value['name'])) > 32)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
 
		if (!$this->error) {
			return TRUE; 
		} else {
			return FALSE;
		}
	}
	
	public function makeAlias($name) {
	$name = strtolower($name);
	$name = preg_replace('/\s+/', '-', $name);
	$name = preg_replace('/-{2,}/', '-', $name);
	$name = str_replace('?', '', $name);
	$name = str_replace('&amp;', '', $name);
	$name = str_replace('&', '', $name);
	$name = str_replace('\\', '', $name);
	$name = str_replace('\'', '', $name);
	$keyword = $this->db->query("select * from url_alias where keyword='" . $this->db->escape($name) . "'");
	if ($keyword->num_rows) {
	$count = 1;
	while ($keyword->num_rows) {
	$kw = $name . "-$count";
	$keyword = $this->db->query("select * from url_alias where keyword='" . $this->db->escape($kw) . "'");
	$count++;
	}
	} else {
	$kw = $name;
	}
	return $kw;
	}
	
	public function alias() {
	$ps = $this->db->query("select * from category p left join category_description pd on (p.category_id = pd.category_id)");
	foreach ($ps->rows as $p) {
	if (!$p['keyword']) {
	$keyword = $this->makeAlias($p['name']);
	$name = $p['name'];
	$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . $p['category_id'] . "'");
	$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . $p['category_id'] . "', keyword = '" . $this->db->escape($keyword) . "'");
$this->db->query("UPDATE " . DB_PREFIX . "category SET keyword = '" . $this->db->escape($keyword) . "' WHERE category_id='" . $p['category_id'] . "'");
	}
	}
	$this->session->data['success'] = "Category Aliases Set";
	$this->redirect($this->url->https('catalog/category'));
	}
}
?>
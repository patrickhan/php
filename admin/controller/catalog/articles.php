<?php
class ControllerCatalogArticles extends Controller {
	private $error = array();
	public function setOn() {	
	$set = $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `group` = 'setA' AND `key` = 'setA'");				
	$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = 'setA', `key` = 'setA', `value` = 'On'");				
	$this->index();
	}	
	
	public function setOff() {
	$set = $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `group` = 'setA' AND `key` = 'setA'");				
	$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = 'setA', `key` = 'setA', `value` = 'Off'");				
	$this->index();
	}
	
	public function setDis() {
	$set = $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `group` = 'setA' AND `key` = 'setA'");				
	$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = 'setA', `key` = 'setA', `value` = 'Dis'");				
	$this->index();
	}
	
	public function index() {
	$fields = mysql_list_fields(DB_DATABASE, 'information');
	$columns = mysql_num_fields($fields);
	for ($i = 0; $i < $columns; $i++) {
	$field_array[] = mysql_field_name($fields, $i);
	}
	if (!in_array('keyword', $field_array)){
	$result = mysql_query('ALTER TABLE information ADD keyword VARCHAR(25)');
	}		
	$fields = mysql_list_fields(DB_DATABASE, 'information');
	$columns = mysql_num_fields($fields);for ($i = 0; $i < $columns; $i++) {
	$field_array[] = mysql_field_name($fields, $i);
	}
	if (!in_array('status', $field_array)){
	$result = mysql_query('ALTER TABLE information ADD `status` INT DEFAULT "1"');
	}
		$this->load->language('catalog/information');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/information');
		
		$this->data['qmark'] = "<img src='" . HTTP_IMAGE . "miniqmark.png'>";
		$this->getList();
	}
	
	public function insert() {
		$this->load->language('catalog/information');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/information');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_information->addInformation($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->https('catalog/articles' . $url));
		}
		
		$this->getForm();
	}
	
	public function update() {
		$this->load->language('catalog/information');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/information');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_information->editInformation($this->request->get['information_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->https('catalog/articles' . $url));
		}
		
		$this->getForm();
	}
	
	public function delete() {
		$this->load->language('catalog/information');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/information');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $information_id) {
				$this->model_catalog_information->deleteInformation($information_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->https('catalog/articles' . $url));
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
			$sort = 'id.title';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		$url = '';
			
			if (isset($this->request->get['type']))
			{
			$this->data['type'] = $this->request->get['type'];
			}
			else
			{
			$this->data['type'] = '-1';
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
			'href'      => $this->url->https('catalog/articles' . $url),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		$this->data['sAlias'] = $this->url->https('catalog/articles/alias');
		$this->data['insert'] = $this->url->https('catalog/articles/insert' . $url);
		$this->data['delete'] = $this->url->https('catalog/articles/delete' . $url);
		$this->data['url'] = $this->url->https('catalog/articles' . $url);
		
		$this->data['informations'] = array();
		
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * 30,
			'limit' => 30
		);
		$set = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `group` = 'setA' AND `key` = 'setA'");
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
		$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = 'setA', `key` = 'setA', `value` = 'On'");	
		}
		$this->data['alls'] = $alls;	
		$this->data['dis'] = $dis;	
		if ($alls) {
		$information_total = $this->model_catalog_information->getTotalArticles();
		
		$results = $this->model_catalog_information->getArticles($data); 
		} else if ($dis) {
		$information_total = $this->model_catalog_information->getdTotalArticles();
		
		$results = $this->model_catalog_information->getdArticles($data); 
		} else {
		$information_total = $this->model_catalog_information->geteTotalArticles();		
		$results = $this->model_catalog_information->geteArticles($data);}
		$this->data['show'] = true;
		
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('catalog/articles/update&information_id=' . $result['information_id'] . $url)
			);
			
			$date_modified = ($result['date_modified'] != '') ? $result['date_modified'] : $result['date_added'];
			$sitemap = $result['sitemap'];
			if ($sitemap == 0)
			{
			$sitemap = "<span style='color:red'>No</span>";
			}
			else
			{
			$sitemap = "<span style='color:green'>Yes</span>";
			}
			
			
			$location = "Articles Page";
			
			/*else if ($location == 3)
			{
			$location = "RC Module";
			}
			else if ($location == 4)
			{
			$location = "LC Module";
			}
			else if ($location == 5)
			{
			$location = "Menu Manager";
			}*/
		
			$type = "Article";
		
			$this->data['informations'][] = array(
				'information_id' => $result['information_id'],
				'short'          => $result['short'],
				'title'          => $result['title'],
				'date_modified'  => $date_modified,
				'sort_order'     => $result['sort_order'],
				'sitemap'        => $sitemap,
				'location'       => $location,
				'type'      	 => $type,
				'selected'       => isset($this->request->post['selected']) && in_array($result['information_id'], $this->request->post['selected']),
				'action'         => $action
			);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_sitemap'] = $this->language->get('column_sitemap');
		$this->data['column_location'] = $this->language->get('column_location');
		$this->data['column_type'] = $this->language->get('column_type');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');
		$this->data['column_modified'] = $this->language->get('column_modified');
		
		$this->data['button_add_information'] = $this->language->get('button_add_information');
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
		
		$url = '';
		
		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_title'] = $this->url->https('catalog/articles&sort=id.title' . $url);
		$this->data['sort_sitemap'] = $this->url->https('catalog/articles&sort=i.sitemap' . $url);
		$this->data['sort_type'] = $this->url->https('catalog/articles&sort=i.type' . $url);
		$this->data['sort_location'] = $this->url->https('catalog/articles&sort=i.location' . $url);
		$this->data['sort_date_modified'] = $this->url->https('catalog/articles&sort=i.date_modified' . $url);
		$this->data['sort_sort_order'] = $this->url->https('catalog/articles&sort=i.sort_order' . $url);
		
		$url = '';
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$pagination = new Pagination();
		$pagination->total = $information_total;
		$pagination->page = $page;
		$pagination->limit = 30; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('catalog/articles' . $url . '&page=%s');
		
		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'catalog/articles_dlist.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_title_tag'] = $this->language->get('entry_title_tag');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_meta_keywords'] = $this->language->get('entry_meta_keywords');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_location'] = $this->language->get('entry_location');
		$this->data['entry_type'] = $this->language->get('entry_type');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}
		
		if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = '';
		}
		$this->data['qmark'] = "<img src='" . HTTP_IMAGE . "miniqmark.png'>";
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('catalog/articles'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		$url = '';
			
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if ( ! isset($this->request->get['information_id'])) {
			$this->data['action'] = $this->url->https('catalog/articles/insert' . $url);
		} else {
			$this->data['action'] = $this->url->https('catalog/articles/update&information_id=' . $this->request->get['information_id'] . $url);
		}
		
		$this->data['cancel'] = $this->url->https('catalog/articles' . $url);
		
		if (isset($this->request->get['information_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$information_info = $this->model_catalog_information->getInformation($this->request->get['information_id']);
		}
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['information_description'])) {
			$this->data['information_description'] = $this->request->post['information_description'];
		} elseif (isset($this->request->get['information_id'])) {
			$this->data['information_description'] = $this->model_catalog_information->getInformationDescriptions($this->request->get['information_id']);
		} else {
			$this->data['information_description'] = array();
		}
		
		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (isset($information_info)) {
			$this->data['keyword'] = $information_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}
		
			$myFile = DIR_APPLICATION . "../robots.txt";
			$fh = fopen($myFile, 'r');
			$lh = filesize($myFile);
			if ($lh < 1) { $lh = 1; }
			$robots = fread($fh, $lh);
			fclose($fh);
			//echo $theData;
		
		if (isset($this->request->post['robots'])) {
			$this->data['robots'] = $this->request->post['robots'];
		} else {
			$this->data['robots'] = $robots;
		}
		
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (isset($information_info)) {
			$this->data['sort_order'] = $information_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}
		
		if (isset($this->request->post['sitemap'])) {
			$this->data['sitemap'] = $this->request->post['sitemap'];
		} elseif (isset($information_info)) {
			$this->data['sitemap'] = $information_info['sitemap'];
		} else {
			$this->data['sitemap'] = '0';
		}
		
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($information_info)) {
			$this->data['status'] = $information_info['status'];
		} else {
			$this->data['status'] = '1';
		}
		
		if (isset($this->request->post['location'])) {
			$this->data['location'] = $this->request->post['location'];
		} elseif (isset($information_info)) {
			$this->data['location'] = $information_info['location'];
		} else {
			$this->data['location'] = '0';
		}
		
		if (isset($this->request->post['type'])) {
			$this->data['type'] = $this->request->post['type'];
		} elseif (isset($information_info)) {
			$this->data['type'] = $information_info['type'];
		} else {
			$this->data['type'] = '0';
		}
		if (isset($this->request->get['information_id'])) {
		if ($this->request->get['information_id'] == '1')
		{
		$this->data['meta'] = true;
		}
		else
		{
		$this->data['meta'] = false;
		}
		}
		else
		{
		$this->data['meta'] = false;
		}
		$this->template = 'catalog/articles_dform.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validateForm() {
		if ( ! $this->user->hasPermission('modify', 'catalog/information')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		foreach ($this->request->post['information_description'] as $language_id => $value) {
			if ((strlen(utf8_decode($value['title'])) < 3) || (strlen(utf8_decode($value['title'])) > 255)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		
			if (strlen(utf8_decode($value['description'])) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}
		}
		
		if ( ! $this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	private function validateDelete() {
		if ( ! $this->user->hasPermission('modify', 'catalog/information')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ( ! $this->error) {
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
	$ps = $this->db->query("select * from information p left join information_description pd on (p.information_id = pd.information_id) WHERE p.location='6'");
	foreach ($ps->rows as $p) {
	if (!$p['keyword']) {
	$keyword = $this->makeAlias($p['title']);
	$name = $p['title'];
	$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=" . $p['information_id'] . "'");
	$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'information_id=" . $p['information_id'] . "', keyword = '" . $this->db->escape($keyword) . "'");
$this->db->query("UPDATE " . DB_PREFIX . "information SET keyword = '" . $this->db->escape($keyword) . "' WHERE information_id='" . $p['information_id'] . "'");
	}
	}
	$this->session->data['success'] = "Content Aliases Set";
	$this->redirect($this->url->https('catalog/articles'));
	}
}
?>
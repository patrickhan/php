<?php 
class ControllerReportExpense extends Controller { 
	private $error = array();
 
	public function index() {
		$this->load->language('report/expense');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('report/expense');
		 
		$this->getList();
	}
	
	public function insert() {
		$this->load->language('report/expense');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('report/expense');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$data = array();
			//print_r($_POST); die;
			//$this->model_report_expense->addExp(array_merge($this->request->post, $data));
			$this->db->query("INSERT INTO `" . DB_PREFIX . "expenses` SET `name`='". $this->db->escape($this->request->post['name'])."', `default`='". $this->db->escape($this->request->post['default'])."', `tax`='". $this->db->escape($this->request->post['tax'])."'");
			$this->session->data['success'] = 'Expense Added';
			
			$this->redirect($this->url->https('report/expense')); 
		}

		$this->getForm();
	}

	public function insertr() {
		$this->load->language('report/expense');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('report/expense');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$data = array();
			//print_r($_POST); die;
			//$this->model_report_expense->addExp(array_merge($this->request->post, $data));
			$month = $this->request->post['month'];
			$year = $this->request->post['year'];
			foreach ($this->request->post['amount'] as $id=>$amount) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "expenses_monthly` SET `expense_id`='". $this->db->escape($id)."', `month`='". $this->db->escape($month)."', `year`='". $this->db->escape($year)."', `amount`='". $this->db->escape($amount)."'");
			}
			$this->session->data['success'] = 'Report Generated';
			
			$this->redirect($this->url->https('report/expense')); 
		}

		$this->getForm2();
	}
	
	private function getList() {
   		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('report/expense'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['add'] = $this->url->https('report/expense/insert');
		$this->data['add2'] = $this->url->https('report/expense/insertr');
		$this->data['delete'] = $this->url->https('report/expense/delete');
		
		$this->data['links'] = array();
		$sqll = '';
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
			$filter_date_end = $this->request->get['filter_date_end'];
			}
			else
			{
			//$filter_date_start = date('Y-m-d', strtotime('-7 day'));
			$filter_date_start = date('Y-m-d', time());
			$filter_date_end = date('Y-m-d', time());
			}
			$start = explode(" ",$filter_date_start);
			$end = explode(" ",$filter_date_end);
			$m1 = date('m',strtotime($start[0]));
			$m2 = date('m',strtotime($end[0]));
			$y1 = $start[1];
			$y2 = $end[1];
			if ($y1 == $y2) {
			$sqll = "WHERE (`month` >= '$m1' AND `month` <= '$m2')";
			}
			else
			{
			//start m1y1, end m2y2
			$sqll = "WHERE (`month` >= '$m1' AND `year` = '$y1') OR (`month` <= '$m2' AND `year` = '$y2') OR (`year` > $y1 AND `year` < '$y2')";
			}
			$results = $this->db->query("select *, sum(`amount`) AS tot from expenses_monthly $sqll GROUP BY year,month");
		
		//print_r($results->rows); die;
		$linkid=0;
		foreach ($results->rows as $result) {
		$year = $result['year'];
		$month = $result['month'];
		$val = $this->db->query("select * from expenses_monthly where `year`='$year' and `month`='$month'");
		foreach ($val->rows as $v) {
		$this->data['expss'][$linkid][$v['expense_id']] = $v['amount'];
		}
		//print_r($val->rows); die;
			$action = array();
			
			$action[] = array(
				'text' => 'Edit',
				'href' => $this->url->https('report/expense/updater&year=' . $result['year'] . '&month=' . $result['month'])
			);
			/*	$action[] = array(
				'text' => 'Delete',
				'href' => $this->url->https('report/expense/updater&year=' . $result['year'] . '&month=' . $result['month'])
			);*/
			//$action[] = array(
			//	'text' => $this->language->get('text_delete'),
			//	'href' => $this->url->https('report/expense/delete&expense_id=' . $result['expense_id'])
			//);
				switch ($result['month']) {
    case 1:
        $month = "January ";
        break;
  case 2:
        $month = "February ";
        break;
  case 3:
        $month = "March ";
        break;
  case 4:
        $month = "April ";
        break;
  case 5:
        $month = "May ";
        break;
  case 6:
        $month = "June ";
        break;
  case 7:
        $month = "July ";
        break;
  case 8:
        $month = "August ";
        break;
  case 9:
        $month = "September ";
        break;
  case 10:
        $month = "October ";
        break;
  case 11:
        $month = "November ";
        break;
  case 12:
        $month = "December ";
        break;
   
}	
		$month .= $result['year'];
			$this->data['links'][] = array(
				'tot'     => $result['tot'],
				'date'     => $month,
				'year'     => $result['url'],
				'link_id'     => $linkid,
				'status'      => ($result['status'] == '1') ? 'Enabled' : 'Disabled',
				'date_posted' => $result['date_posted'],
				'date_modified' => $result['date_modified'],
				'selected'    => isset($this->request->post['selected']) && in_array($result['expense_id'], $this->request->post['selected']),
				'action'      => $action
			);
			$linkid++;
		}
		
		$exp = $this->db->query("select * from expenses");
		
		foreach ($exp->rows as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('report/expense/update&expense_id=' . $result['expense_id'])
			);
			//$action[] = array(
			//	'text' => $this->language->get('text_delete'),
			//	'href' => $this->url->https('report/expense/deleter&expense_id=' . $result['expense_id'])
			//);
					
			$this->data['exps'][] = array(
				'expense_id'     => $result['expense_id'],
				'name'     => $result['name'],
				'tax'     => $result['tax'],
				'default'     => $result['default'],
				'title'     => $result['title'],
				'url'     => $result['url'],
				'status'      => ($result['status'] == '1') ? 'Enabled' : 'Disabled',
				'date_posted' => $result['date_posted'],
				'date_modified' => $result['date_modified'],
				'selected'    => isset($this->request->post['selected']) && in_array($result['expense_id'], $this->request->post['selected']),
				'action'      => $action
			);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_url'] = $this->language->get('column_url');
		$this->data['column_date_posted'] = $this->language->get('column_date_posted');
		$this->data['column_date_modified'] = $this->language->get('column_date_modified');
		$this->data['column_action'] = $this->language->get('column_action');

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
		
		$this->template = 'report/expense_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	public function update() {
		$this->load->language('report/expense');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('report/expense');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
		//print_r($_POST); die;
			$data = array();

			//$this->model_report_expense->editLink($this->request->get['expense_id'], array_merge($this->request->post, $data));
			$this->db->query("UPDATE `" . DB_PREFIX . "expenses` SET `name`='". $this->db->escape($this->request->post['name'])."', `default`='". $this->db->escape($this->request->post['default'])."', `tax`='". $this->db->escape($this->request->post['tax'])."' WHERE `expense_id`='". $this->db->escape($this->request->get['expense_id']) . "'");
			
			$this->session->data['success'] = 'Expense Modified';
			
			$this->redirect($this->url->https('report/expense'));
		}

		$this->getForm();
	}
	
	public function updater() {
		$this->load->language('report/expense');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('report/expense');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
		//print_r($_POST); die;
			$data = array();

			//$this->model_report_expense->editLink($this->request->get['expense_id'], array_merge($this->request->post, $data));
			$month = $this->request->post['month'];
			$year = $this->request->post['year'];
			$this->db->query("DELETE FROM expenses_monthly WHERE month='$month' AND year='$year'");
			foreach ($this->request->post['amount'] as $id=>$amount) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "expenses_monthly` SET `expense_id`='". $this->db->escape($id)."', `month`='". $this->db->escape($month)."', `year`='". $this->db->escape($year)."', `amount`='". $this->db->escape($amount)."'");
			}
			$this->session->data['success'] = 'Expense Modified';
			
			$this->redirect($this->url->https('report/expense'));
		}

		$this->getForm2();
	}
	
	public function delete() {
		$this->load->language('report/expense');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('report/expense');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			//$this->model_report_expense->deleteLinks($this->request->post['selected']);
			
			$this->session->data['success'] = $this->language->get('text_links_deleted');

			$this->redirect($this->url->https('report/expense'));
		} else if (isset($this->request->get['expense_id']) && $this->validateDelete()) {
			$this->db->query("DELETE FROM expenses WHERE expense_id='" . $this->request->get['expense_id'] . "'");
			
			$this->session->data['success'] = 'Expense Deleted';

			$this->redirect($this->url->https('report/expense'));
		}

		$this->getList();
	}
	
	public function deleter() {
		$this->load->language('report/expense');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('report/expense');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			//$this->model_report_expense->deleteLinks($this->request->post['selected']);
			
			$this->session->data['success'] = $this->language->get('text_links_deleted');

			$this->redirect($this->url->https('report/expense'));
		} else if (isset($this->request->get['expense_id']) && $this->validateDelete()) {
			$this->db->query("DELETE FROM expenses WHERE expense_id='" . $this->request->get['expense_id'] . "'");
			
			$this->session->data['success'] = 'Expense Deleted';

			$this->redirect($this->url->https('report/expense'));
		}

		$this->getList();
	}
	
	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_url'] = $this->language->get('entry_url');
		$this->data['entry_description'] = $this->language->get('entry_desc');
		$this->data['entry_comments'] = $this->language->get('entry_comments');
		
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
       		'href'      => $this->url->https('report/expense'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['expense_id'])) {
			$this->data['action'] = $this->url->https('report/expense/insert');
		} else {
			$expense_info = $this->model_report_expense->getExp($this->request->get['expense_id']);
			$this->data = array_merge($this->data, $expense_info);
			$this->data['action'] = $this->url->https('report/expense/update&expense_id=' . $this->request->get['expense_id']);
		}

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (isset($expense_info)) {
			$this->data['name'] = $expense_info['name'];
		} else {
			$this->data['name'] = '';
		}
		
		if (isset($this->request->post['default'])) {
			$this->data['default'] = $this->request->post['default'];
		} elseif (isset($expense_info)) {
			$this->data['default'] = $expense_info['default'];
		} else {
			$this->data['default'] = 0;
		}
		
		if (isset($this->request->post['tax'])) {
			$this->data['tax'] = $this->request->post['tax'];
		} elseif (isset($expense_info)) {
			$this->data['tax'] = $expense_info['tax'];
		} else {
			$this->data['tax'] = 0;
		}
		
		if (isset($this->request->post['url'])) {
			$this->data['url'] = $this->request->post['url'];
		} elseif (isset($expense_info)) {
			$this->data['url'] = $expense_info['url'];
		} else {
			$this->data['url'] = '';
		}
		
		if (isset($this->request->post['comments'])) {
			$this->data['comments'] = $this->request->post['comments'];
		} elseif (isset($expense_info)) {
			$this->data['comments'] = $expense_info['comments'];
		} else {
			$this->data['comments'] = '';
		}
		
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($expense_info)) {
			$this->data['status'] = $expense_info['status'];
		} else {
			$this->data['status'] = '1';
		}
		
		if (isset($this->request->post['title'])) {
			$this->data['title'] = $this->request->post['title'];
		} elseif (isset($expense_info)) {
			$this->data['title'] = $expense_info['title'];
		} else {
			$this->data['title'] = '';
		}
		
		if (isset($this->request->post['description'])) {
			$this->data['description'] = $this->request->post['description'];
		} elseif (isset($expense_info)) {
			$this->data['description'] = $expense_info['description'];
		} else {
			$this->data['description'] = '';
		}
				
		$this->data['cancel'] = $this->url->https('report/expense');

		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->template = 'report/expense_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function getForm2() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_url'] = $this->language->get('entry_url');
		$this->data['entry_description'] = $this->language->get('entry_desc');
		$this->data['entry_comments'] = $this->language->get('entry_comments');
		
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
       		'href'      => $this->url->https('report/expense'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['year'])) {
			$this->data['action'] = $this->url->https('report/expense/insertr');
		} else {
			$this->data['action'] = $this->url->https('report/expense/updater');
		}

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (isset($expense_info)) {
			$this->data['name'] = $expense_info['name'];
		} else {
			$this->data['name'] = '';
		}
		
		if (isset($this->request->post['default'])) {
			$this->data['default'] = $this->request->post['default'];
		} elseif (isset($expense_info)) {
			$this->data['default'] = $expense_info['default'];
		} else {
			$this->data['default'] = 0;
		}
		
		if (isset($this->request->post['month'])) {
			$this->data['month'] = $this->request->post['month'];
		} elseif (isset($this->request->get['month'])) {
			$this->data['month'] = $this->request->get['month'];
		} elseif (isset($expense_info)) {
			$this->data['month'] = $expense_info['month'];
		} else {
			$this->data['month'] = 0;
		}
	
		if (isset($this->request->post['year'])) {
			$this->data['year'] = $this->request->post['year'];
		} elseif (isset($this->request->get['year'])) {
			$this->data['year'] = $this->request->get['year'];
		} elseif (isset($expense_info)) {
			$this->data['year'] = $expense_info['year'];
		} else {
			$this->data['year'] = 0;
		}
		
		if (isset($this->request->post['comments'])) {
			$this->data['comments'] = $this->request->post['comments'];
		} elseif (isset($expense_info)) {
			$this->data['comments'] = $expense_info['comments'];
		} else {
			$this->data['comments'] = '';
		}
		
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($expense_info)) {
			$this->data['status'] = $expense_info['status'];
		} else {
			$this->data['status'] = '1';
		}
		
		if (isset($this->request->post['title'])) {
			$this->data['title'] = $this->request->post['title'];
		} elseif (isset($expense_info)) {
			$this->data['title'] = $expense_info['title'];
		} else {
			$this->data['title'] = '';
		}
		
		if (isset($this->request->post['description'])) {
			$this->data['description'] = $this->request->post['description'];
		} elseif (isset($expense_info)) {
			$this->data['description'] = $expense_info['description'];
		} else {
			$this->data['description'] = '';
		}
				
		$this->data['cancel'] = $this->url->https('report/expense');

		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		if (isset($this->request->get['month'])) {
		$month11 = $this->db->escape($this->request->get['month']);
		$year11 = $this->db->escape($this->request->get['year']);
		$exp = $this->db->query("select e.expense_id, e.name, em.amount as amts from expenses e left join expenses_monthly em on (e.expense_id=em.expense_id) WHERE em.month ='$month11' AND em.year='$year11'");
		$exp2 = $this->db->query("select * from expenses WHERE expense_id NOT IN (select e.expense_id from expenses e left join expenses_monthly em on (e.expense_id=em.expense_id) WHERE em.month ='$month11' AND em.year='$year11')");
		}
		else
		{
		$exp2->rows = array();
		$exp = $this->db->query("select *, `default` as amts from expenses");
		}
		//print_r($exp); die;
		$this->data['expenses'] = $exp->rows;
		$this->data['expenses2'] = $exp2->rows;
		$this->template = 'report/expense_form2.tpl';
		$this->children = array(
			'common/header',
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validateForm() {
	
		// url is a url
		// title, desc, comments length
		// dates are dates
		// status 1 || 0
		if (!$this->user->hasPermission('modify', 'report/expense')) {
			$this->error['warning'] = $this->language->get('error_permission');
			return false;
		}
		return true;
   
  		if (empty($this->request->post['url'])) {
			$this->error['url'] = $this->language->get('error_url');
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
		if (!$this->user->hasPermission('modify', 'report/expense')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
 
		if (!$this->error) {
			return TRUE; 
		} else {
			return FALSE;
		}
	}
	
}
?>
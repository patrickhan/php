<?php
class ModelReportExpense extends Model {
	public function addExp($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "expenses SET url = '" . $this->db->escape($data['url']) . "', 
			title = '" . $this->db->escape($data['title']) . "', description = '" . $this->db->escape($data['description']) . "', 
			status = '" . $this->db->escape($data['status']) . "', comments = '" . $this->db->escape($data['comments']) . "',
			date_modified = NOW(), date_posted = NOW()");
	}
	
	public function editExp($expense_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "expenses SET url = '" . $this->db->escape($data['url']) . "', 
			title = '" . $this->db->escape($data['title']) . "', description = '" . $this->db->escape($data['description']) . "', 
		status = '" . $this->db->escape($data['status']) . "', comments = '" . $this->db->escape($data['comments']) . "',
			date_modified = NOW() WHERE expense_id = '" . $this->db->escape($expense_id) . "'");
	}
	
	public function deleteExps($selected) {
		$selected_str = '';
		
		foreach ($selected as $expense_id) {
			$selected_str .= "'$expense_id',";
		}
		$selected_str = substr($selected_str, 0, -1);
		$this->db->query("DELETE FROM " . DB_PREFIX . "expenses WHERE expense_id IN (" . $selected_str . ")");
	}
	
	public function deleteExp($expense_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "expenses WHERE expense_id = '" . $this->db->escape($expense_id) . "'");
	}
	
	public function getExp($expense_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "expenses WHERE expense_id = '" . $this->db->escape($expense_id) . "'");
		
		return $query->row;
	}
	
	public function getExps() {
		$Exp_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "expenses");
		
		foreach ($query->rows as $result) {
			$Exp_data[] = array(
				'expense_id' => $result['expense_id'],
				'title' => $result['title'],
				'url' => $result['url'],
				'status' => $result['status'],
				'date_posted' => $result['date_posted'],
				'date_modified' => $result['date_modified']
			);
		}
		
		return $Exp_data;
	}
}
?>
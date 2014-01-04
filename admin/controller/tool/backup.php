<?php 
class ControllerToolBackup extends Controller { 
	private $error = array();
	public function Zip($source, $destination){if (file_exists('backups/sitebackup.zip')) { unlink('backups/sitebackup.zip'); }    if (!extension_loaded('zip') || !file_exists($source)) {        return false;    }    $zip = new ZipArchive();    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {        return false;    }    $source = str_replace('\\', '/', realpath($source));    if (is_dir($source) === true)    {        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);        foreach ($files as $file)        {            $file = str_replace('\\', '/', $file);                        if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )                continue;            $file = realpath($file);            if (is_dir($file) === true)            {                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));            }            else if (is_file($file) === true)            {                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));            }        }    }    else if (is_file($source) === true)    {        $zip->addFromString(basename($source), file_get_contents($source));    }    return $zip->close();}
	public function index() {
		$this->load->language('tool/backup');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('tool/backup');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_tool_backup->restore(file_get_contents(@$this->request->files['import']['tmp_name']));
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->https('tool/backup'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_restore'] = $this->language->get('entry_restore');
		
		$this->data['button_backup_download'] = $this->language->get('button_backup_download');
		$this->data['button_backup_save'] = $this->language->get('button_backup_save');
		$this->data['button_restore'] = $this->language->get('button_restore');
		
		$this->data['text_last_backup'] = $this->language->get('text_last_backup');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		
		$this->data['latest_filename'] = '';
		
		if ($latest = $this->model_tool_backup->get_last_backup()) {
			$this->data['latest_filename'] = $latest['filename'];
			$this->data['latest_date_created'] = $latest['date_created'];
		}
		
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
		
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('tool/backup'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		$this->data['action'] = $this->url->https('tool/backup');
		
		$this->data['backup'] = $this->url->https('tool/backup/backup');
		$this->data['backup_save'] = $this->url->https('tool/backup/save');
		$this->data['backup_full'] = $this->url->https('tool/backup/full');
		$this->data['backup_save'] = $this->url->https('tool/backup/save');
		
		$this->template = 'tool/backup.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	public function backup() {
		if ($this->validate()) {
			$this->response->addheader('Pragma', 'public');
			$this->response->addheader('Expires', '0');
			$this->response->addheader('Content-Description', 'File Transfer');
			$this->response->addheader('Content-Type', 'application/octet-stream');
			$this->response->addheader('Content-Disposition', 'attachment; filename=backup.sql');
			$this->response->addheader('Content-Transfer-Encoding', 'binary');
			
			$this->load->model('tool/backup');
			
			$this->response->setOutput($this->model_tool_backup->backup());
		} else {
			return $this->forward('error/error_404', 'index');
		}
	}
	
	public function save() {
		if ($this->validate()) {
			$this->load->model('tool/backup');
			
			$backup = $this->model_tool_backup->backup();
			
			$backup_file = date('d-m-Y-Gisu') . ".sql";
			$fp = fopen('backups/' . $backup_file, 'w') or die("can't open file");
			fwrite($fp, $backup);
			fclose($fp);
			
			$this->model_tool_backup->record_backup($backup_file);
			
			$this->redirect($this->url->https('tool/backup'));
		} else {
			return $this->forward('error/error_404', 'index');
		}
	}
				
	public function full() {
		if ($this->validate()) {
			$this->load->model('tool/backup');
			
			$backup = $this->model_tool_backup->backup();
			
			$backup_file = date('d-m-Y-Gisu') . ".sql";
			$fp = fopen('backups/' . $backup_file, 'w') or die("can't open file");
			fwrite($fp, $backup);
			fclose($fp);
			
			$this->model_tool_backup->record_backup($backup_file);
			ini_set("memory_limit","999M");			$this->Zip(DIR_APPLICATION . '../', 'backups/sitebackup.zip');
			$this->redirect($this->url->https('tool/backup'));
		} else {
			return $this->forward('error/error_404', 'index');
		}
	}
	
	private function validate() {
		if ( ! $this->user->hasPermission('modify', 'tool/backup')) {
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
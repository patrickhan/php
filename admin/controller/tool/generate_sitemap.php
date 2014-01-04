<?php 
class ControllerToolGenerateSitemap extends Controller { 
	private $error = array();
	
	public function index() {
		$this->load->language('tool/generate_sitemap');		
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('tool/generate_sitemap');

		$this->data['heading_title'] = $this->language->get('heading_title');
		 
		$this->data['button_generate'] = $this->language->get('button_generate');
		
		$this->data['text_common'] = $this->language->get('text_common');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['output'])) {
			$this->data['output'] = $this->session->data['output'];
		
			unset($this->session->data['output']);
		} else {
			$this->data['output'] = '';
		}
		$this->data['success'] = '';

		
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home',
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=tool/generate_sitemap',
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->data['generate_sitemap'] = $this->url->https('tool/generate_sitemap/generate');
		$this->data['generate'] = $this->url->https('tool/generate_sitemap/generate');
		
		$this->load->model('tool/generate_sitemap');
			
		$this->template = 'tool/generate_sitemap.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	public function generate() {
		$this->load->language('tool/generate_sitemap');
$myFile = DIR_APPLICATION . "../robots.txt";			
		$fh = fopen($myFile, 'r');			
		$lh = filesize($myFile);			
		if ($lh < 1) 
		{ $lh = 1; }
		$robots = fread($fh, $lh);
		fclose($fh);
		if (strpos($robots,'Sitemap') !== false) {    }else{
		$fh = fopen($myFile, 'a');
		$r1text = "\n\nSitemap: " . HTTP_CATALOG . "sitemaps/sitemapproducts.xml\nSitemap: " . HTTP_CATALOG . "sitemaps/sitemapcategories.xml\nSitemap: " . HTTP_CATALOG . "sitemaps/sitemappages.xml";
		fwrite($fh, $r1text);
		fclose($fh);
		}
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('tool/generate_sitemap');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
			$this->session->data['output'] = $this->model_tool_generate_sitemap->generate();			
			
			$this->redirect($this->url->https('tool/generate_sitemap')); 
		} else {
			return $this->forward('error/error_404', 'index');
		}
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'tool/generate_sitemap')) {
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
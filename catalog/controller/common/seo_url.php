<?php
class ControllerCommonSeoUrl extends Controller {
	public function index() {
	if (isset($this->request->get['_route_']) && ($this->request->get['_route_'] == 'articles' ||  $this->request->get['_route_'] == 'articles/')){	
	$art = true;	
	}else{	
	$art = false;}		if (isset($this->request->get['_route_']) && ($this->request->get['_route_'] == 'contact' ||  $this->request->get['_route_'] == 'contact/')){		$contact = true;		}else{		$contact = false;}		if (isset($this->request->get['_route_']) && ($this->request->get['_route_'] == 'suggest-a-heart' ||  $this->request->get['_route_'] == 'suggest-a-heart/')){		$suggest = true;		}else{		$suggest = false;}		
	if ($art) {
	$this->request->get['route'] = 'information/articles';
	}else if ($contact) {	$this->request->get['route'] = 'information/contact';	}else if ($suggest) {	$this->request->get['route'] = 'information/suggest';	}else{
		if (isset($this->request->get['_route_'])) {
			$parts = explode('/', $this->request->get['_route_']);
			
			foreach ($parts as $part) {			//echo $part; die;
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'");
				
				if ($query->num_rows) {
					$url = explode('=', $query->row['query']);
					
					if ($url[0] == 'product_id') {
						$this->request->get['product_id'] = $url[1];
					}
					
					if ($url[0] == 'category_id') {
						if (!isset($this->request->get['path'])) {
							$this->request->get['path'] = $url[1];
						} else {
							$this->request->get['path'] .= '_' . $url[1];
						}
					}
					
					if ($url[0] == 'manufacturer_id') {
						$this->request->get['manufacturer_id'] = $url[1];
					}
					
					if ($url[0] == 'information_id') {
						$this->request->get['information_id'] = $url[1];
					}
				}
			}
			
			if (isset($this->request->get['product_id'])) {
				$this->request->get['route'] = 'product/product';
			} elseif (isset($this->request->get['path'])) {
				$this->request->get['route'] = 'product/category';
			} elseif (isset($this->request->get['manufacturer_id'])) {
				$this->request->get['route'] = 'product/manufacturer';
			} elseif (isset($this->request->get['information_id'])) {
				$this->request->get['route'] = 'information/information';
			}			}
			}
			
			if (isset($this->request->get['route'])) {
				return $this->forward($this->request->get['route']);
			}
		}
	}

?>
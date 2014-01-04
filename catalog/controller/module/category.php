<?php  
class ControllerModuleCategory extends Controller {
	protected $category_id = 0;
	protected $path = array();
	
	protected function index() {
		$this->language->load('module/category');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->load->model('catalog/category');
		$this->load->model('tool/seo_url');
		
		if (isset($this->request->get['path'])) {
			$this->path = explode('_', $this->request->get['path']);
			
			$this->category_id = end($this->path);
		}
		
		$this->data['category'] = $this->getCategories(0);
												
		$this->id = 'category';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/category.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/category.tpl';
		} else {
			$this->template = 'default/template/module/category.tpl';
		}
		
		$this->render();
  	}
	
	protected function getCategories($parent_id, $current_path = '') {
		$category_id = array_shift($this->path);
		
		$output = '';
		
		$results = $this->model_catalog_category->getCategories($parent_id);
		//print_r($this->session->data['template']); 
		
		if($parent_id == 0){
		
			if($this->session->data['template'] == 'softfoot' && $this->config->get('demo')){
				foreach($results as $result){
						
					if($result['category_id'] > 9 && $result['category_id'] < 12){
							$new_result[] = $result;
							//print_r($new_result);
							//echo("<br />");
					}
				}
				$results = $new_result;
			}else if($this->session->data['template'] == 'technical' && $this->config->get('demo')){
				foreach($results as $result){
						
					if($result['category_id'] < 4){
							$new_result[] = $result;
							//print_r($new_result);
							//echo("<br />");
					}
				}
				$results = $new_result;
			}else if($this->session->data['template'] == 'rainfresh' && $this->config->get('demo')){
				
				foreach($results as $result){
					
					if($result['category_id'] > 6 && $result['category_id'] < 10){
							$new_result[] = $result;
							//print_r($new_result);
							//echo("<br />");
					}
				}
				$results = $new_result;
			}else	if($this->session->data['template'] == 'mystique' && $this->config->get('demo')){
				foreach($results as $result){
					if($result['category_id'] > 3 && $result['category_id'] < 7){
							$new_result[] = $result;
							//print_r($new_result);
							//echo("<br />");
					}
				}
				$results = $new_result;
			}
		}
		
		//print_r($new_result);
		//print_r($new_result);
		
		
		if ($results) { 
			$output .= '<ul>';
    	}
		
		foreach ($results as $result) {	
			if (!$current_path) {
				$new_path = $result['category_id'];
			} else {
				$new_path = $current_path . '_' . $result['category_id'];
			}
			
			$output .= '<li>';
			
			$children = '';
			
			if ($category_id == $result['category_id']) {
				$children = $this->getCategories($result['category_id'], $new_path);
				
			}
			 
			
			if ($this->category_id == $result['category_id']) {
				$output .= '<a href="' . $this->model_tool_seo_url->rewrite($this->url->http('product/category&path=' . $new_path))  . '"><b>' . $result['name'] . '</b></a>';
			} else {
				$output .= '<a href="' . $this->model_tool_seo_url->rewrite($this->url->http('product/category&path=' . $new_path))  . '">' . $result['name'] . '</a>';
			}
			
        	$output .= $children;
        
        	$output .= '</li>'; 
		}
 
		if ($results) {
			$output .= '</ul>';
		}
		
		return $output;
	}		
}
?>
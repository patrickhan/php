<?php  
class ControllerCommonMenu extends Controller {
	protected function index() {
		
		$this->load->model('catalog/menu');
		
		$this->data['menus'] = array();
		
		foreach ($this->model_catalog_menu->getMenus() as $result) {
			$id = 'tab_menu_' . $result['menu_id'];
			$add = TRUE;
			
			if ($result['url'] == 'index.php?route=account/login') {
				if ($this->customer->isLogged()) {
					$add = FALSE;
				}
			} else if ($result['url'] == 'index.php?route=account/logout') {
				if ( ! $this->customer->isLogged()) {
					$add = FALSE;
				}
			}
			
			if ($result['status'] == '1' && $add == TRUE) {
				$this->data['menus'][] = array(
					'title' => $result['title'],
					'url'   => $result['url'],
					'id'    => $id
				);
			}
		}
		
		$this->id = 'menu';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/menu.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/menu.tpl';
		} else {
			$this->template = 'default/template/common/menu.tpl';
		}
		
		if(isset($_COOKIE["demo_switch"])){
			$this->template = $_COOKIE["demo_switch"] . '/template/common/menu.tpl';
		}
		
		$this->render();
	}
}
?>
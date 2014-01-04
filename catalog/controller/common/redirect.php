<?php
class ControllerCommonRedirect extends Controller {
	public function index() {
		$this->load->model('checkout/extension');
		if (isset($this->request->get['url'])) {
			if (isset($this->request->get['b']) && is_numeric($this->request->get['b'])) {
				$this->model_checkout_extension->recordBannerClick($this->request->get['b']);
			}
			
			$this->redirect(html_entity_decode($this->request->get['url']));
		} else {
			$this->redirect('error/not_found');
		}
	}
}
?>
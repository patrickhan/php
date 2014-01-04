<?php
class ControllerGalleryImage extends Controller {
	public function index() {
		$this->language->load('gallery/image');
		
		$this->document->breadcrumbs = array();
		$this->document->breadcrumbs[] = array(
			'href'      => HTTP_SERVER . 'index.php?route=common/home',
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->document->breadcrumbs[] = array(
			'href'      => HTTP_SERVER . 'index.php?route=gallery/album',
			'text'      => $this->language->get('text_album'),
			'separator' => $this->language->get('text_separator')
		);
		
		$this->document->title = $this->language->get('heading_title');
		
		if (isset($this->request->get['album_id'])) {
			$this->load->model('catalog/gallery');
			
			$this->data['text_sort'] = $this->language->get('text_sort');
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else { 
				$page = 1;
			}
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
			} else {
				$sort = 'sort_order';
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
			} else {
				$order = 'ASC';
			}
				
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$results = $this->model_catalog_gallery->getImagesbyAlbumID($this->request->get['album_id'],$sort, $order, ($page - 1) * 12, 12);
			$image_total = $this->model_catalog_gallery->getTotalImage($this->request->get['album_id']);
			$this->model_catalog_gallery->updateViewed($this->request->get['album_id']);
			
			if($results){
				
				$this->load->model('tool/seo_url'); 
				
				$album_info = $this->model_catalog_gallery->getAlbum($this->request->get['album_id']);
				
				$this->data['heading_title'] = $this->language->get('text_album') . ' ' . $album_info['name'];
				
				$this->document->breadcrumbs[] = array(
					'href'      => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=gallery/image&album_id=' . $this->request->get['album_id']),
					'text'      => $album_info['name'],
				'separator' => $this->language->get('text_separator')
				);
				
				$this->data['images'] = array();
				
				//$this->load->model('tool/image');
				$this->load->helper('image');
				
				foreach ($results as $result) {
					if ($result['image']) {
						$image = $result['image'];
					} else {
						$image = 'no_image.jpg';
					}
					
					$this->data['images'][] = array(
						'name'       => $result['name'],
						'thumb'      => image_resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
						'popup'      => image_resize($image, $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
						'date_added' => explode(" ", $result['date_added']),
					);
				}
				
				$url = '';
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				$this->data['sorts'] = array();
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_default'),
					'value' => 'sort_order-ASC',
					'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=gallery/image&album_id='. $this->request->get['album_id'] .'&sort=sort_order&order=ASC')
				);
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_asc'),
					'value' => 'name-ASC',
					'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=gallery/image&album_id='. $this->request->get['album_id'] .'&sort=name&order=ASC')
				);
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_desc'),
					'value' => 'name-DESC',
					'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=gallery/image&album_id='. $this->request->get['album_id'] .'&sort=name&order=DESC')
				);
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_date_added_asc'),
					'value' => 'date_added-ASC',
					'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=gallery/image&album_id='. $this->request->get['album_id'] .'&sort=date_added&order=ASC')
				);
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_date_added_desc'),
					'value' => 'date_added-DESC',
					'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=gallery/image&album_id='. $this->request->get['album_id'] .'&sort=date_added&order=DESC')
				); 
				
				$url = '';
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				$pagination = new Pagination();
				$pagination->total = $image_total;
				$pagination->page = $page;
				$pagination->limit = 12;
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=gallery/image&album_id='. $this->request->get['album_id'] . $url . '&page={page}');
				
				$this->data['pagination'] = $pagination->render();
				
				$this->data['sort'] = $sort;
				$this->data['order'] = $order;
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/gallery/image.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/gallery/image.tpl';
				} else {
					$this->template = 'default/template/gallery/image.tpl';
				}
				
				$this->children = array(
				'common/column_right',
				'common/column_left',
				'common/footer',
				'common/header'
				);
				
				$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
			}else {
				
				$this->document->title = $this->language->get('text_error');
				
				$this->data['heading_title'] = $this->language->get('text_error');
				
				$this->data['text_error'] = $this->language->get('text_error');
				
				$this->data['button_continue'] = $this->language->get('button_continue');
				
				$this->data['continue'] = HTTP_SERVER . 'index.php?route=gallery/album';
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
				} else {
					$this->template = 'default/template/error/not_found.tpl';
				}
				
				$this->children = array(
					'common/column_right',
					'common/column_left',
					'common/footer',
					'common/header'
				);
				
				$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
				
			}
			
		} else {
			
			$this->document->title = $this->language->get('text_error');
			
			$this->data['heading_title'] = $this->language->get('text_error');
			
			$this->data['text_error'] = $this->language->get('text_error');
			
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->data['continue'] = HTTP_SERVER . 'index.php?route=gallery/album';
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}
			
			$this->children = array(
				'common/column_right',
				'common/column_left',
				'common/footer',
				'common/header'
			);
			
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		}
		
	}
}
?>
<?php
class ControllerGalleryAlbum extends Controller {
	private $error = array();
	
	public function index() {
		if ($this->config->get('premium')) {
			$this->language->load('gallery/album');
			
			$this->data['text_sort'] = $this->language->get('text_sort');
			
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
			
			$this->load->model('catalog/gallery');
			$this->load->model('tool/seo_url'); 
			//$this->load->model('tool/image');
			$this->load->helper('image');
			
			
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
			
			$this->data['albums'] = array();
			
			$results = $this->model_catalog_gallery->getAlbums($sort, $order, ($page - 1) * 8, 8);
			$album_total = $this->model_catalog_gallery->getTotalAlbum();
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $result['image'];
				} else {
					$image = 'no_image.jpg';
				}
				
				$this->data['albums'][] = array(
					'name'       => $result['name'],
					'thumb'      => image_resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
					'date_added' => explode(" ",$result['date_added']),
					'href'       => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=gallery/image&album_id=' . $result['album_id']),
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
				'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=gallery/album&sort=sort_order&order=ASC')
			);
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'name-ASC',
				'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=gallery/album&sort=name&order=ASC')
			);
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'name-DESC',
				'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=gallery/album&sort=name&order=DESC')
			);
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_date_added_asc'),
				'value' => 'date_added-ASC',
				'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=gallery/album&sort=date_added&order=ASC')
			); 
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_date_added_desc'),
				'value' => 'date_added-DESC',
				'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=gallery/album&sort=date_added&order=DESC')
			); 
			
			if ($this->config->get('config_review')) {
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_viewed_desc'),
					'value' => 'viewed-DESC',
					'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=gallery/album&sort=viewed&order=DESC')
				); 
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_viewed_asc'),
					'value' => 'viewed-ASC',
					'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=gallery/album&sort=viewed&order=ASC')
				);
			}
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$pagination = new Pagination();
			$pagination->total = $album_total;
			$pagination->page = $page;
			$pagination->limit = 8;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=gallery/album' . $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/gallery/album.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/gallery/album.tpl';
			} else {
				$this->template = 'default/template/gallery/album.tpl';
			}
			
			$this->children = array(
				'common/column_right',
				'common/column_left',
				'common/footer',
				'common/header'
			);
			
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		
		} else {
			$this->language->load('error/not_found');
		
			$this->document->title = $this->language->get('text_error');
			
			$this->data['heading_title'] = $this->language->get('text_error');
		
			$this->data['text_error'] = $this->language->get('text_error');
			
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->data['continue'] = $this->url->http('common/home');
				
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}
			
			$this->children = array(
				'common/header',
				'common/footer',
				'common/column_left',
				'common/column_right'
			);
			
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		}
		
	}
}
?>
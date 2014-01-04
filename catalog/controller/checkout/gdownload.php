<?php
class ControllerCheckoutGDownload extends Controller {
	
	function index() {
		$reference = (isset($this->request->get['order_reference'])) ? $this->request->get['order_reference'] : NULL;
		
		$this->language->load('checkout/gdownload');
		$this->document->title = $this->language->get('heading_title');
		
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		); 
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('checkout/gdownload'),
			'text'      => $this->language->get('text_downloads'),
			'separator' => $this->language->get('text_separator')
		);
		
		$this->load->model('checkout/gdownload');
		$download_total = $this->model_checkout_gdownload->getTotalDownloads($reference);
		
		if ($download_total) {
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_name'] = $this->language->get('text_name');
			$this->data['text_remaining'] = $this->language->get('text_remaining');
			$this->data['text_size'] = $this->language->get('text_size');
			$this->data['text_download'] = $this->language->get('text_download');
			
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}
			
			$this->data['downloads'] = array();
			
			$results = $this->model_checkout_gdownload->getDownloads($reference,($page - 1) * 10, 10);
			
			foreach ($results as $result) {
				if (file_exists(DIR_DOWNLOAD . $result['filename'])) {
					$size = filesize(DIR_DOWNLOAD . $result['filename']);
					
					$i = 0;
					
					$suffix = array(
						'B',
						'KB',
						'MB',
						'GB',
						'TB',
						'PB',
						'EB',
						'ZB',
						'YB'
					);
					
					while (($size / 1024) > 1) {
						$size = $size / 1024;
						$i++;
					}
					
					$this->data['downloads'][] = array(
						'name'       => $result['name'],
						'remaining'  => $result['remaining'],
						'size'       => round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i],
						'href'       => $this->url->https('checkout/gdownload/download&order_reference='.$result['order_reference'].'&product_id='.$result['product_id'].'&download_id='.$result['download_id'])
					);
				}
			}
		
			$pagination = new Pagination();
			$pagination->total = $download_total;
			$pagination->page = $page;
			$pagination->limit = 10; 
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->http('checkout/gdownload&page=%s');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['continue'] = $this->url->https('common/home');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/gdownload.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/checkout/gdownload.tpl';
			} else {
				$this->template = 'default/template/checkout/gdownload.tpl';
			}
			
			$this->children = array(
				'common/header',
				'common/footer',
				'common/column_left',
				'common/column_right'
			);
			
			$view = $this->render(TRUE);
			$this->response->setOutput($view, $this->config->get('config_compression'));
		} else {
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_error'] = $this->language->get('text_error');
			
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->data['continue'] = $this->url->https('common/home');
			
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
	
	
	function download() {
		$this->load->model('checkout/gdownload');
		
		if (isset($this->request->get['order_reference'])) {
			$order_reference = $this->request->get['order_reference'];
		} else {
			$order_reference = 0;
		}
		
		if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$product_id = 0;
		}
		
		if (isset($this->request->get['download_id'])) {
			$download_id = $this->request->get['download_id'];
		} else {
			$download_id = 0;
		}
		
		$download_info = $this->model_checkout_gdownload->getDownload($order_reference,$product_id,$download_id);
		if ($download_info) {
			$file = DIR_DOWNLOAD . $download_info['filename'];
			$mask = basename($download_info['mask']);
			$mime = 'application/octet-stream';
			$encoding = 'binary';
			
			if ( ! headers_sent()) {
				header('Pragma: public');
				header('Expires: 0');
				header('Content-Description: File Transfer');
				header('Content-Type: ' . $mime);
				header('Content-Transfer-Encoding: ' . $encoding);
				header('Content-Disposition: attachment; filename=' . ($mask ? $mask : basename($file)));
				header('Content-Length: ' . filesize($file));
			
				if (file_exists($file)) {
					$file = readfile($file, 'rb');
				
					print($file);
				} else {
					exit('Error: Could not find file ' . $file . '!');
				}
			} else {
				exit('Error: Headers already sent out!');
			}
			$this->model_checkout_gdownload->updateRemaining($order_reference,$product_id,$download_id);
			$this->redirect($this->url->https('checkout/gdownload&order_reference='.$order_reference));
		} else {
			$this->redirect($this->url->https('checkout/gdownload&order_reference='.$order_reference));
		}
		
	}
}
?>
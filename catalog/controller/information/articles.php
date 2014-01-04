<?php
class ControllerInformationArticles extends Controller {
	function closetags($html) {

  #put all opened tags into an array

  preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);

  $openedtags = $result[1];   #put all closed tags into an array

  preg_match_all('#</([a-z]+)>#iU', $html, $result);

  $closedtags = $result[1];

  $len_opened = count($openedtags);

  # all tags are closed

  if (count($closedtags) == $len_opened) {

    return $html;

  }

  $openedtags = array_reverse($openedtags);

  # close tags

  for ($i=0; $i < $len_opened; $i++) {

    if (!in_array($openedtags[$i], $closedtags)){

      $html .= '</'.$openedtags[$i].'>';

    } else {

      unset($closedtags[array_search($openedtags[$i], $closedtags)]);    }

  }  
  return $html;
  }  
  public function set() {
  $ii = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.location='6'");
		//echo $ii->num_rows; die;
		if (!$ii->num_rows) {
		$desc = "There are no articles currently on the site";
		}
		else
		{
		$desc = "";
		$this->load->model('catalog/footer');
		$this->load->model('catalog/information');
		$num = 0;
		foreach ($ii->rows as $rr) {
		$num++;
		//echo "<pre>"; print_r($rr); echo "</pre>"; 
		$dc = $rr['description'];
		//$tt = $rr['title'];
		$idd = $rr['information_id'];
		
		//echo $idd; die;
		//echo $this->url->http('information/information&information_id=' . $idd); die;
		//$urls = $this->urls($this->url->http('information/information&information_id=' . $idd));
		//echo $urls; die;
		//echo $this->model_tool_seo_url->rewrite($urls); die;
		$pos = strpos($dc, '. ', 500);
		$ds = substr($dc, 0, $pos);
		$dr = $this->closetags($ds);
		$this->db->query("UPDATE information_description SET `short`='$dr' WHERE information_id='$idd'");
		//$desc .= "<div id='spacerLine'><a href='" . $urls . "' style='text-decoration:none;'><h1 style='border:0; margin-left: -5px;'>$tt</h1></a><br />$dc<br><div style='float:right'><a href='" . $urls . "'>More</a></div></div><br>";
		//if ($num=='3') {
		//echo $desc; die;
		//}
		}
		//echo $num; die;
		//die;
		}
  }
  public function urls($link) {
		if ($this->config->get('config_seo_url')) {
			$url_data = parse_url(str_replace('&amp;', '&', $link));
			
			$url = ''; 
			
			$data = array();
			
			parse_str($url_data['query'], $data);
			
			foreach ($data as $key => $value) {
				if (($key == 'product_id') || ($key == 'manufacturer_id') || ($key == 'information_id')) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
					
					if ($query->num_rows) {
						$url .= '/' . $query->row['keyword'];
						
						unset($data[$key]);
					}
				} elseif ($key == 'path') {
					$categories = explode('_', $value);
					
					foreach ($categories as $category) {
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$category . "'");
						
						if ($query->num_rows) {
							$url .= '/' . $query->row['keyword'];
						}
					}
					
					unset($data[$key]);
				}
			}
			
			if ($url) {
				unset($data['route']);
				
				$query = '';
				
				if ($data) {
					foreach ($data as $key => $value) {
						$query .= '&' . $key . '=' . $value;
					}
					
					if ($query) {
						$query = '?' . str_replace('&amp;', '&', trim($query, '&'));
					}
				}
				
				return $url_data['scheme'] . '://' . $url_data['host'] . (isset($url_data['port']) ? ':' . $url_data['port'] : '') . str_replace('/index.php', '', $url_data['path']) . $url . $query;
			} else {
				return $link;
			}
		} else {
			return $link;
		}
	}
	public function index() {
		$this->language->load('information/information');
		
		$this->load->model('catalog/information');
		
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->http('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$ii = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.location='6'");
		//echo $ii->num_rows; die;
		if (!$ii->num_rows) {
		$desc = "There are no articles currently on the site";
		}
		else
		{
		$desc = "";
		$this->load->model('catalog/footer');
		$this->load->model('catalog/information');
		$num = 0;
		foreach ($ii->rows as $rr) {
		$num++;
		//echo "<pre>"; print_r($rr); echo "</pre>"; 
		$dc = $rr['short'];
		$tt = $rr['title'];
		$idd = $rr['information_id'];
		//echo $idd; die;
		//echo $this->url->http('information/information&information_id=' . $idd); die;
		$urls = $this->urls($this->url->http('information/information&information_id=' . $idd));
		//echo $urls; die;
		//echo $this->model_tool_seo_url->rewrite($urls); die;
		//$pos = strpos($dc, '. ', 500);
		//$ds = substr($dc, 0, $pos);
		//$dr = $this->closetags($ds);
		$desc .= "<div id='spacerLine'><a href='" . $urls . "' style='text-decoration:none;'><h1 style='border:0; margin-left: -5px;'>$tt</h1></a><br />$dc<br><div style='float:right'><a href='" . $urls . "'>More</a></div></div><br>";
		//if ($num=='3') {
		//echo $desc; die;
		//}
		}
		//echo $num; die;
		//die;
		}
		//print_r($ii->rows);
		if (true) {
			$this->document->title = 'Articles';
			
			$this->document->breadcrumbs[] = array(
				'href'      => $this->url->http('information/articles'),
				'text'      => 'Articles',
				'separator' => $this->language->get('text_separator')
			);
			
			$this->data['heading_title'] = 'Articles';
			
			//$this->document->description = $information_info['meta_description'];
			
			//$this->document->keywords = $information_info['meta_keywords'];
			
			$this->data['button_continue'] = $this->language->get('button_continue');
			//echo $desc; die;
			$this->data['description'] = html_entity_decode($desc);
			
			$this->data['continue'] = $this->url->http('common/home');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/articles.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/information/articles.tpl';
			} else {
				$this->template = 'default/template/information/articles.tpl';
			}
			
			$this->children = array(
				'common/header',
				'common/footer',
				'common/column_left',
				'common/column_right'
			);
			
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		} else {
			$this->document->breadcrumbs[] = array(
				'href'      => $this->url->http('information/information&information_id=' . $this->request->get['information_id']),
				'text'      => $this->language->get('text_error'),
				'separator' => $this->language->get('text_separator')
			);
			
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
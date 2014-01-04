<?php
class ControllerExtensionImageDelete extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('extension/image_delete');
		$this->document->title = $this->language->get('heading_title');
		$this->getForm();
		$varpost = $_POST;
		$files = $_FILES;
		if (isset($this->request->get['sub']))
		{
		if ($subfolder != "/")
		{
			$subfolder .= $this->request->get['sub'];
			$subfolder .= "/";
		}
		else
		{
			$subfolder = "/";
			$subfolder .= $this->request->get['sub'];
			$subfolder .= "/";
		}
		}
		else {
			$subfolder ="/";
			}
			$this->data['subfolder'] = $subfolder;
			
			

			if (isset($varpost['Upload']))
		{
			$this->uploadImages($subfolder, $varpost, $files);
		}
		if (isset($varpost['uploadsNeeded']))
		{
		$this->makeUploads($subfolder, $varpost);
		}
		if (isset($_POST['Delete']))
{
				$this->deleteImages($subfolder, $varpost);
}
		$uploadForm = "<form name='form1' method='post' action='index.php?route=extension/image_delete&sub=$subfolder'>How many files would you like to upload? (Max = 99).
    <input name='uploadsNeeded' type='text' id='uploadsNeeded' maxlength='2' />
    <input type='submit' name='Submit' value='Submit' />
</form>";
		
		$this->data['uploadForm'] = $uploadForm;
		
		$this->getImages($subfolder);
	}
	
	private function makeUploads($subfolder, $varpost)
	{
	$uploadBoxes = "<form name='form4' enctype='multipart/form-data' method='post' action='index.php?route=extension/image_delete&sub=$subfolder'>";
  $uploadsNeeded = $varpost['uploadsNeeded'];
  for($i=0; $i < $uploadsNeeded; $i++){
    $uploadBoxes .= "<input name='uploadFile$i' type='file' id='uploadFile$i' />";
	}
  $uploadBoxes .= "<input name='uploadsNeeded' type='hidden' value='$uploadsNeeded' /> <br>
    <input type='submit' name='Upload' value='Upload' /><br><br></form>";
	$this->data['uploadBoxes'] = $uploadBoxes;
	}
	
	private function uploadImages($subfolder, $varpost, $files)
	{
		$homepath = "../image/data";
		$path = "../image/data";
		$path .= $subfolder;
		$uploadsNeeded = $varpost['uploadsNeeded'];
		
		$allowed_types=array(
    'image/gif',
    'image/jpeg',
    'image/png',
	'image/bmp',
);
		for($i = 0; $i < $uploadsNeeded; $i++){
		
		if (in_array($_FILES["uploadFile$i"]["type"], $allowed_types) && ($_FILES["uploadFile$i"]["size"] < 50000000))
        {
			$file_name = $files['uploadFile'. $i]['name'];
			// strip file_name of slashes
			$file_name = stripslashes($file_name);
			$file_name = str_replace("'","",$file_name);
			//$path .= $file_name;
			$copy = copy($files['uploadFile'. $i]['tmp_name'],"$path".$file_name);
			 // prompt if successfully copied
			 if($copy){
			 echo "$file_name | uploaded sucessfully!<br>";
			 }else{
			 echo "$file_name | could not be uploaded!<br>";
			 }
	 }
	 else
        {
        //echo "Error: Image $i must be either JPEG, GIF, or PNG and less than 50000 kb.<br />";
        }
}
	}
	
	private function getImages($subfolder)
	{
	//$path = "du21.dns77.com/~dev02/jsicart_templates2/image/data";
	$homepath = "../image/data";
	$path = "../image/data";
	$count=0;
	$path .= $subfolder;
	$dir_handle = @opendir($path) or die("Unable to open folder");
	//$code = "";
	$code = "<table border='3' width='100%'>";//bordercolor='D887CE' bgcolor='9b30ff'
	$counter=0;
	$url = "index.php?route=extension/image_delete";
	$folders="<a href='$url'>Main</a><br>
	Subfolders:";
	//echo getcwd();
	//if ($subfolder!="/" && getcwd()!="$path")
	//{
	//$folders .= " <a href='$url&sub=$subfolder..'>Back</a>";
	//}
	while (false !== ($file = readdir($dir_handle))) {
	if($file == "index.php")
	//$count++;
	continue;
	if($file == "index.htm")
	//$count++;
	continue;
	if($file == "index.html")
	//$count++;
	continue;
	if($file == ".")
	//$count++;
	continue;
	if($file == "..")
	//$count++;
	continue;
	
	if (is_dir("$path$file"))
		{
			$folders .= "&nbsp<a href='$url&sub=$subfolder$file'>$file</a>";
		}
	elseif (getimagesize("$path$file"))
	{
	if ($counter==0)
	{
	$code .= "<tr>";
	}
	$val = urlencode("$path$file");
	$code .= "<td height='90px' width='90px'><input type=CHECKBOX name=$count value=$val><img src='$path$file' alt='$path$file' height='90px' width='85px'></td>";
	$counter++;
	if ($counter==7)
	{
	$code .= "</tr>";
	$counter=0;
	}
	}
		$count++;
	}
	closedir($dir_handle);
	if ($counter!=0)
	{
	$code .= "</tr>";
	$counter=0;
	}
	$code .= "</table>";
	$this->data['folders'] = $folders;
	$this->data['code'] = $code;
	
	$this->template = 'extension/image_delete.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	private function deleteImages($subfolder, $varpost)
	{
	$path = "../image/data$subfolder";
	$count = 0;
	$dir_handle = @opendir($path) or die("Unable to open folder");
	while (false !== ($file = readdir($dir_handle))) {

		if($file == "index.php")
		continue;
		if($file == "index.htm")
		continue;
		if($file == "index.html")
		continue;
		if($file == ".")
		continue;
		if($file == "..")
		continue;
		if (getimagesize("$path$file"))
		{
		   $checkbox = urldecode($varpost[$count]);
		   $location = "$path$file";
		   if($checkbox) { //checkbox is selected
				  //Delete the file
				  if(!unlink($checkbox)) die("Failed to delete file");
			}
		}
		$count++;
		}
	}
	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['error_warning'] = '';
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		}
		
		$this->data['error_title'] = '';
 		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		}
		
		$this->data['error_url'] = '';
 		if (isset($this->error['url'])) {
			$this->data['error_url'] = $this->error['url'];
		}
		
		$this->data['error_status'] = '';
 		if (isset($this->error['status'])) {
			$this->data['error_status'] = $this->error['status'];
		}
		
		$this->document->breadcrumbs = array();
	
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('extension/image_display'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
}
}
?>
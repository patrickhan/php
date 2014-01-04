<?php

function error_handler_for_export($errno, $errstr, $errfile, $errline) {
	$config =& Registry::get('config');
	$log =& Registry::get('log');
	
	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$errors = "Notice";
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$errors = "Warning";
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$errors = "Fatal Error";
			break;
		default:
			$errors = "Unknown";
			break;
	}
	
	if (($errors=='Warning') || ($errors=='Unknown')) {
		return TRUE;
	}
	
	if ($config->get('config_error_display')) {
		echo '<b>' . $errors . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
	}
	
	if ($config->get('config_error_log')) {
		$log->write('PHP ' . $errors . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	}
	
	return TRUE;
}

class ModelToolExport extends Model {

	function clean( &$str, $allowBlanks = FALSE ) {
		$result = "";
		$n = strlen( $str );
		for ($m=0; $m<$n; $m++) {
			$ch = substr( $str, $m, 1 );
			if (($ch==" ") && (!$allowBlanks) || ($ch=="\n") || ($ch=="\r") || ($ch=="\t") || ($ch=="\0") || ($ch=="\x0B")) {
				continue;
			}
			$result .= $ch;
		}
		return $result;
	}
	
	function import( &$database, $sql ) {
		foreach (explode(";\n", $sql) as $sql) {
			$sql = trim($sql);
			if ($sql) {
				$database->query($sql);
			}
		}
	}
	
	protected function getDefaultLanguageId( &$database ) {
		$code = $this->config->get('config_admin_language');
		$sql = "SELECT language_id FROM `".DB_PREFIX."language` WHERE code = '$code'";
		$result = $database->query( $sql );
		$result = $database->query( $sql );
		$languageId = 1;
		if ($result->rows) {
			foreach ($result->rows as $row) {
				$languageId = $row['language_id'];
				break;
			}
		}
		return $languageId;
	}
	
	function storeManufacturersIntoDatabase( &$database, &$products, &$manufacturerIds ) {
		// find all manufacturers already stored in the database
		$sql = "SELECT `manufacturer_id`, `name` FROM `".DB_PREFIX."manufacturer`;";
		$result = $database->query( $sql );
		if ($result->rows) {
			foreach ($result->rows as $row) {
				$manufacturerId = $row['manufacturer_id'];
				$name = $row['name'];
				if (!isset($manufacturerIds[$name])) {
					$manufacturerIds[$name] = $manufacturerId;
				}
			}
		}
		
		// add newly introduced manufacturers to the database
		$maxManufacturerId=0;
		foreach ($manufacturerIds as $manufacturerId) {
			$maxManufacturerId = max( $maxManufacturerId, $manufacturerId );
		}
		$sql = "INSERT INTO `".DB_PREFIX."manufacturer` (`manufacturer_id`, `name`, `image`, `sort_order`) VALUES "; 
		$k = strlen( $sql );
		$first = TRUE;
		foreach ($products as $product) {
			$manufacturerName = $product[6];
			if ($manufacturerName=="") {
				continue;
			}
			if (!isset($manufacturerIds[$manufacturerName])) {
				$maxManufacturerId += 1;
				$manufacturerId = $maxManufacturerId;
				$manufacturerIds[$manufacturerName] = $manufacturerId;
				$sql .= ($first) ? "\n" : ",\n";
				$first = FALSE;
				$sql .= "($manufacturerId, '$manufacturerName', '', 0)";
			}
		}
		$sql .= ";\n";
		if (strlen( $sql ) > $k+2) {
			$database->query( $sql );
		}
		return TRUE;
	}
	
	function storeWeightClassesIntoDatabase( &$database, &$products, &$weightClassIds ) {
		// find the default language id
		$languageId = $this->getDefaultLanguageId($database);
		
		// find all weight classes already stored in the database
		$sql = "SELECT `weight_class_id`, `unit` FROM `".DB_PREFIX."weight_class` WHERE `language_id`=$languageId;";
		$result = $database->query( $sql );
		if ($result->rows) {
			foreach ($result->rows as $row) {
				$weightClassId = $row['weight_class_id'];
				$unit = $row['unit'];
				if (!isset($weightClassIds[$unit])) {
					$weightClassIds[$unit] = $weightClassId;
				}
			}
		}
		
		// add newly introduced weight classes to the database
		$maxWeightClassId=0;
		foreach ($weightClassIds as $weightClassId) {
			$maxWeightClassId = max( $maxWeightClassId, $weightClassId );
		}
		$sql = "INSERT INTO `".DB_PREFIX."weight_class` (`weight_class_id`, `unit`, `language_id`, `title`) VALUES "; 
		$k = strlen( $sql );
		$first = TRUE;
		foreach ($products as $product) {
			$unit = $product[16];
			if ($unit=="") {
				continue;
			}
			if (!isset($weightClassIds[$unit])) {
				$maxWeightClassId += 1;
				$weightClassId = $maxWeightClassId;
				$weightClassIds[$unit] = $weightClassId;
				$sql .= ($first) ? "\n" : ",\n";
				$first = FALSE;
				$sql .= "($weightClassId, '$unit', $languageId, '')";
			}
		}
		$sql .= ";\n";
		if (strlen( $sql ) > $k+2) {
			$database->query( $sql );
		}
		return TRUE;
	}
	
	function storeMeasurementClassesIntoDatabase( &$database, &$products, &$measurementClassIds ) {
		// find the default language id
		$languageId = $this->getDefaultLanguageId($database);
		
		// find all measurement classes already stored in the database
		$sql = "SELECT `measurement_class_id`, `unit` FROM `".DB_PREFIX."measurement_class` WHERE `language_id`=$languageId;";
		$result = $database->query( $sql );
		if ($result->rows) {
			foreach ($result->rows as $row) {
				$measurementClassId = $row['measurement_class_id'];
				$unit = $row['unit'];
				if (!isset($measurementClassIds[$unit])) {
					$measurementClassIds[$unit] = $measurementClassId;
				}
			}
		}
		
		// add newly introduced measurement classes to the database
		$maxMeasurementClassId=0;
		foreach ($measurementClassIds as $measurementClassId) {
			$maxMeasurementClassId = max( $maxMeasurementClassId, $measurementClassId );
		}
		$sql = "INSERT INTO `".DB_PREFIX."measurement_class` (`measurement_class_id`, `unit`, `language_id`, `title`) VALUES "; 
		$k = strlen( $sql );
		$first = TRUE;
		foreach ($products as $product) {
			$unit = $product[30];
			if ($unit=="") {
				continue;
			}
			if (!isset($measurementClassIds[$unit])) {
				$maxMeasurementClassId += 1;
				$measurementClassId = $maxMeasurementClassId;
				$measurementClassIds[$unit] = $measurementClassId;
				$sql .= ($first) ? "\n" : ",\n";
				$first = FALSE;
				$sql .= "($measurementClassId, '$unit', $languageId, '')";
			}
		}
		$sql .= ";\n";
		if (strlen( $sql ) > $k+2) {
			$database->query( $sql );
		}
		return TRUE;
	}
	
	function storeProductsIntoDatabase( &$database, &$products ) {
		// find the default language id
		$languageId = $this->getDefaultLanguageId($database);
		
		// start transaction, remove products
		$sql = "START TRANSACTION;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."product`;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."product_description` WHERE language_id=$languageId;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."product_to_category`;\n";
		$this->import( $database, $sql );
		
		// store or update manufacturers
		$manufacturerIds = array();
		$ok = $this->storeManufacturersIntoDatabase( $database, $products, $manufacturerIds );
		if (!$ok) {
			$database->query( 'ROLLBACK;' );
			return FALSE;
		}
		
		// store or update weight classes
		$weightClassIds = array();
		$ok = $this->storeWeightClassesIntoDatabase( $database, $products, $weightClassIds );
		if (!$ok) {
			$database->query( 'ROLLBACK;' );
			return FALSE;
		}
		
		// store or update measurement classes
		$measurementClassIds = array();
		$ok = $this->storeMeasurementClassesIntoDatabase( $database, $products, $measurementClassIds );
		if (!$ok) {
			$database->query( 'ROLLBACK;' );
			return FALSE;
		}
		
		// generate and execute SQL for storing the products
		foreach ($products as $product) {
			$productId = $product[0];
			$productName = addslashes($product[1]);
			$categories = $product[2];
			$quantity = $product[3];
			$model = addslashes($product[5]);
			$manufacturerName = $product[6];
			$manufacturerId = ($manufacturerName=="") ? 0 : $manufacturerIds[$manufacturerName];
			$imageName = $product[7];
			$shipping = $product[9];
			$shipping = ((strtoupper($shipping)=="YES") || (strtoupper($shipping)=="Y")) ? 1 : 0;
			$price = trim($product[10]);
			$dateAdded = $product[12];
			$dateModified = $product[13];
			$dateAvailable = $product[14];
			$weight = ($product[15]=="") ? 0 : $product[15];
			$unit = $product[16];
			$weightClassId = ($unit=="") ? 0 : $weightClassIds[$unit];
			$status = $product[17];
			$status = ((strtoupper($status)=="TRUE") || (strtoupper($status)=="YES") || (strtoupper($status)=="ENABLED")) ? 1 : 0;
			$taxClassId = $product[20];
			$viewed = $product[21];
			$productDescription = addslashes($product[23]);
			$stockStatusId = $product[24];
			$meta = $product[25];
			$length = $product[26];
			$width = $product[27];
			$height = $product[28];
			$keyword = $product[29];
			$measurementUnit = $product[30];
			$measurementClassId = ($measurementUnit=="") ? 0 : $measurementClassIds[$measurementUnit];
			$sku = $product[31];
			$location = $product[32];
			$sql  = "INSERT INTO `".DB_PREFIX."product` (`product_id`,`quantity`,`sku`,`location`,";
			$sql .= "`stock_status_id`,`model`,`manufacturer_id`,`image`,`shipping`,`price`,`date_added`,`date_modified`,`date_available`,`weight`,`weight_class_id`,`status`,";
			$sql .= "`tax_class_id`,`viewed`,`length`,`width`,`height`,`measurement_class_id`) VALUES ";
			$sql .= "($productId,$quantity,'$sku','$location',";
			$sql .= "$stockStatusId,'$model',$manufacturerId,'$imageName',$shipping,$price,";
			$sql .= ($dateAdded=='NOW()') ? "$dateAdded," : "'$dateAdded',";
			$sql .= ($dateModified=='NOW()') ? "$dateModified," : "'$dateModified',";
			$sql .= ($dateAvailable=='NOW()') ? "$dateAvailable," : "'$dateAvailable',";
			$sql .= "$weight,$weightClassId,$status,";
			$sql .= "$taxClassId,$viewed,$length,$width,$height,'$measurementClassId');";
			$sql2 = "INSERT INTO `".DB_PREFIX."product_description` (`product_id`,`language_id`,`name`,`description`,`meta_description`) VALUES ";
			$sql2 .= "($productId,$languageId,'$productName','$productDescription','$meta');";
			$database->query($sql);
			$database->query($sql2);
			if (count($categories) > 0) {
				$sql = "INSERT INTO `".DB_PREFIX."product_to_category` (`product_id`,`category_id`) VALUES ";
				$first = TRUE;
				foreach ($categories as $categoryId) {
					$sql .= ($first) ? "\n" : ",\n";
					$first = FALSE;
					$sql .= "($productId,$categoryId)";
				}
				$sql .= ";";
				$database->query($sql);
			}
			if ($keyword) {
				$sql3 = "DELETE FROM `".DB_PREFIX."url_alias` WHERE `query`='product_id=$productId';";
				$sql4 = "INSERT INTO `".DB_PREFIX."url_alias` (`query`,`keyword`) VALUES ('product_id=$productId','$keyword');";
				$database->query($sql3);
				$database->query($sql4);
			}
		}
		
		// final commit
		$database->query("COMMIT;");
		return TRUE;
	}
	
	protected function detect_encoding( $str ) {
		// auto detect the character encoding of a string
		// du21 does not have mb_, if your server does you can use the line below and coment out the rest of this function // elmatto
		//return mb_detect_encoding( $str, 'UTF-8,ISO-8859-15,ISO-8859-1,cp1251,KOI8-R' );
		static $list = array('utf-8', 'ISO-8859-15', 'ISO-8859-1', 'cp1251', 'KOI8-R');
		$sample = '';
		$string = '';
		
		foreach ($list as $item) {
			$sample = iconv($item, $item, $string);
		if (md5($sample) == md5($string))
			return $item;
		}
		return null;
	}
	
	function uploadProducts( &$reader, &$database ) {
		// find the default language id
		$languageId = $this->getDefaultLanguageId($database);
		
		$data = $reader->sheets[1];
		$products = array();
		$product = array();
		$isFirstRow = TRUE;
		foreach ($data['cells'] as $row) {
			if ($isFirstRow) {
				$isFirstRow = FALSE;
				continue;
			}
			$productId = trim(isset($row[1]) ? $row[1] : "");
			if ($productId=="") {
				continue;
			}
			$name = isset($row[2]) ? $row[2] : "";
			$name = htmlentities( $name, ENT_QUOTES, $this->detect_encoding($name) );
			$categories = isset($row[3]) ? $row[3] : "";
			$sku = isset($row[4]) ? $row[4] : "0";
			$location = isset($row[5]) ? $row[5] : "0";
			$quantity = isset($row[6]) ? $row[6] : "0";
			$model = isset($row[7]) ? $row[7] : "";
			$manufacturer = isset($row[8]) ? $row[8] : "";
			$imageName = isset($row[9]) ? $row[9] : "";
			$shipping = isset($row[10]) ? $row[10] : "yes";
			$price = isset($row[11]) ? $row[11] : "0.00";
			$dateAdded = (isset($row[12]) && (is_string($row[12])) && (strlen($row[12])>0)) ? $row[12] : "NOW()";
			$dateModified = (isset($row[13]) && (is_string($row[13])) && (strlen($row[13])>0)) ? $row[13] : "NOW()";
			$dateAvailable = (isset($row[14]) && (is_string($row[14])) && (strlen($row[14])>0)) ? $row[14] : "NOW()";
			$weight = isset($row[15]) ? $row[15] : "";
			$unit = isset($row[16]) ? $row[16] : "";
			$length = isset($row[17]) ? $row[17] : "";
			$width = isset($row[18]) ? $row[18] : "";
			$height = isset($row[19]) ? $row[19] : "";
			$measurementUnit = isset($row[20]) ? $row[20] : "";
			$status = isset($row[21]) ? $row[21] : "false";
			$taxClassId = isset($row[22]) ? $row[22] : "0";
			$viewed = isset($row[23]) ? $row[23] : "0";
			$langId = isset($row[24]) ? $row[24] : "1";
			if ($langId!=$languageId) {
				continue;
			}
			$keyword = isset($row[25]) ? $row[25] : "";
			$description = isset($row[26]) ? $row[26] : "";
			$description = htmlentities( $description, ENT_QUOTES, $this->detect_encoding($description) );
			$meta = isset($row[27]) ? $row[27] : "";
			$meta = htmlentities( $meta, ENT_QUOTES, $this->detect_encoding($meta) );
			$additionalImageNames = isset($row[28]) ? $row[28] : "";
			$stockStatusId = isset($row[29]) ? $row[29] : "";
			$product = array();
			$product[0] = $productId;
			$product[1] = $name;
			$categories = trim( $this->clean($categories, FALSE) );
			$product[2] = ($categories=="") ? array() : explode( ",", $categories );
			if ($product[2]===FALSE) {
				$product[2] = array();
			}
			$product[3] = $quantity;
			$product[5] = $model;
			$product[6] = $manufacturer;
			$product[7] = $imageName;
			$product[9] = $shipping;
			$product[10] = $price;
			$product[12] = $dateAdded;
			$product[13] = $dateModified;
			$product[14] = $dateAvailable;
			$product[15] = $weight;
			$product[16] = $unit;
			$product[17] = $status;
			$product[20] = $taxClassId;
			$product[21] = $viewed;
			$product[22] = $languageId;
			$product[23] = $description;
			$product[24] = $stockStatusId;
			$product[25] = $meta;
			$product[26] = $length;
			$product[27] = $width;
			$product[28] = $height;
			$product[29] = $keyword;
			$product[30] = $measurementUnit;
			$product[31] = $sku;
			$product[32] = $location;
			$products[$productId] = $product;
		}
		return $this->storeProductsIntoDatabase( $database, $products );
	}
	
	function storeCategoriesIntoDatabase( &$database, &$categories ) {
		// find the default language id
		$languageId = $this->getDefaultLanguageId($database);
		
		// start transaction, remove categories
		$sql = "START TRANSACTION;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."category`;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."category_description` WHERE language_id=$languageId;\n";
		$this->import( $database, $sql );
		
		// generate and execute SQL for inserting the categories
		foreach ($categories as $category) {
			$categoryId = $category[0];
			$imageName = $category[1];
			$parentId = $category[2];
			$sortOrder = $category[3];
			$dateAdded = $category[4];
			$dateModified = $category[5];
			$meta = $category[9];
			$keyword = $category[10];
			$sql2 = "INSERT INTO `".DB_PREFIX."category` (`category_id`, `image`, `parent_id`, `sort_order`, `date_added`, `date_modified`) VALUES ";
			$sql2 .= "( $categoryId, '$imageName', $parentId, $sortOrder, ";
			$sql2 .= ($dateAdded=='NOW()') ? "$dateAdded," : "'$dateAdded',";
			$sql2 .= ($dateModified=='NOW()') ? "$dateModified" : "'$dateModified'";
			$sql2 .= " );";
			$database->query( $sql2 );
			$sql3 = "INSERT INTO `".DB_PREFIX."category_description` (`category_id`, `language_id`, `name`, `description`, `meta_description`) VALUES ";
			$languageId = $category[6];
			$name = addslashes($category[7]);
			$description = addslashes($category[8]);
			$sql3 .= "( $categoryId, $languageId, '$name', '$description', '$meta' );";
			$database->query( $sql3 );
			if ($keyword) {
				$sql4 = "DELETE FROM `".DB_PREFIX."url_alias` WHERE `query`='category_id=$categoryId';";
				$sql5 = "INSERT INTO `".DB_PREFIX."url_alias` (`query`,`keyword`) VALUES ('category_id=$categoryId','$keyword');";
				$database->query($sql4);
				$database->query($sql5);
			}
			
		}
		
		// final commit
		$database->query( "COMMIT;" );
		return TRUE;
	}
	
	function uploadCategories( &$reader, &$database ) {
		// find the default language id
		$languageId = $this->getDefaultLanguageId($database);
		
		$data = $reader->sheets[0];
		$categories = array();
		$isFirstRow = TRUE;
		foreach ($data['cells'] as $row) {
			if ($isFirstRow) {
				$isFirstRow = FALSE;
				continue;
			}
			$categoryId = trim(isset($row[1]) ? $row[1] : "");
			if ($categoryId=="") {
				continue;
			}
			$parentId = isset($row[2]) ? $row[2] : "0";
			$name = isset($row[3]) ? $row[3] : "";
			$name = htmlentities( $name, ENT_QUOTES, $this->detect_encoding($name) );
			$sortOrder = isset($row[4]) ? $row[4] : "0";
			$imageName = trim(isset($row[5]) ? $row[5] : "");
			$dateAdded = (isset($row[6]) && (is_string($row[6])) && (strlen($row[6])>0)) ? $row[6] : "NOW()";
			$dateModified = (isset($row[7]) && (is_string($row[7])) && (strlen($row[7])>0)) ? $row[7] : "NOW()";
			$langId = isset($row[8]) ? $row[8] : "1";
			if ($langId != $languageId) {
				continue;
			}
			$keyword = isset($row[9]) ? $row[9] : "";
			$description = isset($row[10]) ? $row[10] : "";
			$description = htmlentities( $description, ENT_QUOTES, $this->detect_encoding($description) );
			$meta = isset($row[11]) ? $row[11] : "";
			$meta = htmlentities( $meta, ENT_QUOTES, $this->detect_encoding($meta) );
			$category = array();
			$category[0] = $categoryId;
			$category[1] = $imageName;
			$category[2] = $parentId;
			$category[3] = $sortOrder;
			$category[4] = $dateAdded;
			$category[5] = $dateModified;
			$category[6] = $languageId;
			$category[7] = $name;
			$category[8] = $description;
			$category[9] = $meta;
			$category[10] = $keyword;
			$categories[$categoryId] = $category;
		}
		return $this->storeCategoriesIntoDatabase( $database, $categories );
	}
	
	function storeOptionNamesIntoDatabase( &$database, &$options, &$optionIds ) {
		// find the default language id
		$languageId = $this->getDefaultLanguageId($database);
		
		// add option names, ids, and sort orders to the database
		$maxOptionId = 0;
		$sortOrder = 0;
		$sql = "INSERT INTO `".DB_PREFIX."product_option` (`product_option_id`, `product_id`, `sort_order`) VALUES "; 
		$sql2 = "INSERT INTO `".DB_PREFIX."product_option_description` (`product_option_id`, `product_id`, `language_id`, `name`) VALUES ";
		$k = strlen( $sql );
		$first = TRUE;
		foreach ($options as $option) {
			$productId = $option['product_id'];
			$name = $option['option'];
			$langId = $option['language_id'];
			if ($productId=="") {
				continue;
			}
			if ($langId != $languageId) {
				continue;
			}
			if ($name=="") {
				continue;
			}
			if (!isset($optionIds[$productId][$name])) {
				$maxOptionId += 1;
				$optionId = $maxOptionId;
				if (!isset($optionIds[$productId])) {
					$optionIds[$productId] = array();
					$sortOrder = 0;
				}
				$sortOrder += 1;
				$optionIds[$productId][$name] = $optionId;
				$sql .= ($first) ? "\n" : ",\n";
				$sql2 .= ($first) ? "\n" : ",\n";
				$first = FALSE;
				$sql .= "($optionId, $productId, $sortOrder )";
				$sql2 .= "($optionId, $productId, $languageId, '$name' )";
			}
		}
		$sql .= ";\n";
		$sql2 .= ";\n";
		if (strlen( $sql ) > $k+2) {
			$database->query( $sql );
			$database->query( $sql2 );
		}
		return TRUE;
	}
	
	function storeOptionDetailsIntoDatabase( &$database, &$options, &$optionIds ) {
		// find the default language id
		$languageId = $this->getDefaultLanguageId($database);
		
		// generate SQL for storing all the option details into the database
		$sql = "INSERT INTO `".DB_PREFIX."product_option_value` (`product_option_value_id`, `product_id`, `product_option_id`, `quantity`, `subtract`, `price`, `prefix`, `sort_order`) VALUES "; 
		$sql2 = "INSERT INTO `".DB_PREFIX."product_option_value_description` (`product_option_value_id`, `product_id`, `language_id`, `name`) VALUES ";
		$k = strlen( $sql );
		$first = TRUE;
		foreach ($options as $index => $option) {
			$productOptionValueId = $index+1;
			$productId = $option['product_id'];
			$optionName = $option['option'];
			$optionId = $optionIds[$productId][$optionName];
			$optionValue = $option['option_value'];
			$quantity = $option['quantity'];
			$subtract = $option['subtract'];
			$subtract = ((strtoupper($subtract)=="TRUE") || (strtoupper($subtract)=="YES") || (strtoupper($subtract)=="ENABLED")) ? 1 : 0;
			$price = $option['price'];
			$prefix = $option['prefix'];
			$sortOrder = $option['sort_order'];
			$sql .= ($first) ? "\n" : ",\n";
			$sql2 .= ($first) ? "\n" : ",\n";
			$first = FALSE;
			$sql .= "($productOptionValueId, $productId, $optionId, $quantity, $subtract, $price, '$prefix', $sortOrder)";
			$sql2 .= "($productOptionValueId, $productId, $languageId, '$optionValue')";
		}
		$sql .= ";\n";
		$sql2 .= ";\n";
		
		// execute the database query
		if (strlen( $sql ) > $k+2) {
			$database->query( $sql );
			$database->query( $sql2 );
		}
		return TRUE;
	}
	
	function storeOptionsIntoDatabase( &$database, &$options ) {
		// find the default language id
		$languageId = $this->getDefaultLanguageId($database);
		
		// start transaction, remove options
		$sql = "START TRANSACTION;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."product_option`;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."product_option_description` WHERE language_id=$languageId;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."product_option_value`;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."product_option_value_description` WHERE language_id=$languageId;\n";
		$this->import( $database, $sql );
		
		// store option names
		$optionIds = array(); // indexed by product_id and name
		$ok = $this->storeOptionNamesIntoDatabase( $database, $options, $optionIds );
		if (!$ok) {
			$database->query( 'ROLLBACK;' );
			return FALSE;
		}
		
		// store option details
		$ok = $this->storeOptionDetailsIntoDatabase( $database, $options, $optionIds );
		if (!$ok) {
			$database->query( 'ROLLBACK;' );
			return FALSE;
		}
		
		$database->query("COMMIT;");
		return TRUE;
	}
	
	function uploadOptions( &$reader, &$database ) {
		$data = $reader->sheets[2];
		$options = array();
		$i = 0;
		$isFirstRow = TRUE;
		foreach ($data['cells'] as $row) {
			if ($isFirstRow) {
				$isFirstRow = FALSE;
				continue;
			}
			$productId = trim(isset($row[1]) ? $row[1] : "");
			if ($productId=="") {
				continue;
			}
			$languageId = isset($row[2]) ? $row[2] : "";
			$option = isset($row[3]) ? $row[3] : "";
			$optionValue = isset($row[4]) ? $row[4] : "";
			$optionQuantity = isset($row[5]) ? $row[5] : "0";
			$optionSubtract = isset($row[6]) ? $row[6] : "false";
			$optionPrice = isset($row[7]) ? $row[7] : "0";
			$optionPrefix = isset($row[8]) ? $row[8] : "+";
			$sortOrder = isset($row[9]) ? $row[9] : "0";
			$options[$i] = array();
			$options[$i]['product_id'] = $productId;
			$options[$i]['language_id'] = $languageId;
			$options[$i]['option'] = $option;
			$options[$i]['option_value'] = $optionValue;
			$options[$i]['quantity'] = $optionQuantity;
			$options[$i]['subtract'] = $optionSubtract;
			$options[$i]['price'] = $optionPrice;
			$options[$i]['prefix'] = $optionPrefix;
			$options[$i]['sort_order'] = $sortOrder;
			$i += 1;
		}
		return $this->storeOptionsIntoDatabase( $database, $options );
	}
	
	function storeAdditionalImagesIntoDatabase( &$reader, &$database ) {
		// start transaction
		$sql = "START TRANSACTION;\n";
		
		// delete old additional product images from database
		$sql = "DELETE FROM `".DB_PREFIX."product_image`";
		$database->query( $sql );
		
		// insert new additional product images into database
		$data = $reader->sheets[1];  // Products worksheet
		$isFirstRow = TRUE;
		$maxImageId = 0;
		foreach ($data['cells'] as $row) {
			if ($isFirstRow) {
				$isFirstRow = FALSE;
				continue;
			}
			$productId = trim(isset($row[1]) ? $row[1] : "");
			if ($productId=="") {
				continue;
			}
			$imageNames = trim(isset($row[28]) ? $row[28] : "");
			$imageNames = trim( $this->clean($imageNames, FALSE) );
			$imageNames = ($imageNames=="") ? array() : explode( ",", $imageNames );
			foreach ($imageNames as $imageName) {
				$maxImageId += 1;
				$sql = "INSERT INTO `".DB_PREFIX."product_image` (`product_image_id`, product_id, `image`) VALUES ";
				$sql .= "($maxImageId,$productId,'$imageName');";
				$database->query( $sql );
			}
		}
		
		$database->query( "COMMIT;" );
		return TRUE;
	}
	
	function uploadImages( &$reader, &$database ) {
		$ok = $this->storeAdditionalImagesIntoDatabase( $reader, $database );
		return $ok;
	}
	
	function validateHeading( &$data, &$expected ) {
		$heading = array();
		foreach ($data['cells'] as $row) {
			for ($i=1; $i<=count($expected); $i+=1) {
				$heading[] = isset($row[$i]) ? $row[$i] : "";
			}
			break;
		}
		$valid = TRUE;
		for ($i=0; $i < count($expected); $i+=1) {
			if (!isset($heading[$i])) {
				$valid = FALSE;
				break;
			}
			if (strtolower($heading[$i]) != strtolower($expected[$i])) {
				$valid = FALSE;
				break;
			}
		}
		return $valid;
	}
	
	function validateCategories( &$reader ) {
		$expectedCategoryHeading = array
		( "category_id", "parent_id", "name", "sort_order", "image_name", "date_added", "date_modified", "language_id", "seo_keyword", "description", "meta" );
		$data =& $reader->sheets[0];
		return $this->validateHeading( $data, $expectedCategoryHeading );
	}
	
	
	function validateProducts( &$reader ) {
		$expectedProductHeading = array
		( "product_id", "name", "categories", "sku", "location", "quantity", "model", "manufacturer", "image_name", "requires\nshipping", "price", "date_added", "date_modified", "date_available", "weight", "unit", "length", "width", "height", "measurement\nunit", "status\nenabled", /*"special\noffer", "featured",*/ "tax_class_id", "viewed", "language_id", "seo_keyword", "description", "meta", "additional image names", "stock_status_id" );
		$data = $reader->sheets[1];
		return $this->validateHeading( $data, $expectedProductHeading );
	}
	
	function validateOptions( &$reader ) {
		$expectedOptionHeading = array
		( "product_id", "language_id", "option", "option_value", "quantity", "subtract", "price", "prefix", "sort_order" );
		$data = $reader->sheets[2];
		return $this->validateHeading( $data, $expectedOptionHeading );
	}
	
	
	function validateUpload( &$reader ) {
		if (count($reader->sheets) != 3) {
			return FALSE;
		}
		if (!$this->validateCategories( $reader )) {
			return FALSE;
		}
		if (!$this->validateProducts( $reader )) {
			return FALSE;
		}
		if (!$this->validateOptions( $reader )) {
			return FALSE;
		}
		return TRUE;
	}
	
	function clearCache() {
		$this->cache->delete('category');
		$this->cache->delete('category_description');
		$this->cache->delete('manufacturer');
		$this->cache->delete('product');
		$this->cache->delete('product_image');
		$this->cache->delete('product_option');
		$this->cache->delete('product_option_description');
		$this->cache->delete('product_option_value');
		$this->cache->delete('product_option_value_description');
		$this->cache->delete('product_to_category');
		$this->cache->delete('url_alias');
	}
	
	function upload( $filename ) {
		set_error_handler('error_handler_for_export',E_ALL);
		$database = Registry::get('db');
		require_once 'library/Spreadsheet/Excel/Reader.php';
		ini_set("memory_limit","512M");
		ini_set("max_execution_time",180);
		//set_time_limit( 60 );
		$reader=new Spreadsheet_Excel_Reader();
		$reader->setUTFEncoder('iconv');
		$reader->setOutputEncoding('UTF-8');
		$reader->read($filename);
		$ok = $this->validateUpload( $reader );
		if (!$ok) {
			return FALSE;
		}
		$this->clearCache();
		$ok = $this->uploadImages( $reader, $database );
		if (!$ok) {
			return FALSE;
		}
		$ok = $this->uploadCategories( $reader, $database );
		if (!$ok) {
			return FALSE;
		}
		$ok = $this->uploadProducts( $reader, $database );
		if (!$ok) {
			return FALSE;
		}
		$ok = $this->uploadOptions( $reader, $database );
		if (!$ok) {
			return FALSE;
		}
		return $ok;
	}
	
	function populateCategoriesWorksheet( &$worksheet, &$database, $languageId, &$boxFormat, &$textFormat ) {
		// Set the column widths
		$j = 0;
		$worksheet->setColumn($j,$j++,strlen('category_id')+1);
		$worksheet->setColumn($j,$j++,strlen('parent_id')+1);
		$worksheet->setColumn($j,$j++,max(strlen('name'),32)+1);
		$worksheet->setColumn($j,$j++,strlen('sort_order')+1);
		$worksheet->setColumn($j,$j++,max(strlen('image_name'),12)+1);
		$worksheet->setColumn($j,$j++,max(strlen('date_added'),19)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('date_modified'),19)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('language_id'),2)+1);
		$worksheet->setColumn($j,$j++,max(strlen('seo_keyword'),16)+1);
		$worksheet->setColumn($j,$j++,max(strlen('description'),32)+1);
		$worksheet->setColumn($j,$j++,max(strlen('meta'),32)+1);
		
		// The heading row
		$i = 0;
		$j = 0;
		$worksheet->writeString( $i, $j++, 'category_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'parent_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'name', $boxFormat );
		$worksheet->writeString( $i, $j++, 'sort_order', $boxFormat );
		$worksheet->writeString( $i, $j++, 'image_name', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_added', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_modified', $boxFormat );
		$worksheet->writeString( $i, $j++, 'language_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'seo_keyword', $boxFormat );
		$worksheet->writeString( $i, $j++, 'description', $boxFormat );
		$worksheet->writeString( $i, $j++, 'meta', $boxFormat );
		$worksheet->setRow( $i, 30, $boxFormat );
		
		// The actual categories data
		$i += 1;
		$j = 0;
		$query  = "SELECT c.* , cd.*, ua.keyword FROM `".DB_PREFIX."category` c ";
		$query .= "INNER JOIN `".DB_PREFIX."category_description` cd ON cd.category_id = c.category_id ";
		$query .= " AND cd.language_id=$languageId ";
		$query .= "LEFT JOIN `".DB_PREFIX."url_alias` ua ON ua.query=CONCAT('category_id=',c.category_id) ";
		$query .= "ORDER BY c.`parent_id`, `sort_order`, c.`category_id`;";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			$worksheet->write( $i, $j++, $row['category_id'] );
			$worksheet->write( $i, $j++, $row['parent_id'] );
			$worksheet->writeString( $i, $j++, html_entity_decode($row['name'],ENT_QUOTES,'UTF-8') );
			$worksheet->write( $i, $j++, $row['sort_order'] );
			$worksheet->write( $i, $j++, $row['image'] );
			$worksheet->write( $i, $j++, $row['date_added'], $textFormat );
			$worksheet->write( $i, $j++, $row['date_modified'], $textFormat );
			$worksheet->write( $i, $j++, $row['language_id'] );
			$worksheet->writeString( $i, $j++, ($row['keyword']) ? $row['keyword'] : '' );
			$worksheet->writeString( $i, $j++, html_entity_decode($row['description'],ENT_QUOTES,'UTF-8') );
			$worksheet->writeString( $i, $j++, html_entity_decode($row['meta_description'],ENT_QUOTES,'UTF-8') );
			$i += 1;
			$j = 0;
		}
	}
	
	function populateProductsWorksheet( &$worksheet, &$database, &$imageNames, $languageId, &$priceFormat, &$boxFormat, &$weightFormat, &$textFormat ) {
		// Set the column widths
		$j = 0;
		$worksheet->setColumn($j,$j++,max(strlen('product_id'),4)+1);
		$worksheet->setColumn($j,$j++,max(strlen('name'),30)+1);
		$worksheet->setColumn($j,$j++,max(strlen('categories'),12)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('sku'),10)+1);
		$worksheet->setColumn($j,$j++,max(strlen('location'),10)+1);
		$worksheet->setColumn($j,$j++,max(strlen('quantity'),4)+1);
		$worksheet->setColumn($j,$j++,max(strlen('model'),8)+1);
		$worksheet->setColumn($j,$j++,max(strlen('manufacturer'),10)+1);
		$worksheet->setColumn($j,$j++,max(strlen('image_name'),12)+1);;
		$worksheet->setColumn($j,$j++,max(strlen('shipping'),5)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('price'),10)+1,$priceFormat);
		$worksheet->setColumn($j,$j++,max(strlen('date_added'),19)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('date_modified'),19)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('date_available'),10)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('weight'),6)+1,$weightFormat);
		$worksheet->setColumn($j,$j++,max(strlen('unit'),3)+1);
		$worksheet->setColumn($j,$j++,max(strlen('length'),8)+1);
		$worksheet->setColumn($j,$j++,max(strlen('width'),8)+1);
		$worksheet->setColumn($j,$j++,max(strlen('height'),8)+1);
		$worksheet->setColumn($j,$j++,max(strlen('measurement'),3)+1);
		$worksheet->setColumn($j,$j++,max(strlen('status'),5)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('tax_class_id'),2)+1);
		$worksheet->setColumn($j,$j++,max(strlen('viewed'),5)+1);
		$worksheet->setColumn($j,$j++,max(strlen('language_id'),2)+1);
		$worksheet->setColumn($j,$j++,max(strlen('seo_keyword'),16)+1);
		$worksheet->setColumn($j,$j++,max(strlen('description'),32)+1);
		$worksheet->setColumn($j,$j++,max(strlen('meta'),32)+1);
		$worksheet->setColumn($j,$j++,max(strlen('additional image names'),24)+1);
		$worksheet->setColumn($j,$j++,max(strlen('stock_status_id'),3)+1);
		
		// The product headings row
		$i = 0;
		$j = 0;
		$worksheet->writeString( $i, $j++, 'product_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'name', $boxFormat );
		$worksheet->writeString( $i, $j++, 'categories', $boxFormat );
		$worksheet->writeString( $i, $j++, 'sku', $boxFormat );
		$worksheet->writeString( $i, $j++, 'location', $boxFormat );
		$worksheet->writeString( $i, $j++, 'quantity', $boxFormat );
		$worksheet->writeString( $i, $j++, 'model', $boxFormat );
		$worksheet->writeString( $i, $j++, 'manufacturer', $boxFormat );
		$worksheet->writeString( $i, $j++, 'image_name', $boxFormat );
		$worksheet->writeString( $i, $j++, "requires\nshipping", $boxFormat );
		$worksheet->writeString( $i, $j++, 'price', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_added', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_modified', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_available', $boxFormat );
		$worksheet->writeString( $i, $j++, 'weight', $boxFormat );
		$worksheet->writeString( $i, $j++, 'unit', $boxFormat );
		$worksheet->writeString( $i, $j++, 'length', $boxFormat );
		$worksheet->writeString( $i, $j++, 'width', $boxFormat );
		$worksheet->writeString( $i, $j++, 'height', $boxFormat );
		$worksheet->writeString( $i, $j++, "measurement\nunit", $boxFormat );
		$worksheet->writeString( $i, $j++, "status\nenabled", $boxFormat );
		$worksheet->writeString( $i, $j++, 'tax_class_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'viewed', $boxFormat );
		$worksheet->writeString( $i, $j++, 'language_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'seo_keyword', $boxFormat );
		$worksheet->writeString( $i, $j++, 'description', $boxFormat );
		$worksheet->writeString( $i, $j++, 'meta', $boxFormat );
		$worksheet->writeString( $i, $j++, 'additional image names', $boxFormat );
		$worksheet->writeString( $i, $j++, 'stock_status_id', $boxFormat );
		$worksheet->setRow( $i, 30, $boxFormat );
		
		// The actual products data
		$i += 1;
		$j = 0;
		$query  = "SELECT ";
		$query .= "  p.product_id,";
		$query .= "  pd.name,";
		$query .= "  GROUP_CONCAT( DISTINCT CAST(pc.category_id AS CHAR(11)) SEPARATOR \",\" ) AS categories,";
		$query .= "  p.sku,";
		$query .= "  p.location,";
		$query .= "  p.quantity,";
		$query .= "  p.model,";
		$query .= "  m.name AS manufacturer,";
		$query .= "  p.image AS image_name,";
		$query .= "  p.shipping,";
		$query .= "  p.price,";
		$query .= "  p.date_added,";
		$query .= "  p.date_modified,";
		$query .= "  p.date_available,";
		$query .= "  p.weight,";
		$query .= "  wc.unit,";
		$query .= "  p.length,";
		$query .= "  p.width,";
		$query .= "  p.height,";
		$query .= "  p.status,";
		$query .= "  p.tax_class_id,";
		$query .= "  p.viewed,";
		$query .= "  pd.language_id,";
		$query .= "  ua.keyword,";
		$query .= "  pd.description, ";
		$query .= "  pd.meta_description, ";
		$query .= "  p.stock_status_id, ";
		$query .= "  mc.unit AS measurement_unit ";
		$query .= "FROM `".DB_PREFIX."product` p ";
		$query .= "LEFT JOIN `".DB_PREFIX."product_description` pd ON p.product_id=pd.product_id ";
		$query .= "  AND pd.language_id=$languageId ";
		$query .= "LEFT JOIN `".DB_PREFIX."product_to_category` pc ON p.product_id=pc.product_id ";
		$query .= "LEFT JOIN `".DB_PREFIX."url_alias` ua ON ua.query=CONCAT('product_id=',p.product_id) ";
		$query .= "LEFT JOIN `".DB_PREFIX."manufacturer` m ON m.manufacturer_id = p.manufacturer_id ";
		$query .= "LEFT JOIN `".DB_PREFIX."weight_class` wc ON wc.weight_class_id = p.weight_class_id ";
		$query .= "  AND wc.language_id=$languageId ";
		$query .= "LEFT JOIN `".DB_PREFIX."measurement_class` mc ON mc.measurement_class_id=p.measurement_class_id ";
		$query .= "  AND mc.language_id=$languageId ";
		$query .= "GROUP BY p.product_id ";
		$query .= "ORDER BY p.product_id, pc.category_id; ";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			$productId = $row['product_id'];
			$worksheet->write( $i, $j++, $productId );
			$worksheet->writeString( $i, $j++, html_entity_decode($row['name'],ENT_QUOTES,'UTF-8') );
			$worksheet->write( $i, $j++, $row['categories'], $textFormat );
			$worksheet->writeString( $i, $j++, $row['sku'] );
			$worksheet->writeString( $i, $j++, $row['location'] );
			$worksheet->write( $i, $j++, $row['quantity'] );
			$worksheet->writeString( $i, $j++, $row['model'] );
			$worksheet->writeString( $i, $j++, $row['manufacturer'] );
			$worksheet->writeString( $i, $j++, $row['image_name'] );
			$worksheet->write( $i, $j++, ($row['shipping']==0) ? "no" : "yes", $textFormat );
			$worksheet->write( $i, $j++, $row['price'], $priceFormat );
			$worksheet->write( $i, $j++, $row['date_added'], $textFormat );
			$worksheet->write( $i, $j++, $row['date_modified'], $textFormat );
			$worksheet->write( $i, $j++, $row['date_available'], $textFormat );
			$worksheet->write( $i, $j++, $row['weight'], $weightFormat );
			$worksheet->writeString( $i, $j++, $row['unit'] );
			$worksheet->write( $i, $j++, $row['length'] );
			$worksheet->write( $i, $j++, $row['width'] );
			$worksheet->write( $i, $j++, $row['height'] );
			$worksheet->writeString( $i, $j++, $row['measurement_unit'] );
			$worksheet->write( $i, $j++, ($row['status']==0) ? "false" : "true", $textFormat );
			$worksheet->write( $i, $j++, $row['tax_class_id'] );
			$worksheet->write( $i, $j++, $row['viewed'] );
			$worksheet->write( $i, $j++, $row['language_id'] );
			$worksheet->writeString( $i, $j++, ($row['keyword']) ? $row['keyword'] : '' );
			$worksheet->writeString( $i, $j++, html_entity_decode($row['description'],ENT_QUOTES,'UTF-8') );
			$worksheet->writeString( $i, $j++, html_entity_decode($row['meta_description'],ENT_QUOTES,'UTF-8') );
			$names = "";
			if (isset($imageNames[$productId])) {
				$first = TRUE;
				foreach ($imageNames[$productId] AS $name) {
					if (!$first) {
						$names .= ",\n";
					}
					$first = FALSE;
					$names .= $name;
				}
			}
			$worksheet->writeString( $i, $j++, $names );
			$worksheet->write( $i, $j++, $row['stock_status_id'] );
			$i += 1;
			$j = 0;
		}
	}
	
	function populateOptionsWorksheet( &$worksheet, &$database, $languageId, &$priceFormat, &$boxFormat, $textFormat ) {
		// Set the column widths
		$j = 0;
		$worksheet->setColumn($j,$j++,max(strlen('product_id'),4)+1);
		$worksheet->setColumn($j,$j++,max(strlen('language_id'),2)+1);
		$worksheet->setColumn($j,$j++,max(strlen('option'),30)+1);
		$worksheet->setColumn($j,$j++,max(strlen('option_value'),30)+1);
		$worksheet->setColumn($j,$j++,max(strlen('quantity'),4)+1);
		$worksheet->setColumn($j,$j++,max(strlen('subtract'),5)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('price'),10)+1,$priceFormat);
		$worksheet->setColumn($j,$j++,max(strlen('prefix'),5)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('sort_order'),5)+1);
		
		// The options headings row
		$i = 0;
		$j = 0;
		$worksheet->writeString( $i, $j++, 'product_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'language_id', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'option', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'option_value', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'quantity', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'subtract', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'price', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'prefix', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'sort_order', $boxFormat  );
		$worksheet->setRow( $i, 30, $boxFormat );
		
		// The actual options data
		$i += 1;
		$j = 0;
		$query  = "SELECT DISTINCT p.product_id, ";
		$query .= "  pod.name AS option_name, ";
		$query .= "  po.sort_order AS option_sort_order, ";
		$query .= "  povd.name AS option_value, ";
		$query .= "  pov.quantity AS option_quantity, ";
		$query .= "  pov.subtract AS option_subtract, ";
		$query .= "  pov.price AS option_price, ";
		$query .= "  pov.prefix AS option_prefix, ";
		$query .= "  pov.sort_order AS sort_order ";
		$query .= "FROM `".DB_PREFIX."product` p ";
		$query .= "INNER JOIN `".DB_PREFIX."product_description` pd ON p.product_id=pd.product_id ";
		$query .= "  AND pd.language_id=$languageId ";
		$query .= "INNER JOIN `".DB_PREFIX."product_option` po ON po.product_id=p.product_id ";
		$query .= "INNER JOIN `".DB_PREFIX."product_option_description` pod ON pod.product_option_id=po.product_option_id ";
		$query .= "  AND pod.product_id=po.product_id ";
		$query .= "  AND pod.language_id=$languageId ";
		$query .= "INNER JOIN `".DB_PREFIX."product_option_value` pov ON pov.product_option_id=po.product_option_id ";
		$query .= "INNER JOIN `".DB_PREFIX."product_option_value_description` povd ON povd.product_option_value_id=pov.product_option_value_id ";
		$query .= "  AND povd.language_id=$languageId ";
		$query .= "ORDER BY product_id, option_sort_order, sort_order;";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			$worksheet->write( $i, $j++, $row['product_id'] );
			$worksheet->write( $i, $j++, $languageId );
			$worksheet->writeString( $i, $j++, $row['option_name'] );
			$worksheet->writeString( $i, $j++, $row['option_value'] );
			$worksheet->write( $i, $j++, $row['option_quantity'] );
			$worksheet->write( $i, $j++, ($row['option_subtract']==0) ? "false" : "true", $textFormat );
			$worksheet->write( $i, $j++, $row['option_price'], $priceFormat );
			$worksheet->writeString( $i, $j++, $row['option_prefix'], $textFormat );
			$worksheet->write( $i, $j++, $row['sort_order'] );
			$i += 1;
			$j = 0;
		}
	}
	
	function download() {
		set_error_handler('error_handler_for_export',E_ALL);
		$database = Registry::get('db');
		$languageId = $this->getDefaultLanguageId($database);
		
		// We use the package from http://pear.php.net/package/Spreadsheet_Excel_Writer/
		require_once "library/Spreadsheet/Excel/Writer.php";
		
		// Creating a workbook
		$workbook = new Spreadsheet_Excel_Writer();
		$workbook->setTempDir(DIR_CACHE);
		$workbook->setVersion(8); // Use Excel97/2000 Format
		$priceFormat =& $workbook->addFormat(array('Size' => 10,'Align' => 'right','NumFormat' => '######0.00'));
		$boxFormat =& $workbook->addFormat(array('Size' => 10,'vAlign' => 'vequal_space' ));
		$weightFormat =& $workbook->addFormat(array('Size' => 10,'Align' => 'right','NumFormat' => '##0.00'));
		$textFormat =& $workbook->addFormat(array('Size' => 10, 'NumFormat' => "@" ));
		
		// sending HTTP headers
		$workbook->send('backup_categories_products.xls');
		
		// Creating the categories worksheet
		$worksheet =& $workbook->addWorksheet('Categories');
		$worksheet->setInputEncoding ( 'UTF-8' );
		$this->populateCategoriesWorksheet( $worksheet, $database, $languageId, $boxFormat, $textFormat );
		$worksheet->freezePanes(array(1, 1, 1, 1));
		
		// Get all additional product images
		$imageNames = array();
		$query  = "SELECT DISTINCT ";
		$query .= "  p.product_id, ";
		$query .= "  pi.product_image_id AS image_id, ";
		$query .= "  pi.image AS filename ";
		$query .= "FROM `".DB_PREFIX."product` p ";
		$query .= "INNER JOIN `".DB_PREFIX."product_image` pi ON pi.product_id=p.product_id ";
		$query .= "ORDER BY product_id, image_id; ";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			$productId = $row['product_id'];
			$imageId = $row['image_id'];
			$imageName = $row['filename'];
			if (!isset($imageNames[$productId])) {
				$imageNames[$productId] = array();
				$imageNames[$productId][$imageId] = $imageName;
			}
			else {
				$imageNames[$productId][$imageId] = $imageName;
			}
		}
		
		// Creating the products worksheet
		$worksheet =& $workbook->addWorksheet('Products');
		$worksheet->setInputEncoding ( 'UTF-8' );
		$this->populateProductsWorksheet( $worksheet, $database, $imageNames, $languageId, $priceFormat, $boxFormat, $weightFormat, $textFormat );
		$worksheet->freezePanes(array(1, 1, 1, 1));
		
		// Creating the options worksheet
		$worksheet =& $workbook->addWorksheet('Options');
		$worksheet->setInputEncoding ( 'UTF-8' );
		$this->populateOptionsWorksheet( $worksheet, $database, $languageId, $priceFormat, $boxFormat, $textFormat );
		$worksheet->freezePanes(array(1, 1, 1, 1));
		
		// Let's send the file
		$workbook->close();
		exit;
	}

}
?>
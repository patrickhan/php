<?php
		/* DO NOT EDIT THIS FILE - CRITICAL TO WORKING */
		
		if ($config->get('demo') != TRUE) {

			$query = $db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key` = 'config_serial'");
			$result = $query->rows;
			
			$data = 'host=' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . '&addr=' . $_SERVER['SERVER_ADDR'] . '&key=' . $result[0]['value'];
	
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "www.jatech.ca/authorize.php");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			curl_close($ch);
			
			if ($output != "true" and $output != "false") {
				echo $output;
				die();
			} elseif ($output == "false") {
				echo 'You are using an unauthorized version of our software. Please double check your serial number. If problem persists, please contact <a href="mailto:support@jatech.ca">support@jatech.ca</a> - thank you.';
				die();
			}
		}
?>
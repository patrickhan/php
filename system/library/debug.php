<?php
final class Debug {

	// Print Array with preformatted HTML tags
	// By MYSTERY MAN
public function parr($array, $die = FALSE) {
		echo '<pre>';
		print_r($array);
		echo '</pre>';
		if ($die) die;
	}	
}
?>
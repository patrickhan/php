<?php
if ($menus) {
	foreach ($menus as $menu) {
		echo '<a id="' . $menu['id'] . '" href="' . $menu['url'] . '">' . $menu['title'] . '</a>';
	}
}
?>
<?php

function mbt_themes_init() {
	add_filter('body_class', 'mbt_override_twentyseventeen_body_classes', 20);
}
add_action('mbt_init', 'mbt_themes_init', 11);

function mbt_override_twentyseventeen_body_classes($classes) {
	if(get_template() === 'twentyseventeen' && mbt_is_mbt_page()) {
		$index = array_search('has-sidebar', $classes);
		if($index) { array_splice($classes, $index, 1); }
	}
	return $classes;
}

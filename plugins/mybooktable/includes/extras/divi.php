<?php

/*---------------------------------------------------------*/
/* Divi Theme Integration                                  */
/*---------------------------------------------------------*/

function mbt_divi_init() {
	if(strtolower(get_option('template')) === 'divi') {
		add_filter('mbt_is_compatability_mode_on', '__return_false');
		add_action('add_meta_boxes', 'mbt_add_divi_meta_box');
	}
}
add_action('mbt_init', 'mbt_divi_init', 10);

function mbt_add_divi_meta_box() {
	add_meta_box('et_settings_meta_box', esc_html__('Divi Post Settings', 'Divi'), 'et_single_settings_meta_box', 'mbt_book', 'side', 'high');
}

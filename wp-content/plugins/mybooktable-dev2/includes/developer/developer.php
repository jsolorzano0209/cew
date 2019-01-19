<?php

/*---------------------------------------------------------*/
/* General Buy Buttons                                     */
/*---------------------------------------------------------*/

function mbtdev_general_buy_buttons_init() {
	add_filter('mbt_stores', 'mbtdev_add_general_buy_buttons');
}
add_action('mbtdev_init', 'mbtdev_general_buy_buttons_init');

function mbtdev_add_general_buy_buttons($stores) {
	$stores['ejunkie'] = array('name' => 'E-junkie');
	return $stores;
}



/*---------------------------------------------------------*/
/* Paypal Buy Button                                       */
/*---------------------------------------------------------*/

function mbtdev_paypal_buybutton_init() {
	add_filter('mbt_stores', 'mbtdev_add_paypal_buybutton');
	add_filter('mbt_format_buybutton', 'mbtdev_paypal_buybutton_button', 10, 3);
}
add_action('mbtdev_init', 'mbtdev_paypal_buybutton_init');

function mbtdev_add_paypal_buybutton($stores) {
	$stores['paypal'] = array('name' => 'PayPal');
	return $stores;
}

function mbtdev_parse_paypal_url($url) {
	if(preg_match('/\<form.*action="https:\/\/www\.paypal\.com\/cgi-bin\/webscr"/', $url)) {
		$get_vars = array();

		$dom = new DOMDocument;
		$dom->loadHTML($url);
		foreach($dom->getElementsByTagName('input') as $node) {
			if($node->getAttribute('type') == 'hidden') {
				$get_vars[$node->getAttribute('name')] = $node->getAttribute('value');
			}
		}

		return 'https://www.paypal.com/cgi-bin/webscr?'.http_build_query($get_vars);
	}
	return $url;
}

function mbtdev_paypal_buybutton_button($output, $data, $store) {
	if($data['store'] == 'paypal') {
		$url = mbtdev_parse_paypal_url($data['url']);
		if(!empty($data['display']) and $data['display'] == 'text') {
			$output = empty($url) ? '' : '<li><a href="'.htmlspecialchars($url).'" target="_blank" rel="nofollow">'.__('Purchase with Paypal', 'mybooktable').'</a></li>';
		} else {
			$output = empty($url) ? '' : '<div class="mbt-book-buybutton"><a href="'.htmlspecialchars($url).'" target="_blank" rel="nofollow"><img src="'.mbt_image_url($data['store'].'_button.png').'" border="0" alt="'.__('Purchase with Paypal', 'mybooktable').'"/></a></div>';
		}
	}
	return $output;
}

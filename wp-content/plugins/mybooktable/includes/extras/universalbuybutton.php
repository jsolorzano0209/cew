<?php

/*---------------------------------------------------------*/
/* Universal Buy Button                                    */
/*---------------------------------------------------------*/

function mbt_universal_buybutton_init() {
	add_filter('mbt_stores', 'mbt_add_universal_buybutton');
	add_action('wp_head', 'mbt_add_universal_buybutton_css');
	add_filter('mbt_buybutton_editor', 'mbt_universal_buybutton_editor', 10, 4);
	add_filter('mbt_format_buybutton', 'mbt_universal_buybutton_button', 10, 3);
}
add_action('mbt_init', 'mbt_universal_buybutton_init');

function mbt_add_universal_buybutton($stores) {
	$stores['universal'] = array('name' => 'Universal Buy Button');
	return $stores;
}

function mbt_universal_buybutton_editor($output, $data, $id, $store) {
	if($data['store'] == 'universal') {
		$output  = '<input name="'.$id.'[store]" type="hidden" value="'.$data['store'].'">';
		$output .= __('Button Text:').' <input type="text" name="'.$id.'[text]" value="'.(empty($data['text']) ? '' : $data['text']).'"><br>';
		$output .= __('Button URL:').' <input type="text" name="'.$id.'[url]" value="'.(empty($data['url']) ? '' : htmlspecialchars($data['url'])).'"><br>';
		$editor_desc = (empty($store['editor_desc']) ? __('Paste in the product URL for this item.', 'mybooktable').' <a href="'.admin_url('admin.php?page=mbt_help&mbt_video_tutorial=buy_buttons').'" target="_blank">'.__('Learn more about adding Buy Button links.', 'mybooktable').'</a>' : $store['editor_desc']);
		$output .= '<p>'.$editor_desc.'</p>';
	}
	return $output;
}

function mbt_universal_buybutton_button($output, $data, $store) {
	if($data['store'] == 'universal') {
		if(empty($data['url'])) {
			$output = '';
		} else {
			$buybutton_url = parse_url($data['url']);
			$site_url = parse_url(get_site_url());
			$internal = (isset($buybutton_url['host']) and isset($site_url['host']) and ($buybutton_url['host'] == $site_url['host']));

			if(!empty($data['display']) and $data['display'] == 'text') {
				$output = '<li><a class="mbt-universal-text-buybutton" href="'.htmlspecialchars($data['url']).'" '.($internal ? '' : 'target="_blank" ').'rel="nofollow">'.$data['text'].'</a></li>';
			} else {
				$output = '<div class="mbt-book-buybutton"><a class="mbt-universal-buybutton" href="'.htmlspecialchars($data['url']).'" '.($internal ? '' : 'target="_blank" ').' rel="nofollow">'.$data['text'].'</a></div>';
			}
		}
	}
	return $output;
}

function mbt_add_universal_buybutton_css() {
	$book_button_size = mbt_get_setting('book_button_size');
	$listing_button_size = mbt_get_setting('listing_button_size');
	$widget_button_size = mbt_get_setting('widget_button_size');
	echo('<style type="text/css">');
	echo('.mbt-book-buybuttons .mbt-universal-buybutton { margin: 0; display: inline-block; box-sizing: border-box; }');
	//Book Button Size
	if($book_button_size == 'small') { echo('.mbt-book .mbt-book-buybuttons .mbt-universal-buybutton { font-size: 13px; line-height: 13px; padding: 5px 8px; width: 144px; min-height: 25px; }'); }
	else if($book_button_size == 'medium') { echo('.mbt-book .mbt-book-buybuttons .mbt-universal-buybutton { font-size: 15px; line-height: 16px; padding: 6px 12px; width: 172px; min-height: 30px; }'); }
	else { echo('.mbt-book .mbt-book-buybuttons .mbt-universal-buybutton { font-size: 18px; line-height: 20px; padding: 6px 15px; width: 201px; min-height: 35px; }'); }
	//Listing Button Size
	if($listing_button_size == 'small') { echo('.mbt-book-archive .mbt-book .mbt-book-buybuttons .mbt-universal-buybutton { font-size: 13px; line-height: 13px; padding: 5px 8px; width: 144px; min-height: 25px; }'); }
	else if($listing_button_size == 'medium') { echo('.mbt-book-archive .mbt-book .mbt-book-buybuttons .mbt-universal-buybutton { font-size: 15px; line-height: 16px; padding: 6px 12px; width: 172px; min-height: 30px; }'); }
	else { echo('.mbt-book-archive .mbt-book .mbt-book-buybuttons .mbt-universal-buybutton { font-size: 18px; line-height: 20px; padding: 6px 15px; width: 201px; min-height: 35px; }'); }
	//Widget Button Size
	if($widget_button_size == 'small') { echo('.mbt-featured-book-widget .mbt-book-buybuttons .mbt-universal-buybutton { font-size: 13px; line-height: 13px; padding: 5px 8px; width: 144px; min-height: 25px; }'); }
	else if($widget_button_size == 'medium') { echo('.mbt-featured-book-widget .mbt-book-buybuttons .mbt-universal-buybutton { font-size: 15px; line-height: 16px; padding: 6px 12px; width: 172px; min-height: 30px; }'); }
	else { echo('.mbt-featured-book-widget .mbt-book-buybuttons .mbt-universal-buybutton { font-size: 18px; line-height: 20px; padding: 6px 15px; width: 201px; min-height: 35px; }'); }
	echo('</style>');
}

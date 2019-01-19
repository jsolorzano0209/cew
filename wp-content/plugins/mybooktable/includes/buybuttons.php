<?php

/*---------------------------------------------------------*/
/* General Buy Buttons Functions                           */
/*---------------------------------------------------------*/

function mbt_buybuttons_init() {
	add_filter('mbt_stores', 'mbt_add_basic_stores');
}
add_action('mbt_init', 'mbt_buybuttons_init');

function mbt_get_stores() {
	return apply_filters("mbt_stores", array());
}

function mbt_add_basic_stores($stores) {
	if(mbt_get_setting('enable_default_affiliates') or mbt_get_upgrade()) {
		$stores['amazon'] = array('name' => 'Amazon', 'search' => 'https://www.amazon.com/s?field-keywords=${title}');
		$stores['kindle'] = array('name' => 'Amazon Kindle', 'search' => 'https://www.amazon.com/s?field-keywords=${title}');
		$stores['audible'] = array('name' => 'Audible.com', 'search' => 'http://www.audible.com/search');
		$stores['bnn'] = array('name' => 'Barnes and Noble', 'search' => 'https://www.barnesandnoble.com/s/${title}');
		$stores['nook'] = array('name' => 'Barnes and Noble Nook', 'search' => 'https://www.barnesandnoble.com/s/${title}');
		$stores['kobo'] = array('name' => 'Kobo', 'search' => 'https://www.kobo.com/us/en/search?Query=${title}');
	}
	$stores['goodreads'] = array('name' => 'GoodReads', 'search' => 'https://www.goodreads.com/search?q=${title}');
	$stores['cbd'] = array('name' => 'Christian Book Distributor', 'search' => 'https://www.christianbook.com/Christian/Books/easy_find?Ntt=${title}');
	$stores['bam'] = array('name' => 'Books-A-Million', 'search' => 'http://www.booksamillion.com/search?query=${title}');
	$stores['bookbaby'] = array('name' => 'BookBaby');
	$stores['lifeway'] = array('name' => 'Lifeway', 'search' => 'http://www.lifeway.com/Keyword/${title}');
	$stores['mardel'] = array('name' => 'Mardel', 'search' => 'http://www.mardel.com/search/?text=${title}');
	$stores['smashwords'] = array('name' => 'Smashwords', 'search' => 'https://www.smashwords.com/books/search?query=${title}');
	$stores['indiebound'] = array('name' => 'IndieBound', 'search' => 'https://www.indiebound.org/search/book?keys=${title}');
	$stores['createspace'] = array('name' => 'CreateSpace');
	$stores['alibris'] = array('name' => 'Alibris', 'search' => 'https://www.alibris.com/booksearch?keyword=${title}');
	$stores['bookdepository'] = array('name' => 'Book Depository', 'search' => 'https://www.bookdepository.com/search?searchTerm=${title}');
	$stores['itunes'] = array('name' => 'Apple iTunes');
	$stores['ibooks'] = array('name' => 'Apple iBooks');
	$stores['powells'] = array('name' => 'Powells', 'search' => 'http://www.powells.com/SearchResults?kw=title:${title}');
	$stores['scribd'] = array('name' => 'Scribd', 'search' => 'https://www.scribd.com/search?query=${title}');
	$stores['sony'] = array('name' => 'Sony Reader');
	$stores['googleplay'] = array('name' => 'Google Play', 'search' => 'https://play.google.com/store/search?q=${title}');
	$stores['inktera'] = array('name' => 'Inktera', 'search' => 'http://www.inktera.com/store/search');
	$stores['lulu'] = array('name' => 'Lulu', 'search' => 'https://www.lulu.com/shop/search.ep?keyWords=${title}');
	$stores['gumroad'] = array('name' => 'Gumroad');
	$stores['celery'] = array('name' => 'Celery');
	return $stores;
}

function mbt_buybutton_editor($data, $id, $store) {
	$output  = '<input id="'.$id.'_name" name="'.$id.'[store]" type="hidden" value="'.$data['store'].'">';
	$output .= '<textarea id="'.$id.'_url" name="'.$id.'[url]" cols="80">'.(empty($data['url']) ? '' : htmlspecialchars($data['url'])).'</textarea>';
	$editor_desc = (empty($store['editor_desc']) ? __('Paste in the product URL for this item.', 'mybooktable').' <a href="'.admin_url('admin.php?page=mbt_help&mbt_video_tutorial=buy_buttons').'" target="_blank">'.__('Learn more about adding Buy Button links.', 'mybooktable').'</a>' : $store['editor_desc']);
	$editor_search = (empty($store['search']) ? '' : ' <a class="mbt_buybutton_editor_search" href="#" data-href="'.$store['search'].'" target="_blank">'.sprintf(__('Search for books on %s.', 'mybooktable'), $store['name']).'</a>');
	$output .= '<p>'.$editor_desc.$editor_search.'</p>';
	return apply_filters('mbt_buybutton_editor', $output, $data, $id, $store);
}

function mbt_format_buybutton($data, $store) {
	$data = apply_filters('mbt_filter_buybutton_data', $data, $store);
	if(!empty($data['display']) and $data['display'] == 'text') {
		$output = empty($data['url']) ? '' : '<li><a href="'.htmlspecialchars($data['url']).'" target="_blank" rel="nofollow">'.sprintf(__('Buy from %s', 'mybooktable'), $store['name']).'</a></li>';
	} else {
		$output = empty($data['url']) ? '' : '<div class="mbt-book-buybutton"><a href="'.htmlspecialchars($data['url']).'" target="_blank" rel="nofollow"><img src="'.mbt_image_url($data['store'].'_button.png').'" border="0" alt="'.sprintf(__('Buy from %s', 'mybooktable'), $store['name']).'"/></a></div>';
	}
	return apply_filters('mbt_format_buybutton', $output, $data, $store);
}

function mbt_query_buybuttons($post_id, $query = '') {
	$buybuttons = get_post_meta($post_id, "mbt_buybuttons", true);
	$stores = mbt_get_stores();
	if(!empty($buybuttons)) {
		foreach($buybuttons as $i=>$buybutton) {
			if(!isset($stores[$buybutton['store']])) { unset($buybuttons[$i]); continue; }
			if(!empty($query) and is_array($query)) {
				foreach($query as $key=>$value) {
					if(!empty($buybutton[$key]) and !((is_array($value) and in_array($buybutton[$key], $value)) or $buybutton[$key] == $value)) { unset($buybuttons[$i]); continue; }
				}
			}
		}
		$buybuttons = array_values($buybuttons);
	}
	return apply_filters('mbt_query_buybuttons', empty($buybuttons) ? array() : $buybuttons, $query);
}



/*---------------------------------------------------------*/
/* iTunes Affiliate Settings                               */
/*---------------------------------------------------------*/

function mbt_itunes_affiliate_settings_init() {
	add_action('mbt_affiliate_settings_render', 'mbt_itunes_affiliate_settings_render');
	add_action('mbt_settings_save', 'mbt_itunes_affiliate_settings_save');
	add_action('wp_ajax_mbt_itunes_affiliate_token_refresh', 'mbt_itunes_affiliate_token_refresh_ajax');
}
add_action('mbt_init', 'mbt_itunes_affiliate_settings_init');

function mbt_itunes_affiliate_token_refresh_ajax() {
	if(!current_user_can('manage_options')) { die(); }
	mbt_update_setting('itunes_affiliate_token', $_REQUEST['data']);
	echo(mbt_itunes_affiliate_token_feedback());
	die();
}

function mbt_itunes_affiliate_token_feedback() {
	$output = '';
	$affiliate_token = mbt_get_setting("itunes_affiliate_token");
	if(!empty($affiliate_token)) {
		if(preg_match('/^[0-9A-Za-z]+$/', $affiliate_token)) {
			$output .= '<span class="mbt_admin_message_success">'.__('Valid Affiliate Token', 'mybooktable').'</span>';
		} else {
			$output .= '<span class="mbt_admin_message_failure">'.__('Invalid Affiliate Token', 'mybooktable').'</span>';
		}
	}
	return $output;
}

function mbt_itunes_affiliate_settings_save() {
	if(isset($_REQUEST['mbt_itunes_affiliate_token'])) {
		mbt_update_setting('itunes_affiliate_token', $_REQUEST['mbt_itunes_affiliate_token']);
		mbt_update_setting('disable_itunes_affiliates', isset($_REQUEST['mbt_disable_itunes_affiliates']));
	}
}

function mbt_itunes_affiliate_settings_render() {
?>
	<table class="form-table">
		<tbody>
			<tr>
				<th>
					<label for="mbt_itunes_affiliate_token"><?php _e('iTunes Affiliates', 'mybooktable'); ?></label>
					<div class="mbt-affiliate-usedby">
						Used by:
						<ul>
							<li>Apple iBooks Buy Button</li>
							<li>Apple iTunes Buy Button</li>
						</ul>
					</div>
				</th>
				<td>
					<div class="mbt_feedback_above mbt_feedback"><?php echo(mbt_itunes_affiliate_token_feedback()); ?></div>
					<label for="mbt_itunes_affiliate_token"><?php _e('Affiliate Token:', 'mybooktable'); ?></label>
					<input type="text" name="mbt_itunes_affiliate_token" id="mbt_itunes_affiliate_token" value="<?php echo(mbt_get_setting('itunes_affiliate_token')); ?>" class="regular-text">
					<div class="mbt_feedback_refresh" data-refresh-action="mbt_itunes_affiliate_token_refresh" data-element="mbt_itunes_affiliate_token"></div>
					<p class="description"><?php _e('You can find your iTunes Affiliate Token by visiting your <a href="http://affiliate.itunes.apple.com/signin" target="_blank">iTunes Affiliates page</a>. The code should be near the top left of the screen.' , 'mybooktable'); ?></p>
					<p class="description"><input type="checkbox" name="mbt_disable_itunes_affiliates" id="mbt_disable_itunes_affiliates" <?php checked(mbt_get_setting('disable_itunes_affiliates'), true); ?> > <?php _e('Disable iTunes Affiliate System', 'mybooktable'); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
<?php
}



/*---------------------------------------------------------*/
/* Powells Affiliate Settings                              */
/*---------------------------------------------------------*/

function mbt_powells_affiliate_settings_init() {
	add_action('mbt_affiliate_settings_render', 'mbt_powells_affiliate_settings_render');
	add_action('mbt_settings_save', 'mbt_powells_affiliate_settings_save');
	add_action('wp_ajax_mbt_powells_partner_id_refresh', 'mbt_powells_partner_id_refresh_ajax');
	add_action('mbt_filter_buybutton_data', 'mbt_filter_powells_buybuttons_data', 10, 2);
}
add_action('mbt_init', 'mbt_powells_affiliate_settings_init');

function mbt_powells_partner_id_refresh_ajax() {
	if(!current_user_can('manage_options')) { die(); }
	mbt_update_setting('powells_partner_id', $_REQUEST['data']);
	echo(mbt_powells_partner_id_feedback());
	die();
}

function mbt_powells_partner_id_feedback() {
	$output = '';
	$partner_id = mbt_get_setting('powells_partner_id');
	if(!empty($partner_id)) {
		if(preg_match('/^[0-9]+$/', $partner_id)) {
			$output .= '<span class="mbt_admin_message_success">'.__('Valid Partner ID', 'mybooktable').'</span>';
		} else {
			$output .= '<span class="mbt_admin_message_failure">'.__('Invalid Partner ID', 'mybooktable').'</span>';
		}
	}
	return $output;
}

function mbt_powells_affiliate_settings_save() {
	if(isset($_REQUEST['mbt_powells_partner_id'])) {
		mbt_update_setting('powells_partner_id', $_REQUEST['mbt_powells_partner_id']);
		mbt_update_setting('disable_powells_affiliates', isset($_REQUEST['mbt_disable_powells_affiliates']));
	}
}

function mbt_powells_affiliate_settings_render() {
?>
	<table class="form-table">
		<tbody>
			<tr>
				<th>
					<label for="mbt_powells_partner_id"><?php _e("Powell's Affiliates", 'mybooktable'); ?></label>
					<div class="mbt-affiliate-usedby">
						Used by:
						<ul>
							<li>Powell's Buy Button</li>
						</ul>
					</div>
				</th>
				<td>
					<div class="mbt_feedback_above mbt_feedback"><?php echo(mbt_powells_partner_id_feedback()); ?></div>
					<label for="mbt_powells_partner_id"><?php _e('Partner ID:', 'mybooktable'); ?></label>
					<input type="text" name="mbt_powells_partner_id" id="mbt_powells_partner_id" value="<?php echo(mbt_get_setting('powells_partner_id')); ?>" class="regular-text">
					<div class="mbt_feedback_refresh" data-refresh-action="mbt_powells_partner_id_refresh" data-element="mbt_powells_partner_id"></div>
					<p class="description"><?php _e('You can find your Powell\'s Partner ID by visiting your <a href="http://www.powells.com/myaccount" target="_blank">Powell\'s Account page</a> and clicking the "Manage Partner" link under "Partner Program".' , 'mybooktable'); ?></p>
					<p class="description"><input type="checkbox" name="mbt_disable_powells_affiliates" id="mbt_disable_powells_affiliates" <?php checked(mbt_get_setting('disable_powells_affiliates'), true); ?> > <?php _e("Disable Powell's Affiliate System", 'mybooktable'); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
<?php
}

function mbt_filter_powells_buybuttons_data($data, $store) {
	if($data['store'] == 'powells' and !empty($data['url']) and !mbt_is_genius_link($data['url']) and mbt_get_setting('powells_partner_id') and !mbt_get_setting('disable_powells_affiliates')) {
		$partner_id = mbt_get_setting('powells_partner_id');
		$url = parse_url($data['url']);
		if(stripos($url['host'], 'powells.com') !== false) {
			$data['url'] = 'http://'.$url['host'].$url['path'].'?partnerid='.$partner_id;
		} else {
			$data['url'] = '';
		}
	}
	return $data;
}



/*---------------------------------------------------------*/
/* IndieBound Affiliate Settings                           */
/*---------------------------------------------------------*/

function mbt_indiebound_affiliate_settings_init() {
	add_action('mbt_affiliate_settings_render', 'mbt_indiebound_affiliate_settings_render');
	add_action('mbt_settings_save', 'mbt_indiebound_affiliate_settings_save');
	add_action('wp_ajax_mbt_indiebound_affiliate_id_refresh', 'mbt_indiebound_affiliate_id_refresh_ajax');
	add_action('mbt_filter_buybutton_data', 'mbt_filter_indiebound_buybuttons_data', 10, 2);
}
add_action('mbt_init', 'mbt_indiebound_affiliate_settings_init');

function mbt_indiebound_affiliate_id_refresh_ajax() {
	if(!current_user_can('manage_options')) { die(); }
	mbt_update_setting('indiebound_affiliate_id', $_REQUEST['data']);
	echo(mbt_indiebound_affiliate_id_feedback());
	die();
}

function mbt_indiebound_affiliate_id_feedback() {
	$output = '';
	$affiliate_id = mbt_get_setting('indiebound_affiliate_id');
	if(!empty($affiliate_id)) {
		if(preg_match('/^\S+$/', $affiliate_id)) {
			$output .= '<span class="mbt_admin_message_success">'.__('Valid Affiliate ID', 'mybooktable').'</span>';
		} else {
			$output .= '<span class="mbt_admin_message_failure">'.__('Invalid Affiliate ID', 'mybooktable').'</span>';
		}
	}
	return $output;
}

function mbt_indiebound_affiliate_settings_save() {
	if(isset($_REQUEST['mbt_indiebound_affiliate_id'])) {
		mbt_update_setting('indiebound_affiliate_id', $_REQUEST['mbt_indiebound_affiliate_id']);
		mbt_update_setting('disable_indiebound_affiliates', isset($_REQUEST['mbt_disable_indiebound_affiliates']));
	}
}

function mbt_indiebound_affiliate_settings_render() {
?>
	<table class="form-table">
		<tbody>
			<tr>
				<th>
					<label for="mbt_indiebound_affiliate_id"><?php _e("IndieBound Affiliates", 'mybooktable'); ?></label>
					<div class="mbt-affiliate-usedby">
						Used by:
						<ul>
							<li>IndieBound Buy Button</li>
						</ul>
					</div>
				</th>
				<td>
					<div class="mbt_feedback_above mbt_feedback"><?php echo(mbt_indiebound_affiliate_id_feedback()); ?></div>
					<label for="mbt_indiebound_affiliate_id"><?php _e('Affiliate ID:', 'mybooktable'); ?></label>
					<input type="text" name="mbt_indiebound_affiliate_id" id="mbt_indiebound_affiliate_id" value="<?php echo(mbt_get_setting('indiebound_affiliate_id')); ?>" class="regular-text">
					<div class="mbt_feedback_refresh" data-refresh-action="mbt_indiebound_affiliate_id_refresh" data-element="mbt_indiebound_affiliate_id"></div>
					<p class="description"><?php _e('You can find your IndieBound Affiliate ID by visiting your <a href="https://www.indiebound.org/affiliate" target="_blank">IndieBound Affiliates page</a> and clicking "Create your affiliate links".' , 'mybooktable'); ?></p>
					<p class="description"><input type="checkbox" name="mbt_disable_indiebound_affiliates" id="mbt_disable_indiebound_affiliates" <?php checked(mbt_get_setting('disable_indiebound_affiliates'), true); ?> > <?php _e("Disable IndieBound Affiliate System", 'mybooktable'); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
<?php
}

function mbt_filter_indiebound_buybuttons_data($data, $store) {
	if($data['store'] == 'indiebound' and !empty($data['url']) and !mbt_is_genius_link($data['url']) and mbt_get_setting('indiebound_affiliate_id') and !mbt_get_setting('disable_indiebound_affiliates')) {
		$affiliate_id = mbt_get_setting('indiebound_affiliate_id');
		$url = parse_url($data['url']);
		if(stripos($url['host'], 'indiebound.org') !== false) {
			$data['url'] = 'http://'.$url['host'].$url['path'].'?aff='.$affiliate_id;
		} else {
			$data['url'] = '';
		}
	}
	return $data;
}



/*---------------------------------------------------------*/
/* Amazon Affiliate Settings                               */
/*---------------------------------------------------------*/

function mbt_amazon_affiliate_settings_init() {
	add_action('mbt_affiliate_settings_render', 'mbt_amazon_affiliate_settings_render');
}
add_action('mbt_init', 'mbt_amazon_affiliate_settings_init');

function mbt_amazon_affiliate_settings_render() {
?>
	<table class="form-table">
		<tbody>
			<tr>
				<th>
					<label style="color: #666"><?php _e('Amazon Associates', 'mybooktable'); ?></label>
					<div class="mbt-affiliate-usedby">
						Used by:
						<ul>
							<li>Amazon Buy Button</li>
							<li>Kindle Buy Button</li>
							<li>Audible.com Buy Button</li>
						</ul>
					</div>
				</th>
				<td>
					<input type="text" disabled="true" value="" class="regular-text">
					<p class="description"><?php echo(mbt_get_upgrade_message()); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
<?php
}



/*---------------------------------------------------------*/
/* Linkshare Affiliate Settings                            */
/*---------------------------------------------------------*/

function mbt_linkshare_affiliate_settings_init() {
	add_action('mbt_affiliate_settings_render', 'mbt_linkshare_affiliate_settings_render');
}
add_action('mbt_init', 'mbt_linkshare_affiliate_settings_init');

function mbt_linkshare_affiliate_settings_render() {
?>
	<table class="form-table">
		<tbody>
			<tr>
				<th>
					<label style="color: #666"><?php _e('LinkShare', 'mybooktable'); ?></label>
					<div class="mbt-affiliate-usedby">
						Used by:
						<ul>
							<li>Kobo Buy Button</li>
						</ul>
					</div>
				</th>
				<td>
					<input type="text" disabled="true" value="" class="regular-text">
					<p class="description"><?php echo(mbt_get_upgrade_message()); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
<?php
}



/*---------------------------------------------------------*/
/* Commission Junction Affiliate Settings                  */
/*---------------------------------------------------------*/

function mbt_cj_affiliate_settings_init() {
	add_action('mbt_affiliate_settings_render', 'mbt_cj_affiliate_settings_render');
}
add_action('mbt_init', 'mbt_cj_affiliate_settings_init');

function mbt_cj_affiliate_settings_render() {
?>
	<table class="form-table">
		<tbody>
			<tr>
				<th>
					<label style="color: #666"><?php _e('Commission Junction', 'mybooktable'); ?></label>
					<div class="mbt-affiliate-usedby">
						Used by:
						<ul>
							<li>Audible.com Buy Button</li>
							<li>Barnes &amp; Noble Buy Button</li>
							<li>Nook Buy Button</li>
						</ul>
					</div>
				</th>
				<td>
					<input type="text" disabled="true" value="" class="regular-text">
					<p class="description"><?php echo(mbt_get_upgrade_message()); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
<?php
}

function mbt_get_cj_affiliate_link($url, $website_id) {
	$server = 'www.qksrv.net';
	$scheme = 'http';
	$url_info = parse_url($url);
	if(!empty($url_info) and !empty($url_info['scheme'])) { $scheme = $url_info['scheme']; }
	$hashIndex = strpos($url, '#');
	$frag = "";
	if($hashIndex !== false) {
		$frag = substr($url, $hashIndex + 1);
		$url = substr($url, 0, $hashIndex);
	}
	$extraParams = "";
	if(!empty($frag)) { $extraParams = "/fragment/".urlencode($frag); }
	return $scheme."://".$server."/links/".$website_id."/type/am".$extraParams."/".$url;
}



/*---------------------------------------------------------*/
/* Genius Link Validation                                  */
/*---------------------------------------------------------*/

function mbt_is_genius_link($url) {
	if(empty($url)) { return false; }
	$parsed = parse_url($url);
	if(empty($parsed['host']) or empty($parsed['path'])) { return false; }
	return (strcasecmp($parsed['host'], 'buy.geni.us') == 0 or strcasecmp($parsed['host'], 'geni.us') == 0 or strcasecmp($parsed['host'], 'target.georiot.com') == 0);
}



/*---------------------------------------------------------*/
/* Amazon Buy Buttons                                      */
/*---------------------------------------------------------*/

function mbt_amazon_buybuttons_init() {
	add_filter('mbt_filter_buybutton_data', 'mbt_filter_amazon_buybutton_data', 10, 2);
	add_action('wp_ajax_mbt_amazon_buybutton_preview', 'mbt_amazon_buybutton_preview');
	add_action('mbt_buybutton_editor', 'mbt_amazon_buybutton_editor', 10, 4);
}
add_action('mbt_init', 'mbt_amazon_buybuttons_init');

function mbt_get_amazon_AISN($url) {
	$matches = array();
	preg_match("/((dp%2F)|(dp\/)|(dp\/product\/)|(\/ASIN\/)|(gp\/product\/)|(exec\/obidos\/tg\/detail\/\-\/)|(asins=))([A-Z0-9]{10})/", $url, $matches);
	return empty($matches[9]) ? '' : $matches[9];
}

function mbt_get_amazon_tld($url) {
	$matches = array();
	preg_match("/amazon\.([a-zA-Z\.]+)/", $url, $matches);
	return empty($matches) ? 'com' : $matches[1];
}

function mbt_filter_amazon_buybutton_data($data, $store) {
	if(($data['store'] == 'amazon' or $data['store'] == 'kindle') and !empty($data['url']) and !mbt_is_genius_link($data['url'])) {
		$tld = mbt_get_amazon_tld($data['url']);
		$aisn = mbt_get_amazon_AISN($data['url']);
		$data['url'] = empty($aisn) ? '' : 'http://www.amazon.'.$tld.'/dp/'.$aisn.'?tag=ammbt-20';
	}
	return $data;
}

function mbt_amazon_buybutton_preview() {
	if(empty($_REQUEST['data'])) { die(); }
	$id = mbt_get_amazon_AISN($_REQUEST['data']);
	if(mbt_is_genius_link($_REQUEST['data'])) {
		echo('<span class="mbt_admin_message_success">'.__('Valid Genius Link', 'mybooktable').'</span>');
	} else if(empty($id)) {
		echo('<span class="mbt_admin_message_failure">'.__('Invalid Amazon product link', 'mybooktable').'</span>');
	} else {
		echo('<span class="mbt_admin_message_success">'.__('Valid Amazon product link', 'mybooktable').'</span>');
	}
	die();
}

function mbt_amazon_buybutton_editor($editor, $data, $id, $store) {
	if($data['store'] == 'amazon' or $data['store'] == 'kindle') {
		$editor .= '
		<script type="text/javascript">
			var url = jQuery("#'.$id.'_url");
			url.before(jQuery("<div class=\"mbt_feedback_above mbt_feedback\"></div>"));
			url.addClass("mbt_feedback_refresh mbt_feedback_refresh_initial");
			url.attr("data-refresh-action", "mbt_amazon_buybutton_preview");
			url.attr("data-element", "self");
			if(typeof url.mbt_feedback !== "undefined") { url.mbt_feedback(); }
		</script>';
	}
	return $editor;
}



/*---------------------------------------------------------*/
/* Audible Buy Button                                      */
/*---------------------------------------------------------*/

function mbt_audible_buybuttons_init() {
	add_action('mbt_filter_buybutton_data', 'mbt_filter_audible_buybutton_data', 10, 2);
	add_action('wp_ajax_mbt_audible_buybutton_preview', 'mbt_audible_buybutton_preview');
	add_action('mbt_buybutton_editor', 'mbt_audible_buybutton_editor', 10, 4);
}
add_action('mbt_init', 'mbt_audible_buybuttons_init');

function mbt_filter_audible_buybutton_data($data, $store) {
	if($data['store'] == 'audible' and !empty($data['url']) and !mbt_is_genius_link($data['url'])) {
		$data['url'] = mbt_get_cj_affiliate_link($data['url'], 7737731);
	}
	return $data;
}

function mbt_audible_buybutton_preview() {
	if(empty($_REQUEST['data'])) { die(); }
	$parsed = parse_url($_REQUEST['data']);
	if(mbt_is_genius_link($_REQUEST['data'])) {
		echo('<span class="mbt_admin_message_success">'.__('Valid Genius Link', 'mybooktable').'</span>');
	} else if(isset($parsed['host']) and strpos($parsed['host'], 'amazon') !== false) {
		$id = mbt_get_amazon_AISN($_REQUEST['data']);
		if(empty($id)) {
			echo('<span class="mbt_admin_message_failure">'.__('Invalid Audible product link', 'mybooktable').'</span>');
		} else {
			echo('<span class="mbt_admin_message_success">'.__('Valid Audible product link', 'mybooktable').'</span>');
		}
	} else if(isset($parsed['host']) and strpos($parsed['host'], 'audible') !== false) {
		echo('<span class="mbt_admin_message_success">'.__('Valid Audible product link', 'mybooktable').'</span>');
	} else {
		echo('<span class="mbt_admin_message_failure">'.__('Invalid Audible product link', 'mybooktable').'</span>');
	}
	die();
}

function mbt_audible_buybutton_editor($editor, $data, $id, $store) {
	if($data['store'] == 'audible') {
		$editor .= '
		<script type="text/javascript">
			var url = jQuery("#'.$id.'_url");
			url.before(jQuery("<div class=\"mbt_feedback_above mbt_feedback\"></div>"));
			url.addClass("mbt_feedback_refresh mbt_feedback_refresh_initial");
			url.attr("data-refresh-action", "mbt_audible_buybutton_preview");
			url.attr("data-element", "self");
			if(typeof url.mbt_feedback !== "undefined") { url.mbt_feedback(); }
		</script>';
	}
	return $editor;
}



/*---------------------------------------------------------*/
/* Barnes & Noble Buy Buttons                              */
/*---------------------------------------------------------*/

function mbt_bnn_buybuttons_init() {
	add_action('mbt_filter_buybutton_data', 'mbt_filter_bnn_buybutton_data', 10, 2);
	add_action('wp_ajax_mbt_bnn_buybutton_preview', 'mbt_bnn_buybutton_preview');
	add_action('mbt_buybutton_editor', 'mbt_bnn_buybutton_editor', 10, 4);
}
add_action('mbt_init', 'mbt_bnn_buybuttons_init');

function mbt_is_bbn_url_valid($url) {
	return preg_match("/barnesandnoble.com\/((s\/([0-9]{13}))|(w\/.*(([eE][aA][nN]=[0-9]{13})|(\/[0-9]{10}))))/", $url);
}

function mbt_filter_bnn_buybutton_data($data, $store) {
	if(($data['store'] == 'bnn' or $data['store'] == 'nook') and !empty($data['url']) and !mbt_is_genius_link($data['url'])) {
		$data['url'] = mbt_get_cj_affiliate_link($data['url'], 7737731);
	}
	return $data;
}

function mbt_bnn_buybutton_preview() {
	if(empty($_REQUEST['data'])) { die(); }
	if(mbt_is_genius_link($_REQUEST['data'])) {
		echo('<span class="mbt_admin_message_success">'.__('Valid Genius Link', 'mybooktable').'</span>');
	} else if(!mbt_is_bbn_url_valid($_REQUEST['data'])) {
		echo('<span class="mbt_admin_message_failure">'.__('Invalid Barnes &amp; Noble product link', 'mybooktable').'</span>');
	} else {
		echo('<span class="mbt_admin_message_success">'.__('Valid Barnes &amp; Noble product link', 'mybooktable').'</span>');
	}
	die();
}

function mbt_bnn_buybutton_editor($editor, $data, $id, $store) {
	if($data['store'] == 'bnn' or $data['store'] == 'nook') {
		$editor .= '
		<script type="text/javascript">
			var url = jQuery("#'.$id.'_url");
			url.before(jQuery("<div class=\"mbt_feedback_above mbt_feedback\"></div>"));
			url.addClass("mbt_feedback_refresh mbt_feedback_refresh_initial");
			url.attr("data-refresh-action", "mbt_bnn_buybutton_preview");
			url.attr("data-element", "self");
			if(typeof url.mbt_feedback !== "undefined") { url.mbt_feedback(); }
		</script>';
	}
	return $editor;
}



/*---------------------------------------------------------*/
/* Kobo Buy Button                                         */
/*---------------------------------------------------------*/

function mbt_kobo_buybuttons_init() {
	add_action('mbt_filter_buybutton_data', 'mbt_filter_kobo_buybutton_data', 10, 2);
}
add_action('mbt_init', 'mbt_kobo_buybuttons_init');

function mbt_filter_kobo_buybutton_data($data, $store) {
	if($data['store'] == 'kobo' and !empty($data['url']) and !mbt_is_genius_link($data['url'])) {
		$data['url'] = 'http://click.linksynergy.com/deeplink?id=W1PQs9y/1/c&mid=37217&murl='.urlencode($data['url']);
	}
	return $data;
}



/*---------------------------------------------------------*/
/* Gumroad Buy Button                                      */
/*---------------------------------------------------------*/

function mbt_gumroad_buybutton_init() {
	add_filter('mbt_buybutton_editor', 'mbt_gumroad_buybutton_editor', 10, 4);
	add_filter('mbt_format_buybutton', 'mbt_gumroad_buybutton_button', 10, 3);
}
add_action('mbt_init', 'mbt_gumroad_buybutton_init');

function mbt_gumroad_buybutton_editor($output, $data, $id, $store) {
	if($data['store'] == 'gumroad') {
		$output = '<p><input type="checkbox" id="'.$id.'_use_shadowbox" name="'.$id.'[use_shadowbox]" '.checked(!empty($data['use_shadowbox']), true, false).'> <label for="'.$id.'_use_shadowbox">Use shadow box for purchase?</label></p>'.$output;
	}
	return $output;
}

function mbt_gumroad_buybutton_button($output, $data, $store) {
	if($data['store'] == 'gumroad' and !empty($data['use_shadowbox'])) {
		$data['url'] = $data['url'].((strpos($data['url'], '?') === false) ? '?as_embed=true&outbound_embed=true' : '&as_embed=true&outbound_embed=true');

		if(!empty($data['display']) and $data['display'] == 'text') {
			$output = empty($data['url']) ? '' : '<li><div class="mbt-shadowbox-iframe" data-href="'.htmlspecialchars($data['url']).'">'.sprintf(__('Buy from %s', 'mybooktable'), $store['name']).'</div></li>';
		} else {
			$output = empty($data['url']) ? '' : '<div class="mbt-book-buybutton"><div class="mbt-shadowbox-iframe" data-href="'.htmlspecialchars($data['url']).'"><img src="'.mbt_image_url($data['store'].'_button.png').'" border="0" alt="'.sprintf(__('Buy from %s', 'mybooktable'), $store['name']).'"/></div></div>';
		}
	}
	return $output;
}



/*---------------------------------------------------------*/
/* Celery Buy Button                                       */
/*---------------------------------------------------------*/

function mbt_celery_buybutton_init() {
	add_filter('mbt_buybutton_editor', 'mbt_celery_buybutton_editor', 10, 4);
}
add_action('mbt_init', 'mbt_celery_buybutton_init');

function mbt_celery_buybutton_editor($output, $data, $id, $store) {
	if($data['store'] == 'celery') {
		$output = substr_replace($output, ' <a href="https://www.trycelery.com/" target="_blank">'.__('Sign up and Learn more about Celery Pre-Orders', 'mybooktable').'</a>', strlen($output)-4, 0);
	}
	return $output;
}



/*---------------------------------------------------------*/
/* iBooks & iTunes Buy Buttons                             */
/*---------------------------------------------------------*/

function mbt_apple_buybuttons_init() {
	if(!mbt_get_setting('disable_itunes_affiliates')) {
		add_filter('mbt_filter_buybutton_data', 'mbt_filter_apple_buybuttons_data', 10, 2);
		add_action('wp_ajax_mbt_apple_buybutton_preview', 'mbt_apple_buybutton_preview');
		add_action('mbt_buybutton_editor', 'mbt_apple_buybutton_editor', 10, 4);
	}
}
add_action('mbt_init', 'mbt_apple_buybuttons_init');

function mbt_filter_apple_buybuttons_data($data, $store) {
	if(($data['store'] == 'ibooks' or $data['store'] == 'itunes') and !empty($data['url']) and !mbt_is_genius_link($data['url']) and !mbt_get_setting('disable_itunes_affiliates')) {
		$token = mbt_get_setting('itunes_affiliate_token');
		$host = parse_url($data['url'], PHP_URL_HOST);
		if(stripos($host, 'itunes.apple.com') !== false) {
			if(stripos($host, 'geo') === false) { $data['url'] = str_replace($host, 'geo.'.$host, $data['url']); }
			$data['url'] .= (parse_url($data['url'], PHP_URL_QUERY) ? '&' : '?') . 'uo=8&at='.$token;
		} else {
			$data['url'] = '';
		}
	}
	return $data;
}

function mbt_apple_buybutton_preview() {
	if(empty($_REQUEST['data'])) { die(); }
	$host = parse_url($_REQUEST['data'], PHP_URL_HOST);
	if(mbt_is_genius_link($_REQUEST['data'])) {
		echo('<span class="mbt_admin_message_success">'.__('Valid Genius Link', 'mybooktable').'</span>');
	} else if(stripos($host, 'itunes.apple.com') === false) {
		echo('<span class="mbt_admin_message_failure">'.__('Invalid product link', 'mybooktable').'</span>');
	} else {
		echo('<span class="mbt_admin_message_success">'.__('Valid product link', 'mybooktable').'</span>');
	}
	die();
}

function mbt_apple_buybutton_editor($editor, $data, $id, $store) {
	if($data['store'] == 'ibooks' or $data['store'] == 'itunes') {
		$editor .= '
		<script type="text/javascript">
			var url = jQuery("#'.$id.'_url");
			url.before(jQuery("<div class=\"mbt_feedback_above mbt_feedback\"></div>"));
			url.addClass("mbt_feedback_refresh mbt_feedback_refresh_initial");
			url.attr("data-refresh-action", "mbt_apple_buybutton_preview");
			url.attr("data-element", "self");
			if(typeof url.mbt_feedback !== "undefined") { url.mbt_feedback(); }
		</script>';
	}
	return $editor;
}

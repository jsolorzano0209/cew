<?php

/*---------------------------------------------------------*/
/* Amazon Affiliate Settings                               */
/*---------------------------------------------------------*/

function mbtpro_amazon_affiliate_settings_init() {
	remove_filter('mbt_affiliate_settings_render', 'mbt_amazon_affiliate_settings_render');
	add_filter('mbt_affiliate_settings_render', 'mbtpro_amazon_affiliate_settings_render');
	add_action('mbt_settings_save', 'mbtpro_amazon_affiliate_settings_save');
	add_action('wp_ajax_mbtpro_amazon_affiliate_code_refresh', 'mbtpro_amazon_affiliate_code_refresh_ajax');
	add_action('wp_ajax_mbtpro_amazon_onelink_code_refresh', 'mbtpro_amazon_onelink_code_refresh_ajax');
	add_action('wp_footer', 'mbtpro_amazon_onelink_code_insert');
}
add_action('mbtpro_init', 'mbtpro_amazon_affiliate_settings_init');

function mbtpro_amazon_affiliate_code_refresh_ajax() {
	if(!current_user_can('manage_options')) { die(); }
	mbt_update_setting('amazon_buybutton_affiliate_code', $_REQUEST['data']);
	echo(mbtpro_amazon_affiliate_code_feedback());
	die();
}

function mbtpro_amazon_affiliate_code_feedback() {
	$output = '';
	$amazon_affiliate_code = mbt_get_setting('amazon_buybutton_affiliate_code');
	if(!empty($amazon_affiliate_code)) {
		if(preg_match('/^\S+$/', $amazon_affiliate_code)) {
			$output .= '<span class="mbt_admin_message_success">'.__('Valid Affiliate Code', 'mybooktable').'</span>';
		} else {
			$output .= '<span class="mbt_admin_message_failure">'.__('Invalid Affiliate Code', 'mybooktable').'</span>';
		}
	}
	return $output;
}

function mbtpro_amazon_onelink_code_refresh_ajax() {
	if(!current_user_can('manage_options')) { die(); }
	mbt_update_setting('amazon_affiliates_onelink_code', str_replace('\"', '"', $_REQUEST['data']));
	echo(mbtpro_amazon_onelink_code_feedback());
	die();
}

function mbtpro_amazon_onelink_code_feedback() {
	$output = '';
	$amazon_onelink_code = mbt_get_setting('amazon_affiliates_onelink_code');
	if(!empty($amazon_onelink_code)) {
		if(preg_match('/^<script src="[^"]+"><\/script>$/', $amazon_onelink_code)) {
			$output .= '<span class="mbt_admin_message_success">'.__('Valid OneLink Script Code', 'mybooktable').'</span>';
		} else {
			$output .= '<span class="mbt_admin_message_failure">'.__('Invalid OneLink Script Code', 'mybooktable').'</span>';
		}
	}
	return $output;
}

function mbtpro_amazon_affiliate_settings_render() {
	?>
	<table class="form-table">
		<tbody>
			<tr>
				<th>
					<label for="mbt_amazon_buybutton_affiliate_code"><?php _e('Amazon Associates', 'mybooktable'); ?></label>
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
					<div id="mbt-amazon-affiliate-settings-tabs">
						<ul>
							<li><a href="#mbt-amazon-affiliate-code"><?php _e('Affiliate Code', 'mybooktable'); ?></a></li>
							<li><a href="#mbt-amazon-onelink-code"><?php _e('OneLink', 'mybooktable'); ?></a></li>
						</ul>
						<div class="mbt-tab" id="mbt-amazon-affiliate-code">
							<div class="mbt_api_key_feedback mbt_feedback"><?php echo(mbtpro_amazon_affiliate_code_feedback()); ?></div>
							<label for="mbt_amazon_buybutton_affiliate_code"><?php _e('Affiliate Code:', 'mybooktable'); ?></label>
							<input type="text" name="mbt_amazon_buybutton_affiliate_code" id="mbt_amazon_buybutton_affiliate_code" value="<?php echo(mbt_get_setting('amazon_buybutton_affiliate_code')); ?>" class="regular-text">
							<div class="mbt_feedback_refresh" data-refresh-action="mbtpro_amazon_affiliate_code_refresh" data-element="mbt_amazon_buybutton_affiliate_code"></div>
							<p class="description">
								<?php sprintf(__('You can find your Amazon affiliate tracking ID by visiting your %sAmazon Affiliate Homepage%s. The code should be near the top left of the screen and will end in "-20" if you live in the United States of America. %sLearn how to sign up for Amazon Associates.%s', 'mybooktable'), '<a href="https://affiliate-program.amazon.com/gp/associates/network/main.html" target="_blank">', '</a>', '<a href="'.admin_url('admin.php?page=mbt_help&mbt_video_tutorial=amazon_affiliates').'" target="_blank">', '</a>'); ?>
							</p>
						</div>
						<div class="mbt-tab" id="mbt-amazon-onelink-code">
							<div class="mbt_api_key_feedback mbt_feedback"><?php echo(mbtpro_amazon_onelink_code_feedback()); ?></div>

							<label for="mbt_amazon_affiliates_onelink_code"><?php _e('OneLink Script Code:', 'mybooktable'); ?></label>
							<textarea rows="5" cols="60" type="text" name="mbt_amazon_affiliates_onelink_code" id="mbt_amazon_affiliates_onelink_code" style="vertical-align: top;"><?php echo(htmlspecialchars(mbt_get_setting('amazon_affiliates_onelink_code'), ENT_QUOTES)); ?></textarea>
							<div class="mbt_feedback_refresh" data-refresh-action="mbtpro_amazon_onelink_code_refresh" data-element="mbt_amazon_affiliates_onelink_code"></div>
							<p class="description">
								<?php sprintf(__('You can find your Amazon OneLink Script Code by visiting your %sAmazon Affiliate Homepage%s, under Tools > OneLink. %sClick here to learn more about Amazon OneLink%s %sLearn how to sign up for Amazon Associates.%s', 'mybooktable'), '<a href="https://affiliate-program.amazon.com/gp/associates/network/main.html" target="_blank">', '</a>', 'https://affiliate-program.amazon.com/help/node/topic/202164400', '</a>', '<a href="'.admin_url('admin.php?page=mbt_help&mbt_video_tutorial=amazon_affiliates').'" target="_blank">', '</a>'); ?>
							</p>
						</div>
					</div>
					<p class="description"><input type="checkbox" name="mbt_disable_amazon_affiliates" id="mbt_disable_amazon_affiliates" <?php checked(mbt_get_setting('disable_amazon_affiliates'), true); ?> > <?php _e('Disable Amazon Affiliate System', 'mybooktable'); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
	<?php
}

function mbtpro_amazon_affiliate_settings_save() {
	if(isset($_REQUEST['mbt_amazon_buybutton_affiliate_code'])) {
		mbt_update_setting('amazon_buybutton_affiliate_code', $_REQUEST['mbt_amazon_buybutton_affiliate_code']);
		mbt_update_setting('amazon_affiliates_onelink_code', str_replace('\"', '"', $_REQUEST['mbt_amazon_affiliates_onelink_code']));
		mbt_update_setting('disable_amazon_affiliates', isset($_REQUEST['mbt_disable_amazon_affiliates']));
	}
}

function mbtpro_amazon_onelink_code_insert() {
	$onelink_code = mbt_get_setting('amazon_affiliates_onelink_code');
	if($onelink_code && !mbt_get_setting('disable_amazon_affiliates')) {
		echo($onelink_code);
	}
}



/*---------------------------------------------------------*/
/* Linkshare Affiliate Settings                            */
/*---------------------------------------------------------*/

function mbtpro_linkshare_affiliate_settings_init() {
	remove_filter('mbt_affiliate_settings_render', 'mbt_linkshare_affiliate_settings_render');
	add_filter('mbt_affiliate_settings_render', 'mbtpro_linkshare_affiliate_settings_render');
	add_action('mbt_settings_save', 'mbtpro_linkshare_affiliate_settings_save');
	add_action('wp_ajax_mbtpro_linkshare_affiliate_code_refresh', 'mbtpro_linkshare_affiliate_code_refresh_ajax');
}
add_action('mbtpro_init', 'mbtpro_linkshare_affiliate_settings_init');

function mbtpro_linkshare_affiliate_code_refresh_ajax() {
	if(!current_user_can('manage_options')) { die(); }
	mbt_update_setting('linkshare_affiliate_code', $_REQUEST['data']);
	echo(mbtpro_linkshare_affiliate_code_feedback());
	die();
}

function mbtpro_linkshare_affiliate_code_feedback() {
	$output = '';
	$linkshare_affiliate_code = mbt_get_setting("linkshare_affiliate_code");
	if(!empty($linkshare_affiliate_code)) {
		if(preg_match('/^[\S]{11}$/', $linkshare_affiliate_code)) {
			$output .= '<span class="mbt_admin_message_success">'.__('Valid Affiliate Code', 'mybooktable').'</span>';
		} else {
			$output .= '<span class="mbt_admin_message_failure">'.__('Invalid Affiliate Code', 'mybooktable').'</span>';
		}
	}
	return $output;
}

function mbtpro_linkshare_affiliate_settings_render() {
	?>
	<table class="form-table">
		<tbody>
			<tr>
				<th>
					<label for="mbt_linkshare_affiliate_code"><?php _e('LinkShare', 'mybooktable'); ?></label>
					<div class="mbt-affiliate-usedby">
						Used by:
						<ul>
							<li>Kobo Buy Button</li>
						</ul>
					</div>
				</th>
				<td>
					<div class="mbt_api_key_feedback mbt_feedback"><?php echo(mbtpro_linkshare_affiliate_code_feedback()); ?></div>
					<label for="mbt_linkshare_affiliate_code"><?php _e('Affiliate Code:', 'mybooktable'); ?></label>
					<input type="text" name="mbt_linkshare_affiliate_code" id="mbt_linkshare_affiliate_code" value="<?php echo(mbt_get_setting('linkshare_affiliate_code')); ?>" class="regular-text">
					<div class="mbt_feedback_refresh" data-refresh-action="mbtpro_linkshare_affiliate_code_refresh" data-element="mbt_linkshare_affiliate_code"></div>
					<p class="description">
						<?php _e('This is a unique 11-character code that tells Rakuten LinkShare which Publisher a click came from. It is case-sensitive and can be found in any of your affiliate links.', 'mybooktable'); ?>
						<a href="https://rakutenlinkshare.zendesk.com/hc/en-us/articles/201615603-How-do-I-get-links-for-an-advertiser-s-program-"><?php _e('Click here for help finding your affiliate links.', 'mybooktable'); ?></a>
						<br><br>
						<?php _e('Here\'s an example with the ID in red:', 'mybooktable'); ?>
						<br><br>
						<code>http://click.linksynergy.com/fs-bin/click?id=<span style="color:red;font-weight:bold">lMh2Xiq9xN0</span>&amp;offerid=214020.1176&amp;type=3&amp;subid=0</code>
						<br><br>
					</p>
					<p class="description"><input type="checkbox" name="mbt_disable_linkshare_affiliates" id="mbt_disable_linkshare_affiliates" <?php checked(mbt_get_setting('disable_linkshare_affiliates'), true); ?> > <?php _e('Disable Linkshare Affiliate System', 'mybooktable'); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
	<?php
}

function mbtpro_linkshare_affiliate_settings_save() {
	if(isset($_REQUEST['mbt_linkshare_affiliate_code'])) {
		mbt_update_setting('linkshare_affiliate_code', $_REQUEST['mbt_linkshare_affiliate_code']);
		mbt_update_setting('disable_linkshare_affiliates', isset($_REQUEST['mbt_disable_linkshare_affiliates']));
	}
}



/*---------------------------------------------------------*/
/* Commission Junction Affiliate Settings                  */
/*---------------------------------------------------------*/

function mbtpro_cj_affiliate_settings_init() {
	remove_filter('mbt_affiliate_settings_render', 'mbt_cj_affiliate_settings_render');
	add_filter('mbt_affiliate_settings_render', 'mbtpro_cj_affiliate_settings_render');
	add_action('mbt_settings_save', 'mbtpro_cj_affiliate_settings_save');
	add_action('wp_ajax_mbtpro_cj_deep_link_script_refresh', 'mbtpro_cj_deep_link_script_refresh_ajax');
}
add_action('mbtpro_init', 'mbtpro_cj_affiliate_settings_init');

function mbtpro_cj_get_website_id($deep_link_script) {
	$matches = array();
	preg_match('/\<script.*src[\s]*=[\s]*".*am\/([0-9]+)\/include/', $deep_link_script, $matches);
	$website_id = (!empty($matches) and !empty($matches[1])) ? $matches[1] : '';
	return $website_id;
}

function mbtpro_cj_deep_link_script_refresh_ajax() {
	if(!current_user_can('manage_options')) { die(); }
	mbt_update_setting('cj_deep_link_script', wp_unslash($_REQUEST['data']));
	mbt_update_setting('cj_website_id', mbtpro_cj_get_website_id(mbt_get_setting('cj_deep_link_script')));
	echo(mbtpro_cj_deep_link_script_feedback());
	die();
}

function mbtpro_cj_deep_link_script_feedback() {
	$output = '';
	$cj_deep_link_script = mbt_get_setting("cj_deep_link_script");
	if(!empty($cj_deep_link_script)) {
		$website_id = mbtpro_cj_get_website_id($cj_deep_link_script);
		if(!empty($website_id)) {
			$output .= '<span class="mbt_admin_message_success">'.__('Valid Deep Link Script', 'mybooktable').'</span>';
		} else {
			$output .= '<span class="mbt_admin_message_failure">'.__('Invalid Deep Link Script', 'mybooktable').'</span>';
		}
	}
	return $output;
}

function mbtpro_cj_affiliate_settings_render() {
	?>
	<table class="form-table">
		<tbody>
			<tr>
				<th>
					<label for="mbt_cj_deep_link_script"><?php _e('Commission Junction', 'mybooktable'); ?></label>
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
					<div class="mbt_api_key_feedback mbt_feedback"><?php echo(mbtpro_cj_deep_link_script_feedback()); ?></div>
					<label for="mbt_cj_deep_link_script"><?php _e('Deep Link Script:', 'mybooktable'); ?></label>
					<textarea rows="5" cols="60" name="mbt_cj_deep_link_script" id="mbt_cj_deep_link_script" style="vertical-align: top;"><?php echo(esc_html(mbt_get_setting('cj_deep_link_script'))); ?></textarea>
					<div class="mbt_feedback_refresh" data-refresh-action="mbtpro_cj_deep_link_script_refresh" data-element="mbt_cj_deep_link_script" style="vertical-align: top;"></div>
					<p class="description"><?php _e('You can find your Deep Link Script by visiting your <a href="https://members.cj.com/" target="_blank">Commission Junction Account Manager</a>, clicking the "Links" tab, then clicking the "Link Tools" tab, then filling out the "Deep Link Automation" section.', 'mybooktable'); ?></p>
					<p class="description"><input type="checkbox" name="mbt_disable_cj_affiliates" id="mbt_disable_cj_affiliates" <?php checked(mbt_get_setting('disable_cj_affiliates'), true); ?> > <?php _e('Disable Commission Junction Affiliate System', 'mybooktable'); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
	<?php
}

function mbtpro_cj_affiliate_settings_save() {
	if(isset($_REQUEST['mbt_cj_deep_link_script'])) {
		mbt_update_setting('cj_deep_link_script', wp_unslash($_REQUEST['mbt_cj_deep_link_script']));
		mbt_update_setting('cj_website_id', mbtpro_cj_get_website_id(mbt_get_setting('cj_deep_link_script')));
		mbt_update_setting('disable_cj_affiliates', isset($_REQUEST['mbt_disable_cj_affiliates']));
	}
}



/*---------------------------------------------------------*/
/* Amazon Buy Button Affiliate Integration                 */
/*---------------------------------------------------------*/

function mbtpro_amazon_buybutton_init() {
	remove_action('mbt_filter_buybutton_data', 'mbt_filter_amazon_buybutton_data', 10, 2);
	if(mbt_get_setting('disable_amazon_affiliates')) {
		remove_action('wp_ajax_mbt_amazon_buybutton_preview', 'mbt_amazon_buybutton_preview');
		remove_action('mbt_buybutton_editor', 'mbt_amazon_buybutton_editor', 10, 4);
	} else {
		add_filter('mbt_filter_buybutton_data', 'mbtpro_filter_amazon_buybutton_data', 10, 2);
	}
}
add_action('mbtpro_init', 'mbtpro_amazon_buybutton_init');

function mbtpro_filter_amazon_buybutton_data($data, $store) {
	if(($data['store'] == 'amazon' or $data['store'] == 'kindle') and !empty($data['url']) and !mbt_is_genius_link($data['url'])) {
		$tld = mbt_get_amazon_tld($data['url']);
		$aisn = mbt_get_amazon_AISN($data['url']);
		$affiliatecode = mbt_get_setting('amazon_buybutton_affiliate_code');
		$affiliate_tag = '?tag='.(empty($affiliatecode)?'ammbt-20':$affiliatecode);
		$data['url'] = (empty($tld) or empty($aisn)) ? '' : 'http://www.amazon.'.$tld.'/dp/'.$aisn.$affiliate_tag;
	}
	return $data;
}



/*---------------------------------------------------------*/
/* Amazon Kindle Instant Preview Affiliate Integration     */
/*---------------------------------------------------------*/

function mbtpro_kindle_instant_preview_init() {
	add_filter('mbt_get_kindle_instant_preview', 'mbtpro_filter_kindle_instant_preview', 10, 2);
}
add_action('mbtpro_init', 'mbtpro_kindle_instant_preview_init');

function mbtpro_filter_kindle_instant_preview($output, $post_id) {
	$asin = get_post_meta($post_id, 'mbt_unique_id_asin', true);
	if($asin) {
		$affiliatecode = mbt_get_setting('amazon_buybutton_affiliate_code');
		$affiliate_tag = mbt_get_setting('disable_amazon_affiliates') ? '' : '&tag='.(empty($affiliatecode)?'ammbt-20':$affiliatecode);
		$url = 'https://read.amazon.com/kp/card?asin='.$asin.'&preview=inline&linkCode=kpe&ref_=cm_sw_r_kb_dp_Ol2Ywb1Y4HHHK'.$affiliate_tag;
		$output = '<iframe class="mbt_kindle_instant_preview" src="'.$url.'" frameborder="0"></iframe>';
	}
	return $output;
}



/*---------------------------------------------------------*/
/* Barnes & Noble Buy Button Affiliate Integration         */
/*---------------------------------------------------------*/

function mbtpro_bnn_buybutton_init() {
	remove_action('mbt_filter_buybutton_data', 'mbt_filter_bnn_buybutton_data', 10, 2);
	if(mbt_get_setting('disable_cj_affiliates')) {
		remove_action('wp_ajax_mbt_bnn_buybutton_preview', 'mbt_bnn_buybutton_preview');
		remove_action('mbt_buybutton_editor', 'mbt_bnn_buybutton_editor', 10, 4);
	} else {
		add_filter('mbt_filter_buybutton_data', 'mbtpro_filter_bnn_buybutton_data', 10, 2);
	}
}
add_action('mbtpro_init', 'mbtpro_bnn_buybutton_init');

function mbtpro_filter_bnn_buybutton_data($data, $store) {
	if(($data['store'] == 'bnn' or $data['store'] == 'nook') and !empty($data['url']) and !mbt_is_genius_link($data['url'])) {
		$website_id = mbt_get_setting('cj_website_id');
		if(empty($website_id)) { $website_id = 7737731; }
		$data['url'] = mbt_get_cj_affiliate_link($data['url'], $website_id);
	}
	return $data;
}



/*---------------------------------------------------------*/
/* Kobo Buy Button Affiliate Integration                   */
/*---------------------------------------------------------*/

function mbtpro_kobo_buybutton_init() {
	remove_action('mbt_filter_buybutton_data', 'mbt_filter_kobo_buybutton_data', 10, 2);
	if(!mbt_get_setting('disable_linkshare_affiliates')) {
		add_filter('mbt_filter_buybutton_data', 'mbtpro_filter_kobo_buybutton_data', 10, 2);
	}
}
add_action('mbtpro_init', 'mbtpro_kobo_buybutton_init');

function mbtpro_filter_kobo_buybutton_data($data, $store) {
	if($data['store'] == 'kobo' and !empty($data['url']) and !mbt_is_genius_link($data['url'])) {
		$affiliatecode = mbt_get_setting('linkshare_affiliate_code');
		$data['url'] = 'http://click.linksynergy.com/deeplink?id='.(empty($affiliatecode)?'W1PQs9y/1/c':$affiliatecode).'&mid=37217&murl='.urlencode($data['url']);
	}
	return $data;
}



/*---------------------------------------------------------*/
/* Audible.com Buy Button Affiliate Integration            */
/*---------------------------------------------------------*/

function mbtpro2_audible_buybutton_init() {
	remove_action('mbt_filter_buybutton_data', 'mbt_filter_audible_buybutton_data', 10, 2);
	if(mbt_get_setting('disable_cj_affiliates') and mbt_get_setting('disable_amazon_affiliates')) {
		remove_action('wp_ajax_mbt_audible_buybutton_preview', 'mbt_audible_buybutton_preview');
		remove_action('mbt_buybutton_editor', 'mbt_audible_buybutton_editor', 10, 4);
	} else {
		add_filter('mbt_filter_buybutton_data', 'mbtpro2_filter_audible_buybutton_data', 10, 2);
	}
}
add_action('mbtpro2_init', 'mbtpro2_audible_buybutton_init');

function mbtpro2_filter_audible_buybutton_data($data, $store) {
	if($data['store'] == 'audible' and !empty($data['url']) and !mbt_is_genius_link($data['url'])) {
		$parsed = parse_url($data['url']);
		if(isset($parsed['host']) and strpos($parsed['host'], 'amazon') !== false) {
			if(!mbt_get_setting('disable_amazon_affiliates')) {
				$tld = mbt_get_amazon_tld($data['url']);
				$aisn = mbt_get_amazon_AISN($data['url']);
				$affiliatecode = mbt_get_setting('amazon_buybutton_affiliate_code');
				if(empty($affiliatecode)) { $affiliatecode = 'ammbt-20'; }
				$data['url'] = (empty($tld) or empty($aisn)) ? '' : 'http://www.amazon.'.$tld.'/dp/'.$aisn.'?tag='.$affiliatecode;
			}
		} else if(isset($parsed['host']) and strpos($parsed['host'], 'audible') !== false) {
			if(!mbt_get_setting('disable_cj_affiliates')) {
				$website_id = mbt_get_setting('cj_website_id');
				if(empty($website_id)) { $website_id = 7737731; }
				$data['url'] = mbt_get_cj_affiliate_link($data['url'], $website_id);
			}
		}
	}
	return $data;
}

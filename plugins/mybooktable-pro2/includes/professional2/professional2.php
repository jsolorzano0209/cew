<?php

/*---------------------------------------------------------*/
/* Frontend Resources                                      */
/*---------------------------------------------------------*/

function mbtpro2_frontend_init() {
	add_action('wp_enqueue_scripts', 'mbtdev2_enqueue_frontend_resources');
}
add_action('mbtpro2_init', 'mbtpro2_frontend_init');

function mbtdev2_enqueue_frontend_resources() {
	wp_enqueue_style('mbtpro2-frontend-style', plugins_url('frontend.css', __FILE__), array(), MBT_UPGRADEVERSION);
}



/*---------------------------------------------------------*/
/* Grid View Listings Display                              */
/*---------------------------------------------------------*/

function mbtpro2_gridview_init() {
	add_action('mbt_listings_style_settings_render', 'mbtpro2_gridview_settings_render');
	add_action('mbt_settings_save', 'mbtpro2_gridview_settings_save');

	add_filter('mbt_template_folders', 'mbtpro2_add_gridview_template_folder', 30);
	add_action('wp_head', 'mbtpro2_add_gridview_frontend_custom_css');
	if(mbt_get_setting('enable_gridview_hover_box')) { add_action('mbt_book_excerpt_images', 'mbtpro2_gridview_hover_box', 5); }
}
add_action('mbtpro2_init', 'mbtpro2_gridview_init');

function mbtpro2_gridview_settings_render() {
	?>
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="mbt_enable_gridview"><?php _e('Grid View', 'mybooktable'); ?></label></th>
				<td>
					<input type="checkbox" name="mbt_enable_gridview" id="mbt_enable_gridview" <?php checked(mbt_get_setting('enable_gridview'), true); ?> >
					<label for="mbt_enable_gridview"><?php _e('Enable Grid View for Book Listings', 'mybooktable'); ?></label>
					<p class="description"><?php _e('Shows book covers in a responsive grid on book listings.', 'mybooktable'); ?></p>
					<div class="mbt-accordion">
						<h4>Grid View Options</h4>
						<div>
							<input type="checkbox" name="mbt_enable_gridview_shadowbox" id="mbt_enable_gridview_shadowbox" <?php checked(mbt_get_setting('enable_gridview_shadowbox'), true); ?> >
							<label for="mbt_enable_gridview_shadowbox"><?php _e('Enable Grid View Shadowbox', 'mybooktable'); ?></label>
							<p class="description"><?php _e('Shows book details in a shadow box upon clicking the cover image instead of navigating away to the book page.', 'mybooktable'); ?></p>
							<br>
							<input type="checkbox" name="mbt_enable_gridview_hover_box" id="mbt_enable_gridview_hover_box" <?php checked(mbt_get_setting('enable_gridview_hover_box'), true); ?> >
							<label for="mbt_enable_gridview_hover_box"><?php _e('Enable Grid View Hover Details', 'mybooktable'); ?></label>
							<p class="description"><?php _e('Shows book title and buy now button when the user hovers their mouse over the book cover image.', 'mybooktable'); ?></p>
							<br>
							<label for="mbt_gridview_columns"><?php _e('Number of Grid View Columns', 'mybooktable'); ?>:</label>
							<?php $gridview_columns = mbt_get_setting('gridview_columns'); ?>
							<input type="number" min="2" max="8" name="mbt_gridview_columns" id="mbt_gridview_columns" value="<?php echo(empty($gridview_columns) ? 3 : $gridview_columns); ?>" >
							<p class="description"><?php _e('Use this to control the number of columns that the Grid View book listings will have.', 'mybooktable'); ?></p>
						</div>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<?php
}

function mbtpro2_gridview_settings_save() {
	mbt_update_setting('enable_gridview', isset($_REQUEST['mbt_enable_gridview']));
	mbt_update_setting('enable_gridview_shadowbox', isset($_REQUEST['mbt_enable_gridview_shadowbox']));
	mbt_update_setting('enable_gridview_hover_box', isset($_REQUEST['mbt_enable_gridview_hover_box']));
	if(isset($_REQUEST['mbt_gridview_columns'])) { mbt_update_setting('gridview_columns', $_REQUEST['mbt_gridview_columns']); }
}

function mbtpro2_add_gridview_template_folder($folders) {
	if(mbtpro2_is_gridview_active()) {
		$folders[] = plugin_dir_path(__FILE__).'gridview_templates/';
	}
	return $folders;
}

function mbtpro2_gridview_hover_box() {
	if(!mbtpro2_is_gridview_active()) { return; }

	global $post;
	$output = '';
	$output .= '<div class="mbt-grid-book-hover">';
	$output .= apply_filters('mbtpro2_gridview_hover_title', '<h2 class="mbt-book-title" itemprop="name"><a href="'.get_the_permalink().'">'.get_the_title().'</a></h2>');
	$output .= apply_filters('mbtpro2_gridview_hover_buybuttons', '<div class="mbt-book-buybuttons">'.mbt_get_buybuttons($post->ID, true, true).'<div style="clear:both;"></div></div>');
	$output .= '<div class="mbt-grid-book-hover-arrow"></div>';
	$output .= '</div>';
	echo(apply_filters('mbtpro2_gridview_hover_box', $output));
}

function mbtpro2_add_gridview_frontend_custom_css() {
	$gridview_columns = mbt_get_setting('gridview_columns');
	$gridview_columns = min(8, max(2, empty($gridview_columns) ? 3 : $gridview_columns));

	$gridview_margin = 10/($gridview_columns-0.1);
	$gridview_width = $gridview_margin*9;
	echo('<style type="text/css">');
	echo('@media only screen and (min-width: 768px) {');
	echo('.mbt-book-archive .mbt-book.mbt-grid-book { width: '.$gridview_width.'%; margin-right: '.$gridview_margin.'%; }');
	echo('.mbt-book-archive .mbt-book.mbt-grid-book:nth-child('.$gridview_columns.'n+1) { clear: left; }');
	echo('.mbt-book-archive .mbt-book.mbt-grid-book:nth-child('.$gridview_columns.'n) { margin-right: 0; }');
	echo('}');
	echo('</style>');
}

function mbtpro2_is_gridview_active() {
	return apply_filters('mbtpro2_is_gridview_active', mbt_get_setting('enable_gridview') and (mbt_is_booktable_page() or is_post_type_archive('mbt_book') or is_tax('mbt_author') or is_tax('mbt_genre') or is_tax('mbt_series') or is_tax('mbt_tag')));
}



/*---------------------------------------------------------*/
/* Notify Me Buy Button                                    */
/*---------------------------------------------------------*/

function mbtpro2_notifyme_buybutton_init() {
	add_filter('mbt_stores', 'mbtpro2_add_notifyme_buybutton');
	add_filter('mbt_buybutton_editor', 'mbtpro2_notifyme_buybutton_editor', 10, 4);
	remove_action('mbt_integrate_settings_render', 'mbt_mailchimp_api_key_settings_render');
	add_action('mbt_integrate_settings_render', 'mbtpro2_mailchimp_api_key_settings_render');
	add_action('mbt_settings_save', 'mbtpro2_mailchimp_api_key_settings_save');
	add_action('wp_ajax_mbtpro2_mailchimp_api_key_refresh', 'mbtpro2_mailchimp_api_key_refresh_ajax');
}
add_action('mbtpro2_init', 'mbtpro2_notifyme_buybutton_init');

function mbtpro2_add_notifyme_buybutton($stores) {
	$stores['notifyme'] = array('name' => 'Notify Me Button');
	return $stores;
}

function mbtpro2_do_mailchimp_query($api_key, $method, $data = array()) {
	$datacenter = substr(strrchr($api_key, '-'), 1);
	if(empty($datacenter)) { $datacenter = "us1"; }
	$url = "https://{$datacenter}.api.mailchimp.com/2.0/".$method.".json";

	$data['apikey'] = $api_key;

	$options = array(
		'body'		=> $data,
		'sslverify' => false,
		'timeout' 	=> 3,
	);
	$raw_response = wp_remote_post($url, $options);

	if(!is_wp_error($raw_response) and wp_remote_retrieve_response_code($raw_response) == 200) {
		$response = json_decode(wp_remote_retrieve_body($raw_response));
		return $response;
	}
}

function mbtpro2_notifyme_buybutton_editor($output, $data, $id, $store) {
	if($data['store'] == 'notifyme') {
		$output = '';
		$mailchimp_api_key = mbt_get_setting("mailchimp_api_key");

		if(empty($mailchimp_api_key)) {
			$output .= '<span class="mbt_admin_message_failure">'.sprintf(__('You need to set up your MailChimp API Key in order to use this button. <a href="%s" target="_blank">Click here to go to your settings.</a>', 'mybooktable'), admin_url('admin.php?page=mbt_settings&mbt_current_tab=5')).'</span>';
		} else {
			$lists = mbtpro2_do_mailchimp_query($mailchimp_api_key, 'lists/list');
			if(!empty($lists) and is_object($lists) and isset($lists->data)) { $lists = $lists->data; }
			if(!empty($lists) and is_array($lists)) {
				$output .= '<label for="'.$id.'[url]">'.__('Mailchimp List', 'mybooktable').':</label>';
				$output .= '<select name="'.$id.'[url]" class="widefat">';
				$output .= '<option value="">'.__('-- Choose One --', 'mybooktable').'</option>';
				foreach($lists as $list) {
					$output .= '<option value="'.htmlspecialchars($list->subscribe_url_short).'" '.selected($data['url'], $list->subscribe_url_short, false).'>'.$list->name.'</option>';
				}
				$output .= '</select>';
			} else {
				$output .= 'An error occurred trying to query MailChimp!';
			}
		}

		$output .= '<input name="'.$id.'[store]" type="hidden" value="'.$data['store'].'">';
		$output .= __('<p>Use this button to promote your upcoming books! Have users subscribe to a mailing list to be notified when your book is released.</p>', 'mybooktable');
	}
	return $output;
}

function mbtpro2_mailchimp_api_key_refresh_ajax() {
	if(!current_user_can('manage_options')) { die(); }
	mbt_update_setting('mailchimp_api_key', $_REQUEST['data']);
	echo(mbtpro2_mailchimp_api_key_feedback());
	die();
}

function mbtpro2_mailchimp_api_key_feedback() {
	$output = '';
	$mailchimp_api_key = mbt_get_setting("mailchimp_api_key");
	if(!empty($mailchimp_api_key)) {
		$response = mbtpro2_do_mailchimp_query($mailchimp_api_key, 'helper/ping');
		if(!empty($response) and empty($response->error)) {
			$output .= '<span class="mbt_admin_message_success">'.__('Valid API Key', 'mybooktable').'</span>';
		} else {
			$output .= '<span class="mbt_admin_message_failure">'.__('Invalid API Key', 'mybooktable').'</span>';
		}
	}
	return $output;
}

function mbtpro2_mailchimp_api_key_settings_render() {
	?>
	<table class="form-table">
		<tbody>
			<tr>
				<th><?php _e('MailChimp', 'mybooktable'); ?></th>
				<td>
					<div class="mbt_api_key_feedback mbt_feedback"></div>
					<label for="mbt_mailchimp_api_key" class="mbt-integrate-label">API Key:</label>
					<input type="text" name="mbt_mailchimp_api_key" id="mbt_mailchimp_api_key" value="<?php echo(mbt_get_setting('mailchimp_api_key')); ?>" size="60" class="regular-text" />
					<div class="mbt_feedback_refresh mbt_feedback_refresh_initial" data-refresh-action="mbtpro2_mailchimp_api_key_refresh" data-element="mbt_mailchimp_api_key"></div>
					<p class="description"><?php _e('Insert your MailChimp API Key to enable the Notify Me Button. <a href="http://kb.mailchimp.com/accounts/management/about-api-keys" target="_blank">Learn how to get a MailChimp API Key</a>', 'mybooktable'); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
	<?php
}

function mbtpro2_mailchimp_api_key_settings_save() {
	if(isset($_REQUEST['mbt_mailchimp_api_key'])) { mbt_update_setting('mailchimp_api_key', $_REQUEST['mbt_mailchimp_api_key']); }
}



/*---------------------------------------------------------*/
/* Amazon Web Services Settings                            */
/*---------------------------------------------------------*/

function mbtpro2_amazon_web_services_init() {
	remove_action('mbt_integrate_settings_render', 'mbt_amazon_web_services_general_settings_render');
	add_action('mbt_integrate_settings_render', 'mbtpro2_amazon_web_services_general_settings_render');
	add_action('mbt_settings_save', 'mbtpro2_amazon_web_services_settings_save');
	add_action('wp_ajax_mbtpro2_amazon_web_services_api_key_refresh', 'mbtpro2_amazon_web_services_api_key_refresh_ajax');
}
add_action('mbtpro2_init', 'mbtpro2_amazon_web_services_init');

function mbtpro2_amazon_web_services_api_key_refresh_ajax() {
	if(!current_user_can('manage_options')) { die(); }
	mbt_update_setting('aws_access_key_id', $_REQUEST['data']['mbt_aws_access_key_id']);
	mbt_update_setting('aws_secret_key', $_REQUEST['data']['mbt_aws_secret_key']);
	echo(mbtpro2_amazon_web_services_api_key_feedback());
	die();
}

function mbtpro2_amazon_web_services_api_key_feedback() {
	$output = '';

	$aws_accesskey = mbt_get_setting('aws_access_key_id');
	$aws_secretkey = mbt_get_setting('aws_secret_key');

	if(!empty($aws_accesskey) and !empty($aws_secretkey)) {
		$params = array(
			'Service'           => 'AWSECommerceService',
			'Operation'         => 'ItemSearch',
			'ResponseGroup'     => 'ItemAttributes',
			'SearchIndex'       => 'All',
			'Keywords'          => 'test',
		);

		$response = mbtpro2_do_amazon_web_services_query($params);

		if(!empty($response)) {
			$xml = new SimpleXMLElement($response);
			if(!empty($xml->Items->Item)) {
				$output = '<span class="mbt_admin_message_success">'.__('Valid AWS credentials', 'mybooktable').'</span>';
			} else if(!empty($xml->Items->Request->Errors->Error)) {
				$output = '<span class="mbt_admin_message_failure">'.__('AWS Error: ', 'mybooktable').((string)$xml->Items->Request->Errors->Error->Message).'</span>';
			} else if(isset($xml->Error)) {
				$output = '<span class="mbt_admin_message_failure">'.__('AWS Error: ', 'mybooktable').((string)$xml->Error->Message).'</span>';
			} else {
				$output = '<span class="mbt_admin_message_failure">'.__('Unknown AWS Error, please check credentials', 'mybooktable').'</span>';
			}
		} else {
			$output = '<span class="mbt_admin_message_failure">'.__('Invalid AWS Response, please check credentials', 'mybooktable').'</span>';
		}
	}

	return $output;
}

function mbtpro2_amazon_web_services_settings_save() {
	if(isset($_REQUEST['mbt_aws_access_key_id'])) {
		mbt_update_setting('aws_access_key_id', $_REQUEST['mbt_aws_access_key_id']);
		mbt_update_setting('aws_secret_key', $_REQUEST['mbt_aws_secret_key']);
	}
}

function mbtpro2_amazon_web_services_general_settings_render() {
	?>
		<table class="form-table">
			<tbody>
				<tr>
					<th rowspan="3"><label><?php _e('Amazon Web Services', 'mybooktable'); ?></label></th>
					<td style="width:150px">
						<label for="mbt_aws_access_key_id"><?php _e('AWS Access Key ID:', 'mybooktable'); ?></label>
					</td>
					<td style="width:25em">
						<input type="text" id="mbt_aws_access_key_id" name="mbt_aws_access_key_id" value="<?php echo(mbt_get_setting('aws_access_key_id')); ?>" class="regular-text"><br>
					</td>
					<td rowspan="2">
						<div class="mbt_api_key_feedback mbt_feedback"></div>
						<div class="mbt_feedback_refresh mbt_feedback_refresh_initial" data-refresh-action="mbtpro2_amazon_web_services_api_key_refresh" data-element="mbt_aws_access_key_id,mbt_aws_secret_key"></div>
					</td>
				</tr>
				<tr>
					<td>
						<label for="mbt_aws_secret_key"><?php _e('AWS Secret Key:', 'mybooktable'); ?></label>
					</td>
					<td>
						<input type="text" id="mbt_aws_secret_key" name="mbt_aws_secret_key" value="<?php echo(mbt_get_setting('aws_secret_key')); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<p class="description">Insert your AWS Access Key ID and AWS Secret Key in order to <a href="<?php echo(admin_url('admin.php?page=mbt_settings&mbt_current_tab=3')); ?>">enable Amazon Reviews</a> and the Amazon Bulk Book Importer. <a href="https://affiliate-program.amazon.com/gp/advertising/api/detail/main.html" target="_blank"> <?php _e('Learn how to get a your AWS Access Key ID and AWS Secret Key', 'mybooktable'); ?></a></p>
					</td>
				</tr>
			</tbody>
		</table>
	<?php
}

function mbtpro2_do_amazon_web_services_query($params) {
	$associate_tag = mbt_get_setting('amazon_buybutton_affiliate_code');
	if(empty($associate_tag)) { $associate_tag = 'ammbt-20'; }
	$aws_accesskey = mbt_get_setting('aws_access_key_id');
	$aws_secretkey = mbt_get_setting('aws_secret_key');

	if(!empty($aws_accesskey) and !empty($aws_secretkey)) {
		$timestamp = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());

		$params['AssociateTag']   = $associate_tag;
		$params['AWSAccessKeyId'] = $aws_accesskey;
		$params['Timestamp']      = $timestamp;

		$host = 'webservices.amazon.com';
		$path = '/onca/xml';

		ksort($params);
		$params_encoded = array();
		foreach($params as $key => $value) {
			$params_encoded[] = $key . '=' . rawurlencode($value);
		}
		$query_params = implode('&', $params_encoded);

		$canonical  = "GET\n";
		$canonical .= $host."\n";
		$canonical .= $path."\n";
		$canonical .= $query_params;

		$signature = rawurlencode(base64_encode(hash_hmac('sha256', $canonical, $aws_secretkey, true)));

		$request = "http://webservices.amazon.com/onca/xml?".$query_params."&Signature=".$signature;

		$raw_response = wp_remote_get($request, array('timeout' => 3));
		if(!is_wp_error($raw_response) and !empty($raw_response['body'])) {
			return $raw_response['body'];
		}
	}

	return '';
}



/*---------------------------------------------------------*/
/* Amazon Product Reviews                                  */
/*---------------------------------------------------------*/

function mbtpro2_amazon_reviews_init() {
	add_filter('mbt_reviews_boxes', 'mbtpro2_add_amazon_reviews_box');
}
add_action('mbtpro2_init', 'mbtpro2_amazon_reviews_init');

function mbtpro2_add_amazon_reviews_box($reviews) {
	$aws_accesskey = mbt_get_setting('aws_access_key_id');
	$aws_secretkey = mbt_get_setting('aws_secret_key');
	$disabled = (empty($aws_accesskey) or empty($aws_secretkey)) ? '<a href="'.admin_url('admin.php?page=mbt_settings&mbt_current_tab=5').'">'.__('You must input your Amazon Web Services credentials', 'mybooktable').'</a>' : '';
	$reviews['amazon'] = array(
		'name' => __('Amazon Reviews'),
		'callback' => 'mbtpro2_get_amazon_reviews',
		'disabled' => $disabled,
	);
	return $reviews;
}

function mbtpro2_get_amazon_reviews($post_id = 0) {
	if(empty($post_id)) { global $post; $post_id = $post->ID; }
	$output = '';

	$identifier = get_post_meta($post_id, 'mbt_unique_id_asin', true); $id_type = 'ASIN';
	if(empty($identifier)) { $identifier = get_post_meta($post_id, 'mbt_unique_id_isbn', true); $id_type = 'ISBN'; }
	if(!empty($identifier)) {
		$params = array(
			'Service'           => 'AWSECommerceService',
			'Operation'         => 'ItemLookup',
			'ResponseGroup'     => 'Reviews',
			'SearchIndex'       => 'Books',
			'IdType'            => $id_type,
			'ItemId'            => $identifier,
		);
		if($id_type == 'ASIN') { unset($params['SearchIndex']); }

		$response = mbtpro2_do_amazon_web_services_query($params);

		if(!empty($response)) {
			$xml = new SimpleXMLElement($response);
			if(!empty($xml->Items->Item[0]->CustomerReviews->IFrameURL)) {
				$url = preg_replace("/http:\/\//s", "//", ((string)$xml->Items->Item[0]->CustomerReviews->IFrameURL));
				$output .= '<iframe id="mbt_amazon_reviews_box" src="'.$url.'" frameborder="0"></iframe>';
			}
		}
	}

	return $output;
}



/*---------------------------------------------------------*/
/* Amazon Bulk Book Importer                               */
/*---------------------------------------------------------*/

function mbtdev2_amazon_import_init() {
	add_filter('mbt_importers', 'mbtdev2_add_amazon_importer');
	add_filter('mbt_pre_import_book', 'mbtdev2_amazon_books_import_filter_book', 10, 2);
}
add_action('mbtdev2_init', 'mbtdev2_amazon_import_init');

function mbtdev2_add_amazon_importer($importers) {
	$importers['amazon'] = array(
		'name' => __('Amazon Bulk Book Importer', 'mybooktable'),
		'desc' => __('Import your books in bulk from Amazon with a list of ISBNs or ASINs.', 'mybooktable'),
		'get_book_list' => array(
			'render_import_form' => 'mbtdev2_render_amazon_books_import_form',
			'parse_import_form' => 'mbtdev2_parse_amazon_books_import_form',
		),
	);
	return $importers;
}

function mbtdev2_render_amazon_books_import_form() {
	$aws_accesskey = mbt_get_setting('aws_access_key_id');
	$aws_secretkey = mbt_get_setting('aws_secret_key');
	if(empty($aws_accesskey) or empty($aws_secretkey)) {
		echo('<span class="mbt_admin_message_failure">');
		echo(sprintf(__('You need to set up your <a href="%s">Amazon Web Services Credentials</a> in order to use the Amazon Bulk Book Importer.', 'mybooktable'), admin_url('admin.php?page=mbt_settings&mbt_current_tab=5')));
		echo('</span>');
		echo('<script type="text/javascript">jQuery(document).ready(function(){jQuery(".import-button").hide();});</script>');
	} else {
		?>
			<input type="radio" name="mbt_identifier_format" id="mbt_identifier_format_isbn" value="isbn" checked="checked">
			<label for="mbt_identifier_format_isbn" style="padding-right:10px"><?php _e('ISBN'); ?></label>
			<input type="radio" name="mbt_identifier_format" id="mbt_identifier_format_asin" value="asin">
			<label for="mbt_identifier_format_asin"><?php _e('ASIN'); ?></label>
			<h3 id="mbt_amazon_import_title_isbn"><?php _e('Enter your ISBNs here, one per line:', 'mybooktable'); ?></h3>
			<h3 id="mbt_amazon_import_title_asin" style="display:none"><?php _e('Enter your ASINs here, one per line:', 'mybooktable'); ?></h3>
			<textarea name="mbt_identifiers" rows="8" cols="60"></textarea>
			<script type="text/javascript">
				jQuery('#mbt_identifier_format_isbn').change(function(e) {
					jQuery('#mbt_amazon_import_title_isbn').show();
					jQuery('#mbt_amazon_import_title_asin').hide();
				});
				jQuery('#mbt_identifier_format_asin').change(function(e) {
					jQuery('#mbt_amazon_import_title_isbn').hide();
					jQuery('#mbt_amazon_import_title_asin').show();
				});
			</script>
		<?php
	}
}

function mbtdev2_parse_amazon_books_import_form() {
	$identifiers = explode("\n", $_POST['mbt_identifiers']);

	$book_list = array();
	foreach($identifiers as $identifier) {
		$book_list[] = array('format' => $_POST['mbt_identifier_format'], 'identifier' => $identifier);
	}

	return $book_list;
}

function mbtdev2_amazon_books_import_get_asin($identifier) {
	$matches = array();
	preg_match('/(?:^|[^0-9A-Za-z])([0-9A-Za-z]{10})(?:$|[^0-9A-Za-z])/', $identifier, $matches);
	if(empty($matches[1])) { return ''; }
	return $matches[1];
}

function mbtdev2_amazon_books_import_get_isbn($identifier) {
	$matches = array();
	preg_match('/([0-9][0-9\-]{8,}[0-9Xx])/', $identifier, $matches);
	if(empty($matches[1])) { return ''; }
	return preg_replace('/[^0-9Xx]/', '', $matches[1]);
}

function mbtdev2_amazon_books_import_filter_book($data, $import_type) {
	if($import_type !== 'amazon') { return $data; }

	$identifier_parse = $data['format'] == 'asin' ? 'mbtdev2_amazon_books_import_get_asin' : 'mbtdev2_amazon_books_import_get_isbn';
	$identifier = call_user_func($identifier_parse, $data['identifier']);
	if(empty($identifier)) {
		return sprintf(__('Failed to parse %s: "%s"'), $data['format'], $data['identifier']);
	}

	$timestamp = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
	$params = array(
		'Service'           => 'AWSECommerceService',
		'Operation'         => 'ItemLookup',
		'ResponseGroup'     => 'Large',
		'IdType'            => $data['format'] == 'asin' ? 'ASIN' : 'ISBN',
		'ItemId'            => $identifier,
	);
	if($data['format'] !== 'asin') { $params['SearchIndex'] = 'Books'; }

	$response = mbtpro2_do_amazon_web_services_query($params);

	if(empty($response)) {
		return sprintf(__('Unable to retrieve %s %s from Amazon'), $data['format'], $identifier);
	}
	$xml = new SimpleXMLElement($response);
	if(empty($xml->Items->Item[0])) {
		$error = sprintf(__('Unable to retrieve %s %s from Amazon'), $data['format'], $identifier);
		if(!empty($xml->Items->Request->Errors->Error->Message)) { $error .= ': '.(string)$xml->Items->Request->Errors->Error->Message; }
		return $error;
	}
	$item = $xml->Items->Item[0];

	$book = array();
	if(!empty($item->ItemAttributes->Title)) { $book['title'] = (string)$item->ItemAttributes->Title; }
	if(!empty($item->ItemAttributes->Author)) {
		$book['authors'] = array();
		foreach($item->ItemAttributes->Author as $author) {
			$book['authors'][] = (string)$author;
		}
	}
	if(!empty($item->ItemAttributes->Genre)) {
		$book['genres'] = array();
		foreach($item->ItemAttributes->Genre as $genre) {
			$book['genres'][] = (string)$genre;
		}
	}
	if(!empty($item->DetailPageURL)) {
		$store = 'amazon';
		if(!empty($item->ItemAttributes->Binding) and (string)$item->ItemAttributes->Binding == 'Kindle Edition') { $store = 'kindle'; }
		$book['buybuttons'] = array(array('display' => 'button', 'store' => $store, 'url' => (string)$item->DetailPageURL));
	}
	if(!empty($item->ItemAttributes->ListPrice->FormattedPrice)) { $book['price'] = (string)$item->ItemAttributes->ListPrice->FormattedPrice; }
	if(!empty($item->ItemAttributes->Publisher)) { $book['publisher_name'] = (string)$item->ItemAttributes->Publisher; }
	if(!empty($item->ItemAttributes->PublicationDate)) { $date_parts = explode('-', (string)$item->ItemAttributes->PublicationDate); $book['publication_year'] = $date_parts[0]; }
	if(!empty($item->LargeImage->URL)) { $book['image_id'] = mbt_download_and_insert_attachment($item->LargeImage->URL); }
	if(!empty($item->EditorialReviews->EditorialReview[0]->Content)) {
		$excerptlen = 300;
		$content = (string)$item->EditorialReviews->EditorialReview[0]->Content;
		$stripped_content = wp_strip_all_tags($content);
		$book['excerpt'] = (strlen($stripped_content) > $excerptlen) ? substr($stripped_content, 0, $excerptlen).'...' : $stripped_content;
		$book['content'] = $content;
	}
	if(!empty($item->ItemAttributes->ISBN)) { $book['unique_id_isbn'] = (string)$item->ItemAttributes->ISBN; }
	if(!empty($item->ASIN)) { $book['unique_id_asin'] = (string)$item->ItemAttributes->ASIN; }

	return $book;
}

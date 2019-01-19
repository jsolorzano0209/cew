<?php
/*
Plugin Name: MyBookTable Professional Upgrade 2.0
Plugin URI: http://www.authormedia.com/mybooktable/
Description: Adds professional level features to MyBookTable. Requires the <a href="http://www.authormedia.com/mybooktable/" target="_blank">MyBookTable plugin</a>.
Author: Author Media
Author URI: http://www.authormedia.com
Version: 2.2.1
*/

define("MBTPRO2_VERSION", "2.2.1");

/*---------------------------------------------------------*/
/* Initialize Plugin                                       */
/*---------------------------------------------------------*/

function mbtpro2_plugin_information() {
	if($_REQUEST['plugin'] == "mybooktable-pro2") {
		wp_redirect('http://www.authormedia.com/mybooktable');
		die();
	}
}
add_action('install_plugins_pre_plugin-information', 'mbtpro2_plugin_information');

function mbtpro2_plugin_action_links($actions) {
	unset($actions['edit']);
	$actions['upgrade'] = '<a href="http://authormedia.freshdesk.com/support/home" target="_blank">'.__('Get Premium Support', 'mybooktable').'</a>';
	return $actions;
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'mbtpro2_plugin_action_links');

function mbtpro2_init() {
	if(defined('MBTDEV_VERSION') and version_compare(MBTDEV_VERSION, "1.2.0") < 0) { return add_action('admin_notices', 'mbtpro2_old_version_admin_notice'); }
	if(defined('MBTPRO_VERSION') and version_compare(MBTPRO_VERSION, "1.2.0") < 0) { return add_action('admin_notices', 'mbtpro2_old_version_admin_notice'); }

	if(function_exists('mbt_get_upgrade') and mbt_get_upgrade() == 'mybooktable-pro2') {
		define('MBT_UPGRADEVERSION', MBTPRO2_VERSION);

		require_once("includes/professional/professional.php");
		require_once("includes/professional2/professional2.php");

		do_action('mbtpro_init');
		do_action('mbtpro2_init');

		add_filter('mbt_style_folders', 'mbtpro2_add_style_folder', 20);

		add_filter('pre_set_site_transient_update_plugins', 'mbtpro2_update_check');
	}
}
add_action('mbt_init', 'mbtpro2_init', 15);



/*---------------------------------------------------------*/
/* Admin Notices                                           */
/*---------------------------------------------------------*/

function mbtpro2_add_admin_notices() {
	if(!defined('MBT_VERSION')) {
		add_action('admin_notices', 'mbtpro2_no_mybooktable_admin_notice');
	}
}
add_action('admin_init', 'mbtpro2_add_admin_notices');

function mbtpro2_no_mybooktable_admin_notice() {
	?>
	<div id="message" class="error">
		<p>
			<strong><?php _e('You need MyBookTable', 'mybooktable'); ?></strong> &#8211;
			<?php _e('The MyBookTable Professional Add-on cannot function without the MyBookTable plugin', 'mybooktable'); ?>
			<a class="install-button primary" href="https://wordpress.org/plugins/mybooktable/" target="_blank"><?php _e('Download MyBookTable', 'mybooktable'); ?></a>
		</p>
	</div>
	<?php
}

function mbtpro2_old_version_admin_notice() {
	?>
	<div id="message" class="error">
		<p>
			<strong><?php _e('Disable your old MyBookTable Add-ons', 'mybooktable'); ?></strong> &#8211;
			<?php _e('The MyBookTable Professional Upgrade 2.0 replaces your old Add-ons. Please disable them to activate your new features.', 'mybooktable'); ?>
		</p>
	</div>
	<?php
}



/*---------------------------------------------------------*/
/* Styles                                                  */
/*---------------------------------------------------------*/

function mbtpro2_add_style_folder($folders) {
	$folders[] = array('dir' => plugin_dir_path(__FILE__).'styles', 'url' => plugins_url('styles', __FILE__));
	return $folders;
}



/*---------------------------------------------------------*/
/* Updates                                                 */
/*---------------------------------------------------------*/

function mbtpro2_update_check($updates) {
	global $wp_version;
	if(empty($updates->checked)) { return $updates; }

	$api_key = mbt_get_setting('api_key');
	if(!empty($api_key)) {
		$to_send = array(
			'action' => 'basic_check',
			'version' => MBTPRO2_VERSION,
			'api-key' => $api_key,
			'site' => get_bloginfo('url')
		);

		$options = array(
			'timeout' => ((defined('DOING_CRON') && DOING_CRON) ? 30 : 3),
			'body' => $to_send,
			'user-agent' => 'WordPress/'.$wp_version.'; '.get_bloginfo('url')
		);

		$raw_response = wp_remote_post('http://api.authormedia.com/plugins/mybooktable-pro2/update-check', $options);

		if(!is_wp_error($raw_response) and wp_remote_retrieve_response_code($raw_response) == 200) {

			$response = maybe_unserialize(wp_remote_retrieve_body($raw_response));

			if(is_array($response) and !empty($response['new_version']) and !empty($response['package'])) {
				$new_version = $response['new_version'];
				$package = $response['package'];

				$plugin_folder = plugin_basename(dirname(__FILE__));
				$data = (object) array(
					'slug' => 'mybooktable-pro2',
					'new_version' => $new_version,
					'url' => "http://www.mybooktable.com",
					'package' => $package
				);
				$updates->response[$plugin_folder.'/mybooktable-pro2.php'] = $data;
			}
		}
	}

	return $updates;
}

add_filter('plugins_api', 'mbtpro2_plugins_api', 10, 3);
function mbtpro2_plugins_api($result, $action, $args) {
	if(!empty($args->slug) and $args->slug == 'mybooktable-pro2') {
		global $wp_version;
		$data = array(
			"name" => "MyBookTable Professional Upgrade 2.0",
			"slug" => "mybooktable-pro2",
			"version" => MBTPRO2_VERSION,
			"requires" => strval($wp_version),
			"tested" => strval($wp_version),
			"compatibility" => array()
		);
		return (object) $data;
	}
	return $result;
}

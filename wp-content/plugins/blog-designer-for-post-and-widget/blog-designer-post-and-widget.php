<?php
/*
Plugin Name: Blog Designer - Post and Widget
Plugin URL: https://www.wponlinesupport.com/
Description: Display Post on your website with 2 designs(Grid and Slider) with 1 widget.
Version: 1.4
Author: WP Online Support
Author URI: https://www.wponlinesupport.com/
Text Domain: blog-designer-for-post-and-widget
Domain Path: /languages/
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Basic plugin definitions
 * 
 * @package Blog Designer - Post and Widget
 * @since 1.0.0
 */
if( !defined( 'BDPW_VERSION' ) ) {
    define( 'BDPW_VERSION', '1.4' ); // Version of plugin
}
if( !defined( 'BDPW_DIR' ) ) {
    define( 'BDPW_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( !defined( 'BDPW_URL' ) ) {
    define( 'BDPW_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}
if( !defined( 'BDPW_PLUGIN_BASENAME' ) ) {
    define( 'BDPW_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); // Plugin base name
}
if( !defined('BDPW_POST_TYPE') ) {
    define('BDPW_POST_TYPE', 'post'); // Post type name
}
if( !defined('BDPW_CAT') ) {
    define('BDPW_CAT', 'category'); // Plugin category name
}

/**
 * Load Text Domain
 * This gets the plugin ready for translation
 * 
 * @package Blog Designer - Post and Widget
 * @since 1.0
 */
function bdpw_load_textdomain() {
    load_plugin_textdomain( 'blog-designer-for-post-and-widget', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}

// Action to load plugin text domain
add_action('plugins_loaded', 'bdpw_load_textdomain');

/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @package Blog Designer - Post and Widget
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'bdpw_install' );

function bdpw_install() {
    
    // Deactivate Pro Version
    if( is_plugin_active('blog-designer-for-post-and-widget-pro/blog-designer-post-and-widget.php') ) {
        add_action('update_option_active_plugins', 'bdpw_deactivate_pro_version');
    }
}

/**
 * Deactivate lite (Free) version of plugin
 * 
 * @package Blog Designer - Post and Widget
 * @since 1.0
 */
function bdpw_deactivate_pro_version() {
    deactivate_plugins('blog-designer-for-post-and-widget-pro/blog-designer-post-and-widget.php', true);
}

/**
 * Function to display admin notice of activated plugin.
 * 
 * @package Blog Designer - Post and Widget
 * @since 1.0
 */
function bdpw_plugin_admin_notice() {

    global $pagenow;

    $dir                = WP_PLUGIN_DIR . '/blog-designer-for-post-and-widget-pro/blog-designer-post-and-widget.php';
    $notice_link        = add_query_arg( array('message' => 'bdpw-plugin-notice'), admin_url('plugins.php') );
    $notice_transient   = get_transient( 'bdpw_install_notice' );

    // If PRO plugin is active and free plugin exist
    if ( $notice_transient == false && $pagenow == 'plugins.php' && file_exists($dir) && current_user_can( 'install_plugins' ) ) {            
     echo '<div class="updated notice" style="position:relative;">
                <p>
                    <strong>'.sprintf( __('Thank you for activating %s', 'blog-designer-for-post-and-widget'), 'Blog Designer - Post and Widget').'</strong>.<br/>
                    '.sprintf( __('It looks like you had PRO version %s of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it.', 'blog-designer-for-post-and-widget'), '<strong>(<em>Blog Designer - Post and Widget PRO</em>)</strong>' ).'
                </p>
                <a href="'.esc_url( $notice_link ).'" class="notice-dismiss" style="text-decoration:none;"></a>
           </div>';
    }
}

// How it work file, Load admin files
if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
    require_once( BDPW_DIR . '/includes/admin/bdpw-how-it-work.php' );
}

// Action to display notice
add_action( 'admin_notices', 'bdpw_plugin_admin_notice');

// Functions file
require_once( BDPW_DIR . '/includes/bdpw-functions.php' );

// Script Class File
require_once( BDPW_DIR . '/includes/class-bdpw-script.php' );

// Admin Class File
require_once( BDPW_DIR . '/includes/admin/class-bdpw-admin.php' );

// Shortcode File
require_once( BDPW_DIR . '/includes/shortcode/wpsp-post.php' );
require_once( BDPW_DIR . '/includes/shortcode/wpsp-recent-post-slider.php' );

// Widget File
require_once( BDPW_DIR . '/includes/widget/latest-post-widget.php' );

/* Plugin Wpos Analytics Data Starts */
function wpos_analytics_anl27_load() {

    require_once dirname( __FILE__ ) . '/wpos-analytics/wpos-analytics.php';

    $wpos_analytics =  wpos_anylc_init_module( array(
                            'id'            => 27,
                            'file'          => plugin_basename( __FILE__ ),
                            'name'          => 'Blog Designer - Post and Widget',
                            'slug'          => 'blog-designer-post-and-widget',
                            'type'          => 'plugin',
                            'menu'          => 'bdpw-about',
                            'text_domain'   => 'blog-designer-for-post-and-widget',
                            'offers'        => array(
                                                    'trial_premium' => array(
                                                                'button'    => 'TRY FREE FOR 30 DAYS',
                                                                'image'     => 'http://analytics.wponlinesupport.com/?anylc_img=27',
                                                                'link'      => 'https://www.wponlinesupport.com/plugins-plus-themes-powerpack-combo-offer/?ref=blogeditor'
                                                        ),
                                                    ),
                        ));

    return $wpos_analytics;
}

// Init Analytics
wpos_analytics_anl27_load();
/* Plugin Wpos Analytics Data Ends */
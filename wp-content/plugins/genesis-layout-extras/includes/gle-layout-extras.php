<?php
/**
 * Load layout/ frontend relevant functions/ logic for Genesis Child Themes.
 *
 * @package    Genesis Layout Extas
 * @subpackage Frontend/ Layouts
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright (c) 2013, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL-2.0+
 * @link       http://genesisthemes.de/en/wp-plugins/genesis-layout-extras/
 * @link       http://deckerweb.de/twitter
 *
 * @since      2.0.0
 */

/**
 * Prevent direct access to this file.
 *
 * @since 1.7.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


/**
 * Helper function for checking if "Primary Sidebar" or "Secondary Sidebar
 *    (Sidebar Alt)" exist.
 *
 * @since  2.0.0
 *
 * @param  string 	$gle_sidebar
 *
 * @global mixed $wp_registered_sidebars
 *
 * @return bool TRUE if 'Sidebar-Alt' exists (registered), otherwise FALSE.
 */
function ddw_gle_core_sidebars_exists( $gle_sidebar ) {
	
	/** Get global object */
	global $wp_registered_sidebars;

	/** Check for 'Sidebar-Alt' */
	if ( array_key_exists( esc_attr( $gle_sidebar ), $wp_registered_sidebars ) ) {

		return TRUE;

	} else {

		return FALSE;

	}

}  // end of function ddw_gle_core_sidebars_exists


add_action( 'init', 'ddw_gle_register_additional_layouts' );
/**
 * Register additional layout options for Genesis Child Themes.
 *
 * @since  2.0.0
 *
 * @uses   ddw_gle_core_sidebars_exists()
 * @uses   genesis_get_option()
 * @uses   genesis_register_layout()
 */
function ddw_gle_register_additional_layouts() {

	/** Register additional layouts if 'Sidebar-Alt' exists (is registered) */
	if ( ddw_gle_core_sidebars_exists( 'sidebar' ) ) {

		/**
		 * a) special:
		 */

		/** Layout: Sidebars below Content (SBC) (like a special "Full-Width-Content" flavor :) */
		if ( ( function_exists( 'genesis_get_option' ) && genesis_get_option( 'gle_layout_sbc', GLE_SETTINGS_FIELD ) )
			&& ! defined( 'GPEX_PLUGIN_BASEDIR' )
		) {

			genesis_register_layout( 'sidebars-below-content', array(
				'label' => __( 'Sidebars below Content', 'genesis-layout-extras' ),
				'img'   => apply_filters( 'gle_filter_layout_image_sidebars_below_content', esc_url( plugins_url( 'images/gle_sbc.gif', dirname( __FILE__ ) ) ) ),
			) );

		}  // end-if settings check

		/** Layout: Primary below Content (PBC) */
		if ( function_exists( 'genesis_get_option' ) && genesis_get_option( 'gle_layout_pbc', GLE_SETTINGS_FIELD ) ) {

			genesis_register_layout( 'primary-below-content', array(
				'label' => __( 'Primary below Content', 'genesis-layout-extras' ),
				'img'   => apply_filters( 'gle_filter_layout_image_primary_below_content', esc_url( plugins_url( 'images/gle_pbc.gif', dirname( __FILE__ ) ) ) ),
			) );

		}  // end-if settings check

		/** Layout: Primary above Content (PAC) */
		if ( function_exists( 'genesis_get_option' ) && genesis_get_option( 'gle_layout_pac', GLE_SETTINGS_FIELD ) ) {

			genesis_register_layout( 'primary-above-content', array(
				'label' => __( 'Primary above Content', 'genesis-layout-extras' ),
				'img'   => apply_filters( 'gle_filter_layout_image_primary_above_content', esc_url( plugins_url( 'images/gle_pac.gif', dirname( __FILE__ ) ) ) ),
			) );

		}  // end-if settings check

		/** Layout: Header+Nav/Content/Sidebar (HNCS) */
		if ( ! GLE_NO_HNCS_LAYOUT_OPTION
			&& ( function_exists( 'genesis_get_option' ) && genesis_get_option( 'gle_layout_hncs', GLE_SETTINGS_FIELD ) )
		) {

			genesis_register_layout( 'headernav-content-sidebar', array(
				'label' => __( 'Header+Nav/Content/Sidebar', 'genesis-layout-extras' ),
				'img'   => apply_filters( 'gle_filter_layout_image_headernav_content_sidebar', esc_url( plugins_url( 'images/gle_hncs.gif', dirname( __FILE__ ) ) ) ),
			) );

		}  // end-if settings check


		/**
		 * b) 2-column:
		 */

		/** Layout: Content/Sidebar-Alt (CSA) (like a special "Content-Sidebar" flavor :) */
		if ( ( function_exists( 'genesis_get_option' ) && genesis_get_option( 'gle_layout_c_salt', GLE_SETTINGS_FIELD ) )
			&& ! defined( 'GPEX_PLUGIN_BASEDIR' )
		) {

			genesis_register_layout( 'content-sidebaralt', array(
				'label' => __( 'Content/Sidebar-Alt', 'genesis-layout-extras' ),
				'img'   => apply_filters( 'gle_filter_layout_image_content_sidebaralt', esc_url( plugins_url( 'images/gle_c-salt.gif', dirname( __FILE__ ) ) ) ),
			) );

		}  // end-if settings check

		/** Layout: Sidebar-Alt/Content (SAC) (like a special "Sidebar-Content" flavor :) */
		if ( ( function_exists( 'genesis_get_option' ) && genesis_get_option( 'gle_layout_salt_c', GLE_SETTINGS_FIELD ) )
			&& ! defined( 'GPEX_PLUGIN_BASEDIR' )
		) {

			genesis_register_layout( 'sidebaralt-content', array(
				'label' => __( 'Sidebar-Alt/Content', 'genesis-layout-extras' ),
				'img'   => apply_filters( 'gle_filter_layout_image_sidebaralt_content', esc_url( plugins_url( 'images/gle_salt-c.gif', dirname( __FILE__ ) ) ) ),
			) );

		}  // end-if settings check


		/**
		 * c) 3-column:
		 */

		/** Layout: Content/Sidebar-Alt/Sidebar (CSAS) (like a special "Content-Sidebar-Sidebar" flavor :) */
		if ( function_exists( 'genesis_get_option' ) && genesis_get_option( 'gle_layout_c_salt_s', GLE_SETTINGS_FIELD ) ) {

			genesis_register_layout( 'content-sidebaralt-sidebar', array(
				'label' => __( 'Content/Sidebar-Alt/Sidebar', 'genesis-layout-extras' ),
				'img'   => apply_filters( 'gle_filter_layout_image_content_sidebaralt_sidebar', esc_url( plugins_url( 'images/gle_c-salt-s.gif', dirname( __FILE__ ) ) ) ),
			) );

		}  // end-if settings check

		/** Layout: Sidebar/Sidebar-Alt/Content (SSAC) (like a special "Sidebar-Sidebar-Content" flavor :) */
		if ( function_exists( 'genesis_get_option' ) && genesis_get_option( 'gle_layout_s_salt_c', GLE_SETTINGS_FIELD ) ) {

			genesis_register_layout( 'sidebar-sidebaralt-content', array(
				'label' => __( 'Sidebar/Sidebar-Alt/Content', 'genesis-layout-extras' ),
				'img'   => apply_filters( 'gle_filter_layout_image_sidebar_sidebaralt_content', esc_url( plugins_url( 'images/gle_s-salt-c.gif', dirname( __FILE__ ) ) ) ),
			) );

		}  // end-if settings check

		/** Layout: Sidebar/Content/Sidebar-Alt (SCSA) (like a special "Sidebar-Content-Sidebar" flavor :) */
		if ( function_exists( 'genesis_get_option' ) && genesis_get_option( 'gle_layout_s_c_salt', GLE_SETTINGS_FIELD ) ) {

			genesis_register_layout( 'sidebar-content-sidebaralt', array(
				'label' => __( 'Sidebar/Content/Sidebar-Alt', 'genesis-layout-extras' ),
				'img'   => apply_filters( 'gle_filter_layout_image_sidebar_content_sidebaralt', esc_url( plugins_url( 'images/gle_s-c-salt.gif', dirname( __FILE__ ) ) ) ),
			) );

		}  // end-if settings check

	}  // end if 'Sidebar Alt' check

}  // end of function ddw_gle_register_additional_layouts


/**
 * Helper function for returning layout key 'sidebars-below-content'.
 *
 * @since  2.0.0
 *
 * @return string 'sidebars-below-content'
 */
function __gle_return_sidebars_below_content() {

	return 'sidebars-below-content';

}  // end of function __gle_return_sidebars_below_content


/**
 * Helper function for returning layout key 'content-sidebaralt'.
 *
 * @since  2.0.0
 *
 * @return string 'content-sidebaralt'
 */
function __gle_return_content_sidebaralt() {

	return 'content-sidebaralt';

}  // end of function __gle_return_content_sidebaralt


/**
 * Helper function for returning layout key 'sidebaralt-content'.
 *
 * @since  2.0.0
 *
 * @return string 'sidebaralt-content'
 */
function __gle_return_sidebaralt_content() {

	return 'sidebaralt-content';

}  // end of function __gle_return_sidebaralt_content
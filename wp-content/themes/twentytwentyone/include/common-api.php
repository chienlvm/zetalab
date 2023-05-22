<?php
/**
 * Common API: Default common hooks
 *
 * @package chienlvm
 * @subpackage common
 * @since 1.1.0
 */

// common login logout
add_action( 'user_login_out', 'user_login_out' );

add_action( 'admin_login_out', 'user_login_out' );

// // Bookmark hooks.
add_action( 'use-access-page', 'wp_link_manager_disabled_message' );

// // Dashboard hooks.
// add_action( 'activity_box_end', 'wp_dashboard_quota' );
// add_action( 'welcome_panel', 'wp_welcome_panel' );


// Prerendering.
if ( ! is_customize_preview() ) {
	// add_filter( 'admin_print_styles', 'wp_resource_hints', 1 );
}

// Privacy hooks.
// add_filter( 'wp_privacy_personal_data_erasure_page', 'wp_privacy_process_personal_data_erasure_page', 10, 5 );
// add_filter( 'wp_privacy_personal_data_export_page', 'wp_privacy_process_personal_data_export_page', 10, 7 );
// add_action( 'wp_privacy_personal_data_export_file', 'wp_privacy_generate_personal_data_export_file', 10 );
// add_action( 'wp_privacy_personal_data_erased', '_wp_privacy_send_erasure_fulfillment_notification', 10 );

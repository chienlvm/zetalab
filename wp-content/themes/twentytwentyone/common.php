<?php
/**
 * Common API
 * Include all file define api
 * @package chienlvm
 * @subpackage common
 * @since 1.1.0
 */

/** WordPress Administration Hooks */
require_once ABSPATH . 'twentytwentyone/includes/common-api.php';


/** WordPress Multisite support API */
if ( is_multisite() ) {
	// require_once ABSPATH . 'wp-admin/includes/ms-admin-filters.php';
	// require_once ABSPATH . 'wp-admin/includes/ms.php';
	// require_once ABSPATH . 'wp-admin/includes/ms-deprecated.php';
}

<?php

/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */
add_action('init', 'create_securityID');
if (!function_exists('create_securityID')) {
	// khoi tao securityId
	function create_securityID()
	{
		require get_template_directory() . '/classes/SecurityIdManager.php';
		$session = new SecurityIdManager();
		$session->setSecurityId();
	}
}
add_action('admin_post_custom_login', 'custom_login');
add_action('admin_post_nopriv_custom_login', 'custom_login');


function custom_login()
{
	$ip_address = $_SERVER['REMOTE_ADDR'];
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$dataError = array(
		'error' => 'User name and password incorrect'
	);
	// response error when email or password not input
	if (empty($_POST['email']) || empty($_POST['password'])) {
		wp_send_json_success($dataError);
		exit;
	}

	$username = sanitize_text_field($_POST['email']);
	$password = sanitize_text_field($_POST['password']);
	$remember = !empty($_POST['remember']) ? sanitize_text_field($_POST['remember']) : 'off';

	global $wpdb;
	$table_name = $wpdb->prefix . 'tb_login';

	$query = $wpdb->prepare("SELECT * FROM $table_name WHERE email = %s and del_f = 0", $username);
	$user = $wpdb->get_row($query);
	// use login multi times
	if ($user && $user->login_fail_num >= 7) {
		$dataErrorMultiTimes = array(
			'error' => 'Account have lock <br />Please contact admin'
		);
		wp_send_json_success($dataErrorMultiTimes);
		exit;
	}
	$isLogin = false;
	// check password
	if ($user && wp_check_password($password, $user->password)) {
		// check account have active or not
		if (($user->status) == 1) {
			$dataError = array(
				'error' => 'Account have lock <br />Please contact admin'
			);
			wp_send_json_success($dataError);
			exit;
		}
		// check account have active or not
		if (($user->status) == 2) {
			$dataError = array(
				'error' => 'Account not verify <br />Please get verification codes from email!'
			);
			wp_send_json_success($dataError);
			exit;
		}
		// check account have active or not
		if (($user->status) == 0) {
			$_SESSION['isLogin'] = true;
			$isLogin = $_SESSION['isLogin'];
		} else {
			$dataError = array(
				'error' => 'System error'
			);
			wp_send_json_success($dataError);
			exit;
		}
	}
	if (!$isLogin) {
		// update status login fail
		$data_update = array(
			'ip' => $ip_address,
			'login_fail_num' => $user->login_fail_num + 1,
			'browser_login' => $user_agent,
			'del_f' => 0, // status 0 la active, 1 la da xoa
			'status' => 1, // status 0 la active, 1 la lock
			'device_login' => $user_agent,
			'create_dt' => current_time('Y-m-d H:i:s'),
			'update_by' => '$user->member_no'
		);
		$data_where_login_fail = array(
			'email' => $user->email,
		);

		$updated = $wpdb->update($table_name, $data_update, $data_where_login_fail, array('%s', '%d', '%s', '%d', '%d', '%s', '%s', '%s'), array('%s'));
		if ($updated !== 0) {
			$dataError = array(
				'error' => 'Account or password Incorrect'
			);
			wp_send_json_success($dataError);
			exit;
		}
	} else {
		// upadate log login
		$data_update = array(
			'ip' => $ip_address,
			'last_login' => current_time('Y-m-d H:i:s'),
			'login_fail_num' => 0,
			'browser_login' => $user_agent,
			'del_f' => 0, // status 0 la active, 1 la da xoa
			'status' => 0, // status 0 la active, 1 la lock
			'device_login' => $user_agent,
			'create_dt' => current_time('Y-m-d H:i:s'),
			'update_by' => '$user->member_no'
		);
		$data_where_login_fail = array(
			'email' => $user->email,
		);

		$updated = $wpdb->update($table_name, $data_update, $data_where_login_fail, array('%s', '%s', '%d', '%s', '%d', '%d', '%s', '%s', '%s'), array('%s'));
		if ($updated !== 0) {
			// create session
			$_SESSION['member_no'] = $user->member_no;
			$_SESSION['email'] = $user->email;
			$_SESSION['del_f'] = $user->del_f;
			$_SESSION['status'] = $user->status;
			$_SESSION['role'] = $user->role;
			$_SESSION['device_login'] = $user->device_login;
			$_SESSION['login_fail_num'] = $user->login_fail_num;
			$_SESSION['create_dt'] = $user->create_dt;

			echo var_dump($_SESSION);
			if ($remember == 'on') {
				// set session in cookie if save 
				setcookie('custom_login_session', session_id(), time() + 60 * 60 * 24 * 7);
				$table_session = $wpdb->prefix . 'TB_USER_SESSION';
				$memberNo = $user->member_no;
				$sessionName = session_id();
				$browserLogin = get_device_info()['browser'];
				$dt = date("Y-m-d");
				$dateExpired = date( "Y-m-d", strtotime( "$dt +2 month" ) );
				// register login
				$data_insert = array(
					'member_no' => $memberNo,
					'session_name' => $sessionName,
					'browser_login' => $browserLogin, // status 0 la active, 1 la da xoa
					'expiration_dt' => $dateExpired,
					'del_f' => 0,
					'create_by' => $user->email,
				);
				$wpdb->insert($table_session, $data_insert);
			}
			$dataSuccess = array(
				'message' => 'Logged in successfully'
			);
			wp_send_json_success($dataSuccess);
		}
	}



	wp_redirect(home_url('/profile'));
	exit;
}

// This theme requires WordPress 5.3 or later.
if (version_compare($GLOBALS['wp_version'], '5.3', '<')) {
	require get_template_directory() . '/inc/back-compat.php';
}

if (!function_exists('twenty_twenty_one_setup')) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 * @since Twenty Twenty-One 1.0
	 *
	 * @return void
	 */
	function twenty_twenty_one_setup()
	{
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Twenty Twenty-One, use a find and replace
		 * to change 'twentytwentyone' to the name of your theme in all the template files.
		 */
		load_theme_textdomain('twentytwentyone', get_template_directory() . '/languages');
	}
}
add_action('after_setup_theme', 'twenty_twenty_one_setup');


/**
 * Enqueue scripts and styles.
 *
 * @since Twenty Twenty-One 1.0
 *
 * @return void
 */
function twenty_twenty_one_scripts()
{

	wp_enqueue_style('pe-icon-7-stroke.css', get_template_directory_uri() . '/assets/css/pe-icon-7-stroke.css', array(), wp_get_theme()->get('Version'));
	wp_enqueue_style('materialdesignicons.min.css', get_template_directory_uri() . '/assets/css/materialdesignicons.min.css', array(), wp_get_theme()->get('Version'));
	wp_enqueue_style('bootstrap.min.css', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), wp_get_theme()->get('Version'));
	wp_enqueue_style('magnific-popup.css', get_template_directory_uri() . '/assets/css/magnific-popup.css', array(), wp_get_theme()->get('Version'));
	wp_enqueue_style('twenty-twenty-one-style', get_template_directory_uri() . '/style.css', array(), wp_get_theme()->get('Version'));
	wp_enqueue_style('cyan.css', get_template_directory_uri() . '/assets/css/colors/cyan.css', array(), wp_get_theme()->get('Version'));

	// add top page
	wp_enqueue_style('top.css', get_template_directory_uri() . '/assets/css/top/index.css', array(), wp_get_theme()->get('Version'));
	// checkfile
	wp_enqueue_script(
		'chuck',
		get_template_directory_uri() . '/assets/js/chucks/chunk.js',
		array(),
		'1.0.0',
		true
	);
	// add top page
	wp_enqueue_script(
		'top',
		get_template_directory_uri() . '/assets/js/top/index.js',
		array(),
		'1.0.0',
		true
	);
	wp_enqueue_script(
		'register',
		get_template_directory_uri() . '/assets/js/register/index.js',
		array(),
		'1.0.0',
		true
	);
}
add_action('wp_enqueue_scripts', 'twenty_twenty_one_scripts');

// SVG Icons class.
require get_template_directory() . '/classes/class-twenty-twenty-one-svg-icons.php';

// Custom color classes.
require get_template_directory() . '/classes/class-twenty-twenty-one-custom-colors.php';
new Twenty_Twenty_One_Custom_Colors();

// Enhance the theme by hooking into WordPress.
require get_template_directory() . '/inc/template-functions.php';

// Menu functions and filters.
require get_template_directory() . '/inc/menu-functions.php';

// Custom template tags for the theme.
require get_template_directory() . '/inc/template-tags.php';

// Customizer additions.
require get_template_directory() . '/classes/class-twenty-twenty-one-customize.php';
new Twenty_Twenty_One_Customize();

// Block Patterns.
require get_template_directory() . '/inc/block-patterns.php';

// Block Styles.
require get_template_directory() . '/inc/block-styles.php';

// Dark Mode.
require_once get_template_directory() . '/classes/class-twenty-twenty-one-dark-mode.php';
new Twenty_Twenty_One_Dark_Mode();

/**
 * Calculate classes for the main <html> element.
 *
 * @since Twenty Twenty-One 1.0
 *
 * @return void
 */
function twentytwentyone_the_html_classes()
{
	/**
	 * Filters the classes for the main <html> element.
	 *
	 * @since Twenty Twenty-One 1.0
	 *
	 * @param string The list of classes. Default empty string.
	 */
	$classes = apply_filters('twentytwentyone_html_classes', '');
	if (!$classes) {
		return;
	}
	echo 'class="' . esc_attr($classes) . '"';
}
function custom_page_router()
{
	if (is_page('sign-in')) {
		include(get_template_directory() . '/page-login.php');
		exit;
	}
	if (is_page('confirmation_token')) {
		include(get_template_directory() . '/confirmation_token.php');
		exit;
	}

	if (is_page('confirmation_token')) {
		include(get_template_directory() . '/confirmation_token.php');
		exit;
	}
}

add_action('template_redirect', 'custom_page_router');
require get_template_directory() . '/api.php';

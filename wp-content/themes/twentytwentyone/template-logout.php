<?php
/*
Template name: Page - logout
*/
?>

<?php
/**
 * method customs_log_out
 */
function customs_log_out() {
  // logout for wp common
  wp_destroy_current_session();
  wp_clear_auth_cookie();
  wp_set_current_user( 0 );
  // logout custom
  custom_destroy_current_session();
  custom_wp_clear_auth_cookie();
  // redirect sang trang chu
  wp_redirect(home_url());
  exit;
}

function custom_destroy_current_session() {
  global $wpdb;
  $table_name = $wpdb->prefix . 'TB_USER_SESSION'; // Tên bảng trong cơ sở dữ liệu WordPress
  $sessionId = session_id();
  $wpdb->delete( $table_name, array( 'session_name' => $sessionId ) );
}

function custom_wp_clear_auth_cookie() {
  setcookie( 'custom_login_session', ' ', time() + 60 * 60 * 24 * 7 );
}
<?php
// Khai báo các hằng số
use Detection\MobileDetect;

define('API_NAMESPACE', 'api/v1');
define('API_ENDPOINT', 'register');
define('API_ENDPOINT_VERIFY', 'confirmToken');
define('API_ENDPOINT_SUBSCRIPTION', 'subscription');

require get_template_directory() . '/lib/MobileDetect.php';
// import email
require_once get_template_directory() . '/email/sendMail.php';

// Đăng ký API endpoint
add_action('rest_api_init', function () {
  register_rest_route(API_NAMESPACE, '/' . API_ENDPOINT_VERIFY, array(
    'methods' => 'POST',
    'content_type' => 'application/json',
    'callback' => 'confirmToken',
  ));
  register_rest_route(API_NAMESPACE, '/' . API_ENDPOINT, array(
    'methods' => 'POST',
    'content_type' => 'application/json',
    'callback' => 'register',
  ));
  register_rest_route(API_NAMESPACE, '/' . API_ENDPOINT_SUBSCRIPTION, array(
    'methods' => 'POST',
    'content_type' => 'application/json',
    'callback' => 'subscription',
  ));
});
function is_email_exists($email)
{
  global $wpdb;
  $result = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}tb_login WHERE email = %s", $email));
  return (bool) $result;
}
function is_send_email($to, $url)
{
  $mailer = new GmailMailer(EMAIL_FROM, PASSWORD_EMAIL_SEND_FROM);
  $to = $to;
  $subject = 'Verify your email to create your Zetabim account  ';
  include(get_template_directory() . '/email/mailTemplate.php');
  $body = str_replace(
    array('{url}', '{emailFrom}'),
    array($url, $mailer->getEmailFrom()),
    $templateEmail
  );
  $result = $mailer->sendMail($to, $subject, $body);

  if ($result) {
    return true;
  } else {
    return false;
  }
}
function get_device_info()
{
  $detect = new MobileDetect();
  $device = "";
  if ($detect->isMobile()) {
    $device = $detect->isiPhone() ? 'iPhone_' . $detect->version('iPhone') : 'Android_' . $detect->version('Android');
  } elseif ($detect->isTablet()) {
    $device = ($detect->isiPad() || $detect->isiPadOS()) ? 'iPad_' . $detect->version('iPad') : 'Tablet_' . $detect->version('Android');
  } else {
    $device = "Desktop";
  }
  $device_info = array(
    'browser' => $detect->getUserAgent(),
    'device' => $device,
    'is_mobile' => $detect->isMobile(),
    'is_tablet' => $detect->isTablet(),
    'is_desktop' => "Desktop",
  );

  return $device_info;
}
function register_account($token, $request)
{
  global $wpdb;
  $table_name = $wpdb->prefix . 'TB_LOGIN';
  $ip_address = $_SERVER['REMOTE_ADDR'];
  $params = $request->get_params();
  $device_info = get_device_info();
  $email = $params['email'];
  $password = wp_hash_password($params['password']);
  $name = isset($params['name']) ? $params['name'] : "";
  // register login
  $data_insert = array(
    'email' => $email,
    'password' => $password,
    'ip' => $ip_address, // status 0 la active, 1 la da xoa
    'last_login' => current_time('Y-m-d H:i:s'),
    'browser_login' => $device_info['browser'],
    'del_f' => 0,
    'status' => 2, // 0 : active; 1: block; 3 : noActive
    'token' => $token,
    'role' => 0,
    'device_login' => $device_info['device'],
    'create_by' => $email,
  );

  $register_tb_login = $wpdb->insert($table_name, $data_insert);
  if ($register_tb_login !== false) {
    // register tb_member
    $parent_id = $wpdb->insert_id;
    $tb_member = $wpdb->prefix . 'TB_MEMBER';
    $data_insert_member = array(
      'member_no' => intval($parent_id),
      'email' => $email,
      'name' => $name,
      'del_f' => 0,
      'create_by' => $email,
    );
    $register_tb_member = $wpdb->insert($tb_member, $data_insert_member);
    if (!$register_tb_member) {
      $dataError = array(
        'error' => 'System error'
      );
      $response = rest_ensure_response($dataError);
      return $response;
    }
  } else {
    $dataError = array(
      'error' => 'System error'
    );
    $response = rest_ensure_response($dataError);
    return $response;
  }
}

if (!function_exists('register')) {
  function register(WP_REST_Request $request)
  {
    $params = $request->get_params();
    $email = $params['email'];
    $password = $params['password'];
    $name = isset($params['name']) ? $params['name'] : "";
    $token = wp_generate_password(20, false);
    // response error when email not input
    if (empty($email)) {
      $dataError = array(
        'error' => 'Email not empty'
      );
      $response = rest_ensure_response($dataError);
      return $response;
    }
    // response error when password not input
    if (empty($password)) {
      $dataError = array(
        'error' => 'Password not empty'
      );
      $response = rest_ensure_response($dataError);
      return $response;
    }
    // check email đã tồn tại ở table TB_LOGIN hay chưa
    if (is_email_exists($email)) {
      $dataError = array(
        'error' => 'Email already exists'
      );
      $response = rest_ensure_response($dataError);
      return $response;
    } else {
      $urlVerify = 'https://zetabim.com/users/confirmation?confirmation_token=' . $token;
      register_account($token, $request);
      // echo $urlVerify;
      // is_send_email('fcluongloc@yopmail.com', 'https://zetabim.com');
      // create link verify
      // format url: users/confirmation?confirmation_token=8-vsMgnxA3CGrDmuyNyw
    }
    // create token
  }
}

/**
 * confirmToken
 */
if (!function_exists('confirmToken')) {
  function confirmToken(WP_REST_Request $request)
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tb_login';

    $params = $request->get_params();
    $token = isset($params['token']) ? $params['token'] : '';
    // response error when email not input
    if (empty($token)) {
      $dataError = array(
        'error' => 'Token not empty'
      );
      $response = rest_ensure_response($dataError);
      return $response;
    }
    $results = getUserByToken($token);
    if ($results) {
      // Chuyển đổi thành đối tượng DateTime
      $dateTimeObj = DateTime::createFromFormat('Y-m-d H:i:s', $results['create_dt']);
      // Lấy ngày dạng chuỗi
      $date = $dateTimeObj->format('Y-m-d');
      $diff = strtotime(date('Y-m-d')) - strtotime($date);
      $diffInDays = round($diff / (60 * 60 * 24));
      if ($diffInDays > 30) {
        $dataError = array(
          'error' => 'Token expired'
        );
        $response = rest_ensure_response($dataError);
        return $response;
      }
      // update db
      $data_update = array(
        'del_f' => 0, // status 0 la active, 1 la da xoa
        'status' => 0, // status 0 la active, 1 la lock
        'update_by' => $results['email'],
        'token' => null
      );
      $data_where_confirm_token = array(
        'email' => $results['email'],
        'token' => $results['token'],
      );

      $table_name = $wpdb->prefix . 'tb_login';
      $updated = $wpdb->update($table_name, $data_update, $data_where_confirm_token, array('%d', '%d', '%s'), array('%s', '%s'));
      if ($updated !== 0) {
        $_SESSION['member_no'] = $results['member_no'];
        $_SESSION['email'] = $results['email'];
        $_SESSION['del_f'] = $results['del_f'];
        $_SESSION['status'] = $results['status'];
        $_SESSION['role'] = $results['role'];
        $_SESSION['device_login'] = $results['device_login'];
        $_SESSION['login_fail_num'] = $results['login_fail_num'];
        $_SESSION['create_dt'] = $results['create_dt'];
      }
    } else {
      $dataError = array(
        'error' => 'Token inValid'
      );
      $response = rest_ensure_response($dataError);
      return $response;
    }
    $dataSuccess = array(
      'message' => 'Success'
    );
    return rest_ensure_response($dataSuccess);
  }
}
function getUserByToken($token)
{
  global $wpdb;
  $table_name = $wpdb->prefix . 'TB_LOGIN'; // Tên bảng trong cơ sở dữ liệu WordPress
  $query = $wpdb->prepare("SELECT * FROM {$table_name} WHERE status = 2 and token = %s", $token);
  $results = $wpdb->get_results($query);
  $data = array();
  foreach ($results as $result) {
    $data = array(
      'member_no' => $result->member_no,
      'email' => $result->email,
      'token' => $result->token,
      'status' => $result->status,
      'role' => $result->role,
      'device_login' => $result->device_login,
      'login_fail_num' => $result->login_fail_num,
      'create_dt' => $result->create_dt,
      'del_f' => $result->del_f,
    );
  }
  return $data;
}

function getPlanSub($subKey)
{
  if ($subKey === '01') {
    return '01';
  } else if ($subKey === '02') {
    return '02';
  } else if ($subKey === '03') {
    return '03';
  } else {
    return '';
  }
}
function getSubExpired($subKey)
{
  $dt = date("Y-m-d");
  if ($subKey === '01') {
    return date("Y-m-d", strtotime("$dt +1 month"));
  } else if ($subKey === '02') {
    return date("Y-m-d", strtotime("$dt +1 month"));
  } else if ($subKey === '03') {
    return date("Y-m-d", strtotime("$dt +12 month"));;
  } else {
    return '';
  }
}

if (!function_exists('subscription')) {
  function subscription(WP_REST_Request $request)
  {
    $params = $request->get_params();
    $subKey = $params['sub_key'];
    // $memberNo = $_SESSION['member_no'];
    $_SESSION['email'] = 'test';
    $memberNo = '33';
    if (empty($subKey) || $subKey !== '01' || $subKey !== '02' || $subKey !== '03') {
      $dataError = array(
        'error' => 'System error'
      );
      $response = rest_ensure_response($dataError);
      return $response;
    }

    global $wpdb;
    $table_TB_SUBSCRIPTION = $wpdb->prefix . 'TB_SUBSCRIPTION';

    // register login
    $data_insert = array(
      'sub_key' => $subKey,
      'member_no' => $memberNo,
      'sub_create' => current_time('Y-m-d'),
      'sub_plan' => getPlanSub($subKey),
      'del_f' => 0,
      'sub_expired' => getSubExpired($subKey),
      'create_by' => $_SESSION['email'],
    );

    $register_TB_SUBSCRIPTION = $wpdb->insert($table_TB_SUBSCRIPTION, $data_insert);
    if ($register_TB_SUBSCRIPTION !== false) {
      $dataOK = array(
        'message' => 'OK'
      );
      $response = rest_ensure_response($dataOK);
      return $response;
    } else {
      $dataError = array(
        'error' => 'System error'
      );
      $response = rest_ensure_response($dataError);
      return $response;
    }
  }
}

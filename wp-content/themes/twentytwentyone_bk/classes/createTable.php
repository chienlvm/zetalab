<?php
/*
Plugin Name: create table
Description: create table.
Version: 1.0.0
*/

function my_custom_plugin_activate()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    
    $table_TB_LOGIN = $wpdb->prefix . 'TB_LOGIN';
    $sql_TB_LOGIN = "CREATE TABLE IF NOT EXISTS $table_TB_LOGIN (
      member_no VARCHAR(255) NOT NULL,
      email VARCHAR(255) NOT NULL,
      password VARCHAR(255) NOT NULL,
      ip VARCHAR(255) NOT NULL,
      last_login datetime NOT NULL,
      login_fail_num int(10) DEFAULT 0,
      browser_login VARCHAR(255) NULL,
      del_f int(10) DEFAULT 0,
      status int(10) DEFAULT 0,
      token VARCHAR(255) NULL,
      role int(10) DEFAULT 0,
      device_login VARCHAR(255) NULL,
      create_dt DATETIME DEFAULT CURRENT_TIMESTAMP,
      update_dt DATETIME ON UPDATE CURRENT_TIMESTAMP,
      create_by VARCHAR(255) NULL,
      update_by VARCHAR(255) NULL,
      PRIMARY KEY (member_no)
    )$charset_collate;";

  // create table for table member
  $table_TB_MEMBER = $wpdb->prefix . 'TB_MEMBER';
  $sql_TB_MEMBER = "CREATE TABLE IF NOT EXISTS $table_TB_MEMBER (
    member_no VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    date_of_birth datetime NULL,
    address VARCHAR(255) NULL,
    phone_number VARCHAR(255) NULL,
    del_f int(10) DEFAULT 0,
    create_dt DATETIME DEFAULT CURRENT_TIMESTAMP,
    update_dt DATETIME ON UPDATE CURRENT_TIMESTAMP,
    create_by VARCHAR(255) NULL,
    update_by VARCHAR(255) NULL,
    PRIMARY KEY (member_no)
  )$charset_collate;";

  $table_TB_USER_SESSION = $wpdb->prefix . 'TB_USER_SESSION';
  $sql_TB_USER_SESSION = "CREATE TABLE IF NOT EXISTS $table_TB_USER_SESSION (
    session_id VARCHAR(255) NOT NULL,
    member_no VARCHAR(255) NOT NULL,
    session_name VARCHAR(255) NOT NULL,
    browser_login VARCHAR(255) NULL,
    expiration_dt datetime NOT NULL,
    del_f int(10) DEFAULT 0,
    create_dt DATETIME DEFAULT CURRENT_TIMESTAMP,
    update_dt DATETIME ON UPDATE CURRENT_TIMESTAMP,
    create_by VARCHAR(255) NULL,
    update_by VARCHAR(255) NULL,
    PRIMARY KEY (session_id)
  )$charset_collate;";

  $table_TB_MAIL_EXT = $wpdb->prefix . 'TB_MAIL_EXT';
  $sql_TB_MAIL_EXT = "CREATE TABLE IF NOT EXISTS $table_TB_MAIL_EXT (
    mail_id VARCHAR(255) NOT NULL,
    member_no VARCHAR(255) NOT NULL,
    mail_cd VARCHAR(255) NOT NULL,
    mail_direct VARCHAR(255) NULL,
    mail_title VARCHAR(255) NULL,
    mail_txt blob NOT NULL,
    send_dt datetime NOT NULL,
    mail_send_by VARCHAR(255) NOT NULL,
    del_f int(10) DEFAULT 0,
    create_dt DATETIME DEFAULT CURRENT_TIMESTAMP,
    update_dt DATETIME ON UPDATE CURRENT_TIMESTAMP,
    create_by VARCHAR(255) NULL,
    update_by VARCHAR(255) NULL,
    PRIMARY KEY (mail_id)
  )$charset_collate;";

  $table_TB_SUBSCRIPTION = $wpdb->prefix . 'TB_SUBSCRIPTION';
  $sql_TB_SUBSCRIPTION = "CREATE TABLE IF NOT EXISTS $table_TB_SUBSCRIPTION (
    sub_id VARCHAR(255) NOT NULL,
    sub_key VARCHAR(255) NOT NULL,
    member_no VARCHAR(255) NOT NULL,
    sub_create VARCHAR(255) NULL,
    sub_plan VARCHAR(255) NULL,
    sub_remind VARCHAR(255) NULL,
    sub_mail_remind int(10) DEFAULT 0,
    sub_pay int(10) DEFAULT 0,
    sub_expired datetime NOT NULL,
    sub_limit_active int(10) DEFAULT 0,
    del_f int(10) DEFAULT 0,
    create_dt datetime DEFAULT CURRENT_TIMESTAMP,
    create_by VARCHAR(255) NULL,
    update_by VARCHAR(255) NULL,
    PRIMARY KEY (sub_id)
  )$charset_collate;";

  $table_TB_SUBSCRIPTION_HIS = $wpdb->prefix . 'TB_SUBSCRIPTION_HIS';
  $sql_TB_SUBSCRIPTION_HIS = "CREATE TABLE IF NOT EXISTS $table_TB_SUBSCRIPTION_HIS (
    sub_id VARCHAR(255) NOT NULL,
    sub_key VARCHAR(255) NOT NULL,
    member_no VARCHAR(255) NOT NULL,
    sub_create VARCHAR(255) NULL,
    sub_plan VARCHAR(255) NULL,
    sub_remind VARCHAR(255) NULL,
    sub_mail_remind int(10) DEFAULT 0,
    sub_pay int(10) DEFAULT 0,
    sub_expired datetime NOT NULL,
    sub_limit_active int(10) DEFAULT 0,
    del_f int(10) DEFAULT 0,
    create_dt datetime DEFAULT CURRENT_TIMESTAMP,
    create_by VARCHAR(255) NULL,
    update_by VARCHAR(255) NULL,
    PRIMARY KEY (sub_id)
  )$charset_collate;";

  $table_TB_SUBSCRIPTION_MASTER = $wpdb->prefix . 'TB_SUBSCRIPTION_MASTER';
  $sql_TB_SUBSCRIPTION_MASTER = "CREATE TABLE IF NOT EXISTS $table_TB_SUBSCRIPTION_MASTER (
    id int(10) NOT NULL AUTO_INCREMENT,
    sub_key VARCHAR(255) NOT NULL,
    sub_name VARCHAR(255) NOT NULL,
    del_f int(10) DEFAULT 0,
    create_dt DATETIME DEFAULT CURRENT_TIMESTAMP,
    create_by VARCHAR(255) NULL,
    update_by VARCHAR(255) NULL,
    PRIMARY KEY (id)
  )$charset_collate;";

    // Thực thi tạo bảng sử dụng hàm dbDelta()
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_TB_LOGIN);
    dbDelta($sql_TB_MAIL_EXT);
    dbDelta($sql_TB_MEMBER);
    dbDelta($sql_TB_SUBSCRIPTION);
    dbDelta($sql_TB_SUBSCRIPTION_HIS);
    dbDelta($sql_TB_SUBSCRIPTION_MASTER);
    dbDelta($sql_TB_USER_SESSION);
}

register_activation_hook( __FILE__, 'my_custom_plugin_activate' );

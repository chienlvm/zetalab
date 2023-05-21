<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'test' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'aqeU`l[KPi;bMV_b*=zH85nn)Ec7);lu]-IJ2NF?A)tUz1Hsa(i|1dBlIlu$,9uQ' );
define( 'SECURE_AUTH_KEY',  'R(3(-1+A$:L7m<RA^@PYh2MOCtgHw#t;g0]n$xr4@02{6p#2Mw{e|d{*3O@WK;#=' );
define( 'LOGGED_IN_KEY',    'yI7xq^`a5a8^I<j>zdw75n9pQ,mtg>9cd-wQB=A7i~%Wm7KQ}jFrK}b*K`(M@x~Q' );
define( 'NONCE_KEY',        'za6-BS+{dQ%R(y<Rd0.v<mQhQt2<1eEZ%lVc|iiG]WXP4B*r,XM!}:_jB?P&`!Gn' );
define( 'AUTH_SALT',        'A!6m){sl`*>G>V**%3orTtXhc<ApiS{Hu bMO=CUzek|Mw9r3&;t?&h2#GD_B}_4' );
define( 'SECURE_AUTH_SALT', 'T?05gqfun1sZHdQm]=j;II|sq:o&h59,T9+01mW`9-G]P[H#ir%EgHF]j5s*p+W@' );
define( 'LOGGED_IN_SALT',   'aXM:pL&/!61<L4APk,p`kKjXn6r9fLnNpqX#CfT5&LugVbzvqWb~<pStjMW.8<jJ' );
define( 'NONCE_SALT',       '>{Suo+0QsVUXwhbN_mvNF(t6CNI_Lzz=@kn$?=q?&Dc5Gn5cqn)&B<_N91_JY%<F' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
/**Define email */
define( 'EMAIL_FROM', 'info.leeit@gmail.com' );
define( 'PASSWORD_EMAIL_SEND_FROM', 'rmficiifjqrywunb' );
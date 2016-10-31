<?php
define('WP_CACHE', false); // Added by WP Rocket
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
define('WP_HOME','https://buenowines.com.br');
define('WP_SITEURL','https://buenowines.com.br');
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'ct_buen_LjOdog');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '12pandas');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'o4hi3ugn UXCFpIQh PDoxpScY LNG5oPVu ChH2QfMX');
define('SECURE_AUTH_KEY',  'dAPz8Enl ClFsnpiC SzqRlEa8 l1onLGLk 4nlB3goM');
define('LOGGED_IN_KEY',    'qDoMAicX C5P7X3iW XhSkDKns B8BXcTxs I6ucK2de');
define('NONCE_KEY',        'll3LiBkD gzTbLUaV HY82eB6X QPkXx4lv St46RSWB');
define('AUTH_SALT',        '4l5Gk43W SuxA8QCe PgQrPrNf BqXnKxaJ N6mPb2Ms');
define('SECURE_AUTH_SALT', 'RTVCUHqp 5kUrtU5o RO6Xszdg A6IB2BuU 3HIGVEsC');
define('LOGGED_IN_SALT',   'n7G24Tst S6aItDA5 6C1wt2Wu 3jYx1WTT wgKECpMM');
define('NONCE_SALT',       'ZrFsHZaG FtTCVZhj WZsnmspr WPvoKuhw vCrlCtB3');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_loja';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

define('FS_METHOD', 'direct');

define('WP_MEMORY_LIMIT', '96M');

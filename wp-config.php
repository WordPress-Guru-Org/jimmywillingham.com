<?php
define( 'WP_CACHE', true );
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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u330776872_swUWa' );

/** Database username */
define( 'DB_USER', 'u330776872_A4hCj' );

/** Database password */
define( 'DB_PASSWORD', 'eA3qJCGcIu' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'Pxx5BzF03M6Iy00>I,JQ2g9LBjjmb|5QUOKwl<#=lmL]o|8<G/>;FIE]Bm{oTY$*' );
define( 'SECURE_AUTH_KEY',   'Q(aBx9xD-+fV5#O:*oz:n4rvQl+bn#B/vPd3Kfm/O5B.V-R|*Ma`.743&rc/->GM' );
define( 'LOGGED_IN_KEY',     'z+<7g:>QKY:fL<(j{[E,J-//+6P,X+u%W#/b/VuJ%5);n)QedwjZxsV@*-hjGgqI' );
define( 'NONCE_KEY',         'f4Y^4>)7sg+OS!nMRxzvb@78{2 5HR<Z`ww(^C+tFpI`e&Fo8giQcjFPZ[?jq)A{' );
define( 'AUTH_SALT',         'pGMLG?lO>yw6aRsvn/<L5^?#?nVce|jq^:Vpn0wqVn(NXvE%LYMD>F}jn6z3HPXj' );
define( 'SECURE_AUTH_SALT',  'sHL;Yl#/4LLFu{sQ8k?]In>?nNF2zzdF9x$:HK{8Y3Af3<Oq6hP VMLFALgJQnc)' );
define( 'LOGGED_IN_SALT',    'e:qH7/ Fkq=V 9A8/r!6_VNy_?VYxd&hP0.M<L8_0wsGK.}EE?gH}dzdq-^5e$QV' );
define( 'NONCE_SALT',        'VgwRCa:I?v(Z &XgKX!tN@F y+95eUaQiIl&c{q9;<xS0Mq#/xfGrgtj9WD1/g6D' );
define( 'WP_CACHE_KEY_SALT', 'OCVKq9in5rhnnWSuvC%UZ-K.B#`O*>HKFE02!AZ!c(azEU*wB/ZSmBf}m]s5E9Wh' );


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
define( 'WP_DEBUG', false );


/* Add any custom values between this line and the "stop editing" line. */



define( 'FS_METHOD', 'direct' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

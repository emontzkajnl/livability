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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'ad5f3796_1adf48' );

/** Database username */
define( 'DB_USER', 'ad5f3796_1adf48' );

/** Database password */
define( 'DB_PASSWORD', 'Minute11Sneers94Curacy64Waned78' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

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
define( 'AUTH_KEY',          'LA/K+],tm1gwaQuC_+cK#+i,[]6)c4rbcKA&I!r^[0[t#oQQA*53?*_/^[g$xo2j' );
define( 'SECURE_AUTH_KEY',   '+EXzmpwmp[RGPyq0TY+A?N+7dAvCEYz@S~PC[iZ2VRiawf4{2q#KV[qCY1hSrcnk' );
define( 'LOGGED_IN_KEY',     'x957m&Mo}[?}@t.agR;TV9dNx}C,qCjtT+$%yol[PGxxq^XKZ6pm.;>bh-odp_e7' );
define( 'NONCE_KEY',         '6^9!NL@GmQTXPfLB[cv}ca$dx^L#j)yWU$Ok=H_~ksLVxqc?3gJ?IR6a:wX!$u_Z' );
define( 'AUTH_SALT',         '^b$y37$DopNGMM!SmdW$3uT7e;Y(c:n>`^73(1M.Q@55zSWuOpyp@ZCR(Pl]cNlM' );
define( 'SECURE_AUTH_SALT',  'HC,*LPTjS}B~2&lk$MQq)ugbBhX5hJ(VDLvH9OmGF_Yo/1t6r]) Nhg{xW_|&Vzn' );
define( 'LOGGED_IN_SALT',    'Y(J&!z0Cl-*+Wsx9C=O~!Od5Q3?4Kz^J}LjPyz*(muh193-u..!P 2gCGF:rIH~h' );
define( 'NONCE_SALT',        'H~!J> $ZG[H.}e(=>]GwQu<S~Lr65xlp6^=+!oJ7jX?|#cFTu_~a-s;Ljf?Wl>l`' );
define( 'WP_CACHE_KEY_SALT', 't0fGN3jBFxUB;qeFe9ZRx@`jN)g{F1K1NH(9L_>w,E[ttn16*y#Nvcl.TC.,Q^MQ' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';



/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'DISABLE_WP_CRON', true );
define( 'FS_CHMOD_DIR', 0755 );
define( 'FS_CHMOD_FILE', 0644 );
define( 'WP_REDIS_CONFIG', [
	'token' => '',
	'host' => '/var/run/redis-multi-ad5f3796.redis/redis.sock',
	'port' => '0',
	'database' => '1',
	'maxttl' => 86400 * 7,
	'timeout' => 1.0,
	'read_timeout' => 1.0,
	'retry_interval' => 10,
	'retries' => 3,
	'backoff' => 'smart',
	'compression' => 'zstd',
	'serializer' => 'igbinary',
	'async_flush' => true,
	'split_alloptions' => true,
	'prefetch' => true,
	'debug' => false,
	'save_commands' => false,
] );
define( 'WP_REDIS_DISABLED', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

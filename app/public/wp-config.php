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
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',          'TN`jD!s$v[CnCXdU<4JGul?AUnc;F$M|I]84xzEU`JX ZpQ?>3HQ9=/Po.o~/_~x' );
define( 'SECURE_AUTH_KEY',   'w/-R1L[1`|70?cOzjw$i]S6>wBWuS@]7h>TP&]rr#JEh%;etL`=cG>z~k:?dY0>W' );
define( 'LOGGED_IN_KEY',     'uLR5:9g~ AlE{nfWrU}i^|E9j7Kd:/kU4I4$I1JU:C[&i[II(d:s~_2h9$F]|AiL' );
define( 'NONCE_KEY',         'OcFQaa@uic@#hOe*%8@-)S<4wX|V0|(%{^Od5pW0tp4JI=h#ec]+=m:5y1`8N?3*' );
define( 'AUTH_SALT',         'XAmmjtk9+G TVVH#ryir}ZNO!NSXH)!LAi.S4kB7.7<WdJj&(C=?DPLHxd;u(tMs' );
define( 'SECURE_AUTH_SALT',  'W%qwOA#d&cmK)=T:Ki{IsDUJs=C 971b=Osvouppw.j!d]%.MvFY[wIK[kR8GYhn' );
define( 'LOGGED_IN_SALT',    '_C`GEK=|F)%sgwO#wNu5=`@$@n,a4y#F2d?#S_ O~8K1NDVGmQQG>{sn5v/:5M)[' );
define( 'NONCE_SALT',        '?C|D1-a$7c}l!2~Bfz;3Mw5fyxTqcvjT>VxSq~e8~i|Xj57s}vUHM{|{zUJv$uf%' );
define( 'WP_CACHE_KEY_SALT', 'xG>qf)p4=ZGVgL|>.}P8r2V=!vN1xIQ,l&|2CUcUv+Io2OX,e^;_uN&q<Pd,t!#T' );


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

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

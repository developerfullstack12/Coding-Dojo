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
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'Coding-Dojo' );
/** MySQL database username */
define( 'DB_USER', 'Coding-Dojo' );
/** MySQL database password */
define( 'DB_PASSWORD', 'Coding-Dojo' );
/** MySQL hostname */
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
define( 'AUTH_KEY',         'Q2C^TWZ,nzO5r`01J2T4B;L*-j+_[F)b8u2~LgEEiH]mA/,_LR6/n$hb56(_o*]O' );
define( 'SECURE_AUTH_KEY',  'xDSG&7wvT8s7V>Q7cU/!/Pz{:oNNN@JZZPHYv Nt~nBd.>s8xmteFx-.hqLb/EOl' );
define( 'LOGGED_IN_KEY',    '4ux#_JL[MBt(p)/SY/C/%hOM?AN@/:UHIPV/:5Y?&jjjz4i$@k`T%usV,[X^;E,P' );
define( 'NONCE_KEY',        '_2J}]C)!w!>VX +As}##ttGvdxyUtY-7^XF!uifc{9Q|?*j}XV]ts;^u*}z/+bpV' );
define( 'AUTH_SALT',        '[PA+:xPq1I_/z|nULcZ+~g{.T/.#gdbddRI?:KU9S7fU04FP41]o*t_Bk.fUEjMS' );
define( 'SECURE_AUTH_SALT', 'iCOCHV6d.h! uw+{,Jziyu#}*HDW]MTkWLM,HG9cv0<:bGo*$9*D#(p>ufx1P!c%' );
define( 'LOGGED_IN_SALT',   '93peq`BC[D)R8d!`Ld_wK~D60#6*Lam9bHng{N/h:R;&<vRv%g]G!Pe#*V19qd<z' );
define( 'NONCE_SALT',       '4~kJok0NJ(<Nr)X 1kV%iq{Cq::f S9<Ohc,&3cJqU3e=Va=tZ%hd()!$}SbBn1k' );
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
define( 'WP_DEBUG', true );
/* Add any custom values between this line and the "stop editing" line. */
/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
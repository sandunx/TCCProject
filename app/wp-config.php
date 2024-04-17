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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'tcc' );

/** Database username */
define( 'DB_USER', 'wordpress' );

/** Database password */
define( 'DB_PASSWORD', 'wordpress' );

/** Database hostname */
define( 'DB_HOST', 'db' );

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
define('AUTH_KEY',         '-31pwr0k25pg`qh,Yb]N%t[;p>YUJpuD{&[#>x@*b2;NEg/m&/gI|pk?Ed*cF.dX');
define('SECURE_AUTH_KEY',  '/vneW[e.VVB29|w[rH 8^+%r@BB}yl4-|S]wz2d}l_SeUj^@dTNZu=-[@%c^WyUH');
define('LOGGED_IN_KEY',    '9.^.q9!kZ0qfDR{+@@+@h5p<O_=3|vb]ku6fS6q 28&>S~M,oEX3;c:V_SuInkFB');
define('NONCE_KEY',        'SIOojU2/!O+!wArtF5GU |5(ne*6|*{B_bp#+nNkFb<~~j1|Xs%h*,O/C:lV*QQ@');
define('AUTH_SALT',        'xOdHuN*UF!J>GB;)gzO`q|5=OKTr ?@/bc&vqZ0sY(UB*BG(O^)w4}1 4O+wnB[4');
define('SECURE_AUTH_SALT', '-WM[:48.X 9HEaGYuBoRl.NIA?-(<7`e+S4`>>5u,p-%k$wLG_Xbz[N!oT?pyXdx');
define('LOGGED_IN_SALT',   '8nNtEEO*V-+vZ9`B(P{7[KFocZruS+fSv9w+v7+Zx%4dc(-IaD^6>+g=$@KVyizV');
define('NONCE_SALT',       '~]}P<kr$ZyID,Y6k/.4o=YX{=VMU;l[x[N]I1#w{-O%_Wx!|,1yX;=xm{t_PaRUc');

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', FALSE);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

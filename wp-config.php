<?php
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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         ':Sz-.m?w_~*}W&^#0.ZTYFkekq@{1h*}HC8Q:B^# ?so`VQa)mUO2>&9?_^GTv`,');
define('SECURE_AUTH_KEY',  'L^3(V_s^KhH|m5nU)hX1*J=Uf`#3.#|,JPlsA@VdkWo!]Y1c0FTy@.E0DHv[_ETJ');
define('LOGGED_IN_KEY',    'rR,gP_kwbl@FKwCAI7^7,]vBjo@$#i>JsFA~Cf^=iOn<j^k3sES0<o6XlY_d7Z^)');
define('NONCE_KEY',        'N/}nrSFH<E2-k{`6Nnjm@25_KA~7IMe@E;-g{!=5*k,}VgAv=u)hsQV|jRY`pb4k');
define('AUTH_SALT',        '7pnAC*(wb-{IxGVjs+$BWEC9SDejp?r@Dz2^Ud.)3tM^YGGaE<.%Tc7E<Xe2kUdm');
define('SECURE_AUTH_SALT', '37:FML( @Er8MroFHqGlrK]hSOrtcV:4R,<d]G5IuHP@S@&?Vv#/z0t/ZwM!6+-f');
define('LOGGED_IN_SALT',   ',R),NBNf8jahk}?FrAcDjSJI<O+y:4^6irkc)oW:xxOcz.N:XqrXj2x)hZfAj|V/');
define('NONCE_SALT',       '.Y!V%{{$D..@qv*,l P,CfE}Ci28V0e2quqU(.MJrdje8*E|HyH30fNI~K+QnukU');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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

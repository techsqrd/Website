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
define('DB_NAME', 'techsqrd');

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
define('AUTH_KEY',         'D5,yFLo0t9SsHHi&hQwSB1^(I!G&f;P77+ns<CjI=w:(#,#?x&<gq:-5,+6<8<Q6');
define('SECURE_AUTH_KEY',  'C-oH%nOFi`3)u)wVyP7#KP.0Z83ppPg1q@>0LgcrAv/d7#b$F(83|Ok#~bn<(seF');
define('LOGGED_IN_KEY',    '#pDba+yUNO[OzbxjS.ne)!*4:ihQ<aYAECLY]~Q{`h.{<y<*iTVS@_r.F!kwl:P;');
define('NONCE_KEY',        'VX::@=oUHBS96%.4lK$(DvY:W;05bl~z i=aHqY-hfM`@|n!SqzClv:@{UQjz{7e');
define('AUTH_SALT',        '@#23I`SmT,_45p(_B!{bH*{{XR<!wO.9gndpZ~KD}R&l/)&?c:o}wE,bohWV169K');
define('SECURE_AUTH_SALT', '~_J-kl`0nxh2yo02= C(iUnph,*PY`XA2d`gh_{Dl7Oq}iGyIJQ-7M CC25=@-Ow');
define('LOGGED_IN_SALT',   '>-e3HEsD-k_JJ>yh?VxMlu(OQang?C*fe:I(;+JL:C@#D)i2B>|^sjvnO$2JsC`O');
define('NONCE_SALT',       'uHo&u*:COO!LW?Rpi}(c &=9ZDuv({G~OT7UN7[!{r;RNuB72{7Q`iExXO./XT}|');

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

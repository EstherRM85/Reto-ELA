<?php
define('WP_CACHE_KEY_SALT', '912e83efae4324529e55880d531747e2');
define('CONCATENATE_SCRIPTS', false);
define('DISALLOW_FILE_EDIT', true);
define('WP_AUTO_UPDATE_CORE', true);// Esta opción es imprescindible para garantizar que las actualizaciones de WordPress pueden gestionarse correctamente en el paquete de herramientas de WordPress. Si este sitio web WordPress ya no está gestionado por el paquete de herramientas de WordPress, elimine esta línea.
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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define('DB_NAME', 'ndgowgfd');

/** MySQL database username */
define('DB_USER', 'wp_l9dn3');

/** MySQL database password */
define('DB_PASSWORD', 'P_p3A2t0mD');

/** MySQL hostname */
define('DB_HOST', 'localhost:3306');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '%[5)LtF)z6q]__S8;&:6;ZV56N(2~dS2IbQvQc3-UnV22&:Q4MNS2:3C-52JIjqM');
define('SECURE_AUTH_KEY', '_5itgjBL7kke[0*/|zhDz5%6A6L[iKA8z/%Q35rJ3|!C:(840q1l8KHvt9vgudU;');
define('LOGGED_IN_KEY', 'a-f4P-wV;0GAti2M40[735L#8oy/6[G+5@XmehjMOxYCK7Rv6A59a75q@[2Slp2&');
define('NONCE_KEY', 'Uzn-Y895GnC37!3E79@ZxTTv(_/Cw34IS92Me;A;l3az9-&0D|(Ww+Jep2O@6#Ma');
define('AUTH_SALT', 'x9:|]b@8Vn8[2[TkioF0(m!6i#2b8u88BUm0bPD0YpKRA22w_0qT6_D+4FDgXiq*');
define('SECURE_AUTH_SALT', 'aYe9fWJkJS:1@y1@eWb4t/t09fx;12kx/Yb(@x-|HUPs-0/gsDm6f8[a1/X#](-r');
define('LOGGED_IN_SALT', 'j580f;c55t8bUk]-955|82-pqcP[Q;181K*ryUW9[%e4lX5&+2G2F_|7-*Y#%v1w');
define('NONCE_SALT', 'P*0K5g~ibVq]Lot(*!OYJ[&Z_D|(Y#[+&uS81]:;xbyLRt7m5Tw4AGS756|s~Rcd');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'Qa16Y_';


define('WP_ALLOW_MULTISITE', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

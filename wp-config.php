<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache


/**

 * The base configurations of the WordPress.

 *

 * This file has the following configurations: MySQL settings, Table Prefix,

 * Secret Keys, WordPress Language, and ABSPATH. You can find more information

 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing

 * wp-config.php} Codex page. You can get the MySQL settings from your web host.

 *

 * This file is used by the wp-config.php creation script during the

 * installation. You don't have to use the web site, you can just copy this file

 * to "wp-config.php" and fill in the values.

 *

 * @package WordPress

 */

//development db settings
$db['wwdev']['DB_NAME'] = "wordpres_ntrprisewp";
$db['wwdev']['DB_PASSWORD'] = "!$&O2g0V2*83";
$db['wwdev']['DB_USER'] = "wordpres_weis";
$db['wwdev']['DB_HOST'] = "localhost";
//live db settings
$db['production']['DB_NAME'] = "ntrprise_wp";
$db['production']['DB_PASSWORD'] = "ntrprisew315";
$db['production']['DB_USER'] = "ntrprise_ntrprdb";
$db['production']['DB_HOST'] = "localhost";
//stagedev db settings
$db['stagedev']['DB_NAME'] = "cewstage";
$db['stagedev']['DB_PASSWORD'] = "";
$db['stagedev']['DB_USER'] = "root";
$db['stagedev']['DB_HOST'] = "localhost";


$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
	
	switch ($host)
	{
		case 'cew.wordpressdev.work':
			define('ENVIRONMENT', 'wwdev');
			break;
		case 'cewstaging.jonwp.dvp':
			define('ENVIRONMENT', 'stagedev');
			break;
		default:
			define('ENVIRONMENT', 'production');
			break;
	}


// ** MySQL settings - You can get this info from your web host ** //

/** The name of the database for WordPress */

define('DB_NAME', $db[ENVIRONMENT]['DB_NAME']);



/** MySQL database username */

define('DB_USER', $db[ENVIRONMENT]['DB_USER']);



/** MySQL database password */

define('DB_PASSWORD', $db[ENVIRONMENT]['DB_PASSWORD']);



/** MySQL hostname */

define('DB_HOST', $db[ENVIRONMENT]['DB_HOST']);



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

define('AUTH_KEY',         'SG-vi+[k88hsjECwmO|[l2xuaelT7?u=VB|PIz{h10{^],,b-T&9l{48|Mtofm4|');

define('SECURE_AUTH_KEY',  '?8bH|$j$@1F:mI1}jv(^Mg4sd9<B}UMSnf%w07ja`/,Y[#+&Ofl, nU$bnh`R$S}');

define('LOGGED_IN_KEY',    ' HAwm>_0YDlO_[ <||h8N~J4UMIU[wLGm|2i*No|g[%r!W%d:6]cov<=c&-a4^|,');

define('NONCE_KEY',        '@9X-A-}zW^QO3JM[<Jgpry.+@R6E48NS^?TwX%co6~5C1+>V!}1?+Tl}oEXxBvW(');

define('AUTH_SALT',        '^<!{#jEj:yH|nvV#w@8lPl81}zf`R$rZxYkAUEHeD]<v#:mMZX5]NBV9KT(_r5Uk');

define('SECURE_AUTH_SALT', '^+_4sq,z_2cjJe%d;BL+}`jJ[2l}eLl&VKy3JUTl0e(OVIbK2?}Ykt/ -!-R^#5s');

define('LOGGED_IN_SALT',   'L[_Kyh5M-:SnX%.|8 t#vsd>gcu>mnqTGDR3_A#YQ.srTA?1a:y-N}OW8}+|P$6%');

define('NONCE_SALT',       ']0L#/KR@P8 [}rSW$pnE|&02+)G*z95Jrz]/$8c,zabZ7TdMwN/lP2XY0wqkfed>');



/**#@-*/



/**

 * WordPress Database Table prefix.

 *

 * You can have multiple installations in one database if you give each a unique

 * prefix. Only numbers, letters, and underscores please!

 */

$table_prefix  = 'wp_';



/**

 * WordPress Localized Language, defaults to English.

 *

 * Change this to localize WordPress. A corresponding MO file for the chosen

 * language must be installed to wp-content/languages. For example, install

 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German

 * language support.

 */

define('WPLANG', '');



/**

 * For developers: WordPress debugging mode.

 *

 * Change this to true to enable the display of notices during development.

 * It is strongly recommended that plugin and theme developers use WP_DEBUG

 * in their development environments.

 */

define('WP_DEBUG', false);



/* That's all, stop editing! Happy blogging. */



/** Absolute path to the WordPress directory. */

if ( !defined('ABSPATH') )

	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */

require_once(ABSPATH . 'wp-settings.php');

<?php
/**
 * Plugin Name: Shared Vision Light
 * Description: Connect SharedVision with your WordPress powered website, easily.
 * Author: Shared Vision
 * Author URI: https://www.joinsharedvision.com/
 * Version: 1.1.0
 */

defined( 'ABSPATH' ) || exit;

const SHARED_VISION_VERSION = '1.1.1';
const SHARED_VISION_BASE_FILE_PATH = __FILE__;

define( "SHARED_VISION_BASE_PATH", dirname( SHARED_VISION_BASE_FILE_PATH ) );
define( "SHARED_VISION_PLUGIN_IDENTIFIER", ltrim( str_ireplace( dirname( SHARED_VISION_BASE_PATH ), '', SHARED_VISION_BASE_FILE_PATH ), '/' ) );

const SHARED_VISION_NAME = 'Shared Vision';
const SHARED_VISION_ALIAS = 'shared_vision';
const SHARED_VISION_SLUG_PREFIX = 'sharedvision';

const SHARED_VISION_API_DEV_ALIAS = "dev";
const SHARED_VISION_API_LIVE_ALIAS = "live";

if( !defined( 'SHARED_VISION_API_DEV_URL' ) )
    define("SHARED_VISION_API_DEV_URL", "https://dev.api.diy.joinsharedvision.com/");

if( !defined( 'SHARED_VISION_API_LIVE_URL' ) )
    define("SHARED_VISION_API_LIVE_URL", "https://api.creator.joinsharedvision.com/");

if( !defined( 'SHARED_VISION_EMBED_DEV_URL' ) )
    define("SHARED_VISION_EMBED_DEV_URL", "https://dev.list.joinsharedvision.com/static/embed/v1.js");

if( !defined( 'SHARED_VISION_EMBED_LIVE_URL' ) )
    define("SHARED_VISION_EMBED_LIVE_URL", "https://list.joinsharedvision.com/static/embed/v1.js");

require_once SHARED_VISION_BASE_PATH . "/autoload.php";
require_once SHARED_VISION_BASE_PATH . "/_functions/utility.php";

if( is_admin() )
    add_action( 'plugins_loaded', [ SharedVision\AdminController::instance(), 'setup' ], 10 );

// Client request, always load.
function load_shared_vision_script() {
    wp_enqueue_script('shared-vision-light', 'https://list.joinsharedvision.com/static/embed/v1.js', array(), null, true);
}
add_action( "init", function() {
    if( is_admin() )
        return;

    add_action('wp_enqueue_scripts', 'load_shared_vision_script');
});
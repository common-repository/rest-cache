<?php
/*
Plugin Name: REST Cache
Plugin URI: https://perryrylance.com/rest-cache
Author: Perry Rylance
Author URI: https://perryrylance.com
Description: The fastest REST cache for WordPress. This plugin makes REST requests lightning fast and will eliminate almost all load associated with REST requests on your database. Provides facilities for inclusion and exclusion rules, and hooks for 3rd party developers to clear their REST routes.
Version: 1.0.0
Requires at least: 4.4.0
Requires PHP: 5.4
License: Apache License 2.0
Text Domain: rest-cache
*/

namespace PerryRylance\WordPress\RESTCache;

require_once('constants.php');

require_once(REST_CACHE_DIR_PATH . "vendor/autoload.php");
require_once(REST_CACHE_DIR_PATH . "includes/class.plugin.php");

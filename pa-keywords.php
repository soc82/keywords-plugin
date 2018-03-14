<?php

/**
 * Plugin Name: PA Keywords
 * Plugin URI: itsnotavailable.com
 * Description: Add Keywords to Pages and Posts
 * Version: 1.0
 * Author: SOC
 * Author URI: itsnotavailable.com
 * Text Domain: pa-keywords
 */

require_once  'classes/class.keywords.php';

/* ----------------------
-- INSTANTIATE CLASSES
------------------------ */
$runAdminPlugin   = new adminPlugStart();

$runAdminPlugin->run();
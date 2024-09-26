<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Triggers management.
 *
 * @package PhpMyAdmin
 */

/**
 * Include required files
 */
require_once './libraries/common.inc.php';
require_once './libraries/common.lib.php';

/**
 * Include JavaScript libraries
 */
$GLOBALS['js_include_once'][] = 'jquery/jquery-ui-1.8.16.custom.js';
$GLOBALS['js_include_once'][] = 'rte/common.js';
$GLOBALS['js_include_once'][] = 'rte/triggers.js';
$GLOBALS['js_include_once'][] = 'codemirror/lib/codemirror.js';
$GLOBALS['js_include_once'][] = 'codemirror/mode/mysql/mysql.js';

/**
 * Include all other files
 */
require_once './libraries/rte/rte_triggers.lib.php';

/**
 * Do the magic
 */
$_PMA_RTE = 'TRI';
require_once './libraries/rte/rte_main.inc.php';

?>

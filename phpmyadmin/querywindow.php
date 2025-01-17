<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * this file is register_globals safe
 *
 * @todo    move JavaScript out of here into .js files
 * @package PhpMyAdmin
 */

/**
 *
 */
require_once './libraries/common.inc.php';

$is_superuser = PMA_isSuperuser();

/**
 * Gets a core script and starts output buffering work
 */
require_once './libraries/sql_query_form.lib.php';

/**
 * starts output buffering if requested and supported
 */
require_once './libraries/ob.lib.php';
PMA_outBufferPre();

/**
 * load relation params
 */
$cfgRelation = PMA_getRelationsParam();

/**
 * load bookmark support
 */
require_once './libraries/bookmark.lib.php';

$querydisplay_tabs = array(
    'sql',
    'files',
    'history',
    'full',
);

if (isset($_REQUEST['querydisplay_tab'])
 && in_array($_REQUEST['querydisplay_tab'], $querydisplay_tabs)) {
    $querydisplay_tab = $_REQUEST['querydisplay_tab'];
} else {
    $querydisplay_tab = $GLOBALS['cfg']['QueryWindowDefTab'];
}

/**
 * $_REQUEST['no_js'] is set if open new window by JavaScript failed
 * so this page is loaded in main frame
 */
$no_js = PMA_ifSetOr($_REQUEST['no_js'], false);

if ($no_js) {
    $querydisplay_tab = 'full';
    $tabs = false;
} else {
    $tabs = array();
    $tabs['sql']['icon']   = 'b_sql.png';
    $tabs['sql']['text']   = __('SQL');
    $tabs['sql']['fragment']   = '#';
    $tabs['sql']['attr']   = 'onclick="PMA_querywindowCommit(\'sql\');return false;"';
    $tabs['sql']['active'] = (bool) ($querydisplay_tab == 'sql');
    $tabs['import']['icon']   = 'b_import.png';
    $tabs['import']['text']   = __('Import files');
    $tabs['import']['fragment']   = '#';
    $tabs['import']['attr']   = 'onclick="PMA_querywindowCommit(\'files\');return false;"';
    $tabs['import']['active'] = (bool) ($querydisplay_tab == 'files');
    $tabs['history']['icon']   = 'b_bookmark.png';
    $tabs['history']['text']   = __('SQL history');
    $tabs['history']['fragment']   = '#';
    $tabs['history']['attr']   = 'onclick="PMA_querywindowCommit(\'history\');return false;"';
    $tabs['history']['active'] = (bool) ($querydisplay_tab == 'history');

    if ($GLOBALS['cfg']['QueryWindowDefTab'] == 'full') {
        $tabs['all']['text']   = __('All');
        $tabs['all']['fragment']   = '#';
        $tabs['all']['attr']   = 'onclick="PMA_querywindowCommit(\'full\');return false;"';
        $tabs['all']['active'] = (bool) ($querydisplay_tab == 'full');
    }
}

$titles['Change'] = PMA_getIcon('b_edit.png', __('Change'));
$url_query = PMA_generate_common_url($db, $table);

if (! empty($sql_query)) {
    $show_query = 1;
}

if ($no_js) {
    // ... we redirect to appropriate query sql page
    // works only full if $db and $table is also stored/grabbed from $_COOKIE
    if (strlen($table)) {
        include_once './tbl_sql.php';
    } elseif (strlen($db)) {
        include_once './db_sql.php';
    } else {
        include_once './server_sql.php';
    }
    exit;
}

/**
 * Defines the query to be displayed in the query textarea
 */
if (! empty($show_query)) {
    $query_to_display = $sql_query;
} else {
    $query_to_display = '';
}
$sql_query = '';

/**
 * prepare JavaScript functionality
 */
$js_include_once[] = 'common.js';
$js_include_once[] = 'querywindow.js';

if (PMA_isValid($_REQUEST['auto_commit'], 'identical', 'true')) {
    $js_events[] = array(
        'event'     => 'load',
        'function'  => 'PMA_queryAutoCommit',
    );
}
if (PMA_isValid($_REQUEST['init'])) {
    $js_events[] = array(
        'event'     => 'load',
        'function'  => 'PMA_querywindowResize',
    );
}
// always set focus to the textarea
if ($querydisplay_tab == 'sql' || $querydisplay_tab == 'full') {
    $js_events[] = array(
        'event'     => 'load',
        'function'  => 'PMA_querywindowSetFocus',
    );
}

/**
 * start HTTP/HTML output
 */
require_once './libraries/header_http.inc.php';
require_once './libraries/header_meta_style.inc.php';
require_once './libraries/header_scripts.inc.php';
?>
</head>

<body id="bodyquerywindow">
<div id="querywindowcontainer">
<?php

if ($tabs) {
    echo PMA_generate_html_tabs($tabs, array());
    unset($tabs);
}

PMA_sqlQueryForm($query_to_display, $querydisplay_tab);

// Hidden forms and query frame interaction stuff

$_sql_history = PMA_getHistory($GLOBALS['cfg']['Server']['user']);
if (! empty($_sql_history)
 && ($querydisplay_tab == 'history' || $querydisplay_tab == 'full')) {
    $tab = $querydisplay_tab != 'full' ? 'sql' : 'full';
    echo __('SQL history') . ':<br />'
        . '<ul>';
    foreach ($_sql_history as $query) {
        echo '<li>' . "\n";

        // edit link
        $url_params = array(
            'querydisplay_tab' => $tab,
            'sql_query' => $query['sqlquery'],
            'db' => $query['db'],
            'table' => $query['table'],
        );
        echo '<a href="querywindow.php' . PMA_generate_common_url($url_params)
            . '">' . $titles['Change'] . '</a>';

        // execute link
        $url_params['auto_commit'] = 'true';
        echo '<a href="import.php' . PMA_generate_common_url($url_params) . '"'
            . ' target="frame_content">';

        if (! empty($query['db'])) {
            echo '[';
            echo htmlspecialchars(PMA_backquote($query['db']));
            if (! empty($query['table'])) {
                echo '.' . htmlspecialchars(PMA_backquote($query['table']));
            }
            echo  '] ';
        }
        if (strlen($query['sqlquery']) > 120) {
            echo '<span title="' . htmlspecialchars($query['sqlquery']) . '">';
            echo htmlspecialchars(substr($query['sqlquery'], 0, 50)) . ' [...] ';
            echo htmlspecialchars(substr($query['sqlquery'], -50));
            echo '</span>';
        } else {
            echo htmlspecialchars($query['sqlquery']);
        }
        echo '</a>' . "\n";
        echo '</li>' . "\n";
    }
    unset($tab, $_sql_history, $query);
    echo '</ul>' . "\n";
}
?>
<form action="querywindow.php" method="post" name="hiddenqueryform"
    id="hiddenqueryform">
    <?php echo PMA_generate_common_hidden_inputs('', ''); ?>
    <input type="hidden" name="db" value="<?php echo htmlspecialchars($db); ?>" />
    <input type="hidden" name="table" value="<?php echo htmlspecialchars($table); ?>" />
    <input type="hidden" name="sql_query" value="" />
    <input type="hidden" name="querydisplay_tab" value="<?php echo $querydisplay_tab; ?>" />
</form>
</div>
</body>
</html>

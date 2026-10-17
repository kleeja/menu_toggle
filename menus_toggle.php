<?php
//
// menu_toggle, its page in the control panel: ?cp=menus_toggle, in the menu of the Settings page
//

// not for directly open
if (!defined('IN_ADMIN')) {
    exit();
}

//current template
$stylee = 'admin_menus_toggle';
$styleePath = __DIR__;

// the tab: the main menu, the user menu or the menu of the control panel
$menu_toggle_tab = g('smt', default: 'top');

if (!isset(MENU_TOGGLE_MENUS[$menu_toggle_tab])) {
    $menu_toggle_tab = 'top';
}

$action = basename(ADMIN_PATH) . '?cp=menus_toggle';
$menu_toggle_action = $action . '&amp;smt=' . $menu_toggle_tab;
$H_FORM_KEYS = kleeja_add_form_key('adm_menus_toggle');

// every administrator sees the menu of the control panel, only a founder changes it
$menu_toggle_can_edit = $menu_toggle_tab !== 'admin' || (int) $userinfo['founder'] === 1;

$menu_toggle_items = menu_toggle_items($menu_toggle_tab);
$menu_toggle_hidden = menu_toggle_hidden($menu_toggle_tab);

//
// save the switches of the tab
//
if (ip('submit')) {
    if (!kleeja_check_form_key('adm_menus_toggle')) {
        kleeja_admin_err($lang['INVALID_FORM_KEY'], $menu_toggle_action);
    }

    if (!$menu_toggle_can_edit) {
        kleeja_admin_err($lang['HV_NOT_PRVLG_ACCESS'], $menu_toggle_action);
    }

    // the items whose switch is on, only the names that the tab lists count
    $menu_toggle_shown = isset($_POST['shown']) && is_array($_POST['shown']) ? $_POST['shown'] : [];

    // a hidden item that the tab doesn't list stays hidden: the page of a plugin that is disabled for now
    $menu_toggle_new = array_values(array_diff($menu_toggle_hidden, array_keys($menu_toggle_items)));

    foreach (array_keys($menu_toggle_items) as $menu_toggle_name) {
        if (!in_array($menu_toggle_name, $menu_toggle_shown, true)) {
            $menu_toggle_new[] = $menu_toggle_name;
        }
    }

    if ($menu_toggle_new !== $menu_toggle_hidden) {
        // with OPcache, the next pages would read the old settings for a while: Kleeja doesn't tell OPcache
        // that the file changed. OPcache finds a relative path only while the file exists, before update_config()
        if (function_exists('opcache_invalidate') && file_exists(PATH . 'cache/data_config.php')) {
            @opcache_invalidate(PATH . 'cache/data_config.php', true);
        }

        update_config(menu_toggle_config_name($menu_toggle_tab), implode(':', $menu_toggle_new));
    }

    kleeja_admin_info($lang['CONFIGS_UPDATED'], $menu_toggle_action);
}

//
// the page
//
$menu_toggle_tabs = [];

foreach (MENU_TOGGLE_MENUS as $menu_toggle_menu => $menu_toggle_word) {
    $menu_toggle_tabs[] = [
        'title' => $olang[$menu_toggle_word],
        'link' => $action . '&amp;smt=' . $menu_toggle_menu,
        'current' => $menu_toggle_menu === $menu_toggle_tab,
    ];
}

$menu_toggle_exp = $olang[MENU_TOGGLE_MENUS[$menu_toggle_tab] . '_EXP'];
$menu_toggle_rows = [];

foreach ($menu_toggle_items as $menu_toggle_name => $menu_toggle_item) {
    $menu_toggle_rows[] = [
        'name' => $menu_toggle_name,
        'title' => $menu_toggle_item['title'],
        'icon' => $menu_toggle_item['icon'],
        // who sees the link: some links of the user menu are for members, the others for guests
        'audience' =>
            $menu_toggle_item['for'] !== '' ? $olang['MENU_TOGGLE_FOR_' . strtoupper($menu_toggle_item['for'])] : '',
        'shown' => !in_array($menu_toggle_name, $menu_toggle_hidden, true),
    ];
}

// the page is one of the sections of the Settings page, its menu lists them
$go_menu = menu_toggle_settings_menu();

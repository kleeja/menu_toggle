<?php
// Kleeja Plugin
// menu_toggle
// Version: 2.0
// Developer: Kleeja team

// Prevent illegal run
if (!defined('IN_PLUGINS_SYSTEM')) {
    exit();
}

// Plugin Basic Information
$kleeja_plugin['menu_toggle']['information'] = [
    // The casual name of this plugin, anything can a human being understands
    'plugin_title' => [
        'en' => 'Menu Toggle',
        'ar' => 'إظهار القوائم وإخفاؤها',
    ],
    // Who wrote this plugin?
    'plugin_developer' => 'Kleeja.net',
    // This plugin version
    'plugin_version' => '2.0',
    // Explain what is this plugin, why should I use it?
    'plugin_description' => [
        'en' => 'Hide or show the links of the site menus and the pages of the control panel menu.',
        'ar' => 'أخفِ روابط قوائم الموقع وصفحات قائمة لوحة التحكم، أو أظهرها من جديد.',
    ],
    //settings page, if there is one (what after ? like cp=j_plugins)
    'settings_page' => 'cp=menus_toggle',
    // Min version of Kleeja that's requiered to run this plugin
    'plugin_kleeja_version_min' => '4.0.0',
    // Max version of Kleeja that support this plugin, use 0 for unlimited
    'plugin_kleeja_version_max' => '4.9',
    // Should this plugin run before others?, 0 is normal, and higher number has high priority
    'plugin_priority' => 0,
];

//after installation message, you can remove it, it's not requiered
$kleeja_plugin['menu_toggle']['first_run']['ar'] = "
أضافت هذه الإضافة صفحة «إعدادات القوائم» إلى إعدادات لوحة التحكم. يمكنك منها إخفاء روابط من القائمة الرئيسية
وقائمة المستخدم في موقعك، وإخفاء صفحات من قائمة لوحة التحكم.
<br>
<a href='./index.php?cp=menus_toggle'>إعدادات القوائم</a> -
<a href='./index.php?cp=s_help#help-menu_toggle'>دليل الإضافة</a> -
<a href='https://github.com/kleeja/menu_toggle/issues' target='_blank' rel='noopener'>الإبلاغ عن مشكلة</a>
";

$kleeja_plugin['menu_toggle']['first_run']['en'] = "
This plugin added Menu settings to the Settings of the control panel. There you can hide links from the main menu
and the user menu of your site, and pages from the control panel menu.
<br>
<a href='./index.php?cp=menus_toggle'>Menu settings</a> -
<a href='./index.php?cp=s_help#help-menu_toggle'>Plugin guide</a> -
<a href='https://github.com/kleeja/menu_toggle/issues' target='_blank' rel='noopener'>Report a problem</a>
";

// Plugin Installation function
$kleeja_plugin['menu_toggle']['install'] = function ($plg_id) {
    $configs = [];

    // the hidden items of each menu, their names separated by ":"
    foreach (array_keys(MENU_TOGGLE_MENUS) as $menu) {
        $configs[menu_toggle_config_name($menu)] = [
            'value' => '',
            'plg_id' => $plg_id,
            'type' => 'menu_toggle',
        ];
    }

    add_config_r($configs);

    foreach (menu_toggle_translations() as $lang_id => $words) {
        add_olang($words, $lang_id, $plg_id);
    }
};

// 2.0 renamed and rewrote the words, they are written again so a new version brings its new words.
// With queries of its own: add_olang(), delete_olang() and delete_cache() run hooks, and the plugins are still loading
$kleeja_plugin['menu_toggle']['update'] = function ($old_version, $new_version) use ($kleeja_plugin) {
    global $SQL, $dbprefix;

    $result = $SQL->build([
        'SELECT' => 'plg_id',
        'FROM' => "{$dbprefix}plugins",
        'WHERE' => 'plg_name = :name',
        'BIND' => ['name' => 'menu_toggle'],
    ]);

    $row = $SQL->fetch_array($result);
    $plg_id = (int) ($row['plg_id'] ?? 0);
    $SQL->freeresult($result);

    //a plg_id of 0 would delete the words of the core and of every plugin
    if ($plg_id > 0) {
        $SQL->build([
            'DELETE' => "{$dbprefix}lang",
            'WHERE' => 'plg_id = :plg_id',
            'BIND' => ['plg_id' => $plg_id],
        ]);

        foreach (menu_toggle_translations() as $lang_id => $words) {
            foreach ($words as $word => $trans) {
                $SQL->build([
                    'INSERT' => 'word, trans, lang_id, plg_id',
                    'INTO' => "{$dbprefix}lang",
                    'VALUES' => ':word, :trans, :lang_id, :plg_id',
                    'BIND' => ['word' => $word, 'trans' => $trans, 'lang_id' => $lang_id, 'plg_id' => $plg_id],
                ]);
            }
        }
    }

    if (version_compare($old_version, '2.0', '<')) {
        // the developer and the description that the old versions were installed with,
        // the list of plugins shows them while the plugin is disabled
        $SQL->build([
            'UPDATE' => "{$dbprefix}plugins",
            'SET' => 'plg_author = :author, plg_dsc = :description',
            'WHERE' => 'plg_name = :name',
            'BIND' => [
                'author' => 'Kleeja.net',
                'description' => kleeja_html_encode(
                    $kleeja_plugin['menu_toggle']['information']['plugin_description']['en'],
                ),
                'name' => 'menu_toggle',
            ],
        ]);
    }

    // the cached words, and the compiled page of the control panel: an update doesn't delete the compiled templates.
    // They are PHP files, and OPcache would keep running the old ones for a while. Before a file is deleted,
    // OPcache finds a relative path only when the file exists
    $cached = array_merge(
        glob(PATH . 'cache/data_lang*.php') ?: [],
        glob(PATH . 'cache/tpl_admin_menus_toggle*.php') ?: [],
    );

    foreach ($cached as $file) {
        if (function_exists('opcache_invalidate')) {
            @opcache_invalidate($file, true);
        }

        @unlink($file);
    }
};

// Plugin Uninstallation, function to be called at unistalling
$kleeja_plugin['menu_toggle']['uninstall'] = function ($plg_id) {
    delete_config(array_map('menu_toggle_config_name', array_keys(MENU_TOGGLE_MENUS)));

    //a plg_id of 0 would delete the words of the core and of every plugin
    if ((int) $plg_id > 0) {
        foreach (array_keys(menu_toggle_translations()) as $lang_id) {
            delete_olang(null, $lang_id, (int) $plg_id);
        }
    }
};

// Plugin functions
$kleeja_plugin['menu_toggle']['functions'] = [
    // the page of the plugin, reached from the menu of the Settings page: it isn't in the menu of the control panel itself.
    // The pages that a founder hid are taken out of that menu too, but never Settings: 1.1 saved any name it was sent
    'begin_admin_page' => function ($args) {
        $adm_extensions = $args['adm_extensions'];
        $adm_extensions[] = 'menus_toggle';

        $hidden = array_diff(menu_toggle_hidden('admin'), MENU_TOGGLE_ADMIN_KEPT);
        $ext_expt = array_merge($args['ext_expt'], ['menus_toggle'], $hidden);

        // the words of the plugin in the language of the admin, then in English,
        // for a site where they are missing from the language table
        $olang = (array) ($args['olang'] ?? []) + menu_toggle_words();

        return compact('adm_extensions', 'ext_expt', 'olang');
    },

    // a link to the page in the menu of the Settings page, before "Display all the settings"
    'require_admin_page_end_a_configs' => function ($args) {
        return ['go_menu' => menu_toggle_add_to_settings_menu($args['go_menu'] ?? [], false)];
    },

    // on the page of the plugin, Settings is the current item of the menu of the control panel
    'endforeach_ext_admin_page' => function ($args) {
        if ($args['go_to'] !== 'menus_toggle' || $args['s'] !== 'a_configs') {
            return;
        }

        $adm_extensions_menu = $args['adm_extensions_menu'];
        $adm_extensions_menu[$args['i']]['current'] = true;

        return compact('adm_extensions_menu');
    },

    //add as admin page to reach when click on admin menu item we added.
    'not_exists_menus_toggle' => function () {
        $include_alternative = __DIR__ . '/menus_toggle.php';

        return compact('include_alternative');
    },

    // the hidden links of the menus of the site. They are marked as not shown rather than removed,
    // the menus keep their keys for the plugins and the styles that use them
    'Saaheader_links_func' => function ($args) {
        $return = [];

        foreach (['top', 'side'] as $menu) {
            $hidden = menu_toggle_hidden($menu);

            if (!$hidden || !is_array($args[$menu . '_menu'] ?? null)) {
                continue;
            }

            $items = $args[$menu . '_menu'];

            foreach ($items as $key => $item) {
                if (in_array($item['name'] ?? '', $hidden, true)) {
                    $items[$key]['show'] = false;
                }
            }

            $return[$menu . '_menu'] = $items;
        }

        return $return;
    },

    // the guide of the plugin on the help page of the control panel, its words are in language/help_{code}.php
    'admin_help_guides' => function ($args) {
        $help_guides = $args['help_guides'];
        $words = menu_toggle_help_words();

        // the name of the plugin is the key, so Kleeja knows that the plugin has its guide,
        // and the help button of the page of the plugin opens it
        $help_guides['menu_toggle'] = [
            'group' => 'plugins',
            'icon' => 'bars-staggered',
            'title' => $words['MENU_TOGGLE_HELP_TITLE'],
            'intro' => $words['MENU_TOGGLE_HELP_INTRO'],
            'page' => 'menus_toggle',
            'link' => './?cp=menus_toggle',
            // tips and warnings are shown beside the others on wide screens
            'sections' => [
                menu_toggle_help_section($words, 'features', 'FEATURE'),
                menu_toggle_help_section($words, 'steps', 'STEP'),
                menu_toggle_help_section($words, 'faq', 'FAQ'),
                menu_toggle_help_section($words, 'tips', 'TIP'),
                menu_toggle_help_section($words, 'warnings', 'WARNING'),
            ],
        ];

        // the guide of the Settings page, where the page of the plugin is, points to the plugin
        if (isset($help_guides['settings'])) {
            $help_guides['settings']['sections'][] = ['type' => 'text', 'text' => $words['MENU_TOGGLE_HELP_NOTE']];
        }

        return compact('help_guides');
    },
];

/**
 * special functions
 */

if (!function_exists('menu_toggle_hidden')) {
    // the menus that the plugin changes, and the tab of each one on the page of the plugin
    define('MENU_TOGGLE_MENUS', [
        'top' => 'MENU_TOGGLE_TOP_MENU',
        'side' => 'MENU_TOGGLE_SIDE_MENU',
        'admin' => 'MENU_TOGGLE_ADMIN_MENU',
    ]);

    // the pages of the control panel that stay in its menu: the dashboard, Settings (where the page of the plugin is),
    // Help, the plugin itself, and the old pages of logging out and of extensions, which Kleeja never lists
    define('MENU_TOGGLE_ADMIN_KEPT', ['start', 'a_configs', 's_help', 'b_lgoutcp', 'i_exts', 'menus_toggle']);

    /**
     * the name of the setting that holds the hidden items of a menu
     * @param  string $menu top, side or admin
     * @return string
     */
    function menu_toggle_config_name($menu)
    {
        return 'menu_toggle_hidden_' . $menu . 'menu_items';
    }

    /**
     * the names of the hidden items of a menu
     * @param  string   $menu top, side or admin
     * @return string[]
     */
    function menu_toggle_hidden($menu)
    {
        global $config;

        $value = (string) ($config[menu_toggle_config_name($menu)] ?? '');

        return array_values(array_filter(array_map('trim', explode(':', $value)), 'strlen'));
    }

    /**
     * the items of a menu that can be hidden, as the page of the plugin lists them
     * @param  string $menu top, side or admin
     * @return array  name => [title, icon, who sees it]
     */
    function menu_toggle_items($menu)
    {
        global $lang, $olang, $adm_extensions, $ext_icons;

        // the menus of Saaheader(), with the icons that the styles give them
        if ($menu === 'top') {
            return [
                'index' => ['title' => $lang['INDEX'], 'icon' => 'house', 'for' => ''],
                'rules' => ['title' => $lang['RULES'], 'icon' => 'scale-balanced', 'for' => ''],
                'guide' => ['title' => $lang['GUIDE'], 'icon' => 'file-circle-check', 'for' => ''],
                'stats' => ['title' => $lang['STATS'], 'icon' => 'chart-simple', 'for' => ''],
                'report' => ['title' => $lang['REPORT'], 'icon' => 'flag', 'for' => ''],
                'call' => ['title' => $lang['CALL'], 'icon' => 'envelope', 'for' => ''],
            ];
        }

        if ($menu === 'side') {
            return [
                'profile' => ['title' => $lang['PROFILE'], 'icon' => 'user-gear', 'for' => 'members'],
                'fileuser' => ['title' => $lang['YOUR_FILEUSER'], 'icon' => 'folder-open', 'for' => 'members'],
                'logout' => ['title' => $lang['LOGOUT'], 'icon' => 'right-from-bracket', 'for' => 'members'],
                'login' => ['title' => $lang['LOGIN'], 'icon' => 'right-to-bracket', 'for' => 'guests'],
                'register' => ['title' => $lang['REGISTER'], 'icon' => 'user-plus', 'for' => 'guests'],
            ];
        }

        // the pages of the control panel, with the pages of the plugins, in the order of its menu
        $pages = array_diff((array) $adm_extensions, MENU_TOGGLE_ADMIN_KEPT);
        sort($pages);
        $items = [];

        foreach ($pages as $page) {
            $name = isset($page[1]) && $page[1] === '_' ? substr($page, 2) : $page;
            $word = 'R_' . strtoupper($name);

            $items[$page] = [
                'title' => !empty($lang[$word]) ? $lang[$word] : (!empty($olang[$word]) ? $olang[$word] : $name),
                'icon' => !empty($ext_icons[$name]) ? $ext_icons[$name] : 'puzzle-piece',
                'for' => '',
            ];
        }

        return $items;
    }

    /**
     * the menu of the Settings page with the page of the plugin in it, before "Display all the settings"
     * @param  array $go_menu the menu that the Settings page made
     * @param  bool  $current whether the page of the plugin is the one open
     * @return array
     */
    function menu_toggle_add_to_settings_menu($go_menu, $current)
    {
        global $olang;

        $all = $go_menu['all'] ?? null;
        unset($go_menu['all']);

        $go_menu['menus_toggle'] = [
            'name' => $olang['R_MENUS_TOGGLE'] ?? menu_toggle_words()['R_MENUS_TOGGLE'],
            'link' => './?cp=menus_toggle',
            'goto' => 'menus_toggle',
            'current' => $current,
        ];

        if ($all !== null) {
            $go_menu['all'] = $all;
        }

        return $go_menu;
    }

    /**
     * the menu of the Settings page, for the page of the plugin: the same sections that the Settings page lists,
     * so the menu stays the same while moving between them
     * @return array
     */
    function menu_toggle_settings_menu()
    {
        global $SQL, $dbprefix, $lang, $olang;

        $base_action = basename(ADMIN_PATH) . '?cp=options';
        $go_menu = [];

        $result = $SQL->build([
            'SELECT' => 'DISTINCT(c.type), c.display_order, p.plg_disabled, c.plg_id',
            'FROM' => "{$dbprefix}config c",
            'JOINS' => [
                [
                    'LEFT JOIN' => "{$dbprefix}plugins p",
                    'ON' => 'p.plg_id=c.plg_id',
                ],
            ],
            'WHERE' => "c.option <> '' AND c.type <> 'groups'",
            'ORDER BY' => 'c.display_order',
        ]);

        while ($row = $SQL->fetch_array($result)) {
            // the settings of a disabled plugin aren't listed
            if (
                $row['type'] === 'KLIVE' ||
                ((int) $row['plg_id'] > 0 && (is_null($row['plg_disabled']) || (int) $row['plg_disabled'] === 1))
            ) {
                continue;
            }

            $word = 'CONFIG_KLJ_MENUS_' . strtoupper($row['type']);

            $go_menu[$row['type']] = [
                'name' => !empty($lang[$word])
                    ? $lang[$word]
                    : (!empty($olang[$word])
                        ? $olang[$word]
                        : $lang['CONFIG_KLJ_MENUS_OTHER']),
                'link' => $base_action . '&amp;smt=' . $row['type'],
                'goto' => $row['type'],
                'current' => false,
            ];
        }

        $SQL->freeresult($result);

        $go_menu['all'] = [
            'name' => $lang['CONFIG_KLJ_MENUS_ALL'],
            'link' => $base_action . '&amp;smt=all',
            'goto' => 'all',
            'current' => false,
        ];

        return menu_toggle_add_to_settings_menu($go_menu, true);
    }

    /**
     * the words of the plugin in the language of the admin, a word that the translation misses is shown in English
     * @return array
     */
    function menu_toggle_words()
    {
        global $config;
        static $words = [];

        $language = (string) ($config['language'] ?? 'en');

        if (!isset($words[$language])) {
            $translations = menu_toggle_translations();
            $words[$language] = ($translations[$language] ?? []) + $translations['en'];
        }

        return $words[$language];
    }

    /**
     * the words of the guide on the help page, in the language of the admin,
     * a word that the translation misses is shown in English
     * @return array
     */
    function menu_toggle_help_words()
    {
        global $config;

        $words = (array) require __DIR__ . '/language/help_en.php';
        $language = preg_replace('/[^a-z0-9_-]/i', '', (string) ($config['language'] ?? ''));
        $translation = __DIR__ . "/language/help_{$language}.php";

        if ($language !== '' && $language !== 'en' && file_exists($translation)) {
            // in its own line, Prettier drops the brackets of (require $translation) + $words
            $translated = require $translation;
            $words = (array) $translated + $words;
        }

        return $words;
    }

    /**
     * a section of the guide from its numbered words, like MENU_TOGGLE_HELP_TIP_1, MENU_TOGGLE_HELP_TIP_2 ..
     * its title, when it has its own, is MENU_TOGGLE_HELP_TIP_TITLE
     * @param  array  $words
     * @param  string $type  how Kleeja shows it: features, steps, tips, warnings or faq
     * @param  string $name  the name of its words, MENU_TOGGLE_HELP_{name}_1
     * @return array
     */
    function menu_toggle_help_section($words, $type, $name)
    {
        $prefix = 'MENU_TOGGLE_HELP_' . $name;
        $section = ['type' => $type, 'title' => $words[$prefix . '_TITLE'] ?? '', 'items' => []];

        for ($n = 1; isset($words[$prefix . ($type === 'faq' ? '_Q_' : '_') . $n]); $n++) {
            $section['items'][] =
                $type === 'faq'
                    ? ['q' => $words[$prefix . '_Q_' . $n], 'a' => $words[$prefix . '_A_' . $n] ?? '']
                    : $words[$prefix . '_' . $n];
        }

        return $section;
    }

    /**
     * the words of the plugin, they are added to the language table when it is installed or updated.
     * Each one fits in the 255 letters of the column on MySQL
     * @return array
     */
    function menu_toggle_translations()
    {
        return [
            'en' => [
                'R_MENUS_TOGGLE' => 'Menu settings',
                'MENU_TOGGLE_INTRO' =>
                    'Choose which links appear in the menus of your site and in the control panel menu. Hiding a link only removes it from its menu: the page itself can still be opened from its address.',
                'MENU_TOGGLE_TOP_MENU' => 'Main menu',
                'MENU_TOGGLE_SIDE_MENU' => 'User menu',
                'MENU_TOGGLE_ADMIN_MENU' => 'Control panel menu',
                'MENU_TOGGLE_TOP_MENU_EXP' => 'The links of the navigation bar, at the top of every page of your site.',
                'MENU_TOGGLE_SIDE_MENU_EXP' =>
                    'The account links. Members find them in the menu under their username, and guests see Login and Register.',
                'MENU_TOGGLE_ADMIN_MENU_EXP' =>
                    'The pages in the sidebar of the control panel, for every administrator. Dashboard, Settings and Help always stay in it.',
                'MENU_TOGGLE_HINT' =>
                    'Turn a switch off to hide its link, or on to show it again, then save your changes.',
                'MENU_TOGGLE_HIDDEN' => 'Hidden',
                'MENU_TOGGLE_FOR_MEMBERS' => 'For members',
                'MENU_TOGGLE_FOR_GUESTS' => 'For guests',
                'MENU_TOGGLE_SAVE' => 'Save changes',
                'MENU_TOGGLE_FOUNDERS_ONLY' =>
                    'Only founders can change the control panel menu, because every administrator sees it. Ask a founder of this site to change it.',
            ],
            'ar' => [
                'R_MENUS_TOGGLE' => 'إعدادات القوائم',
                'MENU_TOGGLE_INTRO' =>
                    'اختر الروابط التي تظهر في قوائم موقعك وفي قائمة لوحة التحكم. إخفاء الرابط يزيله من قائمته فقط، وتبقى صفحته متاحة لمن يفتحها من عنوانها.',
                'MENU_TOGGLE_TOP_MENU' => 'القائمة الرئيسية',
                'MENU_TOGGLE_SIDE_MENU' => 'قائمة المستخدم',
                'MENU_TOGGLE_ADMIN_MENU' => 'قائمة لوحة التحكم',
                'MENU_TOGGLE_TOP_MENU_EXP' => 'روابط شريط التنقل، أعلى كل صفحة من صفحات موقعك.',
                'MENU_TOGGLE_SIDE_MENU_EXP' =>
                    'روابط الحساب: يجدها الأعضاء في القائمة التي تحت اسمهم، ويرى الزوار رابطَي «دخول» و«تسجيل عضوية».',
                'MENU_TOGGLE_ADMIN_MENU_EXP' =>
                    'صفحات الشريط الجانبي في لوحة التحكم، لكل المشرفين. تبقى فيه دائمًا «بداية لوحة كليجا» و«إعدادات» و«المساعدة».',
                'MENU_TOGGLE_HINT' => 'أوقف المفتاح لإخفاء رابطه، أو شغّله لإظهاره من جديد، ثم احفظ التغييرات.',
                'MENU_TOGGLE_HIDDEN' => 'مخفي',
                'MENU_TOGGLE_FOR_MEMBERS' => 'للأعضاء',
                'MENU_TOGGLE_FOR_GUESTS' => 'للزوار',
                'MENU_TOGGLE_SAVE' => 'حفظ التغييرات',
                'MENU_TOGGLE_FOUNDERS_ONLY' =>
                    'تغيير قائمة لوحة التحكم مقصور على المؤسسين، لأنها تظهر لكل المشرفين. اطلب من أحد مؤسسي الموقع تغييرها.',
            ],
        ];
    }
}

<?php
//
// menu_toggle, its guide on the help page of the control panel
// English
//
// The guide is built by the admin_help_guides hook in init.php, the texts here can have HTML.
//  - a list is numbered: MENU_TOGGLE_HELP_TIP_1, MENU_TOGGLE_HELP_TIP_2 ... it ends at the first missing number,
//    so an item can be added or removed without touching the code
//  - MENU_TOGGLE_HELP_{LIST}_TITLE is the title of the list, a list without it takes the title that Kleeja gives its type
//  - questions and answers are MENU_TOGGLE_HELP_FAQ_Q_1 and MENU_TOGGLE_HELP_FAQ_A_1, a question has no HTML
//  - names in <strong> are written as the control panel shows them, the words of Kleeja are in lang/en/,
//    the words of the plugin are in menu_toggle_translations() of init.php
//

return [
    'MENU_TOGGLE_HELP_TITLE' => 'Menu Toggle',
    'MENU_TOGGLE_HELP_INTRO' =>
        'Choose which links your visitors see in the menus of your site, and which pages your administrators see in the control panel menu.',

    //
    // what the plugin does
    //
    'MENU_TOGGLE_HELP_FEATURE_1' =>
        'Hides or shows the links of the <strong>Main menu</strong>: <strong>Homepage</strong>, <strong>Terms</strong>, <strong>Allowed Extensions</strong>, <strong>Statistics</strong>, <strong>Report</strong> and <strong>Contact Us</strong>.',
    'MENU_TOGGLE_HELP_FEATURE_2' =>
        'Hides or shows the links of the <strong>User menu</strong>: <strong>Profile</strong>, <strong>Your folder</strong> and <strong>Logout</strong> for members, <strong>Login</strong> and <strong>Register</strong> for guests.',
    'MENU_TOGGLE_HELP_FEATURE_3' =>
        'Hides pages from the <strong>Control panel menu</strong>, including the pages that other plugins add. Only founders can change this menu.',
    'MENU_TOGGLE_HELP_FEATURE_4' =>
        'Applies to every visitor and every administrator, wherever the style shows these menus, such as in the header and the footer.',

    //
    // how to use it
    //
    'MENU_TOGGLE_HELP_STEP_1' => 'Open <strong>Settings</strong>, then <strong>Menu settings</strong> in its menu.',
    'MENU_TOGGLE_HELP_STEP_2' =>
        'Choose a tab: <strong>Main menu</strong>, <strong>User menu</strong> or <strong>Control panel menu</strong>.',
    'MENU_TOGGLE_HELP_STEP_3' =>
        'Turn off the switch of each link that you want to hide, and turn on the switch of each link that you want to show again.',
    'MENU_TOGGLE_HELP_STEP_4' => 'Click <strong>Save changes</strong>. The menu changes at once, for everyone.',

    //
    // common questions
    //
    'MENU_TOGGLE_HELP_FAQ_Q_1' => 'Does hiding a link stop people from opening its page?',
    'MENU_TOGGLE_HELP_FAQ_A_1' =>
        'No. Hiding a link only removes it from its menu, and the page still opens from its address. To stop a group from using a page, turn off its permission in <strong>Users &amp; Groups</strong>, from <strong>Edit Permissions</strong> of the group.',
    'MENU_TOGGLE_HELP_FAQ_Q_2' => 'How do I open a page that I hid from the control panel menu?',
    'MENU_TOGGLE_HELP_FAQ_A_2' =>
        'From its address, such as <code>?cp=j_plugins</code> after the address of the control panel for <strong>Plugins</strong>. To bring it back to the menu, turn its switch on in <strong>Settings → Menu settings → Control panel menu</strong>.',
    'MENU_TOGGLE_HELP_FAQ_Q_3' => 'Why can’t I change the control panel menu?',
    'MENU_TOGGLE_HELP_FAQ_A_3' =>
        'Only founders can change it, because every administrator sees it. An account becomes a founder with the <strong>Founder</strong> option of its edit form in <strong>Users &amp; Groups</strong>, which only founders see.',
    'MENU_TOGGLE_HELP_FAQ_Q_4' => 'Why is a link missing from the menu while its switch is on?',
    'MENU_TOGGLE_HELP_FAQ_A_4' =>
        'Kleeja shows some links only when they can be used: <strong>Statistics</strong> while the statistics page is on and the group may open it, <strong>Report</strong> and <strong>Contact Us</strong> to the groups that may use them, <strong>Your folder</strong> while user folders are on, and <strong>Register</strong> while registration is open. The plugin can hide these links, but it can’t show them.',
    'MENU_TOGGLE_HELP_FAQ_Q_5' => 'Why is a link still shown after I hid it?',
    'MENU_TOGGLE_HELP_FAQ_A_5' =>
        'The plugin hides the links of the menus of Kleeja. A link that your style writes in its own templates, or that another plugin adds, isn’t one of them: change it in the style, or in the settings of that plugin.',

    //
    // beside the guide
    //
    'MENU_TOGGLE_HELP_TIP_1' =>
        'Keep the main menu short: hide the links that your visitors rarely need, such as <strong>Allowed Extensions</strong> or <strong>Statistics</strong>.',
    'MENU_TOGGLE_HELP_TIP_2' =>
        'Hide the control panel pages that your team never uses, so the menu is quicker to scan. The pages stay available from their address.',
    'MENU_TOGGLE_HELP_TIP_3' =>
        '<strong>Dashboard</strong>, <strong>Settings</strong> and <strong>Help</strong> can’t be hidden, so <strong>Menu settings</strong> can always be reached from <strong>Settings</strong>.',

    'MENU_TOGGLE_HELP_WARNING_1' =>
        'Hiding a link is not a permission: anyone who knows the address of the page can still open it. To block a page, change the permissions of the groups.',
    'MENU_TOGGLE_HELP_WARNING_2' =>
        'If you hide <strong>Login</strong>, guests can sign in only from the address of the login page, <code>ucp.php?go=login</code>.',

    //
    // in the guide of the Settings page, where the page of the plugin is
    //
    'MENU_TOGGLE_HELP_NOTE' =>
        'To hide links from the menus of your site, or pages from the control panel menu, use <strong>Menu settings</strong>. <a href="#help-menu_toggle">Read its guide</a>.',
];

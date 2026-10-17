<?php
//
// menu_toggle, its guide on the help page of the control panel
// Arabic
//
// The guide is built by the admin_help_guides hook in init.php, the texts here can have HTML.
// The keys are the ones of help_en.php, a key that is missing here is shown in English.
//  - names in <strong> are written as the control panel shows them, the words of Kleeja are in lang/ar/
//  - a <code> that begins or ends with a symbol has dir="ltr", or the symbol moves to the other side
//  - a question has no HTML, names in it are quoted with «»
//

return [
    'MENU_TOGGLE_HELP_TITLE' => 'إظهار القوائم وإخفاؤها',
    'MENU_TOGGLE_HELP_INTRO' =>
        'اختر الروابط التي يراها زوار موقعك في قوائمه، والصفحات التي يراها المشرفون في قائمة لوحة التحكم.',

    //
    // what the plugin does
    //
    'MENU_TOGGLE_HELP_FEATURE_1' =>
        'تُخفي روابط <strong>القائمة الرئيسية</strong> أو تُظهرها: <strong>الرئيسية</strong> و<strong>شروط الخدمة</strong> و<strong>الملفات المسموحة</strong> و<strong>إحصائيات المركز</strong> و<strong>إبلاغ عن محتوى</strong> و<strong>اتصل بنا</strong>.',
    'MENU_TOGGLE_HELP_FEATURE_2' =>
        'تُخفي روابط <strong>قائمة المستخدم</strong> أو تُظهرها: <strong>تعديل البيانات</strong> و<strong>مجلدك الشخصي</strong> و<strong>خروج</strong> للأعضاء، و<strong>دخول</strong> و<strong>تسجيل عضوية</strong> للزوار.',
    'MENU_TOGGLE_HELP_FEATURE_3' =>
        'تُخفي صفحات من <strong>قائمة لوحة التحكم</strong>، ومنها الصفحات التي تضيفها الإضافات الأخرى. تغيير هذه القائمة مقصور على المؤسسين.',
    'MENU_TOGGLE_HELP_FEATURE_4' =>
        'يسري التغيير على كل الزوار وكل المشرفين، في كل مكان يعرض فيه الستايل هذه القوائم، كرأس الصفحة وتذييلها.',

    //
    // how to use it
    //
    'MENU_TOGGLE_HELP_STEP_1' => 'افتح <strong>إعدادات</strong>، ثم <strong>إعدادات القوائم</strong> من قائمتها.',
    'MENU_TOGGLE_HELP_STEP_2' =>
        'اختر تبويبًا: <strong>القائمة الرئيسية</strong> أو <strong>قائمة المستخدم</strong> أو <strong>قائمة لوحة التحكم</strong>.',
    'MENU_TOGGLE_HELP_STEP_3' => 'أوقف مفتاح كل رابط تريد إخفاءه، وشغّل مفتاح كل رابط تريد إظهاره من جديد.',
    'MENU_TOGGLE_HELP_STEP_4' => 'اضغط <strong>حفظ التغييرات</strong>. تتغير القائمة فورًا، لكل من يراها.',

    //
    // common questions
    //
    'MENU_TOGGLE_HELP_FAQ_Q_1' => 'هل يمنع إخفاء الرابط فتح صفحته؟',
    'MENU_TOGGLE_HELP_FAQ_A_1' =>
        'لا. إخفاء الرابط يزيله من قائمته فقط، وتبقى الصفحة تُفتح من عنوانها. لمنع مجموعة من استخدام صفحة، أوقف صلاحيتها من <strong>تعديل الصلاحيات</strong> للمجموعة في <strong>الأعضاء والمجموعات</strong>.',
    'MENU_TOGGLE_HELP_FAQ_Q_2' => 'كيف أفتح صفحة أخفيتها من قائمة لوحة التحكم؟',
    'MENU_TOGGLE_HELP_FAQ_A_2' =>
        'من عنوانها، مثل <code dir="ltr">?cp=j_plugins</code> بعد عنوان لوحة التحكم لصفحة <strong>إضافات برمجية</strong>. ولإعادتها إلى القائمة، شغّل مفتاحها في <strong>إعدادات ← إعدادات القوائم ← قائمة لوحة التحكم</strong>.',
    'MENU_TOGGLE_HELP_FAQ_Q_3' => 'لماذا لا أستطيع تغيير قائمة لوحة التحكم؟',
    'MENU_TOGGLE_HELP_FAQ_A_3' =>
        'تغييرها مقصور على المؤسسين، لأنها تظهر لكل المشرفين. يصبح الحساب مؤسسًا من خيار <strong>مؤسس</strong> في نموذج تعديله في <strong>الأعضاء والمجموعات</strong>، ولا يرى هذا الخيار إلا المؤسسون.',
    'MENU_TOGGLE_HELP_FAQ_Q_4' => 'لماذا لا يظهر رابط في القائمة ومفتاحه مشغّل؟',
    'MENU_TOGGLE_HELP_FAQ_A_4' =>
        'لا تعرض كليجا بعض الروابط إلا حين يمكن استخدامها: <strong>إحصائيات المركز</strong> ما دامت صفحة الإحصائيات مفعّلة وللمجموعة صلاحية فتحها، و<strong>إبلاغ عن محتوى</strong> و<strong>اتصل بنا</strong> للمجموعات التي لها صلاحية استخدامهما، و<strong>مجلدك الشخصي</strong> ما دامت المجلدات الشخصية مفعّلة، و<strong>تسجيل عضوية</strong> ما دام التسجيل مفتوحًا. تستطيع الإضافة إخفاء هذه الروابط، لكنها لا تستطيع إظهارها.',
    'MENU_TOGGLE_HELP_FAQ_Q_5' => 'لماذا ما زال رابط يظهر بعد أن أخفيته؟',
    'MENU_TOGGLE_HELP_FAQ_A_5' =>
        'تُخفي الإضافة روابط قوائم كليجا. أما الرابط الذي يكتبه الستايل في قوالبه، أو الذي تضيفه إضافة أخرى، فليس منها: غيّره من الستايل أو من إعدادات تلك الإضافة.',

    //
    // beside the guide
    //
    'MENU_TOGGLE_HELP_TIP_1' =>
        'اجعل القائمة الرئيسية قصيرة: أخفِ الروابط التي نادرًا ما يحتاجها زوارك، مثل <strong>الملفات المسموحة</strong> أو <strong>إحصائيات المركز</strong>.',
    'MENU_TOGGLE_HELP_TIP_2' =>
        'أخفِ صفحات لوحة التحكم التي لا يستخدمها فريقك، ليسهل تصفح القائمة. تبقى هذه الصفحات متاحة من عناوينها.',
    'MENU_TOGGLE_HELP_TIP_3' =>
        'لا يمكن إخفاء <strong>بداية لوحة كليجا</strong> و<strong>إعدادات</strong> و<strong>المساعدة</strong>، فيمكن الوصول إلى <strong>إعدادات القوائم</strong> من <strong>إعدادات</strong> دائمًا.',

    'MENU_TOGGLE_HELP_WARNING_1' =>
        'إخفاء الرابط ليس صلاحية: من يعرف عنوان الصفحة يستطيع فتحها. لمنع الوصول إلى صفحة، غيّر صلاحيات المجموعات.',
    'MENU_TOGGLE_HELP_WARNING_2' =>
        'إن أخفيت <strong>دخول</strong>، فلن يستطيع الزوار تسجيل الدخول إلا من عنوان صفحة الدخول <code dir="ltr">ucp.php?go=login</code>.',

    //
    // in the guide of the Settings page, where the page of the plugin is
    //
    'MENU_TOGGLE_HELP_NOTE' =>
        'لإخفاء روابط من قوائم موقعك، أو صفحات من قائمة لوحة التحكم، استخدم <strong>إعدادات القوائم</strong>. <a href="#help-menu_toggle">اقرأ دليلها</a>.',
];

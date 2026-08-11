<?php

return [
    'source_zip' => env('SERVICE_IMAGES_ZIP', storage_path('app/assets.zip')),
    'maximum_entries' => 1000,
    'maximum_entry_bytes' => 25 * 1024 * 1024,
    'maximum_archive_bytes' => 1024 * 1024 * 1024,
    'maximum_dimension' => 12000,
    'maximum_pixels' => 80_000_000,
    'webp_quality' => 82,
    'avif_quality' => 58,
    'jpeg_quality' => 84,

    'curated_source' => env('SERVICE_IMAGES_DIRECTORY', storage_path('app/assets')),
    'curated_folders' => [
        'bytalshar' => [
            'service' => 'بيوت شعر', 'expected' => 20, 'stem' => 'bayt-shaar-riyadh',
            'context' => 'بيت شعر خارجي بقماش عازل وواجهات مجهزة',
            'exclude' => ['yrtfdrt.webp'],
        ],
        'hangers' => [
            'service' => 'هناجر حديد', 'expected' => 7, 'stem' => 'hangar-riyadh',
            'context' => 'تنفيذ هنجر وهيكل معدني في موقع العمل',
            'exclude' => ['hangers3.webp'],
        ],
        'bargolat' => [
            'service' => 'برجولات حديد', 'expected' => 15, 'stem' => 'pergola-riyadh',
            'context' => 'برجولة خارجية بهيكل حديد في حديقة',
        ],
        'sandawitshpanel' => [
            'service' => 'غرف ساندوتش بانل', 'expected' => 7, 'stem' => 'sandwich-panel-riyadh',
            'context' => 'غرفة ساندوتش بانل خارجية بألواح معزولة',
        ],
        'matalatalshdalnshai' => [
            'service' => 'مظلات شد إنشائي', 'expected' => 24, 'stem' => 'tensile-structure-shade-riyadh',
            'context' => 'مظلة شد إنشائي بقماش مشدود وهيكل معدني',
            'cover' => 'WhatsApp Image 2026-08-09 at 7.28.45 PM.webp',
        ],
        'madtaltpfc' => [
            'service' => 'مظلات سيارات PVC', 'expected' => 45, 'stem' => 'mazallat-pvc-riyadh',
            'context' => 'مظلة PVC بقماش عازل وهيكل معدني',
            // هذه الصورة تظهر سقف PVC مشدودًا بوضوح، وحُفظ مجلدها الأصلي في التقرير.
            'include' => ['bytalshar/yrtfdrt.webp'],
        ],
    ],

    /*
     * المطابقة تبدأ باسم المجلد، ثم يمكن للاستثناء البصري الموثق أدناه تغيير
     * الخدمة بحسب بصمة الملف. بهذه الطريقة لا يعتمد القرار على اسم واتساب.
     */
    'folders' => [
        ['contains' => ['البي في سي', 'pvc'], 'service' => 'مظلات سيارات PVC', 'stem' => 'mazallat-pvc-riyadh', 'context' => 'مظلة سيارات بغطاء قماشي وهيكل معدني'],
        ['contains' => ['الشد'], 'service' => 'مظلات شد إنشائي', 'stem' => 'tensile-structure-shade-riyadh', 'context' => 'مظلة شد إنشائي بقماش مشدود'],
        ['contains' => ['هناجر'], 'service' => 'هناجر حديد', 'stem' => 'hangar-riyadh', 'context' => 'هيكل هنجر حديد أثناء التنفيذ'],
        ['contains' => ['سندويش', 'ساندوتش'], 'service' => 'غرف ساندوتش بانل', 'stem' => 'sandwich-panel-room-riyadh', 'context' => 'غرفة خارجية بواجهات زجاجية وألواح معزولة'],
        ['contains' => ['برجولات'], 'service' => 'برجولات حديد', 'stem' => 'pergola-riyadh', 'context' => 'برجولة خارجية بهيكل معدني'],
    ],

    'service_stems' => [
        'مظلات سيارات PVC' => 'mazallat-pvc-riyadh',
        'مظلات شد إنشائي' => 'tensile-structure-shade-riyadh',
        'هناجر حديد' => 'hangar-riyadh',
        'شبوك وأسوار' => 'fence-riyadh',
        'غرف ساندوتش بانل' => 'sandwich-panel-room-riyadh',
        'جلسات شتوية زجاجية' => 'glass-winter-room-riyadh',
        'برجولات حديد' => 'pergola-riyadh',
        'بيوت شعر' => 'bayt-shaar-riyadh',
    ],

    'visual_overrides' => [
        // صور شد إنشائي مكررة داخل مجلد PVC؛ الدليل المرئي واضح وتبقى خانة source_folder كما هي.
        '4e4c797e2b1971f3c5c609fd0bf4b25c5d80deb2ef9f01733fde2696c09430ae' => ['service' => 'مظلات شد إنشائي', 'stem' => 'tensile-structure-shade-riyadh', 'context' => 'مظلة شد إنشائي متعددة النقاط بقماش مشدود'],
        'ff14c4ff17d8596a430bc12d0a6724e43a8516a3fb1d06b49b3ff146e58c576f' => ['service' => 'مظلات شد إنشائي', 'stem' => 'tensile-structure-shade-riyadh', 'context' => 'مظلة قماشية مشدودة على أعمدة معدنية'],
        'fd05f4630e445f5787818cfd2443097e0a7d934d03b16e0ad2d278f80c181307' => ['service' => 'مظلات شد إنشائي', 'stem' => 'tensile-structure-shade-riyadh', 'context' => 'مظلة شد إنشائي بشكل قماشي متموج'],
        '85aca593336fcc469215b1750993f703edb7abd3d764ffdd79a7440ef33a931f' => ['service' => 'مظلات شد إنشائي', 'stem' => 'tensile-structure-shade-riyadh', 'context' => 'مظلات شد إنشائي متتابعة فوق مساحة خارجية'],
        '8a67385f042258b415dfdd78b5054b1121ed7bde8b384b959f2b09421565de70' => ['service' => 'مظلات شد إنشائي', 'stem' => 'tensile-structure-shade-riyadh', 'context' => 'مظلة شد إنشائي بتكوين قماشي هندسي'],
        '8d16ca9047ac95fda06720d50829547430e96d38bb0e0a6f0e9371fc1b5cd708' => ['service' => 'مظلات شد إنشائي', 'stem' => 'tensile-structure-shade-riyadh', 'context' => 'مظلة شد إنشائي كبيرة لمساحة عامة'],

        // هياكل برجولات وغرفة زجاجية ظهرت بوضوح داخل مجلد الشد الإنشائي.
        '215cad2e4d4eff8b33cd66b211135469afbccc67bccb17203ba3c919e4606bd1' => ['service' => 'برجولات حديد', 'stem' => 'pergola-riyadh', 'context' => 'هيكل برجولة حديد أثناء التجهيز'],
        '20ca5385dc0b88512965763b97bfb6311efb430865a6844cb959fdca7d90af82' => ['service' => 'برجولات حديد', 'stem' => 'pergola-riyadh', 'context' => 'تفاصيل سقف برجولة بهيكل معدني'],
        'aee41db23183e4e59ac9c8b3c0a86c787bac6c372f37d8e63cac8f3ae191474a' => ['service' => 'غرف ساندوتش بانل', 'stem' => 'sandwich-panel-room-riyadh', 'context' => 'غرفة خارجية جاهزة بألواح وجدران معزولة'],
        'd47c30d1373c7cb2db56493a4cd8bca1fd5545e01ec799938c20b99c3bacbec3' => ['service' => 'برجولات حديد', 'stem' => 'pergola-riyadh', 'context' => 'هيكل برجولة حديد قائم في مساحة خارجية'],
        '0b48b240ee719e7fc5a1d27b9853cffbc676c5b0744b460bae34a9ccc4449cf6' => ['service' => 'برجولات حديد', 'stem' => 'pergola-riyadh', 'context' => 'برجولة حديد بسقف شرائح في فناء خارجي'],
        '1e1111f38d6f4bf4d5f48f8bee1f6df41fc09d88f884bf5e0eff2ef177410332' => ['service' => 'جلسات شتوية زجاجية', 'stem' => 'glass-winter-room-riyadh', 'context' => 'جلسة خارجية مغلقة بواجهات زجاجية'],
        '57932c98f7665909bd038c1934e2c723ee50bfa90d8328ef0bd94838bf8fe74c' => ['service' => 'برجولات حديد', 'stem' => 'pergola-riyadh', 'context' => 'برجولة حديد مستقلة في حديقة'],

        // صور أسوار وشبوك ملاعب وليست هناجر.
        '5b81109e07af5f10ace2f15a06db86f0f121c8c3ae99670e11701f7e7af23bd6' => ['service' => 'شبوك وأسوار', 'stem' => 'fence-riyadh', 'context' => 'سياج شبكي مرتفع حول ملعب رياضي'],
        'dcad9c8663d86484715867d5e46822053902bc00884c3b5e15b3ad5acd7785ec' => ['service' => 'شبوك وأسوار', 'stem' => 'fence-riyadh', 'context' => 'شبوك معدنية محيطة بملعب خارجي'],
        '69de585b86ad11a722509a18a0e23b02d761ca7013dbd448c16043e7501ed6de' => ['service' => 'شبوك وأسوار', 'stem' => 'fence-riyadh', 'context' => 'أعمدة وشبك حماية لملعب رياضي'],
        '83640072b33b4c0ee28b71db395a02ea13dcc4a1c266b4c5161ad5dd41931079' => ['service' => 'شبوك وأسوار', 'stem' => 'fence-riyadh', 'context' => 'سور شبكي معدني لمنشأة رياضية'],
        '322102740d34be21989808c7568285626b831c91fbdef356b9926622e685ff3f' => ['service' => 'شبوك وأسوار', 'stem' => 'fence-riyadh', 'context' => 'شبك حماية مرتفع مثبت حول ملعب'],

        // لقطات داخلية لا تكفي لإثبات خدمة بعينها؛ لا تُربط تلقائيًا.
        '667151a18d1e8bb747a92b6b7f38436764b93cd7dcd71a4e728f37f385edb52b' => ['service' => null, 'reason' => 'لقطة داخلية لا تثبت خدمة مظلات أو إنشاءات خارجية.'],
        'c1b47042bac4907c186553edde462999315e9ae17ec69a9c57c951a4863ad012' => ['service' => null, 'reason' => 'صورة سقف داخلي غير قابلة للإسناد إلى خدمة مؤكدة.'],
        '4d272ee80177176d80b69c5389cb81cef6218e1e740451c07d2a2fe2b078266a' => ['service' => null, 'reason' => 'صورة ديكور داخلي غير قابلة للإسناد إلى خدمة مؤكدة.'],
        'cc3f6686c788a038108f7fb7f24ad8d694aaaaad92f7d150f5641a7ff659b61d' => ['service' => null, 'reason' => 'صورة سقف داخلي غير قابلة للإسناد إلى خدمة مؤكدة.'],
        '11c561423b3ead0e7f58de736ad0054f3098f36e97ed415c4896b7b069d1515f' => ['service' => null, 'reason' => 'لقطة داخلية لا تكفي لتحديد خدمة الموقع.'],
    ],
];

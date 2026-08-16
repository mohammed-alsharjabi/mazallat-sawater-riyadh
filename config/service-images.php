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
    'auto_sync_on_web_request' => env('SERVICE_IMAGES_AUTO_SYNC', true),

    /*
     * مجلدات صور إضافية لخدمات قائمة، وليست خدمات جديدة. تُستورد بعد صور
     * المجلد الأصلي للخدمة فيبقى ترتيب المعرض: القديم أولًا ثم الجديد.
     */
    'additional_folders' => [
        'hangers2' => 'الهناجر',
        'bargolat2' => 'البرجولات',
        'matalatalshdalnshai2' => 'مظلات الشد الإنشائي',
        'bytalshar2' => 'بيوت الشعر',
        'sandawitshpanel2' => 'الساندوتش بنل',
    ],
    'curated_folders' => [
        'pvc' => [
            'folder' => 'madtaltpfc', 'service' => 'مظلات PVC', 'expected' => 45,
            'stem' => 'pvc-shades-riyadh', 'context' => 'مظلة PVC بقماش عازل وهيكل معدني',
            'cover' => 'WhatsApp Image 2026-08-09 at 7.26.33 PM (4).webp',
            'include' => ['bytalshar/yrtfdrt.webp'],
        ],
        'tensile' => [
            'folder' => 'matalatalshdalnshai', 'service' => 'مظلات الشد الإنشائي', 'expected' => 12,
            'stem' => 'tensile-structure-shade-riyadh', 'context' => 'مظلة شد إنشائي بقماش مشدود وهيكل معدني',
            'files' => [
                'WhatsApp Image 2026-08-09 at 7.28.42 PM (1).webp', 'WhatsApp Image 2026-08-09 at 7.28.43 PM.webp',
                'WhatsApp Image 2026-08-09 at 7.28.44 PM (1).webp', 'WhatsApp Image 2026-08-09 at 7.28.44 PM (2).webp',
                'WhatsApp Image 2026-08-09 at 7.28.44 PM (3).webp', 'WhatsApp Image 2026-08-09 at 7.28.44 PM (4).webp',
                'WhatsApp Image 2026-08-09 at 7.28.44 PM (5).webp', 'WhatsApp Image 2026-08-09 at 7.28.44 PM.webp',
                'WhatsApp Image 2026-08-09 at 7.28.45 PM (1).webp', 'WhatsApp Image 2026-08-09 at 7.28.45 PM (2).webp',
                'WhatsApp Image 2026-08-09 at 7.28.45 PM (3).webp', 'WhatsApp Image 2026-08-09 at 7.28.45 PM.webp',
            ],
            'cover' => 'WhatsApp Image 2026-08-09 at 7.28.45 PM.webp',
        ],
        'tents' => [
            'folder' => 'bytalshar', 'service' => 'الخيام', 'expected' => 8, 'stem' => 'tents-riyadh',
            'context' => 'خيمة خارجية ثابتة بتغطية قماشية',
            'files' => ['erwfef.webp', 'kjhgtre.webp', 'poiuytr.webp', 'rtew.webp', 'sd.webp'],
            'include' => [
                'matalatalshdalnshai/WhatsApp Image 2026-08-09 at 7.31.53 PM (1).webp',
                'matalatalshdalnshai/WhatsApp Image 2026-08-09 at 7.31.54 PM.webp',
                'matalatalshdalnshai/WhatsApp Image 2026-08-09 at 7.31.57 PM (3).webp',
            ],
            'cover' => 'rtew.webp',
        ],
        'bayt_shaar' => [
            'folder' => 'bytalshar', 'service' => 'بيوت الشعر', 'expected' => 17, 'stem' => 'bayt-shaar-riyadh',
            'context' => 'بيت شعر خارجي بقماش عازل وواجهات مجهزة',
            'files' => ['dd.webp', 'erwfwergter.webp', 'iuytr.webp', 'iuytre.webp', 'oiuyutyhgrfed.webp', 'poiuytre.webp', 'rtewq.webp', 'rtt.webp', 'trrt.webp', 'trtr.webp', 'uiytre.webp', 'uiytrew.webp', 'uiytreww.webp', 'uytrew.webp', 'ythrefwq.webp'],
            'cover' => 'trtr.webp',
            'include' => [
                'matalatalshdalnshai/WhatsApp Image 2026-08-09 at 7.31.57 PM.webp',
                'matalatalshdalnshai/WhatsApp Image 2026-08-09 at 7.31.58 PM (2).webp',
            ],
        ],
        'hangars' => [
            'folder' => 'hangers', 'service' => 'الهناجر', 'expected' => 4, 'stem' => 'hangar-riyadh',
            'context' => 'تنفيذ هنجر وهيكل معدني في موقع العمل',
            'files' => ['hangers4.webp', 'hangers5webp.webp', 'hangers11.webp', 'hangers16.webp'],
            'cover' => 'hangers16.webp',
        ],
        'sports_fences' => [
            'folder' => 'hangers', 'service' => 'شبوك وأسوار', 'expected' => 4, 'stem' => 'sports-fence-riyadh',
            'context' => 'شبك معدني مرتفع لحماية ملعب رياضي',
            'files' => ['hangers1.webp', 'hangers2.webp', 'hangers3.webp', 'hangers14.webp'],
            'cover' => 'hangers14.webp',
        ],
        'pergolas' => [
            'folder' => 'bargolat', 'service' => 'البرجولات', 'expected' => 16, 'stem' => 'pergola-riyadh',
            'context' => 'برجولة خارجية بهيكل معدني في حديقة', 'cover' => 'ewerger.webp',
            'include' => ['matalatalshdalnshai/WhatsApp Image 2026-08-09 at 7.31.53 PM.webp'],
        ],
        'sandwich_panel' => [
            'folder' => 'matalatalshdalnshai', 'service' => 'الساندوتش بنل', 'expected' => 2,
            'stem' => 'sandwich-panel-riyadh', 'context' => 'مبنى خارجي جاهز بألواح معزولة',
            'files' => ['WhatsApp Image 2026-08-09 at 7.31.54 PM (1).webp', 'WhatsApp Image 2026-08-09 at 7.31.57 PM (2).webp'],
            'cover' => 'WhatsApp Image 2026-08-09 at 7.31.57 PM (2).webp',
        ],
        'glass_rooms' => [
            'folder' => 'sandawitshpanel', 'service' => 'جلسات زجاجية', 'expected' => 8, 'stem' => 'glass-room-riyadh',
            'context' => 'جلسة شتوية مغلقة بواجهات زجاجية', 'cover' => 'aaa.webp',
            'files' => ['aaa.webp', 'etgergrteg.webp', 'gertgre.webp', 'regfr.webp', 'rerre.webp', 'rrr.webp', 'ttrtg.webp'],
            'include' => ['matalatalshdalnshai/WhatsApp Image 2026-08-09 at 7.31.57 PM (1).webp'],
        ],
        'electric_doors' => [
            'folder' => 'ElectronicShuttersDoors', 'service' => 'الأبواب الكهربائية', 'expected' => 13,
            'stem' => 'electric-doors-riyadh', 'context' => 'باب رول كهربائي لمدخل مبنى', 'extensions' => ['png'],
            'files' => [
                'WhatsApp Image 2026-08-10 at 6.14.21 PM (1).png', 'WhatsApp Image 2026-08-10 at 6.14.21 PM.png',
                'WhatsApp Image 2026-08-10 at 6.14.22 PM (1).png', 'WhatsApp Image 2026-08-10 at 6.14.22 PM.png',
                'WhatsApp Image 2026-08-10 at 6.14.24 PM.png', 'WhatsApp Image 2026-08-10 at 6.14.25 PM (1).png',
                'WhatsApp Image 2026-08-10 at 6.14.25 PM.png', 'WhatsApp Image 2026-08-10 at 6.14.27 PM.png',
                'WhatsApp Image 2026-08-10 at 6.14.53 PM.png', 'WhatsApp Image 2026-08-10 at 6.14.54 PM.png',
                'WhatsApp Image 2026-08-10 at 6.14.55 PM.png', 'WhatsApp Image 2026-08-10 at 6.30.40 PM (1).png',
                'WhatsApp Image 2026-08-10 at 6.30.40 PM (2).png',
            ],
            'cover' => 'WhatsApp Image 2026-08-10 at 6.14.25 PM (1).png',
        ],
        'windows' => [
            'folder' => 'ElectronicShuttersDoors', 'service' => 'النوافذ', 'expected' => 7,
            'stem' => 'window-shutters-riyadh', 'context' => 'نافذة خارجية مزودة بشتر رول', 'extensions' => ['png'],
            'files' => [
                'WhatsApp Image 2026-08-10 at 6.14.28 PM.png', 'WhatsApp Image 2026-08-10 at 6.14.29 PM (1).png',
                'WhatsApp Image 2026-08-10 at 6.14.29 PM.png', 'WhatsApp Image 2026-08-10 at 6.14.31 PM (1).png',
                'WhatsApp Image 2026-08-10 at 6.14.31 PM.png', 'WhatsApp Image 2026-08-10 at 6.14.32 PM.png',
                'WhatsApp Image 2026-08-10 at 6.26.56 PM.png',
            ],
            'cover' => 'WhatsApp Image 2026-08-10 at 6.26.56 PM.png',
        ],
        'shutters' => [
            'folder' => 'ElectronicShuttersDoors', 'service' => 'الشترات', 'expected' => 1,
            'stem' => 'shutters-riyadh', 'context' => 'شتر شبكي معدني لحماية واجهة خارجية', 'extensions' => ['png'],
            'files' => ['WhatsApp Image 2026-08-10 at 6.30.40 PM.png'], 'cover' => 'WhatsApp Image 2026-08-10 at 6.30.40 PM.png',
        ],
    ],

    'visual_contexts' => [
        'ElectronicShuttersDoors' => [
            'WhatsApp Image 2026-08-10 at 6.14.21 PM (1).png' => 'أبواب رول كهربائية لمداخل مبنى قيد الإنشاء',
            'WhatsApp Image 2026-08-10 at 6.14.21 PM.png' => 'صف أبواب رول كهربائية لمداخل مبنى',
            'WhatsApp Image 2026-08-10 at 6.14.22 PM (1).png' => 'أبواب رول كهربائية متجاورة لمداخل خارجية',
            'WhatsApp Image 2026-08-10 at 6.14.22 PM.png' => 'باب كراج كهربائي مقطعي بجوار باب رول',
            'WhatsApp Image 2026-08-10 at 6.14.24 PM.png' => 'آلية باب رول مفتوح مع صندوق التشغيل الجانبي',
            'WhatsApp Image 2026-08-10 at 6.14.25 PM (1).png' => 'باب رول كهربائي عريض لمدخل مبنى',
            'WhatsApp Image 2026-08-10 at 6.14.25 PM.png' => 'أبواب رول داخلية متجاورة في ممر خدمات',
            'WhatsApp Image 2026-08-10 at 6.14.27 PM.png' => 'أبواب رول رمادية لمداخل تجارية',
            'WhatsApp Image 2026-08-10 at 6.14.28 PM.png' => 'شتر نافذة رول بلون فاتح أثناء الفتح',
            'WhatsApp Image 2026-08-10 at 6.14.29 PM (1).png' => 'شتر رول أبيض يغطي نافذة خارجية',
            'WhatsApp Image 2026-08-10 at 6.14.29 PM.png' => 'شتر نافذة أبيض مرفوع جزئيًا',
            'WhatsApp Image 2026-08-10 at 6.14.31 PM (1).png' => 'شتر حماية خارجي لنافذة صغيرة',
            'WhatsApp Image 2026-08-10 at 6.14.31 PM.png' => 'شتر رول أبيض لنافذة عريضة',
            'WhatsApp Image 2026-08-10 at 6.14.32 PM.png' => 'شتر نافذة داخلي مع صندوق علوي ومسارات جانبية',
            'WhatsApp Image 2026-08-10 at 6.14.53 PM.png' => 'باب رول كهربائي بلون فاتح لمدخل كراج',
            'WhatsApp Image 2026-08-10 at 6.14.54 PM.png' => 'باب رول كهربائي أسود لمدخل خارجي',
            'WhatsApp Image 2026-08-10 at 6.14.55 PM.png' => 'باب رول كهربائي أبيض لمدخل سيارة',
            'WhatsApp Image 2026-08-10 at 6.26.56 PM.png' => 'نافذتان بواجهتهما شترات رول بيضاء',
            'WhatsApp Image 2026-08-10 at 6.30.40 PM (1).png' => 'مجموعة أبواب رول لمداخل مستودع',
            'WhatsApp Image 2026-08-10 at 6.30.40 PM (2).png' => 'تركيب باب رول طويل على واجهة مبنى',
            'WhatsApp Image 2026-08-10 at 6.30.40 PM.png' => 'شتر شبكي معدني لحماية واجهة خارجية',
        ],
    ],

    /*
     * المطابقة تبدأ باسم المجلد، ثم يمكن للاستثناء البصري الموثق أدناه تغيير
     * الخدمة بحسب بصمة الملف. بهذه الطريقة لا يعتمد القرار على اسم واتساب.
     */
    'folders' => [
        ['contains' => ['البي في سي', 'pvc'], 'service' => 'مظلات PVC', 'stem' => 'pvc-shades-riyadh', 'context' => 'مظلة PVC بغطاء قماشي وهيكل معدني'],
        ['contains' => ['الشد'], 'service' => 'مظلات الشد الإنشائي', 'stem' => 'tensile-structure-shade-riyadh', 'context' => 'مظلة شد إنشائي بقماش مشدود'],
        ['contains' => ['هناجر'], 'service' => 'الهناجر', 'stem' => 'hangar-riyadh', 'context' => 'هيكل هنجر حديد أثناء التنفيذ'],
        ['contains' => ['سندويش', 'ساندوتش'], 'service' => 'الساندوتش بنل', 'stem' => 'sandwich-panel-riyadh', 'context' => 'مبنى خارجي بألواح معزولة'],
        ['contains' => ['برجولات'], 'service' => 'البرجولات', 'stem' => 'pergola-riyadh', 'context' => 'برجولة خارجية بهيكل معدني'],
    ],

    'service_stems' => [
        'مظلات PVC' => 'pvc-shades-riyadh',
        'مظلات الشد الإنشائي' => 'tensile-structure-shade-riyadh',
        'الهناجر' => 'hangar-riyadh',
        'شبوك وأسوار' => 'fence-riyadh',
        'الساندوتش بنل' => 'sandwich-panel-riyadh',
        'جلسات زجاجية' => 'glass-room-riyadh',
        'الجلسات الشتوية' => 'winter-glass-room-riyadh',
        'البرجولات' => 'pergola-riyadh',
        'بيوت الشعر' => 'bayt-shaar-riyadh',
        'الخيام' => 'tents-riyadh',
        'الشترات' => 'shutters-riyadh',
        'النوافذ' => 'window-shutters-riyadh',
        'الأبواب الكهربائية' => 'electric-doors-riyadh',
        'الشترات والأبواب الإلكترونية' => 'electronic-shutters-doors-riyadh',
    ],

    'retired_services' => [],

    'visual_overrides' => [
        // صور شد إنشائي مكررة داخل مجلد PVC؛ الدليل المرئي واضح وتبقى خانة source_folder كما هي.
        '4e4c797e2b1971f3c5c609fd0bf4b25c5d80deb2ef9f01733fde2696c09430ae' => ['service' => 'مظلات الشد الإنشائي', 'stem' => 'tensile-structure-shade-riyadh', 'context' => 'مظلة شد إنشائي متعددة النقاط بقماش مشدود'],
        'ff14c4ff17d8596a430bc12d0a6724e43a8516a3fb1d06b49b3ff146e58c576f' => ['service' => 'مظلات الشد الإنشائي', 'stem' => 'tensile-structure-shade-riyadh', 'context' => 'مظلة قماشية مشدودة على أعمدة معدنية'],
        'fd05f4630e445f5787818cfd2443097e0a7d934d03b16e0ad2d278f80c181307' => ['service' => 'مظلات الشد الإنشائي', 'stem' => 'tensile-structure-shade-riyadh', 'context' => 'مظلة شد إنشائي بشكل قماشي متموج'],
        '85aca593336fcc469215b1750993f703edb7abd3d764ffdd79a7440ef33a931f' => ['service' => 'مظلات الشد الإنشائي', 'stem' => 'tensile-structure-shade-riyadh', 'context' => 'مظلات شد إنشائي متتابعة فوق مساحة خارجية'],
        '8a67385f042258b415dfdd78b5054b1121ed7bde8b384b959f2b09421565de70' => ['service' => 'مظلات الشد الإنشائي', 'stem' => 'tensile-structure-shade-riyadh', 'context' => 'مظلة شد إنشائي بتكوين قماشي هندسي'],
        '8d16ca9047ac95fda06720d50829547430e96d38bb0e0a6f0e9371fc1b5cd708' => ['service' => 'مظلات الشد الإنشائي', 'stem' => 'tensile-structure-shade-riyadh', 'context' => 'مظلة شد إنشائي كبيرة لمساحة عامة'],

        // هياكل برجولات وغرفة زجاجية ظهرت بوضوح داخل مجلد الشد الإنشائي.
        '215cad2e4d4eff8b33cd66b211135469afbccc67bccb17203ba3c919e4606bd1' => ['service' => 'البرجولات', 'stem' => 'pergola-riyadh', 'context' => 'هيكل برجولة حديد أثناء التجهيز'],
        '20ca5385dc0b88512965763b97bfb6311efb430865a6844cb959fdca7d90af82' => ['service' => 'البرجولات', 'stem' => 'pergola-riyadh', 'context' => 'تفاصيل سقف برجولة بهيكل معدني'],
        'aee41db23183e4e59ac9c8b3c0a86c787bac6c372f37d8e63cac8f3ae191474a' => ['service' => 'الساندوتش بنل', 'stem' => 'sandwich-panel-riyadh', 'context' => 'غرفة خارجية جاهزة بألواح وجدران معزولة'],
        'd47c30d1373c7cb2db56493a4cd8bca1fd5545e01ec799938c20b99c3bacbec3' => ['service' => 'البرجولات', 'stem' => 'pergola-riyadh', 'context' => 'هيكل برجولة حديد قائم في مساحة خارجية'],
        '0b48b240ee719e7fc5a1d27b9853cffbc676c5b0744b460bae34a9ccc4449cf6' => ['service' => 'البرجولات', 'stem' => 'pergola-riyadh', 'context' => 'برجولة حديد بسقف شرائح في فناء خارجي'],
        '1e1111f38d6f4bf4d5f48f8bee1f6df41fc09d88f884bf5e0eff2ef177410332' => ['service' => 'جلسات زجاجية', 'stem' => 'glass-room-riyadh', 'context' => 'جلسة شتوية مغلقة بواجهات زجاجية'],
        '57932c98f7665909bd038c1934e2c723ee50bfa90d8328ef0bd94838bf8fe74c' => ['service' => 'البرجولات', 'stem' => 'pergola-riyadh', 'context' => 'برجولة حديد مستقلة في حديقة'],

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

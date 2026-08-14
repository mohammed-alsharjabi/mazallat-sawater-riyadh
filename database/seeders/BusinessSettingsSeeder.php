<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class BusinessSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['site_name', 'مظلات وسواتر الرياض', 'اسم الموقع'],
            ['phone_display', '+966 56 206 6426', 'رقم التواصل الظاهر'],
            ['phone_e164', '+966562066426', 'رقم التواصل الدولي'],
            ['phone_tel', 'tel:+966562066426', 'رابط الاتصال'],
            ['whatsapp_url', 'https://wa.me/966562066426', 'رابط واتساب'],
            ['address', 'الرياض، وسط الرياض، المملكة العربية السعودية', 'العنوان'],
            ['city', 'الرياض', 'المدينة'],
            ['country', 'المملكة العربية السعودية', 'الدولة'],
            ['locale', 'ar_SA', 'اللغة والمنطقة'],
            ['primary_service_area', 'وسط الرياض', 'موقع النشاط الأساسي'],
            ['hero_eyebrow', 'هندسة المساحات الخارجية', 'عبارة Hero العلوية'],
            ['hero_title', 'نصنع الظل الذي يغيّر المكان', 'عنوان Hero'],
            ['hero_description', 'مظلات وسواتر وبرجولات بتصميم هندسي وتنفيذ يليق بمشروعك.', 'وصف Hero'],
            ['about_image', config('site.about_image'), 'صورة من نحن'],
            ['inspection_cta_label', 'اطلب معاينة', 'نص زر المعاينة'],
        ];

        foreach ($settings as [$key, $value, $label]) {
            Setting::query()->firstOrCreate(['key' => $key], [
                'value' => $value,
                'label' => $label,
                'group' => 'business',
                'type' => 'string',
                'is_public' => true,
            ]);
        }

        foreach ([
            ['search_console_verification', '', 'رمز إثبات ملكية Google Search Console'],
            ['ga_measurement_id', '', 'معرّف Google Analytics 4'],
            ['logo_url', '', 'رابط شعار النشاط للبيانات المنظمة'],
        ] as [$key, $value, $label]) {
            Setting::query()->firstOrCreate(['key' => $key], [
                'value' => $value,
                'label' => $label,
                'group' => 'seo',
                'type' => 'string',
                'is_public' => true,
            ]);
        }

        foreach (config('theme.colors', []) as $key => $definition) {
            Setting::query()->firstOrCreate(['key' => $key], [
                'value' => $definition['default'],
                'label' => $definition['label'],
                'group' => 'appearance',
                'type' => 'string',
                'is_public' => true,
            ]);
        }
    }
}

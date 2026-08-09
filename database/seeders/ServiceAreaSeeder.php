<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class ServiceAreaSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->areas() as $data) {
            $area = Area::query()->firstOrCreate(['name' => $data['name']], [
                'excerpt' => $data['excerpt'],
                'content' => $data['content'],
                'is_active' => true,
                'is_primary' => $data['is_primary'],
                'status' => $data['status'],
                'published_at' => $data['status'] === 'published' ? now() : null,
            ]);

            // Bootstrap the primary location on databases created before these fields existed,
            // then preserve later editorial changes made through the dashboard.
            if ($data['is_primary'] && ! Area::query()->where('is_primary', true)->exists()) {
                $area->update(['is_primary' => true, 'status' => 'published', 'published_at' => $area->published_at ?: now()]);
            }

            $area->seo()->firstOrCreate([], [
                'meta_title' => $data['meta_title'],
                'meta_description' => $data['excerpt'],
                'robots' => $data['status'] === 'published' ? 'index,follow,max-image-preview:large' : 'noindex,follow',
                'schema_type' => 'WebPage',
            ]);
        }
    }

    private function areas(): array
    {
        return [
            [
                'name' => 'وسط الرياض',
                'excerpt' => 'الموقع الأساسي للنشاط ونقطة الانطلاق لخدمات المعاينة داخل وسط مدينة الرياض.',
                'content' => "وسط الرياض هو الموقع الأساسي للنشاط. عند طلب المعاينة نحتاج إلى نوع الخدمة، موقع العقار، صور واضحة للمساحة، والمقاسات التقريبية إن توفرت.\n\nتتطلب المواقع ذات المداخل الضيقة أو الحركة الكثيفة تنسيق وقت الوصول ومكان تحميل المواد قبل التنفيذ. تُراجع الأبواب والجدران والخدمات ونقاط التثبيت ومسار المياه في الموقع، ثم يجهز عرض يصف الخامة والمقاسات ونطاق العمل.\n\nلا تظهر مشاريع في هذه الصفحة إلا بعد توثيق مشروع حقيقي وربطه بوسط الرياض من لوحة التحكم.",
                'is_primary' => true,
                'status' => 'published',
                'meta_title' => 'مظلات وسواتر في وسط الرياض | الموقع الأساسي',
            ],
            [
                'name' => 'شمال الرياض',
                'excerpt' => 'نطاق خدمة أساسي للطلبات الواقعة في شمال الرياض، وتبقى صفحته مسودة حتى يضاف محتوى محلي أو مشروع موثق.',
                'content' => "تُسجل طلبات شمال الرياض ضمن نطاق الخدمة الأساسي، مع جمع الحي وصور الموقع ونوع المساحة قبل تحديد موعد المعاينة.\n\nتظل الصفحة مسودة ولا تدخل نتائج البحث أو خريطة الموقع إلى أن يتوفر مشروع حقيقي موثق أو محتوى محلي أصلي يبرر نشرها.",
                'is_primary' => false,
                'status' => 'draft',
                'meta_title' => 'خدمات المظلات والسواتر في شمال الرياض',
            ],
            [
                'name' => 'شرق الرياض',
                'excerpt' => 'نطاق استقبال طلبات شرق الرياض؛ لا تُنشر صفحته قبل اكتمال بيانات محلية موثوقة.',
                'content' => "تساعد معرفة الحي ونوع العقار ومسار الوصول في تجهيز معاينات شرق الرياض. تُطلب صور الجوانب ونقاط التثبيت والمقاسات الأولية لتحديد البيانات الناقصة.\n\nلا تتحول هذه المسودة إلى صفحة عامة إلا عند وجود مشروع منفذ أو شرح محلي مفيد وغير منسوخ يمكن مراجعته واعتماده.",
                'is_primary' => false,
                'status' => 'draft',
                'meta_title' => 'خدمات المظلات والسواتر في شرق الرياض',
            ],
            [
                'name' => 'غرب الرياض',
                'excerpt' => 'نطاق خدمة غرب الرياض محفوظ في قاعدة البيانات كمسودة إلى حين توثيق أعمال أو معلومات محلية أصلية.',
                'content' => "يُسجل في طلب غرب الرياض الاستخدام المطلوب، أبعاد المساحة، العوائق، وأوقات الدخول المناسبة للمعاينة. بعدها تُراجع الخامة وطريقة التثبيت وفق الموقع الفعلي.\n\nتبقى هذه الصفحة غير مفهرسة وغير منشورة ما دام المحتوى المحلي أو سجل المشاريع غير مكتمل.",
                'is_primary' => false,
                'status' => 'draft',
                'meta_title' => 'خدمات المظلات والسواتر في غرب الرياض',
            ],
            [
                'name' => 'جنوب الرياض',
                'excerpt' => 'نطاق أساسي لاستقبال طلبات جنوب الرياض مع إبقاء الصفحة مسودة حتى يكتمل دليل محلي حقيقي.',
                'content' => "تبدأ الطلبات في جنوب الرياض بتحديد ما إذا كانت الخدمة لموقف أو حوش أو سطح أو منشأة، ثم مشاركة الموقع والصور والمقاسات التقريبية.\n\nلا تُنشأ صفحات أحياء آلية من هذا السجل، وتظل الصفحة نفسها مسودة وNoindex إلى أن يرتبط بها مشروع حقيقي أو محتوى محلي تمت مراجعته.",
                'is_primary' => false,
                'status' => 'draft',
                'meta_title' => 'خدمات المظلات والسواتر في جنوب الرياض',
            ],
        ];
    }
}

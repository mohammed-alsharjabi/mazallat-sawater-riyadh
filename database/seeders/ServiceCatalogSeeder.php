<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Material;
use App\Models\Redirect;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Support\SeoSuggestionService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class ServiceCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $this->renameCatalogRecords();
        $materialIds = Material::query()->pluck('id', 'name');
        $taxonomy = config('site.service_taxonomy', []);
        $publishedNames = collect($taxonomy)->flatten()->values();

        Service::query()->whereNotIn('name', $publishedNames)->update([
            'status' => 'draft', 'is_featured' => false, 'published_at' => null,
        ]);
        ServiceCategory::query()->update(['is_active' => false]);

        foreach ($this->catalog() as $categoryIndex => $categoryData) {
            $category = ServiceCategory::query()->firstOrCreate(
                ['name' => $categoryData['name']],
                [
                    'excerpt' => $categoryData['excerpt'],
                    'description' => $categoryData['description'],
                    'sort_order' => $categoryIndex + 1,
                    'is_active' => true,
                ],
            );
            $category->update([
                'excerpt' => $categoryData['excerpt'],
                'description' => $categoryData['description'],
                'sort_order' => $categoryIndex + 1,
                'is_active' => true,
            ]);

            foreach ($categoryData['services'] as $serviceIndex => $data) {
                $isConfirmedForLaunch = in_array($data['name'], config('site.launch_services', []), true);
                $featuredImage = config('site.service_featured_images.'.$data['name']);
                $service = Service::query()->firstOrCreate(
                    ['name' => $data['name']],
                    [
                        'service_category_id' => $category->id,
                        'excerpt' => $data['definition'],
                        'content' => $data['definition'],
                        'types' => $this->lines($data['types']),
                        'use_cases' => $this->lines($data['cases']),
                        'materials_details' => $this->lines($data['material_notes']),
                        'advantages' => $this->lines($data['pros']),
                        'disadvantages' => $this->lines($data['cons']),
                        'installation_steps' => $this->lines($data['steps']),
                        'price_factors' => $this->lines($data['factors']),
                        'selection_tips' => $this->lines($data['tips']),
                        'cta' => $data['cta'],
                        'featured_image' => $featuredImage,
                        'featured_image_alt' => $featuredImage ? $data['name'].' في الرياض' : null,
                        'featured_image_caption' => $featuredImage ? 'خدمة '.$data['name'].' في الرياض' : null,
                        'sort_order' => $serviceIndex + 1,
                        'is_active' => true,
                        'status' => $isConfirmedForLaunch ? 'published' : 'draft',
                        'published_at' => $isConfirmedForLaunch ? now() : null,
                        'is_price_published' => false,
                        'price_from' => null,
                        'price_to' => null,
                    ],
                );
                if ($isConfirmedForLaunch) {
                    $service->update([
                        'service_category_id' => $category->id,
                        'sort_order' => $serviceIndex + 1,
                        'status' => 'published',
                        'is_active' => true,
                        'published_at' => $service->published_at ?: now(),
                        'featured_image' => $featuredImage,
                        'featured_image_alt' => $data['name'].' في الرياض',
                        'featured_image_caption' => 'خدمة '.$data['name'].' في الرياض',
                    ]);
                }

                $ids = collect($data['materials'])
                    ->map(fn (string $name) => $materialIds->get($name))
                    ->filter()
                    ->values();
                $service->materials()->syncWithoutDetaching($ids);

                $faqs = [
                    $data['faq'],
                    [
                        'question' => 'كيف يُحدد سعر '.$data['name'].'؟',
                        'answer' => 'لا يعتمد السعر على الاسم وحده؛ بل يتأثر بـ'.implode('، ', $data['factors']).'. بعد المعاينة وتثبيت المواصفات يمكن إعداد عرض واضح من دون أرقام افتراضية.',
                    ],
                ];

                foreach ($faqs as $faqIndex => $faqData) {
                    $faq = Faq::query()->firstOrCreate(
                        ['question' => $faqData['question']],
                        ['answer' => $faqData['answer'], 'sort_order' => $faqIndex + 1, 'is_active' => true],
                    );
                    $service->faqs()->syncWithoutDetaching([$faq->id]);
                }

                if (in_array($service->name, ['الشترات', 'النوافذ', 'الأبواب الكهربائية'], true)) {
                    $suggestions = app(SeoSuggestionService::class)->suggest('services', $service->toArray(), $service);
                    $service->seo()->firstOrCreate([], [
                        'meta_title' => $service->name.' في الرياض',
                        'meta_description' => $data['definition'],
                        'focus_keyword' => $service->name.' في الرياض',
                        'related_terms' => $this->lines($data['types']),
                        'internal_links' => $suggestions['internal_links'] ?? null,
                        'canonical_url' => route('services.show', $service->slug),
                        'robots' => 'index,follow,max-image-preview:large',
                        'og_title' => $service->name.' في الرياض',
                        'og_description' => 'شاهد أعمال '.$service->name.' وتعرّف على الأنواع والخامات وخطوات المعاينة والتركيب في الرياض.',
                        'schema_type' => 'Service',
                    ]);
                }
            }
        }

        $this->ensureWinterService();

        foreach ($taxonomy as $categoryName => $serviceNames) {
            $category = ServiceCategory::query()->where('name', $categoryName)->first();
            if (! $category) {
                continue;
            }
            $category->update(['sort_order' => array_search($categoryName, array_keys($taxonomy), true) + 1, 'is_active' => true]);
            foreach ($serviceNames as $serviceIndex => $serviceName) {
                Service::query()->where('name', $serviceName)->update([
                    'service_category_id' => $category->id,
                    'sort_order' => $serviceIndex + 1,
                ]);
            }
        }

        $legacyElectronicService = Service::query()->where('name', 'الشترات والأبواب الإلكترونية')->first();
        if ($legacyElectronicService) {
            Redirect::query()->where('old_path', '/الخدمات/'.$legacyElectronicService->slug)->update(['is_active' => false]);
        }

        $this->linkHierarchyAndSources();

        Cache::forget('navigation.service-categories');
    }

    private function catalog(): array
    {
        return [
            require __DIR__.'/data/services/shades.php',
            require __DIR__.'/data/services/fences.php',
            require __DIR__.'/data/services/heritage-roofs.php',
            require __DIR__.'/data/services/metal.php',
            require __DIR__.'/data/services/pergolas.php',
            require __DIR__.'/data/services/shutters-windows-doors.php',
        ];
    }

    private function renameCatalogRecords(): void
    {
        $categoryRenames = [
            'الخدمات التراثية والأسقف' => 'الخيام وبيوت الشعر',
            'الإنشاءات المعدنية' => 'الهناجر والإنشاءات',
        ];
        foreach ($categoryRenames as $old => $new) {
            $category = ServiceCategory::query()->where('name', $old)->first();
            if ($category && ! ServiceCategory::query()->where('name', $new)->exists()) {
                $category->update(['name' => $new, 'slug' => $new]);
            }
        }

        $serviceRenames = [
            'مظلات سيارات PVC' => 'مظلات PVC',
            'مظلات شد إنشائي' => 'مظلات الشد الإنشائي',
            'بيوت شعر' => 'بيوت الشعر',
            'خيام ملكية' => 'الخيام',
            'هناجر حديد' => 'الهناجر',
            'غرف ساندوتش بانل' => 'الساندوتش بنل',
            'برجولات حديد' => 'البرجولات',
            'جلسات شتوية زجاجية' => 'جلسات زجاجية',
        ];
        foreach ($serviceRenames as $old => $new) {
            $service = Service::query()->where('name', $old)->first();
            if ($service && ! Service::query()->where('name', $new)->exists()) {
                $service->update(['name' => $new, 'slug' => $new]);
            }
        }
    }

    private function ensureWinterService(): void
    {
        $glass = Service::query()->where('name', 'جلسات زجاجية')->first();
        if (! $glass) {
            return;
        }

        $winter = Service::query()->firstOrCreate(['name' => 'الجلسات الشتوية'], [
            ...$glass->only([
                'service_category_id', 'content', 'types', 'use_cases', 'materials_details', 'advantages',
                'disadvantages', 'price_factors', 'installation_steps', 'selection_tips', 'cta',
            ]),
            'excerpt' => 'جلسات شتوية زجاجية مريحة تحافظ على وضوح الإطلالة وتقلل أثر الرياح والغبار، مع دراسة التهوية والتظليل والتصريف.',
            'featured_image' => config('site.service_featured_images.الجلسات الشتوية'),
            'featured_image_alt' => 'جلسة شتوية زجاجية في الرياض',
            'featured_image_caption' => 'تصميم جلسة شتوية زجاجية للاستخدام المريح في الأجواء الباردة.',
            'is_featured' => true,
            'is_popular' => true,
            'is_active' => true,
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 2,
        ]);
        $winter->update([
            'status' => 'published', 'is_active' => true, 'published_at' => $winter->published_at ?: now(),
            'featured_image' => config('site.service_featured_images.الجلسات الشتوية'),
        ]);
        $winter->materials()->syncWithoutDetaching($glass->materials()->pluck('materials.id'));
        $winter->faqs()->syncWithoutDetaching($glass->faqs()->pluck('faqs.id'));
    }

    private function linkHierarchyAndSources(): void
    {
        Service::query()->update(['parent_service_id' => null, 'image_source_folder' => null]);
        foreach ([
            'الخيام' => ['بيوت الشعر'],
            'الجلسات الشتوية' => ['جلسات زجاجية'],
            'الشترات والأبواب الإلكترونية' => ['الشترات', 'النوافذ', 'الأبواب الكهربائية'],
        ] as $parentName => $children) {
            $parent = Service::query()->where('name', $parentName)->first();
            if ($parent) {
                Service::query()->whereIn('name', $children)->update(['parent_service_id' => $parent->id]);
            }
        }

        foreach ([
            'مظلات PVC' => 'madtaltpfc',
            'مظلات الشد الإنشائي' => 'matalatalshdalnshai',
            'الجلسات الشتوية' => 'sandawitshpanel',
            'الشترات والأبواب الإلكترونية' => 'ElectronicShuttersDoors',
            'البرجولات' => 'bargolat',
            'بيوت الشعر' => 'bytalshar',
            'الهناجر' => 'hangers',
        ] as $serviceName => $folder) {
            Service::query()->where('name', $serviceName)->update(['image_source_folder' => $folder]);
        }

        foreach (Service::published()->whereNull('featured_image')->get() as $service) {
            $cover = $service->processedImages()->reorder()->orderByDesc('is_cover')->orderBy('sort_order')->first();
            $fallback = $cover?->optimized_path ?: $service->parent?->featured_image;
            if ($fallback) {
                $service->update(['featured_image' => $fallback, 'featured_image_alt' => $service->name.' في الرياض']);
            }
        }
    }

    private function lines(array $items): string
    {
        return implode("\n", $items);
    }
}

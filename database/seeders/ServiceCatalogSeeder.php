<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Material;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $materialIds = Material::query()->pluck('id', 'name');

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

            foreach ($categoryData['services'] as $serviceIndex => $data) {
                $isConfirmedForLaunch = in_array($data['name'], config('site.launch_services', []), true);
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
                        'sort_order' => $serviceIndex + 1,
                        'is_active' => true,
                        'status' => $isConfirmedForLaunch ? 'published' : 'draft',
                        'published_at' => $isConfirmedForLaunch ? now() : null,
                        'is_price_published' => false,
                        'price_from' => null,
                        'price_to' => null,
                    ],
                );

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
            }
        }
    }

    private function catalog(): array
    {
        return [
            require __DIR__.'/data/services/shades.php',
            require __DIR__.'/data/services/fences.php',
            require __DIR__.'/data/services/pergolas.php',
            require __DIR__.'/data/services/metal.php',
            require __DIR__.'/data/services/heritage-roofs.php',
        ];
    }

    private function lines(array $items): string
    {
        return implode("\n", $items);
    }
}

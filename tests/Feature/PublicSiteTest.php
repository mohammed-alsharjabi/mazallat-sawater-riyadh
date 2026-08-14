<?php

namespace Tests\Feature;

use App\Jobs\ProcessNewLead;
use App\Models\Area;
use App\Models\Article;
use App\Models\Lead;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        Service::query()->where('name', 'مظلات PVC')->firstOrFail()->update([
            'status' => 'published',
            'published_at' => now(),
            'is_popular' => true,
        ]);
        Article::query()->firstOrFail()->update(['status' => 'published', 'published_at' => now()]);
    }

    public function test_public_pages_are_server_rendered_with_seo_and_official_contact_data(): void
    {
        $response = $this->get(route('home'));
        $response->assertOk()
            ->assertSee('<html lang="ar-SA" dir="rtl">', false)
            ->assertSee('مظلات PVC')
            ->assertSee('tel:+966562066426', false)
            ->assertSee('https://wa.me/966562066426', false)
            ->assertSee('application/ld+json', false)
            ->assertSee('brand/mazallat-sawater-riyadh-logo.svg', false)
            ->assertSee('brand/mazallat-sawater-riyadh-icon.svg', false)
            ->assertSee('<link rel="canonical"', false)
            ->assertSee('data-mobile-drawer', false)
            ->assertSee('aura-services-grid', false)
            ->assertSee('حلول متكاملة للمنازل والمشاريع');

        foreach (['about', 'services.index', 'projects.index', 'areas.index', 'guide.index', 'prices', 'quote', 'contact', 'privacy', 'terms', 'sitemap', 'robots'] as $route) {
            $this->get(route($route))->assertOk();
        }
    }

    public function test_homepage_navigation_can_be_rendered_repeatedly_from_cache(): void
    {
        $this->seed();

        $this->get(route('home'))->assertOk()->assertSee('مظلات PVC');
        $this->get(route('home'))->assertOk()->assertSee('مظلات PVC');
    }

    public function test_published_detail_pages_are_accessible_by_arabic_slug(): void
    {
        $service = Service::published()->firstOrFail();
        $area = Area::published()->firstOrFail();
        $article = Article::published()->firstOrFail();
        $this->get(route('services.show', $service->slug))->assertOk()
            ->assertSee($service->name)
            ->assertSee('عن الخدمة')
            ->assertSee('اطلب معاينة');
        $this->get(route('areas.show', $area->slug))->assertOk()->assertSee($area->name);
        $this->get(route('guide.show', $article->slug))->assertOk()->assertSee($article->title);
    }

    public function test_curated_service_cover_fills_services_without_gallery_images(): void
    {
        $service = Service::published()->where('name', 'سواتر حديد')->firstOrFail();

        $this->assertSame(0, $service->images()->count());
        $this->assertSame('services/sawater-riyadh.webp', $service->featured_image);

        $this->get(route('services.show', $service->slug))->assertOk()
            ->assertSee('storage/services/sawater-riyadh.webp', false)
            ->assertSee('class="site-header"', false)
            ->assertDontSee('service-design-header', false);
    }

    public function test_about_page_presents_verified_experience_image_and_phone(): void
    {
        $this->get(route('about'))->assertOk()
            ->assertSee('بدأ عملنا في السواتر عام 1999')
            ->assertSee('مشاريع حكومية ولشركات كبيرة')
            ->assertSee('+966 56 206 6426')
            ->assertSee('storage/about/aboutus.webp', false);
    }

    public function test_building_guide_has_ten_published_articles_with_visible_images(): void
    {
        $articles = Article::published()->get();
        $response = $this->get(route('guide.index'))->assertOk();

        $this->assertCount(10, $articles);
        $this->assertSame(10, substr_count($response->getContent(), 'class="article-card"'));
        $this->assertSame(10, substr_count($response->getContent(), 'class="image-shell article-curated-image'));

        foreach ($articles as $article) {
            $this->assertNotEmpty($article->featured_image, $article->title.' has no featured image');
            $response->assertSee('storage/'.$article->featured_image, false);
        }

        $article = $articles->firstOrFail();
        $this->get(route('guide.show', $article->slug))->assertOk()
            ->assertSee('storage/'.$article->featured_image, false);
    }

    public function test_lead_form_validates_and_queues_follow_up(): void
    {
        Queue::fake();
        $service = Service::published()->firstOrFail();
        $response = $this->post(route('leads.store'), [
            'type' => 'quote', 'name' => 'محمد أحمد', 'phone' => '0562066426', 'area' => 'وسط الرياض',
            'service_id' => $service->id, 'preferred_contact' => 'whatsapp', 'message' => 'أحتاج معاينة للموقع',
        ]);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('leads', ['name' => 'محمد أحمد', 'phone' => '0562066426', 'status' => 'new']);
        Queue::assertPushed(ProcessNewLead::class, fn ($job) => $job->leadId === Lead::first()->id);
    }

    public function test_invalid_phone_and_honeypot_are_rejected(): void
    {
        $this->post(route('leads.store'), ['type' => 'quote', 'name' => 'محمد', 'phone' => '123', 'preferred_contact' => 'phone', 'website' => 'spam.example'])
            ->assertSessionHasErrors(['phone', 'website']);
        $this->assertDatabaseCount('leads', 0);
    }

    public function test_missing_page_uses_custom_noindex_404(): void
    {
        $this->get('/'.rawurlencode('صفحة-غير-موجودة'))->assertNotFound()->assertSee('هذه الصفحة ليست هنا')->assertSee('noindex,follow', false);
    }
}

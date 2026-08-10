<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Faq;
use App\Models\Material;
use App\Models\Project;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Testimonial;
use App\Models\TrustItem;
use App\Support\ArticleContent;
use App\Support\Seo;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        $launchOrder = array_flip(config('site.launch_services', []));
        $featuredServices = Service::published()
            ->whereIn('name', config('site.launch_services', []))
            ->with([
                'category',
                'images' => fn ($query) => $query->where('processing_status', 'processed')->orderByDesc('is_cover')->orderBy('sort_order')->limit(8),
            ])
            ->withCount(['images' => fn ($query) => $query->where('processing_status', 'processed'), 'projects' => fn ($query) => $query->published()])
            ->get()
            ->sortBy(fn (Service $service): int => $launchOrder[$service->name] ?? 999)
            ->values();
        $heroService = $featuredServices->firstWhere('name', 'مظلات شد إنشائي') ?: $featuredServices->first();
        $heroImage = $heroService?->images->firstWhere('is_cover', true) ?: $heroService?->images->first();
        $heroSlides = collect([$heroService])
            ->filter()
            ->concat($featuredServices->where('id', '!=', $heroService?->id))
            ->map(function (Service $service): ?array {
                $image = $service->images->firstWhere('is_cover', true) ?: $service->images->first();

                return $image ? ['service' => $service, 'image' => $image] : null;
            })
            ->filter()
            ->take(6)
            ->values();
        $areas = Area::published()->withCount(['projects' => fn ($q) => $q->published()])->orderByDesc('is_primary')->orderBy('name')->limit(8)->get();
        $projects = Project::published()->with(['service', 'area', 'images'])->orderByDesc('is_featured')->latest('published_at')->limit(6)->get();
        $beforeAfterProject = Project::published()->whereHas('images', fn ($query) => $query->where('stage', 'before'))->whereHas('images', fn ($query) => $query->where('stage', 'after'))->with(['service', 'area', 'images'])->latest('published_at')->first();
        $trustItems = TrustItem::query()->where('is_active', true)->orderBy('sort_order')->get();
        $faqs = Faq::query()->where('is_active', true)->where(function ($query) {
            $query->whereHas('services', fn ($q) => $q->published())
                ->orWhereHas('articles', fn ($q) => $q->published())
                ->orWhereHas('areas', fn ($q) => $q->published());
        })->orderBy('sort_order')->limit(6)->get();
        $articles = Article::published()->whereHas('services', fn ($query) => $query->published())->with([
            'category',
            'services' => fn ($query) => $query->published()->with(['images' => fn ($images) => $images->where('processing_status', 'processed')->orderByDesc('is_cover')->limit(1)]),
        ])->latest('published_at')->limit(3)->get();
        $testimonials = Testimonial::query()->where('is_approved', true)->with(['area', 'project'])->latest()->limit(3)->get();
        $seo = Seo::page('مظلات وسواتر الرياض | طلب معاينة وتركيب داخل الرياض', 'خدمات مظلات وسواتر داخل مدينة الرياض. تعرّف على الخيارات والخامات واطلب معاينة وعرض سعر عبر الاتصال أو واتساب.');

        return view('pages.home', compact('featuredServices', 'heroService', 'heroImage', 'heroSlides', 'areas', 'projects', 'beforeAfterProject', 'trustItems', 'faqs', 'articles', 'testimonials', 'seo'));
    }

    public function about(): View
    {
        $seo = Seo::page('من نحن | مظلات وسواتر الرياض', 'تعرف على طريقة عمل مظلات وسواتر الرياض وخطوات المعاينة وتحديد المتطلبات داخل مدينة الرياض.', null, $this->crumbs(['من نحن' => route('about')]));

        return view('pages.about', compact('seo'));
    }

    public function services(): View
    {
        $categories = ServiceCategory::query()->where('is_active', true)->with(['services' => fn ($q) => $q->published()->orderBy('name')])->orderBy('sort_order')->get();
        $seo = Seo::page('خدمات المظلات والسواتر في الرياض', 'تصفح خدمات المظلات والسواتر المتاحة داخل مدينة الرياض واطلب معاينة لتحديد الخيار المناسب للموقع.', null, $this->crumbs(['الخدمات' => route('services.index')]));
        if ($categories->sum(fn (ServiceCategory $category): int => $category->services->count()) === 0) {
            $seo = Seo::noindex($seo);
        }

        return view('pages.services.index', compact('categories', 'seo'));
    }

    public function serviceCategory(string $slug): View
    {
        $category = ServiceCategory::query()->where('slug', $slug)->where('is_active', true)->firstOrFail();
        $services = $category->services()->published()->with('materials')->paginate(12);
        $seo = Seo::page($category->name.' في الرياض', $category->excerpt ?: 'خدمات '.$category->name.' داخل مدينة الرياض.', $category, $this->crumbs(['الخدمات' => route('services.index'), $category->name => url()->current()]));
        $seo = Seo::paginate($services->isEmpty() ? Seo::noindex($seo) : $seo, $services);

        return view('pages.services.category', compact('category', 'services', 'seo'));
    }

    public function service(string $slug): View
    {
        $service = Service::published()->where('slug', $slug)->with([
            'category', 'materials',
            'images' => fn ($q) => $q->where('processing_status', 'processed')->orderByDesc('is_cover')->orderBy('sort_order')->limit(16),
            'faqs' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
            'projects' => fn ($q) => $q->published()->with(['area', 'images'])->limit(6),
            'articles' => fn ($q) => $q->published()->with('category')->limit(4), 'seo',
        ])->firstOrFail();
        $related = Service::published()->whereKeyNot($service->id)->with(['category', 'images' => fn ($q) => $q->where('processing_status', 'processed')->orderByDesc('is_cover')->limit(1)])
            ->orderByRaw('CASE WHEN service_category_id = ? THEN 0 ELSE 1 END', [$service->service_category_id])->limit(4)->get();
        $areas = Area::published()->orderByDesc('is_primary')->orderBy('name')->get();
        $testimonials = Testimonial::query()->where('is_approved', true)->whereHas('project', fn ($query) => $query->where('service_id', $service->id)->published())->with(['area', 'project'])->latest()->limit(3)->get();
        $seo = Seo::page($service->name.' | مظلات وسواتر الرياض', $service->excerpt ?: 'تفاصيل '.$service->name.' وخيارات المعاينة داخل الرياض.', $service, $this->crumbs(['الخدمات' => route('services.index'), $service->name => url()->current()]), [Seo::faqSchema($service->faqs)]);

        return view('pages.services.show', compact('service', 'related', 'areas', 'testimonials', 'seo'));
    }

    public function projects(): View
    {
        $projects = Project::published()->with(['service', 'area', 'images'])->latest('published_at')->paginate(12);
        $seo = Seo::page('مشاريع مظلات وسواتر الرياض', 'مشاريع موثقة يضيفها فريق الموقع بعد التنفيذ داخل أحياء الرياض، مع ربط كل مشروع بالخدمة والمنطقة.', null, $this->crumbs(['المشاريع' => route('projects.index')]));
        $seo = Seo::paginate($projects->isEmpty() ? Seo::noindex($seo) : $seo, $projects);

        return view('pages.projects.index', compact('projects', 'seo'));
    }

    public function project(string $slug): View
    {
        $project = Project::published()->where('slug', $slug)->with(['service', 'area', 'images', 'seo'])->firstOrFail();
        $seo = Seo::page($project->title.' | مشروع في '.$project->area->name, $project->excerpt ?: 'تفاصيل مشروع '.$project->title.' في '.$project->area->name, $project, $this->crumbs(['المشاريع' => route('projects.index'), $project->title => url()->current()]));

        return view('pages.projects.show', compact('project', 'seo'));
    }

    public function areas(): View
    {
        $areas = Area::published()->withCount(['projects' => fn ($q) => $q->published()])->orderByDesc('is_primary')->orderBy('name')->paginate(18);
        $seo = Seo::page('مناطق خدمة المظلات والسواتر داخل الرياض', 'صفحات مناطق الخدمة داخل مدينة الرياض، مع عرض المشاريع الحقيقية المرتبطة بكل منطقة عند توفرها.', null, $this->crumbs(['المناطق' => route('areas.index')]));
        $seo = Seo::paginate($areas->isEmpty() ? Seo::noindex($seo) : $seo, $areas);

        return view('pages.areas.index', compact('areas', 'seo'));
    }

    public function area(string $slug): View
    {
        $area = Area::published()->where('slug', $slug)->with(['faqs' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'), 'seo'])->firstOrFail();
        $projects = $area->projects()->published()->with(['service', 'images'])->latest('published_at')->paginate(9);
        $seo = Seo::page('مظلات وسواتر في '.$area->name, $area->excerpt ?: 'خدمات المظلات والسواتر في '.$area->name.' داخل مدينة الرياض.', $area, $this->crumbs(['المناطق' => route('areas.index'), $area->name => url()->current()]), [Seo::faqSchema($area->faqs)]);
        $seo = Seo::paginate($seo, $projects);

        return view('pages.areas.show', compact('area', 'projects', 'seo'));
    }

    public function guide(): View
    {
        $articles = Article::published()->with('category')->latest('published_at')->paginate(12);
        $categories = ArticleCategory::query()->where('is_active', true)->withCount(['articles' => fn ($q) => $q->published()])->get();
        $seo = Seo::page('دليل المظلات والسواتر', 'مقالات إرشادية تساعدك على فهم الخامات والاستخدامات وعوامل التسعير قبل طلب المعاينة.', null, $this->crumbs(['الدليل' => route('guide.index')]));
        $seo = Seo::paginate($articles->isEmpty() ? Seo::noindex($seo) : $seo, $articles);

        return view('pages.guide.index', compact('articles', 'categories', 'seo'));
    }

    public function article(string $slug, ArticleContent $content): View
    {
        $article = Article::published()->where('slug', $slug)->with([
            'category',
            'services' => fn ($query) => $query->published()->with(['category', 'images' => fn ($images) => $images->where('processing_status', 'processed')->orderByDesc('is_cover')->orderBy('sort_order')->limit(4)]),
            'faqs' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
            'relatedArticles' => fn ($q) => $q->published()->with('category')->limit(4),
            'seo',
        ])->firstOrFail();
        $articleSections = $content->sections($article->body);
        $readingMinutes = $content->readingMinutes($article->body);
        $articleImages = $article->services->flatMap->images->unique('id')->values();
        $articleImage = $articleImages->firstWhere('is_cover', true) ?: $articleImages->first();
        $seo = Seo::page($article->title, $article->excerpt ?: 'مقال من دليل المظلات والسواتر.', $article, $this->crumbs(['الدليل' => route('guide.index'), $article->title => url()->current()]), [Seo::faqSchema($article->faqs)]);

        return view('pages.guide.show', compact('article', 'articleSections', 'readingMinutes', 'articleImages', 'articleImage', 'seo'));
    }

    public function prices(): View
    {
        $services = Service::published()->where('is_price_published', true)->whereNotNull('price_from')->orderBy('name')->get();
        $materials = Material::query()->where('is_active', true)->where('is_price_published', true)->whereNotNull('price_from')->orderBy('name')->get();
        $seo = Seo::page('دليل أسعار المظلات والسواتر في الرياض', 'دليل أسعار لا يعرض إلا البيانات التي يعتمدها فريق الموقع، مع توضيح أن السعر النهائي يتحدد بعد المعاينة والمقاسات.', null, $this->crumbs(['الأسعار' => route('prices')]));
        if ($services->isEmpty() && $materials->isEmpty()) {
            $seo = Seo::noindex($seo);
        }

        return view('pages.prices', compact('services', 'materials', 'seo'));
    }

    public function quote(): View
    {
        $services = Service::published()->orderBy('name')->get(['id', 'name']);
        $areaSize = request()->integer('area_size');
        $estimateMessage = $areaSize > 0 && $areaSize <= 10000 ? 'المساحة التقديرية: '.$areaSize.' م². أرجو ترتيب معاينة للتأكد من المقاسات وتحديد نطاق العمل.' : '';
        $seo = Seo::page('طلب معاينة وعرض سعر في الرياض', 'أرسل موقعك ونوع الخدمة المطلوبة لترتيب التواصل بشأن معاينة وعرض سعر.', null, $this->crumbs(['طلب معاينة' => route('quote')]));

        return view('pages.quote', compact('services', 'estimateMessage', 'seo'));
    }

    public function contact(): View
    {
        $services = Service::published()->orderBy('name')->get(['id', 'name']);
        $seo = Seo::page('تواصل مع مظلات وسواتر الرياض', 'اتصل أو تواصل عبر واتساب أو أرسل نموذج التواصل لخدمات المظلات والسواتر داخل الرياض.', null, $this->crumbs(['تواصل معنا' => route('contact')]));

        return view('pages.contact', compact('services', 'seo'));
    }

    public function privacy(): View
    {
        $seo = Seo::page('سياسة الخصوصية', 'كيفية جمع واستخدام وحماية البيانات المرسلة عبر موقع مظلات وسواتر الرياض.', null, $this->crumbs(['سياسة الخصوصية' => route('privacy')]));

        return view('pages.privacy', compact('seo'));
    }

    public function terms(): View
    {
        $seo = Seo::page('الشروط والأحكام', 'الشروط المنظمة لاستخدام موقع مظلات وسواتر الرياض وإرسال طلبات المعاينة.', null, $this->crumbs(['الشروط والأحكام' => route('terms')]));

        return view('pages.terms', compact('seo'));
    }

    private function crumbs(array $items): array
    {
        return [['name' => 'الرئيسية', 'url' => route('home')], ...collect($items)->map(fn ($url, $name) => ['name' => $name, 'url' => $url])->values()->all()];
    }
}

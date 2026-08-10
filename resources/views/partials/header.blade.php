@php
    $navigationCategories = collect($navServiceCategories)->filter(fn ($category) => !empty($category['services']));
    $navigationServicesCount = $navigationCategories->sum(fn ($category) => count($category['services']));
@endphp
<header class="site-header" data-header>
    <div class="header-utility">
        <div class="container-shell">
            <p><span aria-hidden="true">●</span>{{ $siteSettings['primary_service_area'] ?? $siteSettings['city'] }}</p>
            <div>
                <a href="{{ $siteSettings['whatsapp_url'] }}" target="_blank" rel="noopener">واتساب</a>
                <a href="{{ $siteSettings['phone_tel'] }}" dir="ltr">{{ $siteSettings['phone_display'] }}</a>
            </div>
        </div>
    </div>
    <div class="header-main">
        <div class="container-shell header-inner">
            <a href="{{ route('home') }}" class="brand" aria-label="{{ $siteSettings['site_name'] }} - الرئيسية">
                <span class="brand-mark" aria-hidden="true"><i></i><i></i><i></i></span>
                <span><strong>{{ $siteSettings['site_name'] }}</strong><small>تنفيذ هندسي للمساحات الخارجية</small></span>
            </a>

            <nav id="primary-nav" class="primary-nav" aria-label="التنقل الرئيسي" data-nav>
                <a @class(['active' => request()->routeIs('home')]) href="{{ route('home') }}">الرئيسية</a>
                <div @class(['nav-mega', 'active' => request()->routeIs('services.*')]) data-mega>
                    <button type="button" aria-expanded="false" aria-controls="services-mega-menu" data-mega-toggle>
                        الخدمات <i aria-hidden="true"></i>
                    </button>
                    <div id="services-mega-menu" class="mega-menu" data-mega-menu>
                        <div class="mega-menu-head">
                            <div><span>دليل الخدمات</span><strong>اختر التصنيف ثم الخدمة المناسبة</strong></div>
                            <a href="{{ route('services.index') }}">عرض كل الخدمات <span aria-hidden="true">←</span></a>
                        </div>
                        <div class="mega-category-grid">
                            @foreach($navigationCategories as $category)
                                <section>
                                    <a class="mega-category-title" href="{{ route('services.category', $category['slug']) }}">
                                        <span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                        <strong>{{ $category['name'] }}</strong>
                                    </a>
                                    <div>
                                        @foreach($category['services'] as $item)
                                            <a @class(['active' => request()->routeIs('services.show') && request()->route('slug') === $item['slug']]) href="{{ route('services.show', $item['slug']) }}">
                                                {{ $item['name'] }} <span aria-hidden="true">←</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </section>
                            @endforeach
                        </div>
                        <div class="mega-menu-foot"><span>{{ $navigationServicesCount }} خدمة منشورة ومراجعة</span><a href="{{ route('quote') }}">لست متأكدًا؟ اطلب معاينة</a></div>
                    </div>
                </div>
                <a @class(['active' => request()->routeIs('projects.*')]) href="{{ route('projects.index') }}">أعمالنا</a>
                <a @class(['active' => request()->routeIs('guide.*')]) href="{{ route('guide.index') }}">دليل البناء</a>
                <a @class(['active' => request()->routeIs('about')]) href="{{ route('about') }}">من نحن</a>
                <a @class(['active' => request()->routeIs('contact')]) href="{{ route('contact') }}">تواصل معنا</a>
            </nav>

            <a class="button header-cta" href="{{ route('quote') }}"><span>اطلب معاينة</span><i aria-hidden="true">←</i></a>
            <div class="mobile-header-actions">
                <a href="{{ $siteSettings['phone_tel'] }}" aria-label="اتصال"><span aria-hidden="true">☎</span></a>
                <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-nav" data-nav-toggle><span></span><span></span><span></span><span class="sr-only">فتح القائمة</span></button>
            </div>
        </div>
    </div>
</header>

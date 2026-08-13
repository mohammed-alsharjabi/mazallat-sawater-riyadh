@php
    $navigationCategories = collect($navServiceCategories)->filter(fn ($category) => ! empty($category['services']));
@endphp
@if(request()->routeIs('services.show'))
<header class="site-header service-design-header" data-header>
    <div class="service-design-header-inner">
        <a href="{{ route('home') }}" class="service-design-brand" aria-label="{{ $siteSettings['site_name'] }} - الرئيسية">
            <span class="service-design-brand-symbol"><x-brand-symbol /></span>
            <strong>{{ $siteSettings['site_name'] }}</strong>
        </a>

        <nav class="service-design-nav" aria-label="التنقل الرئيسي">
            <a href="{{ route('home') }}">الرئيسية</a>
            <a class="active" href="{{ route('services.index') }}">الخدمات</a>
            <a href="{{ route('projects.index') }}">أعمالنا</a>
            <a href="{{ route('about') }}">من نحن</a>
        </nav>

        <a class="service-design-contact" href="{{ route('contact') }}">اتصل بنا</a>
        <div class="service-design-controls">
            <a class="service-design-phone" href="{{ $siteSettings['phone_tel'] }}" aria-label="اتصال"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.3 3.8 9.6 8c.3.6.2 1.2-.3 1.6l-1.4 1.1a15 15 0 0 0 5.4 5.4l1.1-1.4c.4-.5 1.1-.6 1.6-.3l4.2 2.3c.6.3.9 1 .7 1.7l-.6 2.1c-.2.8-.9 1.3-1.7 1.4C9.8 22.1 1.9 14.2 2.1 5.4c0-.8.6-1.5 1.4-1.7l2.1-.6c.7-.2 1.4.1 1.7.7Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
            <button class="service-design-toggle" type="button" aria-expanded="false" aria-controls="mobile-navigation" data-nav-toggle><span></span><span></span><span></span><b class="sr-only">فتح القائمة</b></button>
        </div>
    </div>
</header>
@else
<header class="site-header" data-header>
    <div class="container-shell header-inner">
        <a href="{{ route('home') }}" class="brand" aria-label="{{ $siteSettings['site_name'] }} - الرئيسية">
            <span class="brand-symbol"><x-brand-symbol /></span>
            <span><strong>{{ $siteSettings['site_name'] }}</strong><small>تنفيذ مظلات وسواتر داخل الرياض</small></span>
        </a>

        <nav class="desktop-nav" aria-label="التنقل الرئيسي">
            <a @class(['active' => request()->routeIs('home')]) href="{{ route('home') }}">الرئيسية</a>
            <div class="services-dropdown">
                <button type="button" aria-haspopup="true">الخدمات <span aria-hidden="true">⌄</span></button>
                <div class="services-dropdown-panel">
                    @foreach($navigationCategories as $category)
                        <section>
                            <a class="dropdown-category" href="{{ route('services.category', $category['slug']) }}">{{ $category['name'] }}</a>
                            @foreach($category['services'] as $item)
                                <a href="{{ route('services.show', $item['slug']) }}">{{ $item['name'] }}</a>
                            @endforeach
                        </section>
                    @endforeach
                    <a class="dropdown-all" href="{{ route('services.index') }}">عرض جميع الخدمات ←</a>
                </div>
            </div>
            <a @class(['active' => request()->routeIs('projects.*')]) href="{{ route('projects.index') }}">المشاريع</a>
            <a @class(['active' => request()->routeIs('guide.*')]) href="{{ route('guide.index') }}">دليل البناء</a>
            <a @class(['active' => request()->routeIs('about')]) href="{{ route('about') }}">من نحن</a>
            <a @class(['active' => request()->routeIs('contact')]) href="{{ route('contact') }}">تواصل معنا</a>
        </nav>

        <a class="button button-primary header-cta" href="{{ route('quote') }}">اطلب عرض سعر</a>
        <div class="mobile-header-actions">
            <span class="mobile-header-location"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s6-5.5 6-11a6 6 0 1 0-12 0c0 5.5 6 11 6 11Z" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="10" r="2" fill="currentColor"/></svg>{{ $siteSettings['city'] }}</span>
            <a href="{{ $siteSettings['phone_tel'] }}" aria-label="اتصال"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.3 3.8 9.6 8c.3.6.2 1.2-.3 1.6l-1.4 1.1a15 15 0 0 0 5.4 5.4l1.1-1.4c.4-.5 1.1-.6 1.6-.3l4.2 2.3c.6.3.9 1 .7 1.7l-.6 2.1c-.2.8-.9 1.3-1.7 1.4C9.8 22.1 1.9 14.2 2.1 5.4c0-.8.6-1.5 1.4-1.7l2.1-.6c.7-.2 1.4.1 1.7.7Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
            <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="mobile-navigation" data-nav-toggle><span></span><span></span><span></span><b class="sr-only">فتح القائمة</b></button>
        </div>
    </div>

    <div class="service-shortcuts" aria-label="الخدمات الأساسية">
        <div class="container-shell">
            @foreach(collect($navigationCategories)->flatMap(fn ($category) => $category['services'])->take(6) as $item)
                <a href="{{ route('services.show', $item['slug']) }}">{{ $item['name'] }}</a>
            @endforeach
        </div>
    </div>
</header>
@endif

<div class="nav-backdrop" data-nav-backdrop hidden></div>
<aside id="mobile-navigation" class="mobile-drawer" aria-label="قائمة الجوال" aria-hidden="true" data-mobile-drawer>
    <div class="mobile-drawer-head"><strong>{{ $siteSettings['site_name'] }}</strong><button type="button" data-nav-close aria-label="إغلاق القائمة">×</button></div>
    <nav>
        <a href="{{ route('home') }}">الرئيسية</a>
        <details><summary>الخدمات</summary><div>@foreach($navigationCategories as $category)<strong>{{ $category['name'] }}</strong>@foreach($category['services'] as $item)<a href="{{ route('services.show', $item['slug']) }}">{{ $item['name'] }}</a>@endforeach @endforeach</div></details>
        <a href="{{ route('projects.index') }}">المشاريع</a>
        <a href="{{ route('guide.index') }}">دليل البناء</a>
        <a href="{{ route('about') }}">من نحن</a>
        <a href="{{ route('contact') }}">تواصل معنا</a>
    </nav>
    <div class="mobile-drawer-actions"><a class="button button-primary" href="{{ route('quote') }}">اطلب عرض سعر</a><a class="button button-outline" href="{{ $siteSettings['whatsapp_url'] }}" target="_blank" rel="noopener">واتساب</a></div>
</aside>

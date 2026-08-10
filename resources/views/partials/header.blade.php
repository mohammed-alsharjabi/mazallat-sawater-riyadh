@php
    $headerServices = collect($navServiceCategories)->flatMap(fn ($category) => $category['services'])->unique('slug')->take(6);
@endphp
<header class="site-header" data-header>
    <div class="container-shell header-inner">
        <a href="{{ route('home') }}" class="brand" aria-label="{{ $siteSettings['site_name'] }} - الرئيسية">
            <span class="brand-mark" aria-hidden="true"><i></i><i></i><i></i></span>
            <strong>{{ $siteSettings['site_name'] }}</strong>
        </a>
        <nav id="primary-nav" class="primary-nav" aria-label="التنقل الرئيسي" data-nav>
            <a @class(['active' => request()->routeIs('home')]) href="{{ route('home') }}">الرئيسية</a>
            <a @class(['active' => request()->routeIs('services.*')]) href="{{ route('services.index') }}">الخدمات</a>
            <a @class(['active' => request()->routeIs('projects.*')]) href="{{ route('projects.index') }}">المشاريع</a>
            <a @class(['active' => request()->routeIs('guide.*')]) href="{{ route('guide.index') }}">دليل البناء</a>
            <a href="{{ route('contact') }}">تواصل معنا</a>
        </nav>
        <a class="button button-outline header-cta" href="{{ route('quote') }}">اطلب عرض سعر</a>
        <div class="mobile-header-actions">
            <a href="{{ $siteSettings['phone_tel'] }}" aria-label="اتصال"><span aria-hidden="true">☎</span></a>
            <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-nav" data-nav-toggle><span></span><span></span><span></span><span class="sr-only">فتح القائمة</span></button>
        </div>
    </div>
    @if($headerServices->isNotEmpty())
        <nav class="service-rail" aria-label="الخدمات الأساسية"><div class="container-shell">
            @foreach($headerServices as $item)<a @class(['active' => request()->routeIs('services.show') && request()->route('slug') === $item['slug']]) href="{{ route('services.show', $item['slug']) }}">{{ $item['name'] }}</a>@endforeach
        </div></nav>
    @endif
</header>

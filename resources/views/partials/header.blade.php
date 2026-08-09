<div class="utility-bar">
    <div class="container-shell utility-inner">
        <p><span aria-hidden="true">●</span> نخدم جميع أحياء {{ $siteSettings['city'] }}</p>
        <div><a href="{{ route('quote') }}">طلب معاينة</a><a class="font-bold" href="{{ $siteSettings['phone_tel'] }}" dir="ltr">{{ $siteSettings['phone_display'] }}</a></div>
    </div>
</div>
<header class="site-header" data-header>
    <div class="container-shell header-inner">
        <a href="{{ route('home') }}" class="brand" aria-label="{{ $siteSettings['site_name'] }} - الرئيسية">
            <span class="brand-mark" aria-hidden="true"><i></i><i></i><i></i></span>
            <span><strong>مظلات وسواتر</strong><small>الرياض</small></span>
        </a>
        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-nav" data-nav-toggle><span></span><span></span><span></span><span class="sr-only">فتح القائمة</span></button>
        <nav id="primary-nav" class="primary-nav" aria-label="التنقل الرئيسي" data-nav>
            <a @class(['active'=>request()->routeIs('home')]) href="{{ route('home') }}">الرئيسية</a>
            <div class="nav-mega-item" data-mega>
                <div class="nav-mega-trigger"><a @class(['active'=>request()->routeIs('services.*')]) href="{{ route('services.index') }}">الخدمات</a><button type="button" aria-expanded="false" aria-label="عرض قائمة الخدمات" data-mega-toggle>⌄</button></div>
                <div class="mega-menu" data-mega-menu>
                    <div class="container-shell mega-grid">
                        <div class="mega-intro"><p class="eyebrow">حلول هندسية للمساحات الخارجية</p><h2>اختر الخدمة الأقرب لموقعك</h2><p>محتوى واضح يساعدك على تحديد الاستخدام والخامة والعوامل المؤثرة في نطاق العمل.</p><a class="text-link" href="{{ route('services.index') }}">جميع الخدمات ←</a></div>
                        @forelse($navServiceCategories as $category)
                        <section><a class="mega-category" href="{{ route('services.category',$category['slug']) }}">{{ $category['name'] }}</a><ul>@foreach($category['services'] as $service)<li><a href="{{ route('services.show',$service['slug']) }}">{{ $service['name'] }}</a></li>@endforeach</ul></section>
                        @empty
                            <section><p class="empty-mini">تظهر الخدمات المنشورة هنا.</p></section>
                        @endforelse
                        <aside><span>مشروعك داخل الرياض؟</span><strong>ابدأ بمعاينة الموقع والمقاسات</strong><a class="button button-accent" href="{{ route('quote') }}">{{ $siteSettings['inspection_cta_label'] ?? 'اطلب معاينة' }}</a></aside>
                    </div>
                </div>
            </div>
            <a @class(['active'=>request()->routeIs('projects.*')]) href="{{ route('projects.index') }}">المشاريع</a>
            <a @class(['active'=>request()->routeIs('areas.*')]) href="{{ route('areas.index') }}">المناطق</a>
            <a @class(['active'=>request()->routeIs('guide.*')]) href="{{ route('guide.index') }}">الدليل</a>
            <a @class(['active'=>request()->routeIs('prices')]) href="{{ route('prices') }}">الأسعار</a>
            <a href="{{ route('contact') }}">تواصل</a>
        </nav>
        <a class="button button-primary header-cta" href="{{ route('quote') }}">{{ $siteSettings['inspection_cta_label'] ?? 'اطلب معاينة' }}</a>
    </div>
</header>

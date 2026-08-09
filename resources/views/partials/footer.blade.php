<footer class="site-footer">
    <div class="container-shell footer-grid">
        <section>
            <a href="{{ route('home') }}" class="brand brand-light"><span class="brand-mark" aria-hidden="true"><i></i><i></i><i></i></span><span><strong>مظلات وسواتر</strong><small>الرياض</small></span></a>
            <p class="mt-5 max-w-md text-stone-300">موقع لخدمات المظلات والسواتر داخل مدينة الرياض، يتيح استعراض الخدمات والمناطق والمشاريع الموثقة وطلب المعاينة.</p>
        </section>
        <section><h2>روابط مهمة</h2><ul><li><a href="{{ route('about') }}">من نحن</a></li><li><a href="{{ route('services.index') }}">الخدمات</a></li><li><a href="{{ route('guide.index') }}">الدليل</a></li><li><a href="{{ route('prices') }}">دليل الأسعار</a></li></ul></section>
        <section><h2>تواصل</h2><ul><li><a href="{{ $siteSettings['phone_tel'] }}" dir="ltr">{{ $siteSettings['phone_display'] }}</a></li><li><a href="{{ $siteSettings['whatsapp_url'] }}" target="_blank" rel="noopener">واتساب</a></li><li>{{ $siteSettings['address'] }}</li></ul></section>
    </div>
    <div class="container-shell footer-bottom"><p>© {{ now()->year }} {{ $siteSettings['site_name'] }}</p><div><a href="{{ route('privacy') }}">الخصوصية</a><a href="{{ route('terms') }}">الشروط</a></div></div>
</footer>

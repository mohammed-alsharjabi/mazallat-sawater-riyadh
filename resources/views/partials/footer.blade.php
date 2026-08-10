<footer class="site-footer">
    <div class="container-shell footer-bar">
        <a href="{{ route('home') }}" class="brand"><span class="brand-mark" aria-hidden="true"><i></i><i></i><i></i></span><strong>{{ $siteSettings['site_name'] }}</strong></a>
        <span class="footer-location"><i aria-hidden="true">●</i>{{ $siteSettings['primary_service_area'] ?? $siteSettings['city'] }}</span>
        <a class="footer-phone" href="{{ $siteSettings['phone_tel'] }}" dir="ltr">{{ $siteSettings['phone_display'] }}</a>
    </div>
    <div class="container-shell footer-meta"><p>© {{ now()->year }} {{ $siteSettings['site_name'] }}</p><nav aria-label="روابط قانونية"><a href="{{ route('privacy') }}">الخصوصية</a><a href="{{ route('terms') }}">الشروط</a><a href="{{ route('guide.index') }}">دليل البناء</a></nav></div>
</footer>

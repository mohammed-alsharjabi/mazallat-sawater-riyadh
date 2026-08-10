<nav class="mobile-actions" aria-label="إجراءات التواصل السريعة">
    <a class="mobile-call" href="{{ $siteSettings['phone_tel'] }}"><span aria-hidden="true">☎</span><strong>اتصل الآن</strong></a>
    <a class="mobile-whatsapp" href="{{ $currentWhatsapp ?? $siteSettings['whatsapp_url'] }}" target="_blank" rel="noopener"><span aria-hidden="true">◉</span><strong>واتساب</strong></a>
</nav>

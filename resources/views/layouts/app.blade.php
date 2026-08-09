<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#121820">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <title>{{ $seo['title'] ?? $siteSettings['site_name'] }}</title>
    <meta name="description" content="{{ $seo['description'] ?? '' }}">
    <meta name="robots" content="{{ $seo['robots'] ?? 'index,follow' }}">
    <link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">
    @if(!empty($seo['prev']))<link rel="prev" href="{{ $seo['prev'] }}">@endif
    @if(!empty($seo['next']))<link rel="next" href="{{ $seo['next'] }}">@endif
    <link rel="alternate" hreflang="ar-SA" href="{{ $seo['canonical'] ?? url()->current() }}">
    <link rel="alternate" hreflang="x-default" href="{{ $seo['canonical'] ?? url()->current() }}">
    <meta property="og:locale" content="ar_SA">
    <meta property="og:type" content="{{ $seo['og_type'] ?? 'website' }}">
    <meta property="og:site_name" content="{{ $siteSettings['site_name'] }}">
    <meta property="og:title" content="{{ $seo['og_title'] ?? ($seo['title'] ?? $siteSettings['site_name']) }}">
    <meta property="og:description" content="{{ $seo['og_description'] ?? ($seo['description'] ?? '') }}">
    <meta property="og:url" content="{{ $seo['canonical'] ?? url()->current() }}">
    @php($socialImage = !empty($seo['og_image']) ? (\Illuminate\Support\Str::startsWith($seo['og_image'], ['http://','https://']) ? $seo['og_image'] : asset('storage/'.$seo['og_image'])) : null)
    @if($socialImage)<meta property="og:image" content="{{ $socialImage }}"><meta property="og:image:alt" content="{{ $seo['og_title'] ?? ($seo['title'] ?? $siteSettings['site_name']) }}">@endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['og_title'] ?? ($seo['title'] ?? $siteSettings['site_name']) }}">
    <meta name="twitter:description" content="{{ $seo['og_description'] ?? ($seo['description'] ?? '') }}">
    @if($socialImage)<meta name="twitter:image" content="{{ $socialImage }}">@endif
    @if(!empty($siteSettings['search_console_verification']))<meta name="google-site-verification" content="{{ $siteSettings['search_console_verification'] }}">@endif
    @if(!empty($siteSettings['ga_measurement_id']))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $siteSettings['ga_measurement_id'] }}"></script>
        <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date());gtag('config',@js($siteSettings['ga_measurement_id']),{'anonymize_ip':true});</script>
    @endif
    <script>document.documentElement.classList.add('js')</script>
    @vite(['resources/css/app.css','resources/js/app.js'])
    @stack('head')
    @foreach(($seo['schemas'] ?? []) as $schema)
        @if($schema)<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_HEX_TAG|JSON_HEX_AMP) !!}</script>@endif
    @endforeach
</head>
<body class="bg-stone-50 text-stone-900 antialiased">
    <a href="#main-content" class="skip-link">انتقل إلى المحتوى</a>
    @include('partials.header')
    <main id="main-content">@yield('content')</main>
    @include('partials.footer')
    <a class="floating-whatsapp" href="{{ $siteSettings['whatsapp_url'] }}" aria-label="تواصل عبر واتساب" rel="noopener" target="_blank"><span aria-hidden="true">واتساب</span></a>
    @include('partials.mobile-actions')
    @stack('scripts')
</body>
</html>

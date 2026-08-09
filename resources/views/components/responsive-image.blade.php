@props(['image','alt'=>null,'loading'=>'lazy','sizes'=>'(max-width: 700px) 100vw, 50vw','class'=>'','fetchpriority'=>'auto'])
@php
    $variants = $image->variants ?? [];
    $webp = collect($variants['webp'] ?? [])->map(fn($item) => asset('storage/'.$item['path']).' '.$item['width'].'w')->implode(', ');
    $avif = collect($variants['avif'] ?? [])->map(fn($item) => asset('storage/'.$item['path']).' '.$item['width'].'w')->implode(', ');
    $width = $image->width ?: 1200;
    $height = $image->height ?: 800;
@endphp
<span class="image-shell is-loading {{ $class }}" data-image-shell>
    <picture>
        @if($avif)<source type="image/avif" srcset="{{ $avif }}" sizes="{{ $sizes }}">@endif
        @if($webp)<source type="image/webp" srcset="{{ $webp }}" sizes="{{ $sizes }}">@endif
        <img src="{{ asset('storage/'.$image->path) }}" @if($webp) srcset="{{ $webp }}" sizes="{{ $sizes }}" @endif alt="{{ $alt ?: ($image->alt_text ?: 'صورة مشروع مظلات وسواتر') }}" width="{{ $width }}" height="{{ $height }}" loading="{{ $loading }}" decoding="async" fetchpriority="{{ $fetchpriority }}">
    </picture>
    <span class="image-fallback" aria-hidden="true">تعذر تحميل الصورة</span>
</span>

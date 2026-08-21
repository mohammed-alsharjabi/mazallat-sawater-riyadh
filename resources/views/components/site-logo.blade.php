@props(['compact' => false, 'alt' => 'مظلات وسواتر الرياض'])

@php
    $logoPath = $compact ? 'brand/mazallat-sawater-riyadh-icon.svg' : 'brand/mazallat-sawater-riyadh-logo.png';
    $logoFile = public_path($logoPath);
    $logoVersion = is_file($logoFile) ? filemtime($logoFile) : null;
@endphp

<img
    src="{{ asset($logoPath) }}{{ $logoVersion ? '?v='.$logoVersion : '' }}"
    alt="{{ $alt }}"
    @unless($compact) width="2137" height="736" @endunless
    {{ $attributes->class($compact ? 'site-logo site-logo-compact' : 'site-logo') }}
>

@props(['compact' => false, 'alt' => 'مظلات وسواتر الرياض'])

<img
    src="{{ asset($compact ? 'brand/mazallat-sawater-riyadh-icon.svg' : 'brand/mazallat-sawater-riyadh-logo.png') }}"
    alt="{{ $alt }}"
    @unless($compact) width="960" height="320" @endunless
    {{ $attributes->class($compact ? 'site-logo site-logo-compact' : 'site-logo') }}
>

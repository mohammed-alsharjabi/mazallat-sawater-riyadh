@props(['compact' => false, 'alt' => 'مظلات وسواتر الرياض'])

<img
    src="{{ asset($compact ? 'brand/mazallat-sawater-riyadh-icon.svg' : 'brand/mazallat-sawater-riyadh-logo.svg') }}"
    alt="{{ $alt }}"
    {{ $attributes->class($compact ? 'site-logo site-logo-compact' : 'site-logo') }}
>

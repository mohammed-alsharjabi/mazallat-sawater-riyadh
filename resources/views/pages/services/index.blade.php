@extends('layouts.app')
@section('content')
<x-page-hero eyebrow="دليل الخدمات" title="خدمات المظلات والسواتر في الرياض" description="تصفح الخدمات حسب التصنيف، ثم راجع تفاصيل الاستخدام والخامات والمشاريع المرتبطة." />
<section class="section-block"><div class="container-shell space-y-16">@forelse($categories as $category)<section><div class="flex flex-wrap items-end justify-between gap-4"><x-section-heading :eyebrow="$category->services->count().' خدمات منشورة'" :title="$category->name" :description="$category->excerpt" /><a class="text-link" href="{{ route('services.category',$category->slug) }}">صفحة التصنيف ←</a></div><div class="cards-grid mt-9">@foreach($category->services as $service)<x-service-card :service="$service" />@endforeach</div></section>@empty<p class="empty-state">لا توجد خدمات منشورة حاليًا.</p>@endforelse</div></section>
@endsection

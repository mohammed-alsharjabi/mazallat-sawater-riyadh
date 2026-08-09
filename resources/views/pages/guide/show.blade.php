@extends('layouts.app')
@section('content')
<x-page-hero :eyebrow="$article->category->name" :title="$article->title" :description="$article->excerpt" />
<section class="section-block"><div class="article-layout"><article class="prose-content article-body"><p class="article-date">نُشر في {{ $article->published_at->translatedFormat('j F Y') }}</p>{!! nl2br(e($article->body)) !!}</article><aside class="article-aside"><h2>هل تستعد لطلب معاينة؟</h2><p>اجمع موقع المشروع والمقاسات التقريبية وصور المساحة، ثم أرسل الطلب.</p><a class="button button-primary w-full" href="{{ route('quote') }}">طلب معاينة</a>@if($article->services->isNotEmpty())<h3>خدمات مرتبطة</h3><ul>@foreach($article->services as $service)<li><a href="{{ route('services.show',$service->slug) }}">{{ $service->name }}</a></li>@endforeach</ul>@endif</aside></div></section>
<x-faqs :faqs="$article->faqs" />
@if($article->relatedArticles->isNotEmpty())<section class="section-block"><div class="container-shell"><x-section-heading title="مقالات مرتبطة" /><div class="articles-grid mt-9">@foreach($article->relatedArticles as $item)<x-article-card :article="$item" />@endforeach</div></div></section>@endif
@endsection

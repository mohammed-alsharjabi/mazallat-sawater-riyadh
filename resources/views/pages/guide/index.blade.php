@extends('layouts.app')
@section('content')
<x-page-hero eyebrow="محتوى إرشادي" title="دليل المظلات والسواتر" description="معلومات تساعد على تحديد المتطلبات وفهم عوامل الاختيار والتسعير قبل طلب المعاينة." />
<section class="section-block"><div class="container-shell"><div class="category-chips">@foreach($categories as $category)<span>{{ $category->name }} <b>{{ $category->articles_count }}</b></span>@endforeach</div><div class="articles-grid mt-10">@forelse($articles as $article)<x-article-card :article="$article" />@empty<p class="empty-state">لا توجد مقالات منشورة.</p>@endforelse</div><div class="mt-10">{{ $articles->links() }}</div></div></section>
@endsection

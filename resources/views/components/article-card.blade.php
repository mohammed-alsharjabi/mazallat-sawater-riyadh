@props(['article'])
@php
    $cardImage = $article->relationLoaded('services')
        ? $article->services->filter(fn ($service) => $service->relationLoaded('images'))->flatMap->images->sortByDesc('is_cover')->first()
        : null;
@endphp
<article class="article-card">
    @if($cardImage)<a class="card-image" href="{{ route('guide.show', $article->slug) }}"><x-responsive-image :image="$cardImage" :alt="$cardImage->alt_text ?: $article->title" variant="thumbnail" sizes="(max-width: 780px) 36vw, 220px" /></a>@endif
    <div class="card-copy"><p class="card-kicker">{{ $article->category->name }}</p><h3><a href="{{ route('guide.show', $article->slug) }}">{{ $article->title }}</a></h3><p>{{ $article->excerpt }}</p><a class="text-link" href="{{ route('guide.show', $article->slug) }}">اقرأ المقال <span aria-hidden="true">←</span></a></div>
</article>

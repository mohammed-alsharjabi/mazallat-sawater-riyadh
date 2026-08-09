@props(['article'])
<article class="article-card">
    <p class="card-kicker">{{ $article->category->name }}</p>
    <h3><a href="{{ route('guide.show',$article->slug) }}">{{ $article->title }}</a></h3>
    <p>{{ $article->excerpt }}</p>
    <a class="text-link" href="{{ route('guide.show',$article->slug) }}">اقرأ الدليل <span aria-hidden="true">←</span></a>
</article>

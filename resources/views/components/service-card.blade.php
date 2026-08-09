@props(['service'])
<article class="content-card service-card">
    <div class="card-symbol" aria-hidden="true"><span></span><span></span><span></span></div>
    <p class="card-kicker">{{ $service->category?->name }}</p>
    <h3><a href="{{ route('services.show',$service->slug) }}">{{ $service->name }}</a></h3>
    <p>{{ $service->excerpt }}</p>
    <a class="text-link" href="{{ route('services.show',$service->slug) }}">تفاصيل الخدمة <span aria-hidden="true">←</span></a>
</article>

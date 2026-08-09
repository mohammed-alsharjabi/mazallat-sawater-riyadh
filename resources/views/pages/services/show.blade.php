@extends('layouts.app')

@php
    $galleryImages = $service->projects->flatMap->images->take(8);
    $heroImage = $galleryImages->first();
    $types = collect(preg_split('/\r\n|\r|\n/', (string) $service->types))->filter();
    $useCases = collect(preg_split('/\r\n|\r|\n/', (string) $service->use_cases))->filter();
    $materialsDetails = collect(preg_split('/\r\n|\r|\n/', (string) $service->materials_details))->filter();
    $advantages = collect(preg_split('/\r\n|\r|\n/', (string) $service->advantages))->filter();
    $disadvantages = collect(preg_split('/\r\n|\r|\n/', (string) $service->disadvantages))->filter();
    $priceFactors = collect(preg_split('/\r\n|\r|\n/', (string) $service->price_factors))->filter();
    $installationSteps = collect(preg_split('/\r\n|\r|\n/', (string) $service->installation_steps))->filter();
    $selectionTips = collect(preg_split('/\r\n|\r|\n/', (string) $service->selection_tips))->filter();
@endphp

@section('content')
<section class="service-hero">
    <div class="container-shell service-hero-grid">
        <div>
            <nav class="breadcrumbs" aria-label="مسار الصفحة"><a href="{{ route('home') }}">الرئيسية</a><span>/</span><a href="{{ route('services.index') }}">الخدمات</a><span>/</span><a href="{{ route('services.category',$service->category->slug) }}">{{ $service->category->name }}</a><span>/</span><b aria-current="page">{{ $service->name }}</b></nav>
            <p class="eyebrow eyebrow-light">{{ $service->category->name }} · داخل مدينة الرياض</p>
            <h1>{{ $service->name }}</h1>
            <p>{{ $service->excerpt }}</p>
            <div class="hero-actions"><a class="button button-accent" href="{{ route('quote',['service'=>$service->id]) }}">{{ $siteSettings['inspection_cta_label'] ?? 'اطلب معاينة مجانية' }}</a><a class="button button-ghost" href="{{ $siteSettings['whatsapp_url'] }}" target="_blank" rel="noopener">واتساب</a></div>
        </div>
        <div class="service-hero-media">
            @if($heroImage)<x-responsive-image :image="$heroImage" :alt="$heroImage->alt_text ?: $service->name" loading="eager" fetchpriority="high" sizes="(max-width: 900px) 100vw, 48vw" />@else<div class="architectural-fallback compact"><div class="structure-lines"><i></i><i></i><i></i><span></span></div><p><small>معرض الخدمة</small><strong>تظهر صورة من مشروع حقيقي مرتبط</strong></p></div>@endif
        </div>
    </div>
</section>

<section class="section-block"><div class="container-shell detail-grid service-intro-grid"><article class="prose-content" data-reveal><p class="eyebrow">عن الخدمة</p><h2>{{ $service->name }} للمواقع داخل الرياض</h2>{!! nl2br(e($service->content)) !!}</article><aside class="sticky-card"><p class="eyebrow">تواصل مباشر</p><h2>اعرض موقعك على الفريق</h2><p>شارك المنطقة والمقاسات التقريبية والصور عند التواصل لتكوين تصور أولي.</p><a class="button button-primary w-full" href="{{ route('quote',['service'=>$service->id]) }}">طلب معاينة</a><a class="button button-outline w-full" href="{{ $siteSettings['phone_tel'] }}">اتصال <span dir="ltr">{{ $siteSettings['phone_display'] }}</span></a><a class="button button-outline w-full" href="{{ $siteSettings['whatsapp_url'] }}" target="_blank" rel="noopener">واتساب</a></aside></div></section>

@if($galleryImages->isNotEmpty())
<section class="section-block service-gallery-section" data-reveal><div class="container-shell"><x-section-heading eyebrow="صور حقيقية" title="معرض أعمال مرتبط بالخدمة" description="الصور أدناه مأخوذة من مشاريع منشورة ومرتبطة بهذه الخدمة." /><div class="service-gallery mt-10">@foreach($galleryImages as $image)<x-responsive-image :image="$image" :alt="$image->alt_text ?: $service->name" sizes="(max-width: 700px) 100vw, (max-width: 1024px) 50vw, 33vw" />@endforeach</div></div></section>
@endif

<section class="section-block sand-section"><div class="container-shell service-spec-grid">
    <section data-reveal><p class="eyebrow">الأنواع</p><h2>أنواع {{ $service->name }}</h2>@if($types->isNotEmpty())<ul class="architectural-list">@foreach($types as $item)<li><span>{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span>{{ $item }}</li>@endforeach</ul>@else<p class="empty-inline">تُضاف الأنواع المعتمدة من لوحة الإدارة.</p>@endif</section>
    <section data-reveal><p class="eyebrow">الاستخدام</p><h2>أين تكون مناسبة؟</h2>@if($useCases->isNotEmpty())<ul class="architectural-list">@foreach($useCases as $item)<li><span>{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span>{{ $item }}</li>@endforeach</ul>@else<p class="empty-inline">تُضاف الاستخدامات المناسبة بعد اعتمادها.</p>@endif</section>
</div></section>

<section class="section-block"><div class="container-shell service-spec-grid">
    <section data-reveal><p class="eyebrow">المميزات</p><h2>نقاط القوة</h2>@if($advantages->isNotEmpty())<ul class="architectural-list">@foreach($advantages as $item)<li><span>{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span>{{ $item }}</li>@endforeach</ul>@else<p class="empty-inline">تُضاف المميزات بعد مراجعتها.</p>@endif</section>
    <section data-reveal><p class="eyebrow">القيود</p><h2>عيوب أو نقاط تحتاج انتباهًا</h2>@if($disadvantages->isNotEmpty())<ul class="architectural-list">@foreach($disadvantages as $item)<li><span>{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span>{{ $item }}</li>@endforeach</ul>@else<p class="empty-inline">تُضاف القيود الخاصة بالخدمة بعد اعتمادها.</p>@endif</section>
</div></section>

<section class="section-block"><div class="container-shell"><x-section-heading eyebrow="الخامات" title="الخامات المرتبطة بالخدمة" description="تظهر فقط الخامات التي أضيفت وربطت بالخدمة من لوحة الإدارة." />
@if($materialsDetails->isNotEmpty())<ul class="architectural-list mt-10">@foreach($materialsDetails as $item)<li><span>{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span>{{ $item }}</li>@endforeach</ul>@endif
<div class="materials-compare mt-10">@forelse($service->materials as $material)<article><span>{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span><h3>{{ $material->name }}</h3><p>{{ $material->excerpt ?: $material->description }}</p>@if($material->is_price_published && $material->price_from)<small>سعر معتمد يبدأ من {{ number_format($material->price_from,0) }} ر.س {{ $material->price_unit }}</small>@endif</article>@empty<div class="empty-state"><p>لم تُربط خامات معتمدة بهذه الخدمة بعد.</p></div>@endforelse</div></div></section>

<section class="section-block price-factors-section"><div class="container-shell price-factor-layout"><div><p class="eyebrow eyebrow-light">التسعير</p><h2>ما الذي يحدد السعر؟</h2><p>لا يكفي نوع الخدمة وحده لتحديد التكلفة؛ يجب تثبيت المقاسات والخامة وطريقة التنفيذ.</p>@if($service->is_price_published && $service->price_from)<div class="approved-price"><small>سعر منشور ومعتمد</small><strong>يبدأ من {{ number_format($service->price_from,0) }} ر.س {{ $service->price_unit }}</strong><p>{{ $service->price_note ?: 'يُحدد السعر النهائي بعد المعاينة.' }}</p></div>@endif</div><ol>@forelse($priceFactors as $factor)<li><span>{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span><strong>{{ $factor }}</strong></li>@empty<li><p>تُضاف عوامل التسعير الخاصة بالخدمة من لوحة الإدارة.</p></li>@endforelse</ol></div></section>

<section class="section-block workflow-section"><div class="container-shell"><x-section-heading eyebrow="مراحل التركيب" title="من القياس إلى المراجعة" />
<ol class="workflow-grid mt-10">@if($installationSteps->isNotEmpty()) @foreach($installationSteps as $step)<li><span>{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span><h3>{{ $step }}</h3></li>@endforeach @else @foreach(['معاينة الموقع','أخذ المقاسات','اعتماد التصميم','التصنيع','التركيب'] as $step)<li><span>{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span><h3>{{ $step }}</h3></li>@endforeach @endif</ol></div></section>

<section class="section-block sand-section"><div class="container-shell service-spec-grid"><section data-reveal><p class="eyebrow">قبل الاعتماد</p><h2>نصائح الاختيار</h2>@if($selectionTips->isNotEmpty())<ul class="architectural-list">@foreach($selectionTips as $tip)<li><span>{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span>{{ $tip }}</li>@endforeach</ul>@else<p class="empty-inline">تُضاف نصائح الاختيار الخاصة بالخدمة من لوحة الإدارة.</p>@endif</section><aside class="sticky-card"><p class="eyebrow">الخطوة التالية</p><h2>تواصل بشأن {{ $service->name }}</h2><p>{{ $service->cta ?: 'أرسل صور الموقع والمقاسات التقريبية لتحديد موعد المعاينة ومراجعة المواصفات المناسبة.' }}</p><a class="button button-primary w-full" href="{{ route('quote',['service'=>$service->id]) }}">طلب معاينة</a><a class="button button-outline w-full" href="{{ $siteSettings['whatsapp_url'] }}" target="_blank" rel="noopener">إرسال التفاصيل عبر واتساب</a></aside></div></section>

<section class="section-block bg-white"><div class="container-shell"><x-section-heading eyebrow="مشاريع موثقة" title="مشاريع مرتبطة بـ {{ $service->name }}" /><div class="projects-grid mt-9">@forelse($service->projects as $project)<x-project-card :project="$project" />@empty<div class="empty-state"><p>لا توجد مشاريع حقيقية منشورة لهذه الخدمة بعد.</p></div>@endforelse</div></div></section>

<section class="section-block coverage-section"><div class="container-shell coverage-layout"><div class="riyadh-grid" aria-hidden="true"><span>وسط</span><span>شمال</span><span>شرق</span><span>غرب</span><span>جنوب</span><strong>الرياض</strong></div><div><x-section-heading eyebrow="المناطق المخدومة" title="{{ $service->name }} في أحياء الرياض" description="اختر المنطقة للاطلاع على المشاريع المرتبطة بها عند توفرها." /><div class="area-chips mt-7">@foreach($areas as $area)<a href="{{ route('areas.show',$area->slug) }}">{{ $area->name }}</a>@endforeach</div></div></div></section>

<x-faqs :faqs="$service->faqs" />

<section class="section-block related-content"><div class="container-shell"><div class="related-columns"><section><x-section-heading eyebrow="خدمات مرتبطة" title="خيارات قريبة" /> <div class="cards-grid mt-8">@forelse($related as $item)<x-service-card :service="$item" />@empty<p class="empty-inline">لا توجد خدمات مرتبطة منشورة.</p>@endforelse</div></section><section><x-section-heading eyebrow="من الدليل" title="مقالات مرتبطة" /><div class="articles-grid mt-8">@forelse($service->articles as $article)<x-article-card :article="$article" />@empty<p class="empty-inline">لا توجد مقالات مرتبطة بعد.</p>@endforelse</div></section></div></div></section>

<section class="cta-band premium-cta"><div class="container-shell"><div><p class="eyebrow eyebrow-light">{{ $service->name }}</p><h2>اطلب معاينة لموقعك داخل الرياض</h2><p>المعاينة والمقاسات هما الأساس لعرض واضح ومناسب للموقع.</p></div><div><a class="button button-accent" href="{{ route('quote',['service'=>$service->id]) }}">{{ $siteSettings['inspection_cta_label'] ?? 'اطلب معاينة مجانية' }}</a><a class="button button-ghost" href="{{ $siteSettings['whatsapp_url'] }}" target="_blank" rel="noopener">واتساب</a><a class="button button-ghost" href="{{ $siteSettings['phone_tel'] }}">اتصال</a></div></div></section>
@endsection

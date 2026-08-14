@extends('layouts.app')
@section('content')
<x-page-hero eyebrow="خبرة ميدانية منذ 1999" title="من نحن" description="خبرة طويلة في السواتر والمظلات والإنشاءات الخارجية، أساسها فهم الموقع وجودة التنفيذ والالتزام مع العميل." />

<section class="about-story">
    <div class="container-shell">
        <figure class="about-story-image">
            <img src="{{ asset('storage/'.($siteSettings['about_image'] ?? config('site.about_image'))) }}" alt="خبرة مظلات وسواتر الرياض منذ 1999" width="1448" height="1086" loading="eager" decoding="async">
            <figcaption><strong>منذ 1999</strong><span>خبرة في تنفيذ السواتر والمظلات بمدينة الرياض</span></figcaption>
        </figure>

        <div class="about-story-grid">
            <article class="about-story-copy">
                <p class="eyebrow">خبرة تُرى في تفاصيل العمل</p>
                <h2>نعرف المهنة لأننا نمارسها منذ أكثر من ربع قرن</h2>
                <p>بدأ عملنا في السواتر عام 1999، ثم توسعت خبرتنا لتشمل المظلات والخيام وبيوت الشعر والهناجر والبرجولات والشترات والأبواب الكهربائية. هذه السنوات من العمل الميداني علّمتنا أن نجاح المشروع يبدأ من المعاينة الصحيحة واختيار الخامة وطريقة التثبيت المناسبة للموقع.</p>
                <p>نفذنا أعمالًا لمشاريع حكومية ولشركات كبيرة، إلى جانب مشاريع المنازل والمنشآت الخاصة. ونتعامل مع كل مشروع بالمنهج نفسه: فهم الاحتياج، قياس واضح، اتفاق على التفاصيل، ثم تنفيذ منظم ومتابعة حتى التسليم.</p>
            </article>

            <aside class="about-contact-card">
                <span>تواصل مباشرة</span>
                <h2>نسمع احتياجك ونرتب المعاينة</h2>
                <a href="{{ $siteSettings['phone_tel'] }}" dir="ltr">{{ $siteSettings['phone_display'] }}</a>
                <p>اتصل أو أرسل صورة الموقع والمقاسات التقريبية عبر واتساب، وسنساعدك على تحديد الخطوة التالية بوضوح.</p>
                <div><a class="button button-primary" href="{{ route('quote') }}">طلب معاينة</a><a class="button button-outline" href="{{ $siteSettings['whatsapp_url'] }}" target="_blank" rel="noopener">واتساب</a></div>
            </aside>
        </div>

        <ol class="about-principles" aria-label="طريقة عملنا">
            <li><span>01</span><div><h3>نفهم الموقع</h3><p>نراجع الاستخدام والمساحة والعوائق قبل ترشيح الحل.</p></div></li>
            <li><span>02</span><div><h3>نوضح التفاصيل</h3><p>نحدد الخامة والمقاسات ونطاق العمل قبل بدء التنفيذ.</p></div></li>
            <li><span>03</span><div><h3>ننفذ ونتابع</h3><p>تنظيم مراحل العمل ومراجعة النتيجة حتى التسليم.</p></div></li>
        </ol>
    </div>
</section>
@endsection

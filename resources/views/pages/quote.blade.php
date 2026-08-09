@extends('layouts.app')
@section('content')
<x-page-hero eyebrow="طلب منظم" title="طلب معاينة وعرض سعر" description="أرسل بيانات التواصل والموقع ونوع الخدمة. لا يتضمن الإرسال موافقة تلقائية على سعر أو موعد." />
<section class="section-block"><div class="container-shell form-layout"><div><x-lead-form :services="$services" type="quote" :default-message="$estimateMessage" /></div><aside class="contact-panel"><p class="eyebrow eyebrow-light">تواصل مباشر</p><h2>تفضل الاتصال أو واتساب؟</h2><p>استخدم القناة الأنسب لك لشرح موقع المشروع داخل الرياض.</p><a href="{{ $siteSettings['phone_tel'] }}"><small>اتصال</small><strong dir="ltr">{{ $siteSettings['phone_display'] }}</strong></a><a href="{{ $siteSettings['whatsapp_url'] }}" target="_blank" rel="noopener"><small>واتساب</small><strong>فتح المحادثة</strong></a><p class="panel-address">{{ $siteSettings['address'] }}</p></aside></div></section>
@endsection

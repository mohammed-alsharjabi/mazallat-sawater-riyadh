@extends('layouts.app')
@section('content')
<x-page-hero eyebrow="داخل الرياض" title="تواصل معنا" description="اتصل مباشرة، افتح محادثة واتساب، أو أرسل تفاصيل طلبك عبر النموذج." />
<section class="section-block"><div class="container-shell contact-options"><a href="{{ $siteSettings['phone_tel'] }}"><span>اتصال مباشر</span><strong dir="ltr">{{ $siteSettings['phone_display'] }}</strong><small>tel</small></a><a href="{{ $siteSettings['whatsapp_url'] }}" target="_blank" rel="noopener"><span>محادثة واتساب</span><strong>ابدأ المحادثة</strong><small>wa</small></a><div><span>العنوان</span><strong>{{ $siteSettings['address'] }}</strong><small>الرياض</small></div></div><div class="container-narrow mt-14"><x-section-heading title="أرسل رسالة" description="أدخل المعلومات الأساسية وسنحتفظ بها لغرض متابعة طلبك." /><div class="mt-8"><x-lead-form :services="$services" type="contact" /></div></div></section>
@endsection

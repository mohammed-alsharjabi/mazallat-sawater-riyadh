@props(['services'=>collect(),'type'=>'quote','defaultMessage'=>''])
@if(session('success'))<div class="notice-success" role="status">{{ session('success') }} @if(session('lead_whatsapp_url'))<a href="{{ session('lead_whatsapp_url') }}" target="_blank" rel="noopener">إرسال التفاصيل عبر واتساب</a>@endif</div>@endif
<form method="POST" action="{{ route('leads.store') }}" class="lead-form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="type" value="{{ $type }}">
    <div class="honeypot" aria-hidden="true"><label>الموقع <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
    <div class="field-grid">
        <label><span>الاسم <b>*</b></span><input type="text" name="name" value="{{ old('name') }}" required maxlength="100" autocomplete="name">@error('name')<small>{{ $message }}</small>@enderror</label>
        <label><span>رقم الجوال <b>*</b></span><input type="tel" name="phone" value="{{ old('phone') }}" required maxlength="25" dir="ltr" inputmode="tel" placeholder="05xxxxxxxx">@error('phone')<small>{{ $message }}</small>@enderror</label>
        <label><span>الحي أو المنطقة</span><input type="text" name="area" value="{{ old('area') }}" maxlength="120" placeholder="مثال: وسط الرياض">@error('area')<small>{{ $message }}</small>@enderror</label>
        <label><span>المساحة التقريبية بالمتر المربع</span><input type="number" name="area_size" value="{{ old('area_size') }}" min="1" max="1000000" step="0.01" inputmode="decimal" placeholder="مثال: 50">@error('area_size')<small>{{ $message }}</small>@enderror</label>
        <label class="full-field"><span>الخدمة المطلوبة</span><select name="service_id"><option value="">اختر الخدمة</option>@foreach($services as $service)<option value="{{ $service->id }}" @selected(old('service_id')==$service->id)>{{ $service->name }}</option>@endforeach</select>@error('service_id')<small>{{ $message }}</small>@enderror</label>
    </div>
    <fieldset><legend>وسيلة التواصل المفضلة</legend><div class="radio-row"><label><input type="radio" name="preferred_contact" value="phone" @checked(old('preferred_contact','phone')==='phone')> اتصال</label><label><input type="radio" name="preferred_contact" value="whatsapp" @checked(old('preferred_contact')==='whatsapp')> واتساب</label></div></fieldset>
    <label><span>تفاصيل الطلب</span><textarea name="message" rows="5" maxlength="2000" placeholder="اكتب نوع الموقع والمقاسات التقريبية إن توفرت">{{ old('message',$defaultMessage) }}</textarea>@error('message')<small>{{ $message }}</small>@enderror</label>
    <label class="upload-field"><span>صور الموقع <em>اختياري — حتى 5 صور</em></span><input type="file" name="site_images[]" accept="image/jpeg,image/png,image/webp" multiple><small class="field-hint">JPG أو PNG أو WebP، بحد أقصى 5 ميجابايت للصورة. تُحفظ الصور بشكل خاص ولا تظهر للعامة.</small>@error('site_images')<small>{{ $message }}</small>@enderror @error('site_images.*')<small>{{ $message }}</small>@enderror</label>
    <button class="button button-primary w-full sm:w-auto" type="submit">إرسال الطلب</button>
    <p class="form-privacy">بإرسال النموذج أنت توافق على <a href="{{ route('privacy') }}">سياسة الخصوصية</a>.</p>
</form>

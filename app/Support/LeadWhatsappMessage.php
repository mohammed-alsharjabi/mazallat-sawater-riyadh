<?php

namespace App\Support;

use App\Models\Lead;

class LeadWhatsappMessage
{
    public static function make(Lead $lead): string
    {
        $service = $lead->service?->name ?: 'غير محددة';
        $area = $lead->area ?: 'غير محددة';
        $size = $lead->area_size ? rtrim(rtrim((string) $lead->area_size, '0'), '.').' م² تقريبًا' : 'غير محددة';
        $source = $lead->source_url ?: url('/');

        return implode("\n", [
            'السلام عليكم، أرسلت طلب معاينة من موقع مظلات وسواتر الرياض.',
            "الخدمة: {$service}",
            "المنطقة: {$area}",
            "المساحة: {$size}",
            "رابط الصفحة: {$source}",
        ]);
    }

    public static function url(string $message): string
    {
        $phone = preg_replace('/\D+/', '', (string) app(SettingsRepository::class)->all()['phone_e164']);

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($message);
    }
}

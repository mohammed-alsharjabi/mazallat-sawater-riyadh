<?php

namespace App\Support;

use App\Models\Lead;

class LeadWhatsappMessage
{
    public static function make(Lead $lead): string
    {
        $size = $lead->area_size ? rtrim(rtrim((string) $lead->area_size, '0'), '.').' م² تقريبًا' : null;
        $source = $lead->source_url ?: url('/');
        $contactMethod = $lead->preferred_contact === 'phone' ? 'اتصال هاتفي' : 'واتساب';

        $lines = [
            '*طلب جديد من موقع مظلات وسواتر الرياض*',
            'رقم الطلب: #'.$lead->id,
        ];

        if ($lead->name) {
            $lines[] = 'الاسم: '.$lead->name;
        }

        $lines[] = 'رقم الجوال: '.$lead->phone;

        if ($lead->service?->name) {
            $lines[] = 'الخدمة: '.$lead->service->name;
        }

        if ($lead->area) {
            $lines[] = 'الحي أو المنطقة: '.$lead->area;
        }

        if ($size) {
            $lines[] = 'المساحة: '.$size;
        }

        $lines[] = 'التواصل المفضل: '.$contactMethod;

        if ($lead->message) {
            $lines[] = "\n*تفاصيل الطلب:*\n".$lead->message;
        }

        if (($lead->images_count ?? 0) > 0) {
            $lines[] = 'الصور المرفوعة: '.$lead->images_count.' (محفوظة في لوحة التحكم)';
        }

        $lines[] = "\nرابط الصفحة: ".$source;

        return implode("\n", $lines);
    }

    public static function url(string $message): string
    {
        $phone = preg_replace('/\D+/', '', (string) app(SettingsRepository::class)->all()['phone_e164']);

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($message);
    }
}

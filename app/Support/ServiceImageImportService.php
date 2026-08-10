<?php

namespace App\Support;

use App\Jobs\ProcessServiceImage;
use App\Models\Service;
use App\Models\ServiceImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ServiceImageImportService
{
    public function __construct(private ServiceImageProcessor $processor) {}

    public function inspect(string $absolutePath): array
    {
        $size = filesize($absolutePath);
        $info = @getimagesize($absolutePath);
        $mime = $info['mime'] ?? null;
        $detectedMime = (new \finfo(FILEINFO_MIME_TYPE))->file($absolutePath);
        if (! $size || ! $info || ! in_array($mime, ['image/jpeg', 'image/png', 'image/webp', 'image/avif'], true) || $detectedMime !== $mime) {
            throw new RuntimeException('الملف ليس صورة صالحة من نوع JPEG أو PNG أو WebP أو AVIF.');
        }
        $width = (int) $info[0];
        $height = (int) $info[1];
        if ($width > (int) config('service-images.maximum_dimension') || $height > (int) config('service-images.maximum_dimension') || $width * $height > (int) config('service-images.maximum_pixels')) {
            throw new RuntimeException('أبعاد الصورة أو عدد بكسلاتها يتجاوز الحد الآمن للمعالجة.');
        }

        return [
            'hash' => hash_file('sha256', $absolutePath),
            'width' => $width,
            'height' => $height,
            'size' => (int) $size,
            'mime' => $mime,
        ];
    }

    public function ingest(
        Service $service,
        string $absolutePath,
        string $originalName,
        ?string $sourceFolder = null,
        ?string $stem = null,
        ?string $context = null,
        bool $queue = false,
    ): array {
        $details = $this->inspect($absolutePath);
        $duplicate = ServiceImage::withTrashed()->where('content_hash', $details['hash'])->first();
        if ($duplicate) {
            return ['status' => 'duplicate', 'image' => $duplicate, 'details' => $details];
        }

        $stem ??= config('service-images.service_stems.'.$service->name, 'service-'.$service->id.'-riyadh');
        $extension = match ($details['mime']) {
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/avif' => 'avif',
            default => 'jpg',
        };
        $originalPath = 'service-images/originals/'.$service->id.'/'.$details['hash'].'.'.$extension;
        if (! Storage::disk('local')->put($originalPath, file_get_contents($absolutePath))) {
            throw new RuntimeException('تعذر حفظ النسخة الأصلية الخاصة داخل storage.');
        }

        $image = DB::transaction(function () use ($service, $originalName, $sourceFolder, $stem, $context, $details, $originalPath, $queue): ServiceImage {
            $sequence = ((int) ServiceImage::withTrashed()->where('service_id', $service->id)->max('sort_order')) + 1;
            $baseName = $stem.'-'.str_pad((string) $sequence, 2, '0', STR_PAD_LEFT).'.webp';
            $context ??= $this->defaultContext($service);

            return $service->images()->create([
                'original_name' => $originalName,
                'file_name' => $baseName,
                'source_folder' => $sourceFolder,
                'original_path' => $originalPath,
                'content_hash' => $details['hash'],
                'title' => $context.' في الرياض',
                'alt_text' => $context.' كما يظهر في موقع العمل في الرياض',
                'caption' => 'صورة ضمن معرض '.$service->name.' وتوضح '.$context.' في الرياض.',
                'original_width' => $details['width'],
                'original_height' => $details['height'],
                'original_file_size' => $details['size'],
                'sort_order' => $sequence,
                'is_cover' => ! ServiceImage::where('service_id', $service->id)->exists(),
                'processing_status' => $queue ? 'queued' : 'pending',
                'quality_status' => 'pending',
            ]);
        });

        if ($queue) {
            ProcessServiceImage::dispatch($image->id);
        } else {
            $image = $this->processor->process($image);
        }

        return ['status' => $queue ? 'queued' : 'processed', 'image' => $image, 'details' => $details];
    }

    public function reprocess(ServiceImage $image, bool $queue = false): ServiceImage
    {
        $this->processor->removeDerivatives($image);
        if ($queue) {
            $image->update(['processing_status' => 'queued']);
            ProcessServiceImage::dispatch($image->id);

            return $image->refresh();
        }

        return $this->processor->process($image);
    }

    private function defaultContext(Service $service): string
    {
        return match ($service->name) {
            'مظلات سيارات PVC' => 'مظلة سيارات PVC بغطاء قماشي وهيكل معدني',
            'مظلات شد إنشائي' => 'مظلة شد إنشائي بقماش مشدود',
            'هناجر حديد' => 'هيكل هنجر حديد أثناء التنفيذ',
            'شبوك وأسوار' => 'سياج معدني وشبك حماية خارجي',
            'غرف ساندوتش بانل' => 'غرفة خارجية مصنوعة من ألواح ساندوتش بانل',
            'جلسات شتوية زجاجية' => 'جلسة شتوية خارجية بواجهات زجاجية',
            'برجولات حديد' => 'برجولة خارجية بهيكل حديد',
            default => 'تفاصيل تنفيذ '.$service->name,
        };
    }
}

<?php

namespace App\Support;

use App\Models\Service;
use App\Models\ServiceImage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class CuratedServiceAssetImporter
{
    public function __construct(private ServiceImageImportService $images) {}

    public function import(string $source, bool $sync = false, bool $publish = false, bool $queue = false): array
    {
        $source = rtrim(realpath($source) ?: $source, DIRECTORY_SEPARATOR);
        if (! is_dir($source)) {
            throw new RuntimeException('مجلد الصور غير موجود: '.$source);
        }

        $manifest = [];
        $counts = ['processed' => 0, 'queued' => 0, 'duplicate' => 0, 'excluded' => 0, 'failed' => 0];
        $serviceRows = [];

        foreach (config('service-images.curated_folders', []) as $mappingKey => $mapping) {
            $folder = $mapping['folder'] ?? $mappingKey;
            $service = Service::query()->where('name', $mapping['service'])->first();
            if (! $service) {
                throw new RuntimeException('الخدمة غير موجودة في قاعدة البيانات: '.$mapping['service']);
            }

            if ($publish && in_array($service->name, config('site.launch_services', []), true)) {
                $service->update([
                    'status' => 'published',
                    'is_active' => true,
                    'is_featured' => true,
                    'published_at' => $service->published_at ?: now(),
                ]);
            }

            $files = $this->filesFor($source, $folder, $mapping);
            $expected = (int) $mapping['expected'];
            if (count($files) !== $expected) {
                throw new RuntimeException(sprintf('الخدمة %s: المتوقع %d صورة لكن الاختيار الآمن أعاد %d.', $service->name, $expected, count($files)));
            }

            $keptHashes = [];
            $coverImageId = null;
            $duplicateFileNames = ServiceImage::query()
                ->where('service_id', $service->id)
                ->whereNotNull('file_name')
                ->selectRaw('file_name, COUNT(*) AS aggregate')
                ->groupBy('file_name')
                ->havingRaw('COUNT(*) > 1')
                ->pluck('file_name')
                ->all();
            foreach ($files as $index => $file) {
                try {
                    $sequence = $index + 1;
                    $expectedName = $mapping['stem'].'-'.str_pad((string) $sequence, 2, '0', STR_PAD_LEFT).'.webp';
                    $visualContexts = config('service-images.visual_contexts', []);
                    $context = $mapping['contexts'][basename($file)]
                        ?? ($visualContexts[$folder][basename($file)] ?? null)
                        ?? $mapping['context'];
                    $details = $this->images->inspect($file);
                    $relativeSource = ltrim(str_replace($source, '', $file), DIRECTORY_SEPARATOR);
                    $result = $this->images->ingest(
                        $service,
                        $file,
                        basename($file),
                        dirname(str_replace('\\', '/', $relativeSource)),
                        $mapping['stem'],
                        $context,
                        $queue,
                        true,
                        $sequence,
                    );

                    /** @var ServiceImage $image */
                    $image = $result['image'];
                    if ($image->trashed()) {
                        $image->restore();
                    }
                    $mustNormalizeDerivatives = $image->file_name !== $expectedName
                        || in_array($image->file_name, $duplicateFileNames, true)
                        || ! Storage::disk('public')->exists('service-images/'.$service->id.'/'.$expectedName);
                    if ($mustNormalizeDerivatives) {
                        $image->forceFill(['file_name' => $expectedName])->save();
                        $image = $this->images->reprocess($image, $queue);
                        $result['status'] = $queue ? 'queued' : 'processed';
                    }
                    $title = $context.' في الرياض — صورة '.str_pad((string) $sequence, 2, '0', STR_PAD_LEFT);
                    $isCover = isset($mapping['cover'])
                        ? basename($file) === $mapping['cover']
                        : $sequence === 1;
                    $image->update([
                        'sort_order' => $sequence,
                        'is_cover' => $isCover,
                        'source_folder' => dirname(str_replace('\\', '/', $relativeSource)),
                        'original_name' => basename($file),
                        'title' => $title,
                        'alt_text' => $context.' في موقع التنفيذ في الرياض',
                        'caption' => 'صورة حقيقية من أعمال '.$service->name.' في الرياض.',
                    ]);
                    if ($isCover) {
                        $coverImageId = $image->id;
                    }
                    $keptHashes[] = $details['hash'];
                    $counts[$result['status']]++;
                    $manifest[] = $this->row($source, $file, $service, $image, $details, $result['status']);
                } catch (\Throwable $exception) {
                    $counts['failed']++;
                    $manifest[] = [
                        'original_folder' => $folder,
                        'linked_service' => $service->name,
                        'old_name' => basename($file),
                        'processing_status' => 'failed',
                        'processing_notes' => $exception->getMessage(),
                    ];
                }
            }

            if ($sync && $counts['failed'] === 0) {
                ServiceImage::query()
                    ->where('service_id', $service->id)
                    ->whereNotIn('content_hash', $keptHashes)
                    ->get()
                    ->each->delete();
            }

            if (! $coverImageId) {
                throw new RuntimeException('لم تُعثر صورة الغلاف المحددة لخدمة '.$service->name.'.');
            }
            ServiceImage::query()->where('service_id', $service->id)->whereKeyNot($coverImageId)->update(['is_cover' => false]);
            ServiceImage::query()->whereKey($coverImageId)->update(['is_cover' => true]);
            $imported = ServiceImage::query()->where('service_id', $service->id)->count();
            $uniqueNames = ServiceImage::query()->where('service_id', $service->id)->distinct()->count('file_name');
            if ($imported !== $expected || $uniqueNames !== $expected) {
                throw new RuntimeException(sprintf('الخدمة %s: تعذر تثبيت %d اسم صورة فريد (المستوردة %d، الفريدة %d).', $service->name, $expected, $imported, $uniqueNames));
            }
            $serviceRows[] = ['service' => $service->name, 'expected' => $expected, 'imported' => $imported];
        }

        if ($sync && $counts['failed'] === 0) {
            ServiceImage::query()
                ->whereHas('service', fn ($query) => $query->whereIn('name', config('service-images.retired_services', [])))
                ->get()
                ->each->delete();
        }

        $used = collect($manifest)->pluck('source_path')->filter()->all();
        $usedHashes = collect($manifest)
            ->whereIn('processing_status', ['processed', 'queued', 'duplicate'])
            ->pluck('source_hash')
            ->filter()
            ->all();
        foreach ($this->allImages($source) as $file) {
            $relative = ltrim(str_replace($source, '', $file), DIRECTORY_SEPARATOR);
            if (! in_array($relative, $used, true)) {
                $hash = hash_file('sha256', $file);
                $isDuplicate = in_array($hash, $usedHashes, true);
                $counts[$isDuplicate ? 'duplicate' : 'excluded']++;
                $manifest[] = [
                    'source_path' => $relative,
                    'original_folder' => dirname(str_replace('\\', '/', $relative)),
                    'linked_service' => null,
                    'old_name' => basename($file),
                    'processing_status' => $isDuplicate ? 'duplicate_source' : 'excluded',
                    'processing_notes' => $isDuplicate
                        ? 'نسخة مطابقة بالـ Hash لصورة مستوردة؛ لم تُكرر داخل المعرض.'
                        : 'ملف زائد عن العدد المؤكد أو لا يثبت الخدمة بصريًا؛ لم يُنشر.',
                ];
            }
        }

        $payload = [
            'source_directory' => $source,
            'generated_at' => now()->toAtomString(),
            'counts' => $counts,
            'services' => $serviceRows,
            'items' => $manifest,
        ];
        $manifestPath = 'service-image-imports/curated-assets-'.now()->format('Ymd-His').'.json';
        Storage::disk('local')->put($manifestPath, json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        $payload['manifest_path'] = storage_path('app/private/'.$manifestPath);

        return $payload;
    }

    private function filesFor(string $source, string $folder, array $mapping): array
    {
        $folderPath = $source.DIRECTORY_SEPARATOR.$folder;
        if (! is_dir($folderPath)) {
            throw new RuntimeException('المجلد المطلوب غير موجود: '.$folderPath);
        }

        $preferredSubdirectory = trim((string) ($mapping['source_subdirectory'] ?? ''), '/\\');
        $searchPath = $preferredSubdirectory !== '' && is_dir($folderPath.DIRECTORY_SEPARATOR.$preferredSubdirectory)
            ? $folderPath.DIRECTORY_SEPARATOR.$preferredSubdirectory
            : $folderPath;
        $extensions = array_map('mb_strtolower', $mapping['extensions'] ?? ['jpg', 'jpeg', 'png', 'webp', 'avif']);
        $excluded = $mapping['exclude'] ?? [];
        $selected = $mapping['files'] ?? null;
        $files = array_values(array_filter(
            $this->allImages($searchPath),
            fn (string $path): bool => in_array(mb_strtolower(pathinfo($path, PATHINFO_EXTENSION)), $extensions, true)
                && ! in_array(basename($path), $excluded, true)
                && ($selected === null || in_array(basename($path), $selected, true)),
        ));
        if ($selected !== null) {
            $missing = array_values(array_diff($selected, array_map('basename', $files)));
            if ($missing !== []) {
                throw new RuntimeException('ملفات الاختيار الموثق غير موجودة في '.$folder.': '.implode('، ', $missing));
            }
        }
        foreach ($mapping['include'] ?? [] as $relative) {
            $path = $source.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relative);
            if (! is_file($path)) {
                throw new RuntimeException('ملف الاستثناء البصري غير موجود: '.$relative);
            }
            $files[] = $path;
        }
        usort($files, fn (string $a, string $b): int => strnatcasecmp(basename($a), basename($b)));

        return array_values(array_unique($files));
    }

    private function allImages(string $directory): array
    {
        if (! is_dir($directory)) {
            return [];
        }

        $files = collect(File::allFiles($directory))
            ->filter(fn (\SplFileInfo $file): bool => in_array(mb_strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp', 'avif'], true))
            ->map(fn (\SplFileInfo $file): string => $file->getRealPath())
            ->values()
            ->all();
        usort($files, 'strnatcasecmp');

        return $files;
    }

    private function row(string $source, string $file, Service $service, ServiceImage $image, array $details, string $status): array
    {
        $relative = ltrim(str_replace($source, '', $file), DIRECTORY_SEPARATOR);

        return [
            'source_path' => $relative,
            'source_hash' => $details['hash'],
            'original_folder' => dirname(str_replace('\\', '/', $relative)),
            'linked_service' => $service->name,
            'old_name' => basename($file),
            'new_name' => $image->file_name,
            'title' => $image->title,
            'alt_text' => $image->alt_text,
            'caption' => $image->caption,
            'dimensions_before' => $details['width'].'x'.$details['height'],
            'dimensions_after' => $image->width.'x'.$image->height,
            'size_before' => $details['size'],
            'size_after' => $image->file_size,
            'processing_status' => $status,
            'processing_notes' => $image->processing_notes,
        ];
    }
}

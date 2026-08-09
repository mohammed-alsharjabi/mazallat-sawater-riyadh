<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use App\Support\SettingsRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class SettingsEditor extends Component
{
    use AuthorizesRequests;

    public array $values = [];

    public function mount(): void
    {
        $this->authorize('manage-content');
        $this->values = Setting::query()->pluck('value', 'key')->all();
    }

    public function save(): void
    {
        $this->authorize('manage-content');
        $allowed = Setting::query()->pluck('key')->all();
        $this->validate([
            'values' => ['array'], 'values.*' => ['nullable', 'string', 'max:5000'],
            'values.site_name' => ['required', 'string', 'max:120'],
            'values.phone_display' => ['required', 'string', 'max:30'],
            'values.phone_e164' => ['required', 'regex:/^\+9665\d{8}$/'],
            'values.phone_tel' => ['required', 'regex:/^tel:\+9665\d{8}$/'],
            'values.whatsapp_url' => ['required', 'regex:#^https://wa\.me/9665\d{8}$#'],
            'values.address' => ['required', 'string', 'max:255'],
            'values.city' => ['required', 'string', 'max:100'],
            'values.country' => ['required', 'string', 'max:100'],
            'values.search_console_verification' => ['nullable', 'regex:/^[A-Za-z0-9_-]{10,200}$/'],
            'values.ga_measurement_id' => ['nullable', 'regex:/^G-[A-Z0-9]{5,20}$/'],
            'values.logo_url' => ['nullable', 'url', 'max:2048'],
        ]);
        foreach ($this->values as $key => $value) {
            if (in_array($key, $allowed, true)) {
                Setting::query()->where('key', $key)->update(['value' => $value]);
            }
        }
        app(SettingsRepository::class)->clear();
        session()->flash('success', 'حُفظت الإعدادات.');
    }

    public function render()
    {
        return view('livewire.admin.settings-editor', ['groups' => Setting::query()->orderBy('group')->orderBy('id')->get()->groupBy('group')])
            ->layout('components.layouts.admin', ['title' => 'إعدادات النشاط']);
    }
}

<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Setting;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Illuminate\Validation\Rule;

class SettingForm extends Component
{
    public bool $isCreating = false;
    public ?string $key = null;
    public ?string $value = null;
    public string $label = '';

    public function rules()
    {
        return [
            'key' => [
                Rule::requiredIf($this->isCreating),
                'nullable',
                'string',
                'max:100',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('settings', 'key')->ignore($this->isCreating ? null : $this->key, 'key'),
            ],
            'value' => ['nullable', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'key.regex' => 'Use lowercase letters, numbers, and underscores only (e.g. currency_symbol).',
        ];
    }

    #[On('create-setting')]
    public function create(): void
    {
        $this->resetValidation();
        $this->reset(['key', 'value', 'label']);
        $this->isCreating = true;
        $this->dispatch('open-modal', name: 'setting-form-modal');
    }

    #[On('edit-setting')]
    public function edit($key): void
    {
        $this->resetValidation();
        $setting = Setting::findOrFail($key);

        $this->isCreating = false;
        $this->key = $setting->key;
        $this->value = $setting->value;
        $this->label = Str::title(str_replace('_', ' ', $setting->key));

        $this->dispatch('open-modal', name: 'setting-form-modal');
    }

    #[On('load-default-settings')]
    public function loadDefaults(): void
    {
        $defaults = [
            'store_name' => 'My Store',
            'store_address' => 'Store Address',
            'store_phone' => '-',
            'opening_balance_date' => now()->startOfYear()->toDateString(),
            'opening_balance_amount' => '0',
            'currency_symbol' => '$',
            'currency_position' => 'left',
            'currency_fraction_digits' => '2',
            'currency_thousand_separator' => ',',
            'currency_decimal_separator' => '.',
        ];

        foreach ($defaults as $key => $value) {
            if (! Setting::query()->where('key', $key)->exists()) {
                Setting::set($key, $value);
            }
        }

        $this->dispatch('pg:eventRefresh-setting-table');
        $this->dispatch('toast', message: 'Default settings loaded. Edit any value as needed.', type: 'success');
    }

    public function save(): void
    {
        $this->validate();

        try {
            $key = $this->isCreating
                ? Str::snake(trim((string) $this->key))
                : $this->key;

            Setting::set($key, $this->value);

            $this->dispatch('close-modal', name: 'setting-form-modal');
            $this->dispatch('pg:eventRefresh-setting-table');
            $this->dispatch(
                'toast',
                message: $this->isCreating ? 'Setting created successfully.' : 'Setting updated successfully.',
                type: 'success'
            );
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Failed to save setting: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.settings.setting-form');
    }
}

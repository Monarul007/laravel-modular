<?php

namespace App\Core;

use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

class SettingsManager
{
    protected array $settings = [];

    public function get(string $key, mixed $default = null): mixed
    {
        if (isset($this->settings[$key])) {
            return $this->settings[$key];
        }

        $value = Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = Setting::where('key', $key)->first();
            return $setting ? $this->castValue($setting->value, $setting->type) : $default;
        });

        $this->settings[$key] = $value;
        return $value;
    }

    public function set(string $key, mixed $value, string $group = 'general'): void
    {
        $type = $this->getValueType($value);
        
        Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $this->prepareValue($value, $type),
                'type' => $type,
                'group' => $group
            ]
        );

        $this->settings[$key] = $value;
        Cache::forget("setting.{$key}");
    }

    public function getGroup(string $group): array
    {
        return Cache::remember("settings.group.{$group}", 3600, function () use ($group) {
            $settings = Setting::where('group', $group)->get();
            $result = [];
            
            foreach ($settings as $setting) {
                $result[$setting->key] = $this->castValue($setting->value, $setting->type);
            }
            
            return $result;
        });
    }

    public function forget(string $key): void
    {
        Setting::where('key', $key)->delete();
        unset($this->settings[$key]);
        Cache::forget("setting.{$key}");
    }

    protected function getValueType(mixed $value): string
    {
        return match (true) {
            is_bool($value) => 'boolean',
            is_int($value) => 'integer',
            is_float($value) => 'float',
            is_array($value) => 'array',
            default => 'string'
        };
    }

    protected function prepareValue(mixed $value, string $type): string
    {
        return match ($type) {
            'boolean' => $value ? '1' : '0',
            'array' => json_encode($value),
            default => (string) $value
        };
    }

    protected function castValue(string $value, string $type): mixed
    {
        return match ($type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'float' => (float) $value,
            'array' => json_decode($value, true),
            default => $value
        };
    }
}
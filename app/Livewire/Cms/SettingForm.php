<?php

namespace App\Livewire\Cms;

use App\Models\Setting;
use Livewire\Attributes\Validate;
use Livewire\Component;

class SettingForm extends Component
{
    #[Validate('nullable|string|max:150')]
    public string $company_name = '';

    #[Validate('nullable|string|max:255')]
    public string $tagline = '';

    #[Validate('nullable|string|max:255')]
    public string $logo_url = '';

    #[Validate('nullable|string|max:255')]
    public string $favicon_url = '';

    #[Validate('required|in:corporate,creative,minimal')]
    public string $active_template = 'corporate';

    #[Validate('nullable|string|max:20')]
    public string $primary_color = '';

    #[Validate('nullable|string|max:20')]
    public string $secondary_color = '';

    #[Validate('nullable|email|max:150')]
    public string $contact_email = '';

    #[Validate('nullable|string|max:30')]
    public string $whatsapp_number = '';

    #[Validate('nullable|string')]
    public string $whatsapp_default_message = '';

    #[Validate('nullable|string|max:30')]
    public string $contact_phone = '';

    #[Validate('nullable|string')]
    public string $contact_address = '';

    #[Validate('nullable|string')]
    public string $maps_embed_url = '';

    #[Validate('nullable|string|max:150')]
    public string $operational_hours = '';

    #[Validate('nullable|string|max:100')]
    public string $ppiu_license = '';

    #[Validate('nullable|string|max:100')]
    public string $pihk_license = '';

    #[Validate('nullable|string|max:255')]
    public string $footer_copyright = '';

    /** @var array<int, array{platform: string, url: string}> */
    public array $socialLinks = [];

    public function mount(): void
    {
        $setting = Setting::current();

        $this->company_name = (string) $setting->company_name;
        $this->tagline = (string) $setting->tagline;
        $this->logo_url = (string) $setting->logo_url;
        $this->favicon_url = (string) $setting->favicon_url;
        $this->active_template = $setting->active_template ?? 'corporate';
        $this->primary_color = (string) $setting->primary_color;
        $this->secondary_color = (string) $setting->secondary_color;
        $this->contact_email = (string) $setting->contact_email;
        $this->whatsapp_number = (string) $setting->whatsapp_number;
        $this->whatsapp_default_message = (string) $setting->whatsapp_default_message;
        $this->contact_phone = (string) $setting->contact_phone;
        $this->contact_address = (string) $setting->contact_address;
        $this->maps_embed_url = (string) $setting->maps_embed_url;
        $this->operational_hours = (string) $setting->operational_hours;
        $this->ppiu_license = (string) $setting->ppiu_license;
        $this->pihk_license = (string) $setting->pihk_license;
        $this->footer_copyright = (string) $setting->footer_copyright;
        $this->socialLinks = $setting->social_links?->map(fn ($link) => [
            'platform' => $link['platform'] ?? '',
            'url' => $link['url'] ?? '',
        ])->all() ?? [];
    }

    public function addSocialLink(): void
    {
        $this->socialLinks[] = ['platform' => '', 'url' => ''];
    }

    public function removeSocialLink(int $index): void
    {
        unset($this->socialLinks[$index]);
        $this->socialLinks = array_values($this->socialLinks);
    }

    public function save(): void
    {
        $this->validate();

        Setting::current()->update([
            'company_name' => $this->company_name ?: null,
            'tagline' => $this->tagline ?: null,
            'logo_url' => $this->logo_url ?: null,
            'favicon_url' => $this->favicon_url ?: null,
            'active_template' => $this->active_template,
            'primary_color' => $this->primary_color ?: null,
            'secondary_color' => $this->secondary_color ?: null,
            'contact_email' => $this->contact_email ?: null,
            'whatsapp_number' => $this->whatsapp_number ?: null,
            'whatsapp_default_message' => $this->whatsapp_default_message ?: null,
            'contact_phone' => $this->contact_phone ?: null,
            'contact_address' => $this->contact_address ?: null,
            'maps_embed_url' => $this->maps_embed_url ?: null,
            'operational_hours' => $this->operational_hours ?: null,
            'ppiu_license' => $this->ppiu_license ?: null,
            'pihk_license' => $this->pihk_license ?: null,
            'footer_copyright' => $this->footer_copyright ?: null,
            'social_links' => array_values(array_filter($this->socialLinks, fn ($l) => trim($l['url'] ?? '') !== '')),
        ]);

        session()->flash('status', 'Pengaturan berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.cms.setting-form');
    }
}

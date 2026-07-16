<?php

namespace App\Livewire\Cms;

use App\Models\Testimonial;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class TestimonialManager extends Component
{
    use WithPagination;

    public bool $showForm = false;
    public ?int $editingId = null;

    #[Validate('required|string|max:150')]
    public string $jamaah_name = '';

    #[Validate('nullable|string|max:150')]
    public string $jamaah_city = '';

    #[Validate('nullable|string|max:255')]
    public string $jamaah_photo_url = '';

    #[Validate('nullable|in:umroh,haji,wisata_religi')]
    public string $package_type = '';

    #[Validate('nullable|integer|min:2000|max:2100')]
    public ?string $year = null;

    #[Validate('required|integer|min:1|max:5')]
    public int $rating = 5;

    #[Validate('required|string|max:2000')]
    public string $content = '';

    public bool $is_published = false;

    public function create(): void
    {
        $this->reset(['jamaah_name', 'jamaah_city', 'jamaah_photo_url', 'package_type', 'year', 'content', 'is_published', 'editingId']);
        $this->rating = 5;
        $this->showForm = true;
    }

    public function edit(int $testimonialId): void
    {
        $testimonial = Testimonial::query()->findOrFail($testimonialId);
        $this->editingId = $testimonial->id;
        $this->jamaah_name = $testimonial->jamaah_name;
        $this->jamaah_city = (string) $testimonial->jamaah_city;
        $this->jamaah_photo_url = (string) $testimonial->jamaah_photo_url;
        $this->package_type = (string) $testimonial->package_type;
        $this->year = $testimonial->year !== null ? (string) $testimonial->year : null;
        $this->rating = $testimonial->rating;
        $this->content = $testimonial->content;
        $this->is_published = $testimonial->is_published;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $payload = [
            'jamaah_name' => $this->jamaah_name,
            'jamaah_city' => $this->jamaah_city ?: null,
            'jamaah_photo_url' => $this->jamaah_photo_url ?: null,
            'package_type' => $this->package_type ?: null,
            'year' => $this->year !== null && $this->year !== '' ? $this->year : null,
            'rating' => $this->rating,
            'content' => $this->content,
            'is_published' => $this->is_published,
        ];

        if ($this->editingId) {
            Testimonial::query()->findOrFail($this->editingId)->update($payload);
        } else {
            $payload['order'] = (int) (Testimonial::query()->max('order') ?? 0) + 1;
            Testimonial::query()->create($payload);
        }

        $this->showForm = false;
        $this->reset(['jamaah_name', 'jamaah_city', 'jamaah_photo_url', 'package_type', 'year', 'content', 'is_published', 'editingId']);
    }

    public function togglePublish(int $testimonialId): void
    {
        $testimonial = Testimonial::query()->findOrFail($testimonialId);
        $testimonial->update(['is_published' => !$testimonial->is_published]);
    }

    public function delete(int $testimonialId): void
    {
        Testimonial::query()->findOrFail($testimonialId)->delete();
    }

    public function render()
    {
        return view('livewire.cms.testimonial-manager', [
            'testimonials' => Testimonial::query()->orderBy('order')->paginate(10),
        ]);
    }
}

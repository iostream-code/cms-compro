<?php

namespace App\Livewire\Cms;

use App\Models\Package;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PackageForm extends Component
{
    public ?Package $package = null;

    #[Validate('required|in:umroh,haji,wisata_religi')]
    public string $type = 'umroh';

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string|max:255')]
    public string $short_description = '';

    #[Validate('nullable|string')]
    public string $description = '';

    #[Validate('nullable|string|max:255')]
    public string $duration = '';

    #[Validate('nullable|numeric|min:0')]
    public ?string $price_from = null;

    #[Validate('required|string|size:3')]
    public string $price_currency = 'IDR';

    #[Validate('nullable|string|max:255')]
    public string $departure_city = '';

    #[Validate('nullable|string|max:255')]
    public string $departure_airport = '';

    #[Validate('nullable|date')]
    public ?string $departure_date = null;

    #[Validate('nullable|string|max:255')]
    public string $departure_date_note = '';

    #[Validate('nullable|integer|min:0')]
    public ?string $seats_total = null;

    #[Validate('nullable|integer|min:0')]
    public ?string $seats_available = null;

    #[Validate('nullable|string|max:255')]
    public string $airline = '';

    #[Validate('nullable|string|max:255')]
    public string $hotel_makkah = '';

    #[Validate('nullable|string|max:255')]
    public string $hotel_madinah = '';

    #[Validate('nullable|string')]
    public string $requirements = '';

    #[Validate('nullable|string')]
    public string $terms_conditions = '';

    #[Validate('nullable|string|max:255')]
    public string $brochure_url = '';

    #[Validate('nullable|string|max:255')]
    public string $image_url = '';

    public bool $is_published = false;

    /** @var array<int, string> */
    public array $facilities = [];

    /** @var array<int, array{day: string, title: string, description: string}> */
    public array $itinerary = [];

    /** @var array<int, array{label: string, price: string}> */
    public array $roomTypes = [];

    public function mount(?Package $package = null): void
    {
        if (!$package || !$package->exists) {
            return;
        }

        $this->package = $package;

        $this->type = $package->type;
        $this->name = $package->name;
        $this->short_description = (string) $package->short_description;
        $this->description = (string) $package->description;
        $this->duration = (string) $package->duration;
        $this->price_from = $package->price_from !== null ? (string) $package->price_from : null;
        $this->price_currency = $package->price_currency;
        $this->departure_city = (string) $package->departure_city;
        $this->departure_airport = (string) $package->departure_airport;
        $this->departure_date = $package->departure_date?->format('Y-m-d');
        $this->departure_date_note = (string) $package->departure_date_note;
        $this->seats_total = $package->seats_total !== null ? (string) $package->seats_total : null;
        $this->seats_available = $package->seats_available !== null ? (string) $package->seats_available : null;
        $this->airline = (string) $package->airline;
        $this->hotel_makkah = (string) $package->hotel_makkah;
        $this->hotel_madinah = (string) $package->hotel_madinah;
        $this->requirements = (string) $package->requirements;
        $this->terms_conditions = (string) $package->terms_conditions;
        $this->brochure_url = (string) $package->brochure_url;
        $this->image_url = (string) $package->image_url;
        $this->is_published = $package->is_published;

        $this->facilities = $package->facilities?->all() ?? [];
        $this->itinerary = $package->itinerary?->map(fn ($day) => [
            'day' => (string) ($day['day'] ?? ''),
            'title' => $day['title'] ?? '',
            'description' => $day['description'] ?? '',
        ])->all() ?? [];
        $this->roomTypes = $package->room_types?->map(fn ($room) => [
            'label' => $room['label'] ?? '',
            'price' => (string) ($room['price'] ?? ''),
        ])->all() ?? [];
    }

    public function addFacility(): void
    {
        $this->facilities[] = '';
    }

    public function removeFacility(int $index): void
    {
        unset($this->facilities[$index]);
        $this->facilities = array_values($this->facilities);
    }

    public function addItineraryDay(): void
    {
        $this->itinerary[] = ['day' => (string) (count($this->itinerary) + 1), 'title' => '', 'description' => ''];
    }

    public function removeItineraryDay(int $index): void
    {
        unset($this->itinerary[$index]);
        $this->itinerary = array_values($this->itinerary);
    }

    public function addRoomType(): void
    {
        $this->roomTypes[] = ['label' => '', 'price' => ''];
    }

    public function removeRoomType(int $index): void
    {
        unset($this->roomTypes[$index]);
        $this->roomTypes = array_values($this->roomTypes);
    }

    public function save(): void
    {
        $this->validate();

        $payload = [
            'type' => $this->type,
            'name' => $this->name,
            'short_description' => $this->short_description ?: null,
            'description' => $this->description ?: null,
            'duration' => $this->duration ?: null,
            'price_from' => $this->price_from !== '' ? $this->price_from : null,
            'price_currency' => $this->price_currency,
            'departure_city' => $this->departure_city ?: null,
            'departure_airport' => $this->departure_airport ?: null,
            'departure_date' => $this->departure_date ?: null,
            'departure_date_note' => $this->departure_date_note ?: null,
            'seats_total' => $this->seats_total !== '' ? $this->seats_total : null,
            'seats_available' => $this->seats_available !== '' ? $this->seats_available : null,
            'airline' => $this->airline ?: null,
            'hotel_makkah' => $this->hotel_makkah ?: null,
            'hotel_madinah' => $this->hotel_madinah ?: null,
            'requirements' => $this->requirements ?: null,
            'terms_conditions' => $this->terms_conditions ?: null,
            'brochure_url' => $this->brochure_url ?: null,
            'image_url' => $this->image_url ?: null,
            'is_published' => $this->is_published,
            'facilities' => array_values(array_filter($this->facilities, fn ($f) => trim($f) !== '')),
            'itinerary' => array_values(array_filter($this->itinerary, fn ($d) => trim($d['title'] ?? '') !== '')),
            'room_types' => array_values(array_filter($this->roomTypes, fn ($r) => trim($r['label'] ?? '') !== '')),
        ];

        if ($this->package) {
            $this->package->update($payload);
        } else {
            $payload['slug'] = $this->uniqueSlug($this->name);
            $payload['order'] = (int) (Package::query()->max('order') ?? 0) + 1;
            $payload['created_by'] = auth()->id();
            Package::query()->create($payload);
        }

        session()->flash('status', 'Paket berhasil disimpan.');

        $this->redirect(route('cms.packages.index'));
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $suffix = 1;

        while (Package::query()->where('slug', $slug)->exists()) {
            $slug = "{$base}-" . ++$suffix;
        }

        return $slug;
    }

    public function render()
    {
        return view('livewire.cms.package-form');
    }
}

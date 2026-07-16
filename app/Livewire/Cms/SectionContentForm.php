<?php

namespace App\Livewire\Cms;

use App\Models\Section;
use App\Models\SectionType;
use Livewire\Component;

class SectionContentForm extends Component
{
    public Section $section;

    /** @var array<string, mixed> */
    public array $data = [];

    public function mount(Section $section): void
    {
        $this->section = $section;
        $this->data = $section->content ?? [];
        $this->initializeDefaults($this->fields());
    }

    public function fields(): array
    {
        return $this->section->typeDefinition()?->schema['fields'] ?? [];
    }

    /**
     * Isi default kosong untuk field yang belum ada di content -- penting
     * khusus buat repeater supaya data_get() awal selalu array, bukan null.
     */
    private function initializeDefaults(array $fields): void
    {
        foreach ($fields as $field) {
            if (data_get($this->data, $field['key']) !== null) {
                continue;
            }

            $this->data[$field['key']] = match ($field['type']) {
                'repeater' => [],
                'boolean' => $field['default'] ?? false,
                default => $field['default'] ?? '',
            };
        }
    }

    public function addRepeaterItem(string $key): void
    {
        $items = data_get($this->data, $key, []);
        $items[] = [];
        data_set($this->data, $key, $items);
    }

    public function removeRepeaterItem(string $key, int $index): void
    {
        $items = data_get($this->data, $key, []);
        unset($items[$index]);
        data_set($this->data, $key, array_values($items));
    }

    public function save(): void
    {
        $this->section->update(['content' => $this->data]);

        session()->flash('status', 'Konten section berhasil disimpan.');

        $this->redirect(route('cms.sections.index', $this->section->page));
    }

    public function render()
    {
        return view('livewire.cms.section-content-form', [
            'fields' => $this->fields(),
        ]);
    }
}

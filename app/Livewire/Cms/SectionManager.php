<?php

namespace App\Livewire\Cms;

use App\Models\Page;
use App\Models\Section;
use App\Models\SectionType;
use Livewire\Component;

class SectionManager extends Component
{
    public Page $page;
    public bool $showTypePicker = false;

    /**
     * Dipanggil dari Blade lewat $wire.call('reorder', idsInNewOrder)
     * setelah SortableJS selesai drag -- lihat catatan di view.
     *
     * @param array<int, int> $orderedIds
     */
    public function reorder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $sectionId) {
            Section::query()
                ->where('id', $sectionId)
                ->where('page_id', $this->page->id) // pastikan section memang milik halaman ini
                ->update(['order' => $index + 1]);
        }
    }

    public function addSection(string $typeKey): void
    {
        $maxOrder = Section::query()->where('page_id', $this->page->id)->max('order') ?? 0;

        Section::query()->create([
            'page_id' => $this->page->id,
            'type' => $typeKey,
            'content' => [], // diisi di form section -- lihat SPRINT_PLAN.md Hari 3
            'order' => $maxOrder + 1,
            'is_visible' => true,
        ]);

        $this->showTypePicker = false;
    }

    public function toggleVisibility(int $sectionId): void
    {
        $section = Section::query()->where('page_id', $this->page->id)->findOrFail($sectionId);
        $section->update(['is_visible' => !$section->is_visible]);
    }

    public function removeSection(int $sectionId): void
    {
        Section::query()->where('page_id', $this->page->id)->findOrFail($sectionId)->delete();
    }

    public function render()
    {
        return view('livewire.cms.section-manager', [
            'sections' => $this->page->sections()->get(),
            'availableTypes' => SectionType::query()->where('is_active', true)->orderBy('order')->get(),
        ]);
    }
}

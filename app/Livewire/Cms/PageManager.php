<?php

namespace App\Livewire\Cms;

use App\Models\Page;
use App\Services\TenantDatabaseManager;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class PageManager extends Component
{
    use WithPagination;

    public bool $showForm = false;
    public ?string $editingId = null;

    #[Validate('required|string|max:150')]
    public string $title = '';

    #[Validate('required|string|max:150')]
    public string $slug = '';

    public string $search = '';

    public function updatedTitle(): void
    {
        // Auto-generate slug selama user belum ngedit slug secara manual
        if (!$this->editingId) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function create(): void
    {
        $this->reset(['title', 'slug', 'editingId']);
        $this->showForm = true;
    }

    public function edit(string $pageId): void
    {
        $page = Page::query()->findOrFail($pageId);
        $this->editingId = $page->id;
        $this->title = $page->title;
        $this->slug = $page->slug;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editingId) {
            $page = Page::query()->findOrFail($this->editingId);
            $page->update(['title' => $this->title, 'slug' => $this->slug]);
        } else {
            Page::query()->create([
                'title' => $this->title,
                'slug' => $this->slug,
                'order' => Page::query()->max('order') + 1,
            ]);
        }

        $this->showForm = false;
        $this->reset(['title', 'slug', 'editingId']);
    }

    public function togglePublish(string $pageId): void
    {
        $page = Page::query()->findOrFail($pageId);
        $page->update(['is_published' => !$page->is_published]);
    }

    public function delete(string $pageId): void
    {
        Page::query()->findOrFail($pageId)->delete(); // soft delete
    }

    public function render()
    {
        $pages = Page::query()
            ->when($this->search, fn ($q) => $q->where(
                'title',
                TenantDatabaseManager::caseInsensitiveLikeOperator(),
                "%{$this->search}%"
            ))
            ->orderBy('order')
            ->paginate(10);

        return view('livewire.cms.page-manager', ['pages' => $pages]);
    }
}

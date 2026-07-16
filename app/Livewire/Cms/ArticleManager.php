<?php

namespace App\Livewire\Cms;

use App\Models\Article;
use App\Services\TenantDatabaseManager;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class ArticleManager extends Component
{
    use WithPagination;

    public bool $showForm = false;
    public ?string $editingId = null;

    #[Validate('required|string|max:200')]
    public string $title = '';

    #[Validate('required|string|max:200')]
    public string $slug = '';

    #[Validate('nullable|string|max:255')]
    public string $excerpt = '';

    #[Validate('required|string')]
    public string $body = '';

    #[Validate('nullable|string|max:100')]
    public string $category = '';

    #[Validate('nullable|string|max:255')]
    public string $featured_image_url = '';

    public bool $is_published = false;

    public string $search = '';

    public function updatedTitle(): void
    {
        if (!$this->editingId) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function create(): void
    {
        $this->reset(['title', 'slug', 'excerpt', 'body', 'category', 'featured_image_url', 'is_published', 'editingId']);
        $this->showForm = true;
    }

    public function edit(string $articleId): void
    {
        $article = Article::query()->findOrFail($articleId);
        $this->editingId = $article->id;
        $this->title = $article->title;
        $this->slug = $article->slug;
        $this->excerpt = (string) $article->excerpt;
        $this->body = $article->body;
        $this->category = (string) $article->category;
        $this->featured_image_url = (string) $article->featured_image_url;
        $this->is_published = $article->is_published;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $payload = [
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt ?: null,
            'body' => $this->body,
            'category' => $this->category ?: null,
            'featured_image_url' => $this->featured_image_url ?: null,
            'is_published' => $this->is_published,
            'published_at' => $this->is_published ? now() : null,
        ];

        if ($this->editingId) {
            Article::query()->findOrFail($this->editingId)->update($payload);
        } else {
            $payload['created_by'] = auth()->id();
            Article::query()->create($payload);
        }

        $this->showForm = false;
        $this->reset(['title', 'slug', 'excerpt', 'body', 'category', 'featured_image_url', 'is_published', 'editingId']);
    }

    public function togglePublish(string $articleId): void
    {
        $article = Article::query()->findOrFail($articleId);
        $article->update([
            'is_published' => !$article->is_published,
            'published_at' => !$article->is_published ? now() : $article->published_at,
        ]);
    }

    public function delete(string $articleId): void
    {
        Article::query()->findOrFail($articleId)->delete(); // soft delete
    }

    public function render()
    {
        $articles = Article::query()
            ->when($this->search, fn ($q) => $q->where(
                'title',
                TenantDatabaseManager::caseInsensitiveLikeOperator(),
                "%{$this->search}%"
            ))
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.cms.article-manager', ['articles' => $articles]);
    }
}

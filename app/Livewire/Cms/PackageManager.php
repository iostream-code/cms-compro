<?php

namespace App\Livewire\Cms;

use App\Models\Package;
use App\Services\TenantDatabaseManager;
use Livewire\Component;
use Livewire\WithPagination;

class PackageManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $typeFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function togglePublish(string $packageId): void
    {
        $package = Package::query()->findOrFail($packageId);
        $package->update(['is_published' => !$package->is_published]);
    }

    public function delete(string $packageId): void
    {
        Package::query()->findOrFail($packageId)->delete(); // soft delete
    }

    public function render()
    {
        $packages = Package::query()
            ->when($this->search, fn ($q) => $q->where(
                'name',
                TenantDatabaseManager::caseInsensitiveLikeOperator(),
                "%{$this->search}%"
            ))
            ->when($this->typeFilter, fn ($q) => $q->where('type', $this->typeFilter))
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.cms.package-manager', ['packages' => $packages]);
    }
}

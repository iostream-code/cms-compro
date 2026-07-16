<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PackagePublicController extends Controller
{
    public function index(Request $request): View
    {
        $packages = Package::query()
            ->where('is_published', true)
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->string('type')))
            ->orderByDesc('created_at')
            ->paginate(9)
            ->withQueryString();

        return view('public.packages.index', ['packages' => $packages]);
    }

    public function show(Package $package): View
    {
        abort_unless($package->is_published, 404);

        return view('public.packages.show', ['package' => $package]);
    }
}

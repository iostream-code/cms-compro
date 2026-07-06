<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $page = Page::query()
            ->where('slug', 'home')
            ->where('is_published', true)
            ->with('sections')
            ->firstOrFail();

        return view('public.page', ['page' => $page]);
    }
}

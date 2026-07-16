<?php

namespace App\Livewire\Cms;

use App\Models\Article;
use App\Models\Package;
use App\Models\Page;
use App\Models\Testimonial;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.cms.dashboard', [
            'pageCount' => Page::query()->count(),
            'packageCount' => Package::query()->count(),
            'articleCount' => Article::query()->count(),
            'testimonialCount' => Testimonial::query()->count(),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\View\View;

class TestimonialPublicController extends Controller
{
    public function index(): View
    {
        $testimonials = Testimonial::query()
            ->where('is_published', true)
            ->orderBy('order')
            ->paginate(12);

        return view('public.testimonials', ['testimonials' => $testimonials]);
    }
}

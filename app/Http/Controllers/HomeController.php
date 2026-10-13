<?php

namespace App\Http\Controllers;

use App\Support\Portfolio;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Portfolio $portfolio): View
    {
        return view('pages.home', [
            'identity' => $portfolio->identity(),
            'metrics' => $portfolio->metrics(),
            'expertise' => $portfolio->expertise(),
            'projects' => $portfolio->featuredProjects(),
            'projectCount' => $portfolio->projects()->count(),
            'skillGroups' => $portfolio->skillGroups(),
            'certifications' => $portfolio->certifications(),
            'timeline' => $portfolio->timeline(),
            'testimonials' => $portfolio->testimonials(),
            'railSections' => $portfolio->navSections(),
            'meta' => [
                'title' => __('seo.home.title'),
                'description' => __('seo.home.description'),
            ],
        ]);
    }
}

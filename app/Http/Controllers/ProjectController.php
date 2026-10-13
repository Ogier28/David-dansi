<?php

namespace App\Http\Controllers;

use App\Support\Portfolio;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Portfolio $portfolio): View
    {
        return view('pages.projects.index', [
            'projects' => $portfolio->projects(),
            'categories' => $portfolio->projectCategories(),
            'meta' => [
                'title' => __('seo.projects.title'),
                'description' => __('seo.projects.description'),
            ],
        ]);
    }

    public function show(Portfolio $portfolio, string $project): View
    {
        $found = $portfolio->findProjectBySlug($project);

        abort_if($found === null, 404);

        $neighbours = $portfolio->projectNeighbours($found['key']);

        return view('pages.projects.show', [
            'project' => $found,
            'previous' => $neighbours['previous'],
            'next' => $neighbours['next'],
            'meta' => [
                'title' => __('seo.project.title', ['title' => $found['title']]),
                'description' => str($found['excerpt'])->limit(300)->toString(),
                'image' => $found['image'],
            ],
        ]);
    }
}

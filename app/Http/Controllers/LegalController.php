<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LegalController extends Controller
{
    public function notice(): View
    {
        return $this->page('notice', 'legal');
    }

    public function privacy(): View
    {
        return $this->page('privacy', 'privacy');
    }

    /**
     * Les deux pages partagent la même mise en page : seul le contenu change.
     */
    protected function page(string $documentKey, string $metaKey): View
    {
        return view('pages.legal', [
            'document' => [
                'title' => __("legal.{$documentKey}.title"),
                'updated' => __("legal.{$documentKey}.updated"),
                'sections' => (array) __("legal.{$documentKey}.sections"),
            ],
            'meta' => [
                'title' => __("seo.{$metaKey}.title"),
                'description' => __("seo.{$metaKey}.description"),
            ],
        ]);
    }
}

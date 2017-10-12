<?php

namespace Despark\Cms\Seo\Http\Controllers\Admin;

use Despark\Cms\Http\Controllers\Controller;

class SeoReadabilityController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function check()
    {
        $text = array_values($_GET)[0];
        if (strlen($text) > 0) {
            dd('YEY');
        }

        return 'No text found.';
    }
}

<?php

namespace Despark\Cms\Seo\Http\Controllers\Admin;

use DaveChild\TextStatistics as TS;
use Despark\Cms\Http\Controllers\Controller;
use Despark\Cms\Seo\Helpers\FleschKincaidReadingEaseHelper;
use Despark\Cms\Seo\Helpers\HtmlHelper;
use Despark\Cms\Seo\Helpers\SentencesHelper;
use Despark\Cms\Seo\Http\Requests\Admin\SeoReadabilityRequest;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class SeoReadabilityController extends Controller
{
    /**
     * Check readability score.
     *
     * @return array
     */
    public function check(SeoReadabilityRequest $request, FleschKincaidReadingEaseHelper $fleschKincaidReadingEaseHelper, HtmlHelper $htmlHelper, SentencesHelper $sentencesHelper)
    {
        $html = $request->get('html');
        $text = HtmlPageCrawler::create($html)->text();
        $textStatistics = new TS\TextStatistics();
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->loadHTML($html);
        $fleschKincaidReadingEaseResult = $textStatistics->fleschKincaidReadingEase($text);
        $results['fleschKincaidReadingEaseResult'] = $fleschKincaidReadingEaseHelper->getTextResult($fleschKincaidReadingEaseResult);
        $results['html'] = $htmlHelper->getTextResult($dom);
        $results['sentences'] = $sentencesHelper->getTextResults($text);
        $results['showTextLengthError'] = strlen($text) <= 50 ? true : false;

        return $results;
    }
}

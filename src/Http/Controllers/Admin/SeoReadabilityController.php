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
    protected $textStatistics;

    protected $html;

    protected $dom;

    protected $results;

    protected $text;

    public function __construct(SeoReadabilityRequest $request)
    {
        $this->html = $request->get('html');
        $this->text = HtmlPageCrawler::create($this->html)->text();
        $this->textStatistics = new TS\TextStatistics();
        $this->dom = new \DOMDocument();
        $this->dom->preserveWhiteSpace = false;
        $this->dom->loadHTML($this->html);
    }

    /**
     * Check readability score.
     *
     * @return array
     */
    public function check(FleschKincaidReadingEaseHelper $fleschKincaidReadingEaseHelper, HtmlHelper $htmlHelper, SentencesHelper $sentencesHelper)
    {
        $fleschKincaidReadingEaseResult = $this->textStatistics->fleschKincaidReadingEase($this->text);
        $this->results['fleschKincaidReadingEaseResult'] = $fleschKincaidReadingEaseHelper->getTextResult($fleschKincaidReadingEaseResult);
        $this->results['html'] = $htmlHelper->getTextResult($this->dom);
        $this->results['sentences'] = $sentencesHelper->getTextResults($this->text);
        $this->results['showTextLengthError'] = strlen($this->text) <= 50 ? true : false;

        return $this->results;
    }
}

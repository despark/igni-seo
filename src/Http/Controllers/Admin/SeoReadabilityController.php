<?php

namespace Despark\Cms\Seo\Http\Controllers\Admin;

use Despark\Cms\Http\Controllers\Controller;
use DaveChild\TextStatistics as TS;

class SeoReadabilityController extends Controller
{
    protected $textStatistics;

    protected $colors = ['red', 'orange', 'green'];

    protected $text;

    protected $fleschKincaidReadingEaseTextResults = [
        [
            'min' => 0,
            'max' => 30,
            'sentence' => 'which is considered very difficult to read. Try to make shorter sentences, using less difficult words to improve readability.',
            'color' => 'red',
        ],
        [
            'min' => 30,
            'max' => 50,
            'sentence' => 'which is considered difficult to read. Try to make shorter sentences, using less difficult words to improve readability.',
            'color' => 'red',
        ],
        [
            'min' => 50,
            'max' => 60,
            'sentence' => 'which is considered fairly difficult to read. Try to make shorter sentences to improve readability.',
            'color' => 'orange',
        ],
        [
            'min' => 60,
            'max' => 80,
            'sentence' => 'which is considered fairly easy to read.',
            'color' => 'green',
        ],
        [
            'min' => 80,
            'max' => 90,
            'sentence' => 'which is considered easy to read.',
            'color' => 'green',
        ],
        [
            'min' => 90,
            'max' => 100,
            'sentence' => 'which is considered very easy to read.',
            'color' => 'green',
        ],
    ];

    public function __construct()
    {
        $this->textStatistics = new TS\TextStatistics();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function check()
    {
        $this->text = $_GET['text'];

        if (strlen($this->text) > 0) {
            $data['first'] = $this->getFleschKincaidReadingEaseResult();
            $data['second'] = $this->getAmountOfWordsFollowingEachOfTheSubheadings();

            return $data;
            $sentencesCount = $this->textStatistics->sentenceCount($this->text);
        }

        return 'No text found.';
    }

    protected function getFleschKincaidReadingEaseResult()
    {
        $fleschKincaidReadingEaseResult = $this->textStatistics->fleschKincaidReadingEase($this->text);

        foreach ($this->fleschKincaidReadingEaseTextResults as $textResult) {
            if ($textResult['min'] <= $fleschKincaidReadingEaseResult && $textResult['max'] > $fleschKincaidReadingEaseResult) {
                return [
                    'score' => 'The copy scores '.$fleschKincaidReadingEaseResult.' in the <a href="https://en.wikipedia.org/wiki/Flesch%E2%80%93Kincaid_readability_tests" target="_blank">Flesch Reading Ease test</a>, '.$textResult['sentence'],
                    'color' => $textResult['color'],
                ];
            }
        }
    }

    protected function getAmountOfWordsFollowingEachOfTheSubheadings()
    {
        $hasSubHeading = false;
        $badSubHeadings = 0;
        $color = 'green';
        $words = 0;
        $dom = new \DOMDocument();
        $dom->loadHTML($this->text);
        $textInhtml = [];
        foreach ($dom->getElementsByTagName('*') as $key => $node) {
            if ($node->tagName !== 'html' && $node->tagName !== 'body') {
                $textInhtml[$key] = ['element' => $node->tagName, 'text' => strip_tags($dom->saveHTML($node))];
            }
        }

        foreach ($textInhtml as $key => $node) {
            if (preg_match('/h(\d)/', $node['element'])) {
                if ($hasSubHeading) {
                    if ($words > 300 && $words <= 350 && $badSubHeadings == 0) {
                        ++$badSubHeadings;
                        $color = 'orange';
                    } elseif ($words > 350) {
                        ++$badSubHeadings;
                        $color = 'red';
                    }
                    $words = 0;
                } else {
                    $hasSubHeading = true;
                }
            } else {
                if ($hasSubHeading) {
                    $words += str_word_count($node['text']);
                }
            }
        }

        if ($badSubHeadings) {
            return [
                'score' => $badSubHeadings.' subheading is followed by more than the recommended maximum of 300 words. Try to insert another subheading.',
                'color' => $color,
            ];
        }

        return [
            'score' => 'The amount of words following each of the subheadings doesn\'t exceed the recommended maximum of 300 words, which is great.',
            'color' => $color,
        ];
    }
}

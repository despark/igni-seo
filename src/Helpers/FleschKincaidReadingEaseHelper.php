<?php

namespace Despark\Cms\Seo\Helpers;

class FleschKincaidReadingEaseHelper
{
    protected $colors;

    protected $textResults = [
        [
            'min' => 0,
            'max' => 30,
            'sentence' => 'which is considered very difficult to read. Try to make shorter sentences, using less difficult words to improve readability.',
        ],
        [
            'min' => 30,
            'max' => 50,
            'sentence' => 'which is considered difficult to read. Try to make shorter sentences, using less difficult words to improve readability.',
        ],
        [
            'min' => 50,
            'max' => 60,
            'sentence' => 'which is considered fairly difficult to read. Try to make shorter sentences to improve readability.',
        ],
        [
            'min' => 60,
            'max' => 80,
            'sentence' => 'which is considered fairly easy to read.',
        ],
        [
            'min' => 80,
            'max' => 90,
            'sentence' => 'which is considered easy to read.',
        ],
        [
            'min' => 90,
            'max' => 100,
            'sentence' => 'which is considered very easy to read.',
        ],
    ];

    public function __construct()
    {
        $this->colors = config('igniseo.colors');
    }

    public function getTextResult($result)
    {
        $color = null;

        foreach ($this->textResults as $key => $textResult) {
            if ($textResult['min'] <= $result && $textResult['max'] > $result) {
                if ($key < 2) {
                    $color = $this->colors['red'];
                } elseif ($key == 2) {
                    $color = $this->colors['orange'];
                } else {
                    $color = $this->colors['green'];
                }

                return [
                    'text' => 'The copy scores '.$result.' in the <a href="https://en.wikipedia.org/wiki/Flesch%E2%80%93Kincaid_readability_tests" target="_blank">Flesch Reading Ease test</a>, '.$textResult['sentence'],
                    'color' => $color,
                ];
            }
        }
    }
}

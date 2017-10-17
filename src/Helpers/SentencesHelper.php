<?php

namespace Despark\Cms\Seo\Helpers;

class SentencesHelper
{
    protected $colors;

    protected $transitionWords;

    public function __construct()
    {
        $this->colors = config('igniseo.colors');
        $this->transitionWords = config('igniseo.transition_words');
    }

    public function getTextResults($text)
    {
        preg_match_all('/\w+[^.!?]*[.!?]/', $text, $matches);
        $sentences = count($matches[0]);
        if ($sentences) {
            $sentencesWithMoreThan20Words = 0;
            $sentencesWithTransitionWords = 0;
            foreach ($matches[0] as $key => $match) {
                if (str_word_count($match) > 20) {
                    ++$sentencesWithMoreThan20Words;
                }

                foreach ($this->transitionWords as $transitionWord) {
                    preg_match_all('/\b'.$transitionWord.'\b/', strtolower($match), $transitionWordsFound);
                    if ($transitionWordsFound[0]) {
                        ++$sentencesWithTransitionWords;
                        break;
                    }
                }
            }

            $sentencesWithMoreThan20WordsInPecentage = round($sentencesWithMoreThan20Words / $sentences * 100, 1);
            $sentencesWithTransitionWordsInPecentage = round($sentencesWithTransitionWords / $sentences * 100, 1);

            $results['moreThan20Words'] = $this->buildReturn($sentencesWithMoreThan20WordsInPecentage, 'moreThan20Words');
            $results['transitionWords'] = $this->buildReturn($sentencesWithTransitionWordsInPecentage, 'transitionWords');

            return $results;
        }

        return [
            'moreThan20Words' => [
                'text' => 'No sentences found, so we cannot find those, with <a href="https://support.siteimprove.com/hc/en-gb/articles/114094113972-Readability-Why-are-long-sentences-over-20-words-" target="_blank">more than 20 words</a>.',
                'color' => $this->colors['red'],
            ],
            'transitionWords' => [
                'text' => 'No sentences found, so we cannot find those, containing a <a href="https://en.wikipedia.org/wiki/Transitional_phrase" target="_blank">transition word or phrase</a>. ',
                'color' => $this->colors['red'],
            ],
        ];
    }

    protected function buildReturn($result, $type)
    {
        $text = '';
        $color = null;
        if ($type === 'moreThan20Words') {
            $text .= $result.'% of the sentences contain <a href="https://support.siteimprove.com/hc/en-gb/articles/114094113972-Readability-Why-are-long-sentences-over-20-words-" target="_blank">more than 20 words</a>, which is ';
            if ($result > 25) {
                $text .= 'more than';
            } elseif ($result < 25) {
                $text .= 'less than';
            } else {
                $text .= 'equal to';
            }
            $text .= ' the recommended maximum of 25%.';

            if ($result > 25) {
                $text .= ' Try to shorten the sentences.';
            }

            if ($result > 25 && $result <= 30) {
                $color = $this->colors['orange'];
            } elseif ($result > 30) {
                $color = $this->colors['red'];
            } else {
                $color = $this->colors['green'];
            }

            return ['text' => $text, 'color' => $color];
        } elseif ($type === 'transitionWords') {
            $text .= $result.'% of the sentences contain a <a href="https://en.wikipedia.org/wiki/Transitional_phrase" target="_blank">transition word or phrase</a>, which is ';
            if ($result > 30) {
                $text .= 'more than';
            } elseif ($result < 30) {
                $text .= 'less than';
            } else {
                $text .= 'equal to';
            }
            $text .= ' the recommended minimum of 30%.';

            if ($result >= 20 && $result < 30) {
                $color = $this->colors['orange'];
            } elseif ($result >= 30) {
                $color = $this->colors['green'];
            } else {
                $color = $this->colors['red'];
            }

            return ['text' => $text, 'color' => $color];
        }
    }
}

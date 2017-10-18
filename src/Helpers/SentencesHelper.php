<?php

namespace Despark\Cms\Seo\Helpers;

class SentencesHelper
{
    protected $colors;

    protected $transitionWords;

    protected $passiveVoiceWords;

    protected $config;

    public function __construct()
    {
        $this->config = config('igniseo');
        $this->colors = $this->config['colors'];
        $this->transitionWords = $this->config['transition_words'];
        $this->passiveVoiceWords = $this->config['passive_voice'];
    }

    public function getTextResults($text)
    {
        preg_match_all('/\w+[^.!?]*[.!?]/', $text, $matches);
        $sentences = count($matches[0]);
        if ($sentences) {
            $sentencesWithMoreThan20Words = 0;
            $sentencesWithTransitionWords = 0;
            $sentencesWithPassiveVoice = 0;

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

                $sentenceToArray = explode(' ', strtolower($match));
                foreach ($this->passiveVoiceWords['past_participle_verbs'] as $verb) {
                    $passiveVoiceFound = array_keys(preg_grep('/\w*ed\b|\b'.$verb.'\b/', $sentenceToArray));
                    foreach ($passiveVoiceFound as $position) {
                        // TODO remove white spaces in searched word
                        if ($position != 0 && in_array($sentenceToArray[$position - 1], $this->passiveVoiceWords['verbs'])) {
                            ++$sentencesWithPassiveVoice;
                            break 2;
                        }
                    }
                }
            }

            $sentencesWithPassiveVoiceInPecentage = round($sentencesWithPassiveVoice / $sentences * 100, 1);
            $sentencesWithMoreThan20WordsInPecentage = round($sentencesWithMoreThan20Words / $sentences * 100, 1);
            $sentencesWithTransitionWordsInPecentage = round($sentencesWithTransitionWords / $sentences * 100, 1);

            $results['passiveVoice'] = $this->buildReturn($sentencesWithPassiveVoiceInPecentage, 'passiveVoice');
            $results['moreThan20Words'] = $this->buildReturn($sentencesWithMoreThan20WordsInPecentage, 'moreThan20Words');
            $results['transitionWords'] = $this->buildReturn($sentencesWithTransitionWordsInPecentage, 'transitionWords');

            return $results;
        }

        return [
            'passiveVoice' => [
                'text' => 'No sentences found, so we cannot find those, containing <a href="https://en.wikipedia.org/wiki/English_passive_voice" target="_blank">passive voice</a>.',
                'color' => $this->colors['red'],
            ],
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
        } else {
            $text .= $result.'% of the sentences contain <a href="https://en.wikipedia.org/wiki/English_passive_voice" target="_blank">passive voice</a>, which is ';
            if ($result > 10) {
                $text .= 'more than';
            } elseif ($result < 10) {
                $text .= 'less than';
            } else {
                $text .= 'equal to';
            }
            $text .= ' the recommended maximum of 10%.';

            if ($result > 10) {
                $text .= ' Try to use their active counterparts.';
            }

            if ($result > 10 && $result < 15) {
                $color = $this->colors['orange'];
            } elseif ($result <= 10) {
                $color = $this->colors['green'];
            } else {
                $color = $this->colors['red'];
            }

            return ['text' => $text, 'color' => $color];
        }
    }
}

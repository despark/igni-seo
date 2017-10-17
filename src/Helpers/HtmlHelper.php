<?php

namespace Despark\Cms\Seo\Helpers;

class HtmlHelper
{
    protected $colors;

    public function __construct()
    {
        $this->colors = config('igniseo.colors');
    }

    public function getTextResult($dom)
    {
        $hasSubheading = false;
        $badSubHeadings = 0;
        $color['subheading'] = $this->colors['green'];
        $color['paragraph'] = $this->colors['green'];
        $words = 0;
        $badParagraphs = 0;

        foreach ($dom->getElementsByTagName('*') as $key => $node) {
            if ($node->tagName !== 'html' && $node->tagName !== 'body') {
                if (preg_match('/h(\d)/', $node->tagName)) {
                    if ($badSubHeadings == 0 && $words > 300 && $words <= 350) {
                        ++$badSubHeadings;
                        $color['subheading'] = $this->colors['orange'];
                    } elseif ($hasSubheading && $words > 350) {
                        ++$badSubHeadings;
                        $color['subheading'] = $this->colors['red'];
                    } elseif (! $hasSubheading) {
                        $hasSubheading = true;
                    }
                    $words = 0;
                } else {
                    $wordInCurrentNode = str_word_count(strip_tags($dom->saveHTML($node)));
                    $words += $wordInCurrentNode;

                    if ($node->tagName === 'p' && $wordInCurrentNode > 150) {
                        ++$badParagraphs;
                        if ($wordInCurrentNode <= 200 && $color['paragraph'] !== $this->colors['red']) {
                            $color['paragraph'] = $this->colors['orange'];
                        } elseif ($wordInCurrentNode > 200) {
                            $color['paragraph'] = $this->colors['red'];
                        }
                    }
                }
            }

            if ($dom->getElementsByTagName('*')->length == $key + 1 && $words > 300 && $hasSubheading) {
                $color['subheading'] = ($badSubHeadings == 0 && $words <= 350) ? $this->colors['orange'] : $this->colors['red'];
                ++$badSubHeadings;
            }
        }

        if (! $hasSubheading) {
            $results['subheadings'] = [
                'text' => 'The text does not contain any subheadings. Add at least one subheading.',
                'color' => $this->colors['red'],
            ];
        }

        if ($badSubHeadings) {
            $pluralWords = [str_plural('subheading', $badSubHeadings), $badSubHeadings > 1 ? 'are' : 'is'];

            $results['subheadings'] = [
                'text' => $badSubHeadings.' '.$pluralWords[0].' '.$pluralWords[1].' followed by more than the recommended maximum of 300 words. Try to insert another subheading.',
                'color' => $color['subheading'],
            ];
        } else {
            $results['subheadings'] = [
                'text' => 'The amount of words following each of the subheadings doesn\'t exceed the recommended maximum of 300 words, which is great.',
                'color' => $color['subheading'],
            ];
        }

        if ($badParagraphs) {
            $results['paragraphs'] = [
                'text' => $badParagraphs.' of the paragraphs contain more than the recommended maximum of 150 words.',
                'color' => $color['paragraph'],
            ];
        } else {
            $results['paragraphs'] = [
                'text' => 'None of the paragraphs contain more than the recommended maximum of 150 words.',
                'color' => $color['paragraph'],
            ];
        }

        return $results;
    }
}

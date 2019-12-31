<?php


namespace App\Service;


use App\Dto\Feed;

class FrequentWordsService
{
    const STOP_WORDS_COUNT = 50;

    const FREQUENT_WORDS_COUNT = 10;
    /**
     * @var EnglishCommonWordsService
     */
    private $commonWordsService;
    /**
     * FrequentWordsService constructor.
     * @param EnglishCommonWordsService $commonWordsService
     */
    public function __construct(EnglishCommonWordsService $commonWordsService)
    {
        $this->commonWordsService = $commonWordsService;
    }

    /**
     * @param Feed $feed
     * @return array
     */
    public function getWords(Feed $feed)
    {
        $data = [];
        $data[] = strip_tags($feed->getTitle());
        $data[] = strip_tags($feed->getSubtitle());
        foreach ($feed->getEntryCollection() as $entry) {
            $data[] = strip_tags($entry->getTitle());
            $data[] = strip_tags($entry->getSummary());
        }

        $string = implode($data, ' ');
        $stopWords = $this->commonWordsService->getWords();
        $stopWords = array_slice($stopWords, 0, static::STOP_WORDS_COUNT);

        return $this->extractCommonWords($string, $stopWords, static::FREQUENT_WORDS_COUNT);
    }

    /**
     * @see https://stackoverflow.com/questions/3175390/most-used-words-in-text-with-php
     * @param string $string
     * @param array $stopWords
     * @param int $maxCount
     * @return array
     */
    private function extractCommonWords(string $string, array $stopWords, int $maxCount = 5): array
    {
        $string = preg_replace('/ss+/i', '', $string);
        $string = trim($string); // trim the string
        $string = preg_replace('/[^a-zA-Z -]/', '', $string); // only take alphabet characters, but keep the spaces and dashes too…
        $string = strtolower($string); // make it lowercase

        preg_match_all('/\b.*?\b/i', $string, $matchWords);
        $matchWords = $matchWords[0];

        foreach ( $matchWords as $key => $item ) {
            if ( $item == '' || in_array(strtolower($item), $stopWords) || strlen($item) <= 3 ) {
                unset($matchWords[$key]);
            }
        }

        $wordCount = str_word_count( implode(" ", $matchWords) , 1);
        $frequency = array_count_values($wordCount);
        arsort($frequency);

        return array_slice($frequency, 0, $maxCount);
    }
}
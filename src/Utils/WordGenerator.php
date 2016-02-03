<?php
namespace WordCounter\Utils;

class WordGenerator
{
    protected $wordLength;
    protected $words = [];
    protected $next = -1;

    public function __construct($wordLength, $differentWords = 100)
    {
        $this->wordLength = $wordLength;
        for ($i = 0 ; $i < $differentWords ; $i++) {
            $this->words[$i] = $this->generateWord($wordLength);
        }
    }

    public function getWord()
    {
        $this->next = ($this->next + 1) % count($this->words);
        return $this->words[$this->next];

        // rand is slower
//        return $this->words[rand(0, count($this->words) - 1)];
    }

    protected function generateWord($length)
    {
        $res = '';
        for ($i = 0 ; $i < $length ; $i++) {
            $res .= chr(rand(ord('a'), ord('z')));
        }
        return $res;
    }
}

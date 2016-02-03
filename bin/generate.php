#!/usr/bin/php
<?php
use WordCounter\Utils\BufferedFileWriter;
use WordCounter\Utils\WordGenerator;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$words = isset($argv[1]) ? (int)$argv[1] : 10000;
$file = isset($argv[2]) ? $argv[2] : 'test.in';
$wordLength = isset($argv[3]) ? (int)$argv[3] : 3;
$wordsPerLine = isset($argv[4]) ? (int)$argv[4] : 10;

$wordGenerator = new WordGenerator($wordLength);

$writer = new BufferedFileWriter($file);
for ($i = 1; $i <= $words ; $i++) {
    $writer->write($wordGenerator->getWord() . ($i % $wordsPerLine == 0 ? "\n" : ' '));
}

$writer->close();

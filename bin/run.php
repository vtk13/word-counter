#!/usr/bin/php
<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

ini_set('memory_limit', memory_get_usage(true) + 300000);

$inFile = isset($argv[1]) ? $argv[1] : 'test.in';
$outFile = isset($argv[2]) ? $argv[2] : 'test.out';
$outDir = dirname(__DIR__) . '/cache';

$parser = new \WordCounter\Parser();
$parser->parse($inFile, $outDir);

$merger = new \WordCounter\Merger();
$mergedFile = $merger->merge($outDir);

$counter = new \WordCounter\Counter();
$counter->count($mergedFile, $outFile);

<?php
namespace WordCounter;

use WordCounter\Utils\BufferedFileWriter;
use WordCounter\Utils\LineReader;

class Counter
{
    public function count($inFile, $outFile)
    {
        $reader = new LineReader($inFile);
        $writer = new BufferedFileWriter($outFile);

        $prev = $reader->current();
        $n = 1;
        $reader->next();

        while ($reader->valid()) {
            if (strcmp($reader->current(), $prev) == 0) {
                $n++;
            } else {
                $writer->write($prev . ' ' . $n . "\n");
                $prev = $reader->current();
                $n = 1;
            }
            $reader->next();
        }

        $writer->close();
    }
}

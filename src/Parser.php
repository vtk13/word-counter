<?php
namespace WordCounter;

use WordCounter\Utils\CharReader;

class Parser
{
    public function parse($inFile, $outDir, $chunk = 1000)
    {
        $this->cleanDir($outDir);

        $input = new CharReader($inFile);
        $input->skipToWord();

        $n = 0;
        while ($input->valid()) {
            $words = [];
            for ($i = 0 ; $input->valid() && $i < $chunk ; $i++) {
                $words[] = strtolower($input->readWord());
                $input->skipToWord();
            }
            sort($words);
            file_put_contents($outDir . "/{$n}.0", implode("\n", $words));
            $n++;
        }
    }

    protected function cleanDir($dir)
    {
        $files = new \DirectoryIterator($dir);
        while ($files->valid()) {
            if (in_array(substr($files->current(), -2), ['.0', '.1'])) {
                unlink($dir . '/' . $files->current());
            }
            $files->next();
        }
    }
}

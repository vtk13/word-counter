<?php
namespace WordCounter;

use WordCounter\Utils\BufferedFileWriter;
use WordCounter\Utils\LineReader;

class Merger
{
    /**
     * Слить все файла в директории в один сохранив сортировку.
     *
     * @param $dir
     * @return string
     */
    public function merge($dir)
    {
        $inSuffix = 0;
        while ($this->run($dir, $inSuffix) > 1) {
            $inSuffix = ($inSuffix + 1) % 2;
        }

        return $dir . '/0.' . (($inSuffix + 1) % 2);
    }

    /**
     * Один проход слияния файлов.
     *
     * Функия беред по два файла с указанным суффиксом и сливает их в один файл с другим префиксом. В итоге
     * получается в 2 раза меньне файлов.
     *
     * @param $dir
     * @param $inSuffix
     * @return int
     */
    protected function run($dir, $inSuffix)
    {
        $outSuffix = ($inSuffix + 1) % 2;
        $files = new \DirectoryIterator($dir);
        $n = 0;
        while ($files->valid()) {
            list($chunk1, $chunk2) = $this->nextPair($files, $inSuffix);
            $outFile = $dir . '/' . $n . '.' . $outSuffix;
            if ($chunk2) {
                $this->mergeTwoChunks($chunk1, $chunk2, $outFile);
            } elseif ($chunk1) {
                $this->copyChunk($chunk1, $outFile);
            } else {
                return $n;
            }
            $n++;
        }
        return $n;
    }

    /**
     * Собственно алгоритм слияния двух файлов.
     *
     * @param $file1
     * @param $file2
     * @param $outFile
     */
    protected function mergeTwoChunks($file1, $file2, $outFile)
    {
        $chunk1 = new LineReader($file1);
        $chunk2 = new LineReader($file2);
        $out = new BufferedFileWriter($outFile);

        while ($chunk1->valid() && $chunk2->valid()) {
            if (strcmp($chunk1->current(), $chunk2->current()) < 0) {
                $out->write($chunk1->current() . "\n");
                $chunk1->next();
            } else {
                $out->write($chunk2->current() . "\n");
                $chunk2->next();
            }
        }

        while ($chunk1->valid()) {
            $out->write($chunk1->current() . "\n");
            $chunk1->next();
        }

        while ($chunk2->valid()) {
            $out->write($chunk2->current() . "\n");
            $chunk2->next();
        }

        $chunk1->close();
        $chunk2->close();
        unlink($file1);
        unlink($file2);
        $out->close();
    }

    private function copyChunk($file1, $outFile)
    {
        $chunk1 = new LineReader($file1);
        $out = new BufferedFileWriter($outFile);

        while ($chunk1->valid()) {
            $out->write($chunk1->current() . "\n");
            $chunk1->next();
        }

        $chunk1->close();
        unlink($file1);
        $out->close();
    }

    /**
     * Получить очередную пару файлов для слияния.
     *
     * @param \DirectoryIterator $files
     * @param $suffix
     * @return array
     */
    protected function nextPair(\DirectoryIterator $files, $suffix)
    {
        $res = [];
        while ($files->valid() && count($res) < 2) {
            $fileSuffix = substr($files->current(), -strlen($suffix));
            if (strcmp($fileSuffix, $suffix) == 0) {
                $res[] = $files->getPathname();
            }
            $files->next();
        }
        return array_pad($res, 2, null);
    }
}

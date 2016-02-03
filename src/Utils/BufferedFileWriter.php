<?php
namespace WordCounter\Utils;

class BufferedFileWriter
{
    protected $f;
    protected $buffer = '';
    protected $bufferSize;

    public function __construct($filename, $bufferSize = 2048)
    {
        $this->f = fopen($filename, 'w+');
        $this->bufferSize = $bufferSize;
    }

    public function write($str)
    {
        $this->buffer .= $str;
        if (strlen($this->buffer) > $this->bufferSize) {
            fputs($this->f, $this->buffer);
            $this->buffer = '';
        }
    }

    public function close()
    {
        if (strlen($this->buffer) > 0) {
            fputs($this->f, $this->buffer);
        }
        fclose($this->f);
    }
}

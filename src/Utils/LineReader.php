<?php
namespace WordCounter\Utils;

class LineReader implements \Iterator
{
    protected $filename;
    protected $f;
    protected $index;
    protected $current;

    public function __construct($filename)
    {
        $this->filename = $filename;
        $this->rewind();
    }

    public function close()
    {
        if ($this->f) {
            fclose($this->f);
            $this->f = null;
        }
    }

    public function current()
    {
        return $this->current;
    }

    public function next()
    {
        while ($this->valid()) {
            $this->current = trim(fgets($this->f));
            if (strlen($this->current) > 0) {
                $this->index++;
                return;
            } // else skip empty lines
        }
    }

    public function key()
    {
        return $this->index;
    }

    public function valid()
    {
        return !feof($this->f);
    }

    public function rewind()
    {
        $this->close();

        $this->f = fopen($this->filename, 'r');
        $this->next();
        $this->index = 0;
    }
}

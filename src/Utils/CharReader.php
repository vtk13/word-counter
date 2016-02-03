<?php
namespace WordCounter\Utils;

class CharReader implements \Iterator
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

    public function readWord()
    {
        $res = '';
        while (ctype_alpha($this->current())) {
            $res .= $this->current();
            $this->next();
        }
        return $res;
    }

    public function skipToWord()
    {
        while ($this->valid() && !ctype_alpha($this->current())) {
            $this->next();
        }
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
        $this->current = fgetc($this->f);
        $this->index++;
    }

    public function key()
    {
        return $this->index;
    }

    public function valid()
    {
        return $this->current !== false;
    }

    public function rewind()
    {
        $this->close();

        $this->f = fopen($this->filename, 'r');
        $this->index = 0;
        $this->current = fgetc($this->f);
    }
}

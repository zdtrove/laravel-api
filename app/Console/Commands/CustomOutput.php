<?php

namespace App\Console\Commands;

trait CustomOutput
{
    public function comment($string, $verbosity = null)
    {
        parent::comment($this->prepend($string), $verbosity);
    }

    public function error($string, $verbosity = null)
    {
        parent::error($this->prepend($string), $verbosity);
    }

    public function info($string, $verbosity = null)
    {
        parent::info($this->prepend($string), $verbosity);
    }

    public function warn($string, $verbosity = null)
    {
        parent::warn($this->prepend($string), $verbosity);
    }

    protected function prepend($string)
    {
        return $this->getPrependString() . $string;
    }

    protected function getPrependString()
    {
        return date('[Y-m-d H:i:s]') . ' ';
    }
}

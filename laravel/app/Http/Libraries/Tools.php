<?php

namespace App\Http\Libraries;

class Tools
{
    public function isValidVal($val, $opt = null)
    {
        return (isset($val) && !empty($val) ? $val : $opt);
    }
}

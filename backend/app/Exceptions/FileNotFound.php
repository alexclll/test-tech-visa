<?php

namespace App\Exceptions;

use Exception;

class FileNotFound extends Exception
{
    public function __construct()
    {
        parent::__construct('file_not_found');
    }
}

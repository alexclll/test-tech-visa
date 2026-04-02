<?php

namespace App\Exceptions;

use Exception;

class VisaFileNotFound extends Exception
{
    public function __construct()
    {
        parent::__construct('visa_file_not_found');
    }
}

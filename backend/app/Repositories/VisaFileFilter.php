<?php

namespace App\Repositories;

use App\Enums\VisaFileType;

class VisaFileFilter
{
    public function __construct(
        public readonly ?VisaFileType $type,
    ) {}
}

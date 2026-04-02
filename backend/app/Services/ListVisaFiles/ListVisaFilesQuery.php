<?php

namespace App\Services\ListVisaFiles;

use App\Enums\VisaFileType;

class ListVisaFilesQuery
{
    public function __construct(
        public readonly VisaFileType $type,
        public readonly int $page,
        public readonly int $perPage,
    ) {}
}

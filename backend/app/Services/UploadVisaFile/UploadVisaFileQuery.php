<?php

namespace App\Services\UploadVisaFile;

use App\Enums\VisaFileType;
use Illuminate\Http\UploadedFile;

class UploadVisaFileQuery
{
    public function __construct(
        public readonly UploadedFile $file,
        public readonly VisaFileType $type,
    ) {}
}

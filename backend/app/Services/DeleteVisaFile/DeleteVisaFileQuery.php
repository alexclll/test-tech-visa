<?php

namespace App\Services\DeleteVisaFile;

class DeleteVisaFileQuery
{
    public function __construct(
        public readonly string $id,
    ) {}
}

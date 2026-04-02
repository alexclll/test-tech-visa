<?php

namespace App\Services\DeleteVisaFile;

use App\Exceptions\FileNotFound;
use App\Exceptions\VisaFileNotFound;
use App\Repositories\VisaFileRepository;
use Illuminate\Support\Facades\Storage;

class DeleteVisaFileService
{
    public function __construct(private readonly VisaFileRepository $repository) {}

    public function execute(DeleteVisaFileQuery $query): void
    {
        $visaFile = $this->repository->findById($query->id);

        if (!$visaFile) {
            throw new VisaFileNotFound();
        }

        if (! Storage::disk('local')->exists($visaFile->path)) {
            throw new FileNotFound();
        }

        Storage::disk('local')->delete($visaFile->path);
        $this->repository->delete($visaFile);
    }
}

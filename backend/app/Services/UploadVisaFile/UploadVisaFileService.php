<?php

namespace App\Services\UploadVisaFile;

use App\Models\VisaFile;
use App\Repositories\VisaFileRepository;
use Illuminate\Contracts\Config\Repository as Config;

class UploadVisaFileService
{
    public function __construct(
        private readonly VisaFileRepository $repository,
        private readonly Config $config,
    ) {}

    public function execute(UploadVisaFileQuery $query): VisaFile
    {
        $storedPath = $query->file->store(
            $this->config->get('filesystems.visa_files_path') . '/' . $query->type->value,
        );

        return $this->repository->create([
            'original_name' => $query->file->getClientOriginalName(),
            'stored_name' => basename($storedPath),
            'path' => $storedPath,
            'mime_type' => $query->file->getMimeType(),
            'file_type' => $query->type,
            'size' => $query->file->getSize(),
        ]);
    }
}

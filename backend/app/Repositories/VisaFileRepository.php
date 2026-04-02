<?php

namespace App\Repositories;

use App\Models\VisaFile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class VisaFileRepository
{
    public function get(VisaFileFilter $filter, Pagination $pagination): LengthAwarePaginator
    {
        return VisaFile::when($filter->type, fn ($query) => $query->where('file_type', $filter->type))
            ->orderByDesc('created_at')
            ->paginate($pagination->perPage, ['*'], 'page', $pagination->page);
    }

    public function findById(string $id): ?VisaFile
    {
        return VisaFile::find($id);
    }

    public function create(array $data): VisaFile
    {
        return VisaFile::create($data);
    }

    public function delete(VisaFile $visaFile): void
    {
        $visaFile->delete();
    }
}

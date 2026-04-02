<?php

namespace App\Services\ListVisaFiles;

use App\Repositories\Pagination;
use App\Repositories\VisaFileFilter;
use App\Repositories\VisaFileRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListVisaFilesService
{
    public function __construct(private readonly VisaFileRepository $repository) {}

    public function execute(ListVisaFilesQuery $query): LengthAwarePaginator
    {
        return $this->repository->get(
            new VisaFileFilter($query->type),
            new Pagination($query->page, $query->perPage),
        );
    }
}

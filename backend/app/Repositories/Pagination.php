<?php

namespace App\Repositories;

class Pagination
{
    public function __construct(
        public readonly int $page,
        public readonly int $perPage,
    ) {}
}

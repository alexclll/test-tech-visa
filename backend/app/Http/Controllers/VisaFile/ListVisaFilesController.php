<?php

namespace App\Http\Controllers\VisaFile;

use App\Enums\VisaFileType;
use App\Http\Controllers\Controller;
use App\Services\ListVisaFiles\ListVisaFilesQuery;
use App\Services\ListVisaFiles\ListVisaFilesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListVisaFilesController extends Controller
{
    public function __construct(
        private readonly ListVisaFilesService $service,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(VisaFileType::values())],
            'page' => ['required', 'integer', 'min:1'],
            'perPage' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $result = $this->service->execute(
            new ListVisaFilesQuery(
                VisaFileType::from($validated['type']),
                $validated['page'],
                $validated['perPage'],
            ),
        );

        return response()->json([
            'data' => $result->map(fn ($file) => [
                'id' => $file->id,
                'originalName' => $file->original_name,
                'fileType' => $file->file_type,
                'size' => $file->size,
                'createdAt' => $file->created_at->toDateTimeString(),
            ]),
            'meta' => [
                'currentPage' => $result->currentPage(),
                'lastPage' => $result->lastPage(),
                'perPage' => $result->perPage(),
                'total' => $result->total(),
            ],
        ]);
    }
}

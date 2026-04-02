<?php

namespace App\Http\Controllers\VisaFile;

use App\Exceptions\FileNotFound;
use App\Exceptions\VisaFileNotFound;
use App\Http\Controllers\Controller;
use App\Services\DeleteVisaFile\DeleteVisaFileQuery;
use App\Services\DeleteVisaFile\DeleteVisaFileService;
use Illuminate\Http\JsonResponse;

class DeleteVisaFileController extends Controller
{
    public function __construct(private readonly DeleteVisaFileService $service) {}

    public function __invoke(string $id): JsonResponse
    {
        try {
            $this->service->execute(new DeleteVisaFileQuery($id));
        } catch (FileNotFound | VisaFileNotFound $exception) {
            return response()->json(['error' => $exception->getMessage()], 404);
        }

        return response()->json(null, 204);
    }
}

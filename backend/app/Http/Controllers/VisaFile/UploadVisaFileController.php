<?php

namespace App\Http\Controllers\VisaFile;

use App\Enums\VisaFileType;
use App\Http\Controllers\Controller;
use App\Services\UploadVisaFile\UploadVisaFileQuery;
use App\Services\UploadVisaFile\UploadVisaFileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadVisaFileController extends Controller
{
    public function __construct(
        private readonly UploadVisaFileService $service
    ) {}

    public function __invoke(Request $request, VisaFileType $type): JsonResponse
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:pdf,png,jpg,jpeg',
                'max:4096'
            ],
        ]);

        $visaFile = $this->service->execute(
            new UploadVisaFileQuery($request->file('file'), $type),
        );

        return response()->json([
            'id' => $visaFile->id,
            'original_name' => $visaFile->original_name,
            'file_type' => $visaFile->file_type,
            'size' => $visaFile->size,
            'created_at' => $visaFile->created_at->toDateTimeString(),
        ], 201);
    }
}

<?php

use App\Enums\VisaFileType;
use App\Http\Controllers\VisaFile\DeleteVisaFileController;
use App\Http\Controllers\VisaFile\ListVisaFilesController;
use App\Http\Controllers\VisaFile\UploadVisaFileController;
use Illuminate\Support\Facades\Route;

Route::get('/visa-files', ListVisaFilesController::class);

Route::post('/visa-files/{type}', UploadVisaFileController::class)
    ->whereIn('type', VisaFileType::values());

Route::delete('/visa-files/{id}', DeleteVisaFileController::class)
    ->whereUuid('id');

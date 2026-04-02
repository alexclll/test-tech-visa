<?php

namespace App\Models;

use App\Enums\VisaFileType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaFile extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'original_name',
        'stored_name',
        'path',
        'mime_type',
        'file_type',
        'size',
    ];

    protected $casts = [
        'file_type' => VisaFileType::class,
    ];
}

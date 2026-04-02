<?php

namespace Tests\Feature;

use App\Enums\VisaFileType;
use App\Models\VisaFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class UploadVisaFileTest extends TestCase
{
    use RefreshDatabase;

    const UPLOAD_ROUTE = '/api/visa-files/';

    public static function visaFileTypes(): array
    {
        return array_map(fn (string $value) => [$value], VisaFileType::values());
    }

    #[DataProvider('visaFileTypes')]
    public function test_it_uploads_a_file_stores_it_on_disk_and_persists_in_database(string $type): void
    {
        Storage::fake('local');
        $now = $this->freezeTime();
        $file = UploadedFile::fake()->create('passport.pdf', 100, 'application/pdf');

        $response = $this->postJson(self::UPLOAD_ROUTE . $type, [
            'file' => $file,
        ]);

        $visaFile = VisaFile::first();

        $response->assertStatus(201)
            ->assertExactJson([
                'id' => $visaFile->id,
                'original_name' => 'passport.pdf',
                'file_type' => $type,
                'size' => 100 * 1024,
                'created_at' => $now->toDateTimeString(),
            ]);

        $this->assertDatabaseHas('visa_files', [
            'id' => $visaFile->id,
            'original_name' => 'passport.pdf',
            'stored_name' => $visaFile->stored_name,
            'path' => $visaFile->path,
            'mime_type' => 'application/pdf',
            'file_type' => $type,
            'size' => 100 * 1024,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $this->assertDatabaseCount('visa_files', 1);
        $this->assertStringStartsWith('visa-files/' . $type . '/', $visaFile->path);
        Storage::disk('local')->assertExists($visaFile->path);
    }

    public function test_it_rejects_an_unauthorized_file_type(): void
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('malware.exe', 100, 'application/octet-stream');

        $response = $this->postJson(self::UPLOAD_ROUTE . VisaFileType::Passport->value, [
            'file' => $file,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file']);

        $this->assertDatabaseCount('visa_files', 0);
        Storage::disk('local')->assertDirectoryEmpty('visa-files');
    }
}

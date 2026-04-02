<?php

namespace Tests\Feature;

use App\Enums\VisaFileType;
use App\Models\VisaFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DeleteVisaFileTest extends TestCase
{
    use RefreshDatabase;

    const DELETE_ROUTE = '/api/visa-files/';

    public function test_it_deletes_a_file_from_disk_and_database(): void
    {
        Storage::fake('local');
        $visaFile = VisaFile::factory()->ofType(VisaFileType::Passport)->create();
        Storage::disk('local')->put($visaFile->path, 'content');

        $response = $this->deleteJson(self::DELETE_ROUTE . $visaFile->id);

        $response->assertStatus(204);
        $this->assertDatabaseCount('visa_files', 0);
        Storage::disk('local')->assertMissing($visaFile->path);
    }

    public function test_it_returns_404_when_file_not_found_in_database(): void
    {
        $response = $this->deleteJson(self::DELETE_ROUTE . fake()->uuid());

        $response->assertStatus(404)
            ->assertJson(['error' => 'visa_file_not_found']);
    }

    public function test_it_returns_404_when_file_missing_on_disk(): void
    {
        Storage::fake('local');
        $visaFile = VisaFile::factory()->ofType(VisaFileType::Passport)->create();

        $response = $this->deleteJson(self::DELETE_ROUTE . $visaFile->id);

        $response->assertStatus(404)
            ->assertJson(['error' => 'file_not_found']);

        $this->assertDatabaseCount('visa_files', 1);
    }
}

<?php

namespace Tests\Feature;

use App\Enums\VisaFileType;
use App\Models\VisaFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListVisaFilesTest extends TestCase
{
    use RefreshDatabase;

    const LIST_ROUTE = '/api/visa-files';

    public function test_it_returns_files_filtered_by_type(): void
    {
        $now = $this->freezeTime();
        $passportFile = VisaFile::factory()->ofType(VisaFileType::Passport)->create();
        VisaFile::factory()->count(2)->ofType(VisaFileType::Photo)->create();

        $response = $this->getJson(self::LIST_ROUTE . '?type=passport&page=1&perPage=15');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'id' => $passportFile->id,
                'originalName' => $passportFile->original_name,
                'fileType' => VisaFileType::Passport->value,
                'size' => $passportFile->size,
                'createdAt' => $now->toDateTimeString(),
            ]);
    }

    public function test_it_paginates_results(): void
    {
        VisaFile::factory()->count(5)->ofType(VisaFileType::Passport)->create();

        $response = $this->getJson(self::LIST_ROUTE . '?type=passport&page=1&perPage=2');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.total', 5)
            ->assertJsonPath('meta.lastPage', 3)
            ->assertJsonPath('meta.perPage', 2);
    }

    public function test_it_requires_type_page_and_per_page(): void
    {
        $response = $this->getJson(self::LIST_ROUTE);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['type', 'page', 'perPage']);
    }

    public function test_it_rejects_invalid_type(): void
    {
        $response = $this->getJson(self::LIST_ROUTE . '?type=invalid&page=1&perPage=15');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['type']);
    }
}

<?php

namespace Database\Factories;

use App\Enums\VisaFileType;
use App\Models\VisaFile;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisaFileFactory extends Factory
{
    protected $model = VisaFile::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(VisaFileType::cases());

        return [
            'original_name' => $this->faker->word() . '.pdf',
            'stored_name' => $this->faker->uuid() . '.pdf',
            'path' => config('filesystems.visa_files_path') . '/' . $type->value . '/' . $this->faker->uuid() . '.pdf',
            'mime_type' => 'application/pdf',
            'file_type' => $type,
            'size' => $this->faker->numberBetween(1024, 4 * 1024 * 1024),
        ];
    }

    public function ofType(VisaFileType $type): static
    {
        return $this->state(fn () => [
            'file_type' => $type,
            'path' => config('filesystems.visa_files_path') . '/' . $type->value . '/' . $this->faker->uuid() . '.pdf',
        ]);
    }
}

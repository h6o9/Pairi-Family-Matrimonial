<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'marital_statuses',
        'qualifications',
        'incomes',
        'residences',
        'physical_body_types',
        'religions',
        'languages',
        'mother_tongues',
        'communities',
        'body_types',
        'complexions',
        'physical_disabilities',
        'sub_communities',
        'employment_types',
        'residence_statuses',
        'fields_of_study',
        'graduation_years',
        'universities',
    ];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            if (! Schema::hasTable($tableName)) {
                Schema::create($tableName, function (Blueprint $table) {
                    $table->id();
                    $table->string('name')->unique();
                    $table->enum('status', ['active', 'inactive'])->default('active');
                    $table->timestamps();
                });
            }
        }

        $defaults = [
            'marital_statuses' => config('pairi_family.marital_statuses', []),
            'qualifications' => config('pairi_family.qualifications', []),
            'incomes' => config('pairi_family.monthly_income_ranges', []),
            'religions' => config('pairi_family.religions', []),
            'languages' => config('pairi_family.languages', []),
            'mother_tongues' => config('pairi_family.mother_tongues', []),
            'body_types' => config('pairi_family.body_types', []),
            'complexions' => config('pairi_family.complexions', []),
            'employment_types' => array_column(config('pairi_family.employment_types', []), 'label'),
            'residence_statuses' => config('pairi_family.residential_statuses', []),
        ];

        foreach ($defaults as $tableName => $names) {
            $now = now();
            $rows = array_map(fn ($name) => [
                'name' => $name,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ], $names);

            if ($rows !== []) {
                DB::table($tableName)->insertOrIgnore($rows);
            }
        }
    }

    public function down(): void
    {
        foreach (array_reverse($this->tables) as $tableName) {
            Schema::dropIfExists($tableName);
        }
    }
};

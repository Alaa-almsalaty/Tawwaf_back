<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop index created by morphs()
        DB::statement('ALTER TABLE media DROP INDEX media_model_id_model_type_index');

        // Change model_id type to string (UUID safe)
        DB::statement('ALTER TABLE media MODIFY model_id VARCHAR(255) NOT NULL');

        // Recreate index
        DB::statement('CREATE INDEX media_model_id_model_type_index ON media (model_id, model_type)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE media DROP INDEX media_model_id_model_type_index');
        DB::statement('ALTER TABLE media MODIFY model_id BIGINT UNSIGNED NOT NULL');
        DB::statement('CREATE INDEX media_model_id_model_type_index ON media (model_id, model_type)');
    }
};

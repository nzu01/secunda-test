<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {

        DB::statement('CREATE EXTENSION IF NOT EXISTS cube');
        DB::statement('CREATE EXTENSION IF NOT EXISTS earthdistance');

        DB::statement("
            CREATE INDEX IF NOT EXISTS buildings_earth_gist
            ON buildings
            USING gist (ll_to_earth(latitude, longitude));
        ");
    }

    public function down(): void
    {
        DB::statement("DROP INDEX IF EXISTS buildings_earth_gist");
    }
};

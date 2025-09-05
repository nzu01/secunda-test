<?php

use App\Helper\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique()->index();
            $table->string('title');

            $table->uuid('building_uuid');
            $table->foreign('building_uuid')
                ->references('uuid')
                ->on('buildings')
                ->onDelete('restrict');
            $table->timestamps();

        });
        MigrationHelper::autoUuidUpdate('organizations');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};

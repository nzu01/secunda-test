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
        Schema::create('organization_types', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->index();
            $table->string('title');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::table('organization_types', function (Blueprint $table) {
            $table->uuid('parent_uuid')->nullable();
            $table->foreign('parent_uuid')
                ->references('uuid')
                ->on('organization_types')
                ->onDelete('cascade');
        });
        MigrationHelper::autoUuidUpdate('organization_types');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_types');
    }
};

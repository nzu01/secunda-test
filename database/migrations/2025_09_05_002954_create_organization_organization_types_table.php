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
        Schema::create('organization_organization_types', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique()->index();

            $table->uuid('organization_uuid');
            $table->foreign('organization_uuid')
                ->references('uuid')
                ->on('organizations')
                ->onDelete('restrict');

            $table->uuid('organization_type_uuid');
            $table->foreign('organization_type_uuid')
                ->references('uuid')
                ->on('organization_types')
                ->onDelete('restrict');

            $table->unique(['organization_type_uuid', 'organization_uuid']);

            $table->timestamps();
        });

        MigrationHelper::autoUuidUpdate('organization_organization_types');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_organization_types');
    }
};

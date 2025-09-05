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
        Schema::create('organization_contacts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->index();
            $table->uuid('organization_uuid');
            $table->foreign('organization_uuid')
                ->references('uuid')
                ->on('organizations')
                ->onDelete('restrict');

            $table->enum('contact_type', ['email', 'phone', 'other']);
            $table->string('value');

            $table->timestamps();
        });

        MigrationHelper::autoUuidUpdate('organization_contacts');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_contacts');
    }
};

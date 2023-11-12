<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable(false);

            $table->decimal('default_price_per_day_gbp', 8, 2, true);

            $table->timestamp('created_at')
                ->nullable(false)
                ->useCurrent();

            $table->timestamp('updated_at')
                ->nullable(false)
                ->useCurrent()
                ->useCurrentOnUpdate();
        });

        Schema::create('parking_spaces', function (Blueprint $table) {
            $table->id();

            $table->foreignId('location_id')
                ->references('id')
                ->on('locations')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_spaces');

        Schema::dropIfExists('locations');
    }
};

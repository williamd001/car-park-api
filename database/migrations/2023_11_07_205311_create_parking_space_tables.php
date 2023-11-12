<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->string('first_name')
                ->nullable(false);

            $table->string('last_name')
                ->nullable(false);

            $table->timestamp('created_at')
                ->nullable(false)
                ->useCurrent();

            $table->timestamp('updated_at')
                ->nullable(false)
                ->useCurrent()
                ->useCurrentOnUpdate();
        });

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

        Schema::create('parking_space_bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreignId('parking_space_id')
                ->references('id')
                ->on('parking_spaces')
                ->cascadeOnDelete();

            $table->date('date_from')
                ->nullable(false);

            $table->date('date_to')
                ->nullable(false);

            $table->decimal('price_gbp',
                8,
                2,
                true
            )
                ->nullable(false);

            $table->timestamp('created_at')
                ->nullable(false)
                ->useCurrent();

            $table->timestamp('updated_at')
                ->nullable(false)
                ->useCurrent()
                ->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_space_bookings');

        Schema::dropIfExists('parking_spaces');

        Schema::dropIfExists('locations');

        Schema::dropIfExists('customers');
    }
};

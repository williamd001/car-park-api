<?php

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
        Schema::create('bookings', function (Blueprint $table) {
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

            $table->decimal(
                'price_gbp',
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
        Schema::dropIfExists('bookings');
    }
};

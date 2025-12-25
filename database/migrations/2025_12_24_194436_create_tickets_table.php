<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TicketStatus;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('flight_seat_id')
                ->constrained('flight_seats')
                ->onDelete('cascade');

            $table->string('ticket_number')->unique(); // رقم التذكرة
            $table->decimal('price', 8, 2);
          $table->string('status')->default(TicketStatus::BOOKED->value);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};

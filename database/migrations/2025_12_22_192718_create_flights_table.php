<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\FlightStatus;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('flight_number');
            $table->string('carrier');
            $table->string('aircraft_type');
            $table->string('departure_airport');
            $table->string('arrival_airport');
            $table->dateTime('departure_time');
            $table->date('departure_date');
            $table->dateTime('arrival_time');
            $table->date('arrival_date');
           
            $table->integer('duration_minutes');
            $table->index(['departure_airport', 'arrival_airport', 'departure_date'], 'flights_search_index');
            $table->string('status')->default(FlightStatus::SCHEDULED->value);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};

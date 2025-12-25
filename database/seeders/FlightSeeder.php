<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Flight;
use App\Models\FlightSeat;
use App\Enums\FlightStatus;
use App\Enums\SeetsClass;
use Carbon\Carbon;

class FlightSeeder extends Seeder
{
    public function run(): void
    {
        $airports = ['CAI', 'JED', 'DXB', 'RUH', 'MED', 'LXR'];
        $carriers = ['EgyptAir', 'Saudia', 'Emirates', 'FlyDubai'];
        $aircrafts = ['A320', 'A330', 'B737', 'B787'];

        for ($i = 1; $i <= 30; $i++) {

            $departureDate = Carbon::now()->addDays(rand(1, 30));
            $arrivalDate = (clone $departureDate);

            $flight = Flight::create([
                'flight_number'      => 'FL' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'carrier'            => $carriers[array_rand($carriers)],
                'aircraft_type'      => $aircrafts[array_rand($aircrafts)],
                'departure_airport'  => $airports[array_rand($airports)],
                'arrival_airport'    => $airports[array_rand($airports)],
                'departure_time'     => $departureDate->copy()->setTime(rand(0, 23), rand(0, 59)),
                'departure_date'     => $departureDate->toDateString(),
                'arrival_time'       => $arrivalDate->copy()->addMinutes(rand(60, 300)),
                'arrival_date'       => $arrivalDate->toDateString(),
                'duration_minutes'   => rand(60, 300),
                'status'             => FlightStatus::SCHEDULED->value,
            ]);

            
            for ($s = 1; $s <= 30; $s++) {
                $class = $s <= 10 ? SeetsClass::BUSINESS->value : SeetsClass::ECONOMY->value;
                FlightSeat::create([
                    'flight_id'   => $flight->id,
                    'seat_number' => $this->generateSeatNumber($s),
                    'class'       => $s <= 10
                        ? SeetsClass::BUSINESS->value
                        : SeetsClass::ECONOMY->value,
                    'is_available' => true,
                    'price'       => $this->generateSeatPrice($class)
                ]);
            }
        }
    }

    private function generateSeatNumber($index): string
    {
        $row = ceil($index / 6); 
        $letters = ['A', 'B', 'C', 'D', 'E', 'F'];
        $letter = $letters[($index - 1) % 6];

        return $row . $letter; 
    }

     private function generateSeatPrice(string $class): int
    {
        if ($class === SeetsClass::BUSINESS->value) {
            return rand(3000, 5000); // سعر كرسي بيزنس
        } else {
            return rand(1000, 2500); // سعر كرسي إكونومي
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\Location;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        // تأكدي إن فيه Location
        $location = Location::first() ?? Location::create([
            'city' => 'New York',
            'country' => 'USA',
            'address' => '1012 Ocean Avenue, New York, USA',
        ]);

        // Hotel
        $hotel = Hotel::create([
            'name' => 'HarborHaven Hideaway',
            'description' => 'Luxury hotel near the ocean with modern rooms.',
            'location_id' => $location->id,
        ]);

        // Hotel Photos (Gallery)
        $hotelPhotos = [
            'hotels/hotel1.png',
            'hotels/hotel2.png',
            'hotels/hotel3.jpg',
        ];

        foreach ($hotelPhotos as $photoPath) {
            $hotel->photos()->create([
                'url' => $photoPath, // public/hotels/...
            ]);
        }

        // Rooms
        $rooms = [
            [
                'price' => 150,
                'bedroom_number' => 3,
                'bathroom_number' => 2,
                'area' => '1848 Sqft',
            ],
            [
                'price' => 200,
                'bedroom_number' => 4,
                'bathroom_number' => 3,
                'area' => '2200 Sqft',
            ],
        ];

        foreach ($rooms as $index => $roomData) {
            $room = $hotel->rooms()->create(array_merge($roomData, [
                'description' => 'Spacious room with sea view',
                'is_available' => true,
            ]));

            // Room Photos (يمكن لكل غرفة أكثر من صورة)
            $roomPhotos = [
                "rooms/room{$index}_1.jpg",
                "rooms/room{$index}_2.jpg",
            ];

            foreach ($roomPhotos as $photoPath) {
                $room->photos()->create([
                    'url' => $photoPath, // public/rooms/...
                ]);
            }
        }
    }
}

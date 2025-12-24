<?php

namespace App\Http\Resources\Hotel;

use App\Enums\BookingStatus;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelDetailsResource extends JsonResource
{
    protected $checkDate;

    public function __construct($resource, $checkDate = null)
    {
        parent::__construct($resource);
        $this->checkDate = $checkDate ?? Carbon::today();
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'location' => $this->location,
            'photos' => $this->photos->map(fn($photo) => asset('storage/' . $photo->url)),
            'rating' => $this->ratings->avg('rating') ?? 0,
            'rooms' => $this->rooms->map(function($room) {

                // حساب الأيام المحجوزة
                $bookedDates = collect();
                foreach ($room->bookings as $booking) {
                    if (in_array($booking->status, [BookingStatus::PENDING->value, BookingStatus::CONFIRMED->value])) {
                        $checkIn = Carbon::parse($booking->check_in_date)->startOfDay();
                        $checkOut = Carbon::parse($booking->check_out_date)->startOfDay()->subDay();
                        if ($checkOut < $checkIn) {
                            $checkOut = $checkIn;
                        }
                        $period = CarbonPeriod::create($checkIn, $checkOut);
                        foreach ($period as $date) {
                            $bookedDates->push($date->format('Y-m-d'));
                        }
                    }
                }

                $start = Carbon::today();
                $end = Carbon::today()->addDays(30); // فقط 30 يوم من اليوم
                $allDates = collect(CarbonPeriod::create($start, $end))->map(fn($d) => $d->format('Y-m-d'));
                $availableDates = $allDates->diff($bookedDates->unique())->values();

                return [
                    'id' => $room->id,
                    'description' => $room->description,
                    'price' => $room->price,
                    'bedroom_number' => $room->bedroom_number,
                    'bathroom_number' => $room->bathroom_number,
                    'area' => $room->area,
                    'photos' => $room->photos->map(fn($photo) => asset('storage/' . $photo->url)),
                    'available_dates' => $availableDates,
                ];
            }),
        ];
    }
}

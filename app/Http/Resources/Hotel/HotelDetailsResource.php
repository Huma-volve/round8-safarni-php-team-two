<?php

namespace App\Http\Resources\Hotel;

use App\Enums\BookingStatus;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelDetailsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'location' => $this->location,
            'photos'   => $this->photos->map(fn ($p) => asset('storage/' . $p->url)),
            'rating'   => round($this->ratings->avg('rating') ?? 0, 1),

            'rooms' => $this->rooms->map(function ($room) {

                // اجلب الحجوزات المحجوزة مسبقًا
                $bookedDates = collect();
                foreach ($room->bookings()->whereIn('status', [BookingStatus::PENDING->value, BookingStatus::CONFIRMED->value])->get() as $booking) {
                    $checkIn  = Carbon::parse($booking->check_in_date)->startOfDay();
                    $checkOut = Carbon::parse($booking->check_out_date)->startOfDay()->subDay();

                    if ($checkOut < $checkIn) {
                        $checkOut = $checkIn;
                    }

                    $period = CarbonPeriod::create($checkIn, $checkOut);
                    foreach ($period as $date) {
                        $bookedDates->push($date->format('Y-m-d'));
                    }
                }

                // توليد قائمة الأيام القادمة
                $start = Carbon::today();
                $end   = Carbon::today()->addDays(30);
                $allDates = collect(CarbonPeriod::create($start, $end))->map(fn($d) => $d->format('Y-m-d'));

                // إزالة الأيام المحجوزة
                $availableDates = $allDates->diff($bookedDates->unique())->values();

                return [
                    'id'              => $room->id,
                    'description'     => $room->description,
                    'price'           => $room->price,
                    'bedroom_number'  => $room->bedroom_number,
                    'bathroom_number' => $room->bathroom_number,
                    'area'            => $room->area,
                    'photos'          => $room->photos->map(fn ($p) => asset('storage/' . $p->url)),
                    'available_dates' => $availableDates,
                ];
            }),
        ];
    }
}

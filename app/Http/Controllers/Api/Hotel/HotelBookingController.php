<?php

namespace App\Http\Controllers\Api\Hotel;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hotel\CheckAvailabilityRequest;
use App\Http\Requests\Hotel\StoreHotelBookingRequest;
use App\Http\Resources\Hotel\BookingResource;
use App\Models\Booking;
use App\Models\HotelRoom;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HotelBookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = $request->user()
            ->bookings()
            ->where('bookable_type', HotelRoom::class)
            ->with(['bookable.hotel.photos', 'bookable.photos'])
            ->latest()
            ->paginate(10);

        return apiResponse(true, 'Bookings retrieved successfully', BookingResource::collection($bookings), 200);
    }

    public function checkAvailability(CheckAvailabilityRequest $request, HotelRoom $room)
    {
        $validated = $request->validated();

        $isAvailable = $this->isRoomAvailable(
            $room,
            $validated['check_in_date'],
            $validated['check_out_date']
        );

        return apiResponse(true, 'Room availability checked', [
            'available' => $isAvailable,
            'room_id' => $room->id,
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
        ], 200);
    }

    public function store(StoreHotelBookingRequest $request)
    {
        $validated = $request->validated();
        $room = HotelRoom::findOrFail($validated['room_id']);

        if (!$room->is_available || !$this->isRoomAvailable($room, $validated['check_in_date'], $validated['check_out_date'])) {
            return apiResponse(false, 'Room is not available for the selected dates', null, 422);
        }

        $checkIn = Carbon::parse($validated['check_in_date']);
        $checkOut = Carbon::parse($validated['check_out_date']);
        $days = $checkOut->diffInDays($checkIn);
        $totalPrice = $room->price * $days;

        $hotel = $room->hotel;
        if ($hotel->discount_percentage > 0) {
            $discount = ($totalPrice * $hotel->discount_percentage) / 100;
            $totalPrice = max(0, $totalPrice - $discount);
        }

        DB::beginTransaction();
        try {
            $booking = $request->user()->bookings()->create([
                'bookable_type' => HotelRoom::class,
                'bookable_id' => $room->id,
                'status' => BookingStatus::PENDING->value,
                'total_price' => $totalPrice,
                'check_in_date' => $validated['check_in_date'],
                'check_out_date' => $validated['check_out_date'],
                'note_to_owner' => $validated['note_to_owner'] ?? null,
                'booked_date' => now(),
            ]);

            $room->update(['is_available' => false]);
            DB::commit();

            return apiResponse(true, 'Booking created successfully', new BookingResource($booking->load(['bookable.hotel.photos', 'bookable.photos'])), 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return apiResponse(false, 'Failed to create booking', $e->getMessage(), 500);
        }
    }

    public function show(Request $request, Booking $booking)
    {
        if ($booking->user_id !== $request->user()->id) {
            return apiResponse(false, 'Unauthorized', null, 403);
        }

        $booking->load(['bookable.hotel.photos', 'bookable.photos']);
        return apiResponse(true, 'Booking retrieved successfully', new BookingResource($booking), 200);
    }

    public function cancel(Request $request, Booking $booking)
    {
        if ($booking->user_id !== $request->user()->id) {
            return apiResponse(false, 'Unauthorized', null, 403);
        }

        if (in_array($booking->status, [BookingStatus::CANCELLED->value, BookingStatus::COMPLETED->value])) {
            return apiResponse(false, 'Cannot cancel this booking', null, 422);
        }

        $booking->update(['status' => BookingStatus::CANCELLED->value]);
        $booking->bookable->update(['is_available' => true]);

        return apiResponse(true, 'Booking cancelled successfully', new BookingResource($booking->load(['bookable.hotel.photos', 'bookable.photos'])), 200);
    }

    private function isRoomAvailable(HotelRoom $room, $checkIn, $checkOut): bool
    {
        return !Booking::where('bookable_type', HotelRoom::class)
            ->where('bookable_id', $room->id)
            ->whereIn('status', [BookingStatus::PENDING->value, BookingStatus::CONFIRMED->value])
            ->where('check_in_date', '<', $checkOut)
            ->where('check_out_date', '>', $checkIn)
            ->exists();
    }
}

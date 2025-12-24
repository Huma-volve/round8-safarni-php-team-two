<?php

namespace App\Http\Controllers\Api\Hotel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hotel\StoreHotelRatingRequest;
use App\Http\Requests\Hotel\UpdateHotelRatingRequest;
use App\Http\Resources\Hotel\RatingResource;
use App\Models\Hotel;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HotelRatingController extends Controller
{
    public function index(Hotel $hotel)
    {
        $ratings = $hotel->ratings()
            ->with('user')
            ->latest()
            ->paginate(10);

        return apiResponse(true, 'Ratings retrieved successfully', RatingResource::collection($ratings), 200);
    }

    public function store(StoreHotelRatingRequest $request, Hotel $hotel)
    {
        // Normalize photos to always be an array
        $photos = $request->file('photos', []);
        if (!is_array($photos)) {
            $photos = [$photos];
        }

        // Check if user already rated this hotel
        $existingRating = $hotel->ratings()->where('user_id', $request->user()->id)->first();
        if ($existingRating) {
            return apiResponse(false, 'You have already rated this hotel. Use update endpoint to modify your rating.', null, 422);
        }

        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $photoPaths = [];
            foreach ($photos as $photo) {
                $photoPaths[] = $photo->store('ratings/hotels', 'public');
            }

            $rating = $hotel->ratings()->create([
                'user_id' => $request->user()->id,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
                'photos' => json_encode($photoPaths),
            ]);

            $this->updateHotelRating($hotel);
            DB::commit();

            return apiResponse(true, 'Rating created successfully', new RatingResource($rating->load('user')), 200);
        } catch (\Exception $e) {
            DB::rollBack();
            foreach ($photoPaths as $path) {
                Storage::disk('public')->delete($path);
            }
            return apiResponse(false, 'Failed to create rating', $e->getMessage(), 500);
        }
    }

    public function update(UpdateHotelRatingRequest $request, Hotel $hotel, Rating $rating)
    {
        if ($rating->rateable_id !== $hotel->id || $rating->rateable_type !== Hotel::class) {
            return apiResponse(false, 'Rating not found for this hotel', null, 404);
        }

        $validated = $request->validated();

        // Normalize photos
        $photos = $request->file('photos', []);
        if (!is_array($photos)) {
            $photos = [$photos];
        }

        DB::beginTransaction();
        try {
            $existingPhotos = json_decode($rating->photos, true) ?? [];

            // Delete specified photos
            if (!empty($validated['delete_photos'])) {
                foreach ($validated['delete_photos'] as $photoToDelete) {
                    if (in_array($photoToDelete, $existingPhotos)) {
                        Storage::disk('public')->delete($photoToDelete);
                        $existingPhotos = array_diff($existingPhotos, [$photoToDelete]);
                    }
                }
            }

            // Upload new photos
            foreach ($photos as $photo) {
                $existingPhotos[] = $photo->store('ratings/hotels', 'public');
            }

            $rating->update([
                'rating' => $validated['rating'] ?? $rating->rating,
                'comment' => $validated['comment'] ?? $rating->comment,
                'photos' => json_encode(array_values($existingPhotos)),
            ]);

            $this->updateHotelRating($hotel);
            DB::commit();

            return apiResponse(true, 'Rating updated successfully', new RatingResource($rating->load('user')), 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return apiResponse(false, 'Failed to update rating', $e->getMessage(), 500);
        }
    }

    public function destroy(Hotel $hotel, Rating $rating)
    {
        if ($rating->rateable_id !== $hotel->id || $rating->rateable_type !== Hotel::class) {
            return apiResponse(false, 'Rating not found for this hotel', null, 404);
        }

        if (auth()->id() !== $rating->user_id) {
            return apiResponse(false, 'Unauthorized', null, 403);
        }

        DB::beginTransaction();
        try {
            $photos = json_decode($rating->photos, true) ?? [];
            foreach ($photos as $photo) {
                Storage::disk('public')->delete($photo);
            }

            $rating->delete();
            $this->updateHotelRating($hotel);
            DB::commit();

            return apiResponse(true, 'Rating deleted successfully', null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return apiResponse(false, 'Failed to delete rating', $e->getMessage(), 500);
        }
    }

    // Update hotel average rating & reviews count
    private function updateHotelRating(Hotel $hotel): void
    {
        $hotel->update([
            'average_rating' => $hotel->ratings()->avg('rating') ?? 0,
            'reviews_count' => $hotel->ratings()->count(),
        ]);
    }
}

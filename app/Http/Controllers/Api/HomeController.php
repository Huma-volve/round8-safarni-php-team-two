<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tour;
use Illuminate\Http\Request;

class HomeController extends Controller
{
            public function index()
    {

            $categories = Category::select('id', 'key', 'title')->get();

            $recommendedTours = Tour::where('available', true)
            ->orderByDesc('rating')
            ->limit(6)
            ->get();


            $availableTours = Tour::where('available', true)
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

                    return response()->json([
            'success' => true,
            'data' => [
                'categories' => $categories,
                'recommended_tours' => $recommendedTours,
                'available_tours' => $availableTours
            ]
        ]);

     
    }
}

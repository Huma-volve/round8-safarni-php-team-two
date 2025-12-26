<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Http\Request;

class SearchController extends Controller
{
        public function index(Request $request)
    {
        $query = Tour::query()->where('available', 'active');

        if ($request->filled('location')) {
            $query->where('location', 'LIKE', '%' . $request->location . '%');
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        
        if ($request->sort === 'rating_desc') {
            $query->orderByDesc('rating');
        } elseif ($request->sort === 'price_asc') {
            $query->orderBy('price');
        } elseif ($request->sort === 'price_desc') {
            $query->orderByDesc('price');
        }

        $results = $query->paginate(20);


         if ($results->isEmpty()) {
        return response()->json([
            'success' => false,
            'error' => [
                'code' => 'NOT_FOUND',
                'message' => 'No tours found for the given search criteria'
            ]
        ], 404);
    }else{

        return response()->json([
            'success' => true,
            'data' => $results->items(),
            'meta' => [
                'total' => $results->total(),
                'current_page' => $results->currentPage(),
                'per_page' => $results->perPage(),
            ]
        ]);
    }
    }

}

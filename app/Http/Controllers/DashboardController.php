<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        // Get the search query, default to empty if not set.
        $search = $request->get('search', '');

        $items = Item::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'LIKE', "%{$search}%")
                             ->orWhere('description', 'LIKE', "%{$search}%");
            })
            ->oldest()
            ->limit(10)
            ->get();

        return Inertia::render('Dashboard', [
            'items' => $items,
        ]);
    }
}

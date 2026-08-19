<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Controllers;

use App\Http\Controllers\Controller;
use Diepxuan\Catalog\Http\Livewire\Component\InputYearWorked;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class YearController extends Controller
{
    public function select(Request $request): JsonResponse
    {
        $currentYear = (int) now()->year;
        $validated = $request->validate([
            'year' => ['required', 'integer', 'min:' . InputYearWorked::FIRST_YEAR, 'max:' . ($currentYear + 1)],
        ]);

        $year = (int) $validated['year'];
        catalog()->year($year);

        return response()->json([
            'year' => $year,
        ]);
    }
}

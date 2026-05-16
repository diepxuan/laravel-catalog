<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:27:52
 */

namespace Diepxuan\Catalog\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['message' => 'Catalog API is not implemented.'], 501);
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Catalog API is not implemented.'], 501);
    }

    public function show(string $id): JsonResponse
    {
        return response()->json(['message' => 'Catalog API is not implemented.'], 501);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        return response()->json(['message' => 'Catalog API is not implemented.'], 501);
    }

    public function destroy(string $id): JsonResponse
    {
        return response()->json(['message' => 'Catalog API is not implemented.'], 501);
    }
}

<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-07-03 19:22:49
 */

namespace Diepxuan\Catalog\Http\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SoPh3Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return SoPh3::getAsSoFilt3([
            'pMa_Cty'   => \CatalogService::company()->id,
            'pNgay_Ct1' => \CatalogService::timerFrom(),
            'pNgay_Ct2' => \CatalogService::timerTo(),
            'pMa_CT'    => $this->pMa_CT,
            'pMa_Kh'    => $this->pMa_Kh,
            'pMa_Nt'    => $this->pMa_Nt,
            'pMa_Bp'    => $this->pMa_Bp,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): void {}

    /**
     * Display the specified resource.
     */
    public function show(string $id): void {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): void {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): void {}
}

<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-30 07:52:43
 */

namespace Diepxuan\Catalog\Http\Controllers;

use App\Http\Controllers\Controller;
use Diepxuan\Catalog\Http\Requests\SimbaRequest;
use Diepxuan\Catalog\Models\Category;
use Diepxuan\Catalog\Models\InventoryTicket;
use Diepxuan\Simba\Models\KhoHang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SimbaRequest $request)
    {
        $validated = $request->validated();

        return view('catalog::inventory.index', [
            'lstPhdck' => InventoryTicket::whereNgayCt($request->get('from'), $request->get('to'))
                ->whereKhoXuat($request->get('khoxuat'))
                ->orderBy('ngay_ct', 'asc')
                ->orderBy('so_ct', 'asc')
                ->orderBy('ma_kh', 'asc')
                ->get(),
            'from'    => $request->get('from'),
            'to'      => $request->get('to'),
            'khoxuat' => $request->get('khoxuat'),
            'lstKho'  => KhoHang::isEnable()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('catalog::inventory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        return Redirect::route('inventory.index');
    }

    /**
     * Show the specified resource.
     */
    public function show(SimbaRequest $request, InventoryTicket $tonkho)
    {
        $lstPhdck = InventoryTicket::whereNgayCt($request->get('from'), $request->get('to'))
            ->whereKhoXuat($request->get('khoxuat'))
            ->orderBy('ngay_ct', 'asc')
            ->orderBy('so_ct', 'asc')
            ->orderBy('ma_kh', 'asc')
            ->get()
        ;

        if ($request->get('to')->eq($tonkho->ngayCt)) {
            $query = array_merge($request->query(), [
                'tonkho' => $tonkho->getKey(),
                'from'   => $request->get('from')->format('Y-m-d'),
                'to'     => $request->get('to')->addDay()->format('Y-m-d'),
            ]);

            return Redirect::route('inventory.show', array_merge(['tonkho' => $tonkho->getKey()], $query));
        }

        $currentIndex = $lstPhdck->search(static fn ($item) => $tonkho->getKey() === $item->getKey());

        return view('catalog::inventory.show', [
            'phdck'     => $tonkho,
            'nextPhdck' => $lstPhdck->get($currentIndex + 1),
            'prevPhdck' => $lstPhdck->get($currentIndex - 1),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param mixed $id
     */
    public function edit($id)
    {
        return view('catalog::inventory.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        return Redirect::route('inventory.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $id
     */
    public function destroy($id): void {}
}

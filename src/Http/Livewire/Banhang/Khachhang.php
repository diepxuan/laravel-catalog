<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-07 13:05:53
 */

namespace Diepxuan\Catalog\Http\Livewire\Banhang;

use Diepxuan\Catalog\Models\ArDmKh;
use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsARDelDMKH;
use Diepxuan\Simba\StoredProcedures\AsARGetDMKH;
use Diepxuan\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Component Khachhang.
 *
 * Hiển thị danh sách khách hàng với tìm kiếm và phân trang.
 * Sử dụng Stored Procedure asARGetDMKH thay vì Eloquent query.
 */
class Khachhang extends Component
{
    /**
     * Từ khóa tìm kiếm.
     */
    public string $search = '';

    /**
     * Số lượng items mỗi trang.
     */
    protected int $perPage = 50;

    /**
     * Query string.
     *
     * @var array<string, string>
     */
    protected $queryString = [
        'search' => ['except' => ''],
    ];

    /**
     * Reset pagination về trang 1 khi search thay đổi.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Xóa khách hàng theo mã qua Stored Procedure.
     *
     * @param string $maKh mã khách hàng
     */
    public function deleteKhachHang(string $maKh): void
    {
        $khachHang = ArDmKh::withoutGlobalScopes()
            ->where('ma_kh', $maKh)
            ->first()
        ;

        if (!$khachHang) {
            $this->dispatch('error', message: 'Không tìm thấy khách hàng.');

            return;
        }

        if ($khachHang->hasTransactions()) {
            $this->dispatch('error', message: 'Không thể xóa khách hàng đã có giao dịch.');

            return;
        }

        // Đánh dấu ksd = false qua Stored Procedure
        try {
            AsARDelDMKH::call([
                'pMa_cty' => SModel::CTY,
                'pMa_kh'  => $maKh,
            ]);

            $this->dispatch('success', message: 'Đã xóa khách hàng ' . $maKh);
            $this->dispatch('khachhang-saved');
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Không thể xóa khách hàng: ' . $e->getMessage());
        }
    }

    /**
     * Render the component.
     *
     * Sử dụng Stored Procedure asARGetDMKH thay vì Eloquent query.
     */
    public function render(): View
    {
        $khachhangs = $this->getKhachHangsPaginated();

        return view('catalog::banhang.khachhang', [
            'khachhangs' => $khachhangs,
        ])->layout('catalog::layouts.app');
    }

    /**
     * Lấy danh sách khách hàng đã phân trang từ SP.
     */
    protected function getKhachHangsPaginated(): LengthAwarePaginatorContract
    {
        // Gọi SP asARGetDMKH với module AR
        $results = AsARGetDMKH::getCustomers(
            maCty: SModel::CTY,
            search: '' !== $this->search ? $this->search : null,
        );

        // Filter bổ sung trong PHP cho search mở rộng (ten_kh, dia_chi, tel)
        // SP chỉ hỗ trợ prefix search trên ma_kh
        if ('' !== $this->search) {
            $results = $this->filterSearchResults($results);
        }

        return $this->paginateCollection($results);
    }

    /**
     * Filter kết quả SP cho search mở rộng (ten_kh, dia_chi, tel).
     *
     * @param mixed $results
     */
    protected function filterSearchResults($results): Collection
    {
        $search = mb_strtolower($this->search);

        return collect($results)->filter(static function ($item) use ($search): bool {
            $fields = [
                $item->MA_KH ?? '',
                $item->TEN_KH ?? '',
                $item->DIA_CHI ?? '',
                $item->TEL ?? '',
            ];

            foreach ($fields as $field) {
                if (str_contains(mb_strtolower($field), $search)) {
                    return true;
                }
            }

            return false;
        })->values();
    }

    /**
     * Manual pagination cho Collection.
     *
     * @param mixed $items
     */
    protected function paginateCollection($items): LengthAwarePaginatorContract
    {
        $page   = Paginator::resolveCurrentPage();
        $total  = \count($items);
        $offset = ($page - 1) * $this->perPage;

        $pagedItems = collect($items)->slice($offset, $this->perPage)->values();

        return new LengthAwarePaginator(
            $pagedItems,
            $total,
            $this->perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()],
        );
    }
}

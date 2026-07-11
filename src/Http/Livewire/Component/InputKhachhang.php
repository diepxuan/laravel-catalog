<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-07-09
 */

namespace Diepxuan\Catalog\Http\Livewire\Component;

use Diepxuan\Simba\StoredProcedures\AsARGetDMKH;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Modelable;
use Livewire\Component;

/**
 * Input autocomplete đối tượng (khách hàng, nhà cung cấp, nhân viên).
 *
 * Hỗ trợ nhiều mode (single hoặc multi-mode, OR logic):
 *   - khachhang  -> SP asARGetDMKH(pModuleId = 'AR')
 *   - nhacungcap -> SP asARGetDMKH(pModuleId = 'AP')
 *   - nhanvien   -> SP asARGetDMKH(pModuleId = 'CA')
 *   - all        -> tổng hợp cả 3 module
 *
 * Nguồn tra cứu:
 *   - simba-docs/data/sysDictionaryInfo.md (MA_KH, MA_NCC -> ARDMKH)
 *   - simba-docs/data/sysDAOInfo.md        (ARDMKH -> asARGetDMKH)
 *   - simba-docs/procedures/AR/procedures.md (asARGetDMKH signature)
 *
 * Lưu ý: Component này chỉ lookup/chọn; thêm/sửa/xóa thuộc task nghiệp vụ.
 */
class InputKhachhang extends Component
{
    /** Mode lọc đối tượng. */
    public string $mode = 'khachhang';

    /** Giá trị selected (mã đối tượng). */
    #[Modelable]
    public ?string $value = null;

    /** Text hiển thị (tên đối tượng). */
    public string $search = '';

    /** Placeholder text. */
    public string $placeholder = '';

    /**
     * Cache danh mục theo module trong lifecycle render hiện tại.
     *
     * @var array<string, array<int, array<string, mixed>>>
     */
    protected array $dmCache = [];

    /**
     * Mount component.
     */
    public function mount(?string $value = null, string $mode = 'khachhang'): void
    {
        $this->value       = $value;
        $this->mode        = $mode;
        $this->placeholder = $this->getPlaceholderByMode();

        if ($value) {
            $row = $this->findOneByMaKh($value);
            if ($row) {
                $this->search = $row['ten_kh'] ?? '';
            }
        }
    }

    /**
     * Chọn đối tượng từ dropdown.
     */
    public function selectCustomer(string $ma_kh, string $ten_kh): void
    {
        $this->value  = $ma_kh;
        $this->search = $ten_kh;

        $this->dispatch('value-updated', $ma_kh);
    }

    /**
     * Đóng dropdown.
     */
    public function blur(): void
    {
        $this->dispatch('close-dropdown');
    }

    /**
     * Xóa selection.
     */
    public function clear(): void
    {
        $this->value  = null;
        $this->search = '';
        $this->dispatch('value-updated', null);
    }

    /**
     * Render component.
     */
    public function render(): View
    {
        return view('catalog::components.input-khachhang', [
            'customers' => $this->customerOptions(),
        ]);
    }

    /**
     * Danh sách rút gọn cho Alpine local search.
     *
     * @return array<int, array{ma_kh: string, ten_kh: string, dia_chi: string, tel: string}>
     */
    protected function customerOptions(): array
    {
        $seen   = [];
        $merged = [];

        foreach ($this->resolveModules() as $moduleId) {
            foreach ($this->fetchDmKhByModule($moduleId) as $row) {
                $maKh = $row['ma_kh'] ?? null;
                if (null === $maKh || isset($seen[$maKh])) {
                    continue;
                }

                $seen[$maKh] = true;
                $merged[]    = [
                    'ma_kh'   => (string) $maKh,
                    'ten_kh'  => (string) ($row['ten_kh']  ?? ''),
                    'dia_chi' => (string) ($row['dia_chi'] ?? ''),
                    'tel'     => (string) ($row['tel']     ?? ''),
                ];
            }
        }

        return $merged;
    }

    /**
     * Map mode -> danh sách pModuleId.
     *
     * @return array<int, string>
     */
    protected function resolveModules(): array
    {
        $modes = array_map('trim', preg_split('/[,.|]/', $this->mode) ?: []);

        if (\in_array('all', $modes, true)) {
            return ['AR', 'AP', 'CA'];
        }

        $map = [
            'khachhang'  => 'AR',
            'nhacungcap' => 'AP',
            'nhanvien'   => 'CA',
        ];

        $modules = [];
        foreach ($modes as $m) {
            if (isset($map[$m])) {
                $modules[] = $map[$m];
            }
        }

        return [] === $modules ? ['AR'] : array_values(array_unique($modules));
    }

    /**
     * Gọi SP asARGetDMKH cho 1 module.
     *
     * Simba dùng `ksd = 0` cho bản ghi đang sử dụng, `ksd = 1` là khóa.
     */
    protected function fetchDmKhByModule(string $moduleId): array
    {
        if (isset($this->dmCache[$moduleId])) {
            return $this->dmCache[$moduleId];
        }

        $rows = AsARGetDMKH::call([
            'pMa_cty'   => \CatalogService::company()->id,
            'pMa_kh'    => null,
            'pStruct'   => '0',
            'pModuleId' => $moduleId,
        ])->all();

        return $this->dmCache[$moduleId] = array_values(array_filter(
            array_map(
                static function ($row) {
                    if (\is_array($row)) {
                        return self::normalizeRow($row);
                    }
                    if (\is_object($row)) {
                        return self::normalizeRow((array) $row);
                    }

                    return [];
                },
                $rows
            ),
            static fn (array $row): bool => self::isActiveRow($row)
        ));
    }

    /**
     * Chuẩn hóa key của một row về lowercase (giữ nguyên thứ tự key).
     */
    protected static function normalizeRow(array $row): array
    {
        $lower = [];
        foreach ($row as $k => $v) {
            $lower[strtolower((string) $k)] = $v;
        }

        return $lower;
    }

    /**
     * Tìm 1 record theo mã đối tượng, dùng cùng SP để tránh nguồn khác.
     * Cũng áp dụng lọc `ksd = 0` theo lookup Simba.
     */
    protected function findOneByMaKh(string $maKh): ?array
    {
        foreach ($this->resolveModules() as $moduleId) {
            $rows = AsARGetDMKH::call([
                'pMa_cty'   => \CatalogService::company()->id,
                'pMa_kh'    => $maKh,
                'pStruct'   => '0',
                'pModuleId' => $moduleId,
            ])->all();
            foreach ($rows as $row) {
                $obj = \is_array($row) ? $row : (array) $row;
                $lower = self::normalizeRow($obj);
                if (($lower['ma_kh'] ?? null) === $maKh && self::isActiveRow($lower)) {
                    return $lower;
                }
            }
        }

        return null;
    }

    protected static function isActiveRow(array $row): bool
    {
        $ksd = $row['ksd'] ?? false;
        if (\is_string($ksd)) {
            $ksd = trim($ksd);
        }

        return !\in_array($ksd, [true, 1, '1'], true);
    }

    /**
     * Lấy placeholder theo mode.
     */
    protected function getPlaceholderByMode(): string
    {
        $modes = array_map('trim', preg_split('/[,.|]/', $this->mode) ?: []);

        if (\count($modes) > 1 || \in_array('all', $modes, true)) {
            return 'Chọn đối tượng...';
        }

        return match ($modes[0]) {
            'khachhang'  => 'Chọn khách hàng...',
            'nhacungcap' => 'Chọn nhà cung cấp...',
            'nhanvien'   => 'Chọn nhân viên...',
            default      => 'Chọn đối tượng...',
        };
    }
}

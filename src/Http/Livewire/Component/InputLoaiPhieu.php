<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-11-27
 */

namespace Diepxuan\Catalog\Http\Livewire\Component;

use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsSIGetDmSo_ct;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Input autocomplete chọn Loại phiếu (MA_CT) theo phân hệ.
 *
 * Dùng lại được cho MỌI màn cần chọn loại chứng từ: PO, SO, CA, IN, GL...
 *
 * Nguồn dữ liệu:
 *   - SP `asSIGetDmSo_ct` (4 tham số thật theo INFORMATION_SCHEMA):
 *       @pMa_cty, @pPhan_he, @pStruct, @pLanguageId
 *   - Form gốc `frmPORptBK01.InitOtherComponents()`:
 *       Commons.GetDataReader("asSIGetDmSo_ct", [CompanyID, MyMenuInfo.moduleid, "0", CurrentCultureName])
 *       -> DisplayMember = TEN_CT, ValueMember = MA_CT
 *       -> sau đó BỔ SUNG item "Tất cả" (mã rỗng) ở cuối
 *
 * Trả về: ma_ct, ten_ct
 *
 * Cách dùng trong blade:
 *   <livewire:catalog::component.input-loai-phieu
 *       wire:model="pMa_ct"
 *       phan-he="PO"
 *       :include-all="true" />
 */
class InputLoaiPhieu extends Component
{
    #[Modelable]
    public ?string $value = null;

    /** Text hiển thị. */
    public string $search = '';

    /** Phân hệ: PO / SO / CA / IN / GL ... (sysMenu.moduleid). */
    public string $phanHe = 'PO';

    /** Có thêm lựa chọn "Tất cả" (mã rỗng) hay không. */
    public bool $includeAll = true;

    /** Nhãn cho lựa chọn "Tất cả". */
    public string $allLabel = 'Tất cả';

    /**
     * Culture name gui xuong SP.
     *
     * QUAN TRONG: form goc dung SystemInformations.CurrentCultureName
     * (vd 'vi-VN'). Neu truyen app()->getLocale() thi Laravel tra 'en'
     * -> SP asSIGetDmSo_ct tra 0 dong. Da kiem chung:
     *   'vi-VN' -> 6 dong | 'en' -> 0 | 'vi' -> 0 | '' -> 2
     */
    public string $languageId = 'vi-VN';

    public string $placeholder = 'Chọn loại phiếu';

    /** @var array<int, array{ma_ct: string, ten_ct: string}>|null */
    protected ?array $ctCache = null;

    public function mount(?string $value = null, ?string $phanHe = null, bool $includeAll = true): void
    {
        if (null !== $phanHe && '' !== $phanHe) {
            $this->phanHe = $phanHe;
        }

        $this->includeAll = $includeAll;

        if (null !== $value && '' !== $value) {
            $this->value = $value;
            $this->refreshSearchFromValue();
        }
    }

    /**
     * Khi parent truyen san gia tri qua wire:model (vd pMa_ct='PO3'),
     * mount() khong nhan duoc -> phai sync o hydrate.
     */
    public function hydrate(): void
    {
        if (null !== $this->value && '' !== trim((string) $this->value) && '' === trim($this->search)) {
            $this->refreshSearchFromValue();
        }
    }

    public function updatedValue(mixed $newValue): void
    {
        $val = null === $newValue ? '' : trim((string) $newValue);

        if ('' === $val) {
            $this->search = $this->includeAll ? $this->allLabel : '';

            return;
        }

        $row = $this->findOneByMaCt($val);
        $this->search = null !== $row ? (string) $row['ten_ct'] : $val;
    }

    /**
     * Chọn loại phiếu từ dropdown.
     */
    public function selectLoaiPhieu(?string $ma_ct = null, ?string $ten_ct = null): void
    {
        $ma = null === $ma_ct ? '' : trim($ma_ct);

        $this->value = '' === $ma ? null : $ma;
        $this->search = null === $ten_ct ? '' : $ten_ct;

        $this->dispatch('loai-phieu-updated', ma_ct: $this->value, ten_ct: $this->search);
    }

    /**
     * Đồng bộ khi parent set mã qua event.
     */
    #[On('loai-phieu-set')]
    public function setValue(?string $ma_ct = null): void
    {
        if (null === $ma_ct || '' === trim($ma_ct)) {
            $this->value = null;
            $this->search = $this->includeAll ? $this->allLabel : '';

            return;
        }

        $this->value = $ma_ct;
        $this->refreshSearchFromValue();
    }

    public function clear(): void
    {
        $this->value = null;
        $this->search = $this->includeAll ? $this->allLabel : '';

        $this->dispatch('loai-phieu-updated', ma_ct: null, ten_ct: null);
    }

    public function render(): View
    {
        // Dam bao search luon phan anh dung value hien tai (ke ca lan render dau).
        if (null !== $this->value && '' !== trim((string) $this->value) && '' === trim($this->search)) {
            $this->refreshSearchFromValue();
        }

        return view('catalog::components.input-loai-phieu', [
            'loaiPhieuList' => $this->ctOptions(),
        ]);
    }

    /**
     * @return array<int, array{ma_ct: string, ten_ct: string}>
     */
    protected function ctOptions(): array
    {
        if (null !== $this->ctCache) {
            return $this->ctCache;
        }

        $rows = AsSIGetDmSo_ct::call([
            'pMa_cty' => SModel::CTY,
            'pPhan_he' => mb_strtoupper($this->phanHe),
            'pStruct' => '0',
            'pLanguageId' => $this->languageId,
        ])->all();

        $out = [];
        foreach ($rows as $row) {
            $row = \is_array($row) ? $row : (array) $row;

            $ma = trim((string) ($row['ma_ct'] ?? ''));
            if ('' === $ma) {
                continue;
            }

            $out[] = [
                'ma_ct' => $ma,
                'ten_ct' => (string) ($row['ten_ct'] ?? ''),
            ];
        }

        // Form gốc bổ sung "Tất cả" (mã rỗng) ở cuối danh sách.
        if ($this->includeAll) {
            $out[] = ['ma_ct' => '', 'ten_ct' => $this->allLabel];
        }

        return $this->ctCache = $out;
    }

    /**
     * @return null|array{ma_ct: string, ten_ct: string}
     */
    protected function findOneByMaCt(string $maCt): ?array
    {
        foreach ($this->ctOptions() as $row) {
            if ($row['ma_ct'] === $maCt) {
                return $row;
            }
        }

        return null;
    }

    protected function refreshSearchFromValue(): void
    {
        if (null === $this->value || '' === trim($this->value)) {
            $this->search = $this->includeAll ? $this->allLabel : '';

            return;
        }

        $row = $this->findOneByMaCt($this->value);
        $this->search = null !== $row ? (string) $row['ten_ct'] : $this->value;
    }
}

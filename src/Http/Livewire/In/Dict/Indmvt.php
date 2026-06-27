<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\In\Dict;

use Diepxuan\Catalog\Models\Simba\InDmKho;
use Diepxuan\Catalog\Models\Simba\InDmNhvt;
use Diepxuan\Catalog\Models\Simba\InDmVt as InDmVtModel;
use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsDoiMa;
use Diepxuan\Simba\StoredProcedures\AsINGetDMBOM;
use Diepxuan\Simba\StoredProcedures\AsInGetDmLoaiGiaTon;
use Diepxuan\Simba\StoredProcedures\AsInGetDmLoaiVt;
use Diepxuan\Simba\StoredProcedures\ProcedureCaller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Livewire\Component;

class Indmvt extends Component
{
    /** @var list<array<string, mixed>> */
    public array $rows = [];

    /** @var list<array<string, mixed>> */
    public array $nhvtOptions = [];

    /** @var list<array<string, mixed>> */
    public array $khoOptions = [];

    /** @var list<array<string, mixed>> */
    public array $loaiOptions = [];

    /** @var list<array<string, mixed>> */
    public array $giaTonOptions = [];

    /** @var array<string, mixed> */
    public array $form = [];

    /** @var list<array<string, mixed>> */
    public array $bomRows = [];

    /** @var list<string> */
    public array $originalBomKeys = [];

    /** @var array<string, string> */
    public array $renameForm = [
        'old_ma_vt' => '',
        'new_ma_vt' => '',
    ];

    public string $search = '';
    public ?string $selectedMaVt = null;
    public ?string $deleteMaVt = null;
    public bool $showForm = false;
    public bool $showRenameForm = false;
    public bool $isEditing = false;
    public ?string $statusMessage = null;
    public ?string $errorMessage = null;
    public ?string $loadError = null;

    public function mount(): void
    {
        $this->resetForm();
        $this->refreshData();
    }

    public function refreshData(): void
    {
        $this->loadError = null;
        $this->loadRows();
        $this->loadLookups();
    }

    public function updated(string $property): void
    {
        if ('form.ma_nhvt' === $property) {
            $this->fillAccountsFromGroup();
        }
    }

    public function selectItem(string $maVt): void
    {
        $this->selectedMaVt = $maVt;
        $this->deleteMaVt = null;
        $this->loadBomRows($maVt);
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showForm = true;
        $this->showRenameForm = false;
        $this->deleteMaVt = null;
        $this->selectedMaVt = null;
        $this->dispatch('indmvt-form-opened');
    }

    public function openEdit(?string $maVt = null): void
    {
        $maVt ??= $this->selectedMaVt;
        if (null === $maVt || '' === trim($maVt)) {
            $this->errorMessage = 'Chưa chọn vật tư để sửa.';

            return;
        }

        $row = $this->findRow($maVt);
        if (null === $row) {
            $this->errorMessage = "Không tìm thấy vật tư {$maVt}.";

            return;
        }

        $this->fillFormFromRow($row);
        $this->selectedMaVt = $this->form['ma_vt'];
        $this->isEditing = true;
        $this->showForm = true;
        $this->showRenameForm = false;
        $this->deleteMaVt = null;
        $this->loadBomRows((string) $this->form['ma_vt']);
        $this->dispatch('indmvt-form-opened');
    }

    public function cancelForm(): void
    {
        $this->showForm = false;
        $this->resetValidation();
    }

    public function openRename(?string $maVt = null): void
    {
        $maVt ??= $this->selectedMaVt;
        if (null === $maVt || '' === trim($maVt)) {
            $this->errorMessage = 'Chưa chọn vật tư để đổi mã.';

            return;
        }

        if (null === $this->findRow($maVt)) {
            $this->errorMessage = "Không tìm thấy vật tư {$maVt}.";

            return;
        }

        $this->renameForm = [
            'old_ma_vt' => $maVt,
            'new_ma_vt' => '',
        ];
        $this->selectedMaVt = $maVt;
        $this->deleteMaVt = null;
        $this->showForm = false;
        $this->showRenameForm = true;
        $this->statusMessage = null;
        $this->errorMessage = null;
        $this->dispatch('indmvt-rename-opened');
    }

    public function cancelRename(): void
    {
        $this->showRenameForm = false;
        $this->resetValidation();
    }

    public function renameItem(): void
    {
        $this->statusMessage = null;
        $this->errorMessage = null;

        $validator = Validator::make($this->renameForm, [
            'old_ma_vt' => ['required', 'string', 'max:20'],
            'new_ma_vt' => ['required', 'string', 'max:20', 'different:old_ma_vt'],
        ], [
            'old_ma_vt.required' => 'Mã vật tư cũ không được rỗng.',
            'new_ma_vt.required' => 'Mã vật tư mới không được rỗng.',
            'new_ma_vt.different' => 'Mã vật tư mới phải khác mã hiện tại.',
        ]);

        $validator->after(function ($validator): void {
            $oldMaVt = trim((string) ($this->renameForm['old_ma_vt'] ?? ''));
            $newMaVt = trim((string) ($this->renameForm['new_ma_vt'] ?? ''));
            if ('' !== $oldMaVt && null === $this->findRow($oldMaVt)) {
                $validator->errors()->add('old_ma_vt', 'Không tìm thấy vật tư cần đổi mã.');
            }
            if ('' !== $newMaVt && $this->codeExists($newMaVt)) {
                $validator->errors()->add('new_ma_vt', 'Mã vật tư mới đã tồn tại.');
            }
        });

        $validator->validate();

        $oldMaVt = trim((string) $this->renameForm['old_ma_vt']);
        $newMaVt = trim((string) $this->renameForm['new_ma_vt']);

        try {
            AsDoiMa::call([
                'pMa_Cty' => $this->companyId(),
                'pCode_name' => 'MA_VT',
                'pOldValue' => $oldMaVt,
                'pNewValue' => $newMaVt,
                'pTable_name' => 'INDMVT',
            ]);

            $this->statusMessage = "Đã đổi mã vật tư {$oldMaVt} thành {$newMaVt}.";
            $this->selectedMaVt = $newMaVt;
            $this->showRenameForm = false;
            $this->refreshData();
            $this->loadBomRows($newMaVt);
        } catch (\Throwable $exception) {
            $this->errorMessage = $exception->getMessage();
        }
    }

    public function confirmDelete(string $maVt): void
    {
        $this->deleteMaVt = $maVt;
        $this->selectedMaVt = $maVt;
        $this->showRenameForm = false;
        $this->statusMessage = null;
        $this->errorMessage = null;
    }

    public function cancelDelete(): void
    {
        $this->deleteMaVt = null;
    }

    public function deleteItem(): void
    {
        if (null === $this->deleteMaVt || '' === trim($this->deleteMaVt)) {
            return;
        }

        try {
            $this->callWriteProcedure('asINDelDMVT', [
                'pMa_cty' => $this->companyId(),
                'pMa_vt'  => $this->deleteMaVt,
            ]);

            $this->statusMessage = "Đã xóa vật tư {$this->deleteMaVt}.";
            $this->deleteMaVt = null;
            $this->selectedMaVt = null;
            $this->showForm = false;
            $this->refreshData();
        } catch (\Throwable $exception) {
            $this->errorMessage = $exception->getMessage();
        }
    }

    public function save(): void
    {
        $this->statusMessage = null;
        $this->errorMessage = null;

        $this->validateForm();

        try {
            DB::connection((new SModel())->getConnectionName())->transaction(function (): void {
                $procedure = $this->isEditing ? 'asINUpdDMVT' : 'asINInsDMVT';
                $this->callWriteProcedure($procedure, $this->dmvtPayload());
                $this->syncBomRows();
            });

            $this->statusMessage = $this->isEditing
                ? "Đã cập nhật vật tư {$this->form['ma_vt']}."
                : "Đã thêm vật tư {$this->form['ma_vt']}.";
            $this->showForm = false;
            $this->selectedMaVt = (string) $this->form['ma_vt'];
            $this->refreshData();
            $this->loadBomRows((string) $this->form['ma_vt']);
        } catch (\Throwable $exception) {
            $this->errorMessage = $exception->getMessage();
        }
    }

    public function addBomRow(): void
    {
        $this->form['co_ct'] = true;
        $this->bomRows[] = $this->blankBomRow();
    }

    public function removeBomRow(int $index): void
    {
        if (!isset($this->bomRows[$index])) {
            return;
        }

        unset($this->bomRows[$index]);
        $this->bomRows = array_values($this->bomRows);
    }

    public function fillBomItem(int $index): void
    {
        if (!isset($this->bomRows[$index])) {
            return;
        }

        $maLk = trim((string) ($this->bomRows[$index]['ma_lk'] ?? ''));
        $item = '' === $maLk ? null : $this->findRow($maLk);
        if (null === $item) {
            return;
        }

        $this->bomRows[$index]['ten_lk'] = $item['ten_vt'] ?? '';
        $this->bomRows[$index]['dvt'] = $item['dvt'] ?? '';
    }

    public function render(): View
    {
        return view('catalog::in.dict.indmvt', [
            'displayRows' => $this->filteredRows(),
        ])->layout('catalog::layouts.app');
    }

    private function loadRows(): void
    {
        try {
            $collection = InDmVtModel::getAsINGetDMVT([
                'pMa_Cty'   => $this->companyId(),
                'pMa_vt'    => null,
                'pStruct'   => null,
                'pLanguage' => $this->languageName(),
            ]);

            $this->rows = $this->normalizeRows($collection->all());
        } catch (\Throwable $exception) {
            $this->rows = [];
            $this->loadError = $exception->getMessage();
        }
    }

    private function loadLookups(): void
    {
        $this->nhvtOptions = $this->tryLookup(fn () => InDmNhvt::getAsINGetDMNHVT([
            'pMa_Cty'  => $this->companyId(),
            'pMa_nhvt' => null,
            'pStruct'  => null,
        ])->all());

        $this->khoOptions = $this->tryLookup(fn () => InDmKho::getAsINGetDMKHO([
            'pMa_Cty' => $this->companyId(),
            'pMa_kho' => null,
            'pStruct' => null,
        ])->all());

        $this->loaiOptions = $this->tryLookup(fn () => AsInGetDmLoaiVt::call([
            'pMa_cty'   => $this->companyId(),
            'pLanguage' => $this->languageName(),
        ])->all());

        $this->giaTonOptions = $this->tryLookup(fn () => AsInGetDmLoaiGiaTon::call([
            'pMa_cty'   => $this->companyId(),
            'pLanguage' => $this->languageName(),
        ])->all());
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function tryLookup(callable $callback): array
    {
        try {
            return $this->normalizeRows($callback());
        } catch (\Throwable) {
            return [];
        }
    }

    private function loadBomRows(string $maVt): void
    {
        try {
            $collection = AsINGetDMBOM::call([
                'pMa_cty' => $this->companyId(),
                'pMa_vt'  => $maVt,
            ]);

            $this->bomRows = array_map(
                fn (array $row): array => array_merge($this->blankBomRow(), [
                    'ma_lk'    => $row['ma_lk'] ?? '',
                    'ten_lk'   => $row['ten_lk'] ?? '',
                    'dvt'      => $row['dvt'] ?? '',
                    'so_luong' => (float) ($row['so_luong'] ?? 0),
                    'he_so'    => (float) ($row['he_so'] ?? 1),
                    'ghi_chu'  => $row['ghi_chu'] ?? '',
                    'ksd'      => $this->toBool($row['ksd'] ?? false),
                ]),
                $this->normalizeRows($collection->all())
            );
            $this->originalBomKeys = array_values(array_filter(array_map(
                static fn (array $row): string => trim((string) ($row['ma_lk'] ?? '')),
                $this->bomRows
            )));
        } catch (\Throwable $exception) {
            $this->bomRows = [];
            $this->originalBomKeys = [];
            $this->errorMessage = $exception->getMessage();
        }
    }

    private function resetForm(): void
    {
        $this->form = [
            'ma_vt' => '', 'loai' => '1', 'ten_vt' => '', 'part_no' => '', 'dvt' => '',
            'dvt_ban' => '', 'hs_dvtban' => 1, 'dvt_mua' => '', 'hs_dvtmua' => 1,
            'ton_kho' => true, 'gia_ton' => '1', 'tk_vt' => '', 'tk_dt' => '', 'tk_dtnb' => '',
            'tk_gv' => '', 'tk_tl' => '', 'tk_ck' => '', 'tk_km' => '', 'tk_dd' => '', 'tk_cpnvl' => '',
            'ma_thue' => '', 'tinh_gt' => false, 'ma_nhvt' => '', 'ma_plvt1' => '', 'ma_plvt2' => '',
            'ma_plvt3' => '', 'ma_nhvat' => '', 'sl_min' => 0, 'sl_max' => 0, 'gia_nt0' => 0,
            'gia_nt2' => 0, 'ts_nk' => 0, 'ts_gtgt' => 0, 'ts_xk' => 0, 'ts_ttdb' => 0,
            'ma_kho' => '', 'ma_vitri' => '', 'co_ct' => false, 'ghi_chu' => '',
            'nha_sx' => '', 'nha_pp' => '', 'nuoc_sx' => '', 'ksd' => false,
        ];
        $this->bomRows = [];
        $this->originalBomKeys = [];
        $this->renameForm = ['old_ma_vt' => '', 'new_ma_vt' => ''];
        $this->statusMessage = null;
        $this->errorMessage = null;
        $this->resetValidation();
    }

    /**
     * @param array<string, mixed> $row
     */
    private function fillFormFromRow(array $row): void
    {
        $this->resetForm();
        foreach (array_keys($this->form) as $key) {
            if (array_key_exists($key, $row)) {
                $this->form[$key] = $row[$key];
            }
        }

        $this->form['ton_kho'] = $this->toBool($this->form['ton_kho']);
        $this->form['tinh_gt'] = $this->toBool($this->form['tinh_gt']);
        $this->form['co_ct'] = $this->toBool($this->form['co_ct']);
        $this->form['ksd'] = $this->toBool($this->form['ksd']);
    }

    private function validateForm(): void
    {
        $validator = Validator::make($this->form, [
            'ma_vt' => ['required', 'string', 'max:20'],
            'ten_vt' => ['required', 'string', 'max:100'],
            'loai' => ['required', 'string', 'max:1'],
            'dvt' => [$this->form['ton_kho'] ? 'required' : 'nullable', 'string', 'max:8'],
            'tk_vt' => [$this->form['ton_kho'] ? 'required' : 'nullable', 'string', 'max:20'],
            'part_no' => ['nullable', 'string', 'max:16'],
            'dvt_ban' => ['nullable', 'string', 'max:8'],
            'dvt_mua' => ['nullable', 'string', 'max:8'],
            'ma_nhvt' => ['nullable', 'string', 'max:8'],
            'ma_plvt1' => ['nullable', 'string', 'max:8'],
            'ma_plvt2' => ['nullable', 'string', 'max:8'],
            'ma_plvt3' => ['nullable', 'string', 'max:8'],
            'ma_nhvat' => ['nullable', 'string', 'max:8'],
            'ma_kho' => ['nullable', 'string', 'max:8'],
            'ma_vitri' => ['nullable', 'string', 'max:8'],
            'ma_thue' => ['nullable', 'string', 'max:20'],
            'sl_min' => ['numeric'], 'sl_max' => ['numeric'], 'gia_nt0' => ['numeric'], 'gia_nt2' => ['numeric'],
            'hs_dvtban' => ['numeric'], 'hs_dvtmua' => ['numeric'],
            'ts_nk' => ['integer'], 'ts_gtgt' => ['integer'], 'ts_xk' => ['integer'], 'ts_ttdb' => ['integer'],
        ], [
            'ma_vt.required' => 'Mã vật tư không được rỗng.',
            'ten_vt.required' => 'Tên vật tư không được rỗng.',
            'dvt.required' => 'ĐVT bắt buộc khi theo dõi tồn kho.',
            'tk_vt.required' => 'TK vật tư bắt buộc khi theo dõi tồn kho.',
        ]);

        $validator->after(function ($validator): void {
            $seen = [];
            foreach ($this->bomRows as $index => $row) {
                $maLk = trim((string) ($row['ma_lk'] ?? ''));
                if ('' === $maLk) {
                    continue;
                }
                if ($maLk === trim((string) $this->form['ma_vt'])) {
                    $validator->errors()->add("bomRows.{$index}.ma_lk", 'Mã linh kiện không được trùng mã vật tư cha.');
                }
                if (isset($seen[$maLk])) {
                    $validator->errors()->add("bomRows.{$index}.ma_lk", 'Mã linh kiện bị trùng trong BOM.');
                }
                $seen[$maLk] = true;
                if ((float) ($row['so_luong'] ?? 0) <= 0) {
                    $validator->errors()->add("bomRows.{$index}.so_luong", 'Số lượng BOM phải lớn hơn 0.');
                }
            }
        });

        $validator->validate();
    }

    /**
     * @return array<string, mixed>
     */
    private function dmvtPayload(): array
    {
        $payload = [
            'pMa_cty' => $this->companyId(),
            'pMa_vt' => $this->text('ma_vt'),
            'pLoai' => $this->text('loai', '1'),
            'pTen_vt' => $this->text('ten_vt'),
            'pPart_no' => $this->text('part_no'),
            'pDvt' => $this->text('dvt'),
            'pDvt_ban' => $this->text('dvt_ban'),
            'pHs_dvtban' => $this->number('hs_dvtban', 1),
            'pDvt_mua' => $this->text('dvt_mua'),
            'pHs_dvtmua' => $this->number('hs_dvtmua', 1),
            'pTon_kho' => $this->boolInt('ton_kho'),
            'pGia_ton' => $this->text('gia_ton', '1'),
            'pTk_vt' => $this->text('tk_vt'),
            'pTk_dt' => $this->text('tk_dt'),
            'pTk_dtnb' => $this->text('tk_dtnb'),
            'pTk_gv' => $this->text('tk_gv'),
            'pTk_tl' => $this->text('tk_tl'),
            'pTk_ck' => $this->text('tk_ck'),
            'pMa_thue' => $this->text('ma_thue'),
            'pTinh_gt' => $this->boolInt('tinh_gt'),
            'pMa_nhvt' => $this->text('ma_nhvt'),
            'pMa_plvt1' => $this->text('ma_plvt1'),
            'pMa_plvt2' => $this->text('ma_plvt2'),
            'pMa_plvt3' => $this->text('ma_plvt3'),
            'pMa_nhvat' => $this->text('ma_nhvat'),
            'pSl_min' => $this->number('sl_min'),
            'pSl_max' => $this->number('sl_max'),
            'pGia_nt0' => $this->number('gia_nt0'),
            'pGia_nt2' => $this->number('gia_nt2'),
            'pTs_nk' => (int) $this->number('ts_nk'),
            'pTs_gtgt' => (int) $this->number('ts_gtgt'),
            'pTs_xk' => (int) $this->number('ts_xk'),
            'pTs_ttdb' => (int) $this->number('ts_ttdb'),
            'pMa_kho' => $this->text('ma_kho'),
            'pMa_vitri' => $this->text('ma_vitri'),
            'pCo_ct' => $this->bomRows === [] ? 0 : 1,
            'pGhi_chu' => $this->text('ghi_chu'),
            'pNha_sx' => $this->text('nha_sx'),
            'pNha_pp' => $this->text('nha_pp'),
            'pNuoc_sx' => $this->text('nuoc_sx'),
            'pKsd' => $this->boolInt('ksd'),
            'pLUser' => $this->lUser(),
            'pRet' => ['type' => 'INT', 'output' => true],
        ];

        if (!$this->isEditing) {
            return $this->insertPayloadAfter($payload, 'pDvt_mua', [
                'pDvdg' => 1,
                'pQcdg' => '',
            ]);
        }

        return $this->insertPayloadAfter($payload, 'pCo_ct', [
            'pMa_lo' => '',
        ]);
    }

    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $insert
     * @return array<string, mixed>
     */
    private function insertPayloadAfter(array $payload, string $afterKey, array $insert): array
    {
        $keys = array_keys($payload);
        $index = array_search($afterKey, $keys, true);
        if (false === $index) {
            return array_merge($payload, $insert);
        }

        $offset = $index + 1;

        return array_merge(
            array_slice($payload, 0, $offset, true),
            $insert,
            array_slice($payload, $offset, null, true),
        );
    }

    private function syncBomRows(): void
    {
        $currentKeys = [];
        foreach ($this->bomRows as $row) {
            $maLk = trim((string) ($row['ma_lk'] ?? ''));
            if ('' === $maLk) {
                continue;
            }

            $currentKeys[] = $maLk;
            $payload = [
                'pMa_cty' => $this->companyId(),
                'pMa_vt' => $this->text('ma_vt'),
                'pMa_lk' => $maLk,
                'pTen_lk' => trim((string) ($row['ten_lk'] ?? '')),
                'pDvt' => trim((string) ($row['dvt'] ?? '')),
                'pSo_luong' => (float) ($row['so_luong'] ?? 0),
                'pHe_so' => (float) ($row['he_so'] ?? 1),
                'pGhi_chu' => trim((string) ($row['ghi_chu'] ?? '')),
                'pKsd' => $this->toBool($row['ksd'] ?? false) ? 1 : 0,
                'pLUser' => $this->lUser(),
                'pRet' => ['type' => 'INT', 'output' => true],
            ];

            $this->callWriteProcedure(
                in_array($maLk, $this->originalBomKeys, true) ? 'asINUpdDMBOM' : 'asINInsDMBOM',
                $payload
            );
        }

        foreach (array_diff($this->originalBomKeys, $currentKeys) as $deletedKey) {
            $this->callWriteProcedure('asINDelDMBOM', [
                'pMa_cty' => $this->companyId(),
                'pMa_vt' => $this->text('ma_vt'),
                'pMa_lk' => $deletedKey,
            ]);
        }
    }

    /**
     * @param array<string, mixed> $params
     */
    private function callWriteProcedure(string $procedure, array $params): void
    {
        $result = ProcedureCaller::call($procedure, $params, (new SModel())->getConnectionName());
        $ret = $result->first()->pRet ?? null;
        if (null !== $ret && 0 !== (int) $ret) {
            throw new \RuntimeException("{$procedure} trả pRet={$ret}.");
        }
    }

    private function fillAccountsFromGroup(): void
    {
        $maNhvt = trim((string) ($this->form['ma_nhvt'] ?? ''));
        if ('' === $maNhvt) {
            return;
        }

        foreach ($this->nhvtOptions as $group) {
            if (($group['ma_nhvt'] ?? null) !== $maNhvt) {
                continue;
            }

            foreach (['tk_vt', 'tk_dt', 'tk_dtnb', 'tk_gv', 'tk_tl', 'tk_ck'] as $field) {
                if ('' === trim((string) ($this->form[$field] ?? '')) && isset($group[$field])) {
                    $this->form[$field] = $group[$field];
                }
            }

            return;
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function filteredRows(): array
    {
        $search = mb_strtolower(trim($this->search));
        if ('' === $search) {
            return $this->rows;
        }

        return array_values(array_filter($this->rows, static function (array $row) use ($search): bool {
            $haystack = mb_strtolower(implode(' ', [
                $row['ma_vt'] ?? '', $row['ten_vt'] ?? '', $row['ma_nhvt'] ?? '', $row['dvt'] ?? '', $row['tk_vt'] ?? '',
            ]));

            return str_contains($haystack, $search);
        }));
    }

    /**
     * @return null|array<string, mixed>
     */
    private function findRow(string $maVt): ?array
    {
        foreach ($this->rows as $row) {
            if (($row['ma_vt'] ?? null) === $maVt) {
                return $row;
            }
        }

        return null;
    }

    private function codeExists(string $maVt): bool
    {
        $needle = mb_strtolower(trim($maVt));
        foreach ($this->rows as $row) {
            if (mb_strtolower(trim((string) ($row['ma_vt'] ?? ''))) === $needle) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<int, mixed> $rows
     * @return list<array<string, mixed>>
     */
    private function normalizeRows(array $rows): array
    {
        return array_map(function (mixed $row): array {
            $array = is_array($row) ? $row : get_object_vars($row);
            $normalized = [];
            foreach ($array as $key => $value) {
                $normalized[strtolower((string) $key)] = $value;
            }

            return $normalized;
        }, $rows);
    }

    /**
     * @return array<string, mixed>
     */
    private function blankBomRow(): array
    {
        return ['ma_lk' => '', 'ten_lk' => '', 'dvt' => '', 'so_luong' => 1, 'he_so' => 1, 'ghi_chu' => '', 'ksd' => false];
    }

    private function text(string $key, string $default = ''): string
    {
        return trim((string) ($this->form[$key] ?? $default));
    }

    private function number(string $key, float|int $default = 0): float
    {
        $value = $this->form[$key] ?? $default;

        return is_numeric($value) ? (float) $value : (float) $default;
    }

    private function boolInt(string $key): int
    {
        return $this->toBool($this->form[$key] ?? false) ? 1 : 0;
    }

    private function toBool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        return in_array((string) $value, ['1', 'true', 'on', 'yes', '✔'], true);
    }

    private function companyId(): string
    {
        try {
            return (string) (\CatalogService::company()->id ?? SModel::CTY);
        } catch (\Throwable) {
            return SModel::CTY;
        }
    }

    private function languageName(): string
    {
        try {
            return (string) (\CatalogService::language()->name ?? 'vi-VN');
        } catch (\Throwable) {
            return 'vi-VN';
        }
    }

    private function lUser(): string
    {
        try {
            return (string) (\CatalogService::simbaUser()->username ?? auth()->user()->name ?? 'SYSTEM');
        } catch (\Throwable) {
            return 'SYSTEM';
        }
    }
}

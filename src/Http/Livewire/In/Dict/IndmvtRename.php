<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\In\Dict;

use Diepxuan\Catalog\Models\Simba\InDmVt as InDmVtModel;
use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsDoiMa;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Livewire\Component;

class IndmvtRename extends Component
{
    /** @var array<string, string> */
    public array $renameForm = [
        'old_ma_vt' => '',
        'new_ma_vt' => '',
    ];

    public bool $showRenameForm = false;

    public ?string $statusMessage = null;

    public ?string $errorMessage = null;

    /** @var list<string> */
    protected $listeners = [
        'indmvt-list.rename-clicked' => 'openRename',
        'indmvt-list.item-selected' => 'closeForExternalAction',
        'indmvt-list.create-clicked' => 'closeForExternalAction',
        'indmvt-list.deleted' => 'closeForExternalAction',
    ];

    public function openRename(?string $maVt = null): void
    {
        if (null === $maVt || '' === trim($maVt)) {
            $this->errorMessage = 'Chưa chọn vật tư để đổi mã.';

            return;
        }

        if (!$this->codeExists($maVt)) {
            $this->errorMessage = "Không tìm thấy vật tư {$maVt}.";

            return;
        }

        $this->renameForm = [
            'old_ma_vt' => $maVt,
            'new_ma_vt' => '',
        ];
        $this->showRenameForm = true;
        $this->statusMessage = null;
        $this->errorMessage = null;
        $this->dispatch('indmvt-rename-opened');
    }

    public function cancelRename(): void
    {
        $this->showRenameForm = false;
        $this->resetValidation();
        $this->dispatch('indmvt-rename.cancelled');
    }

    public function closeForExternalAction(?string $maVt = null): void
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
            if ('' !== $oldMaVt && !$this->codeExists($oldMaVt)) {
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
            $this->showRenameForm = false;
            $this->dispatch('indmvt-rename.saved', oldMaVt: $oldMaVt, newMaVt: $newMaVt);
        } catch (\Throwable $exception) {
            $this->errorMessage = $exception->getMessage();
        }
    }

    public function render(): View
    {
        return view('catalog::in.dict.indmvt-rename');
    }

    private function codeExists(string $maVt): bool
    {
        try {
            $collection = InDmVtModel::getAsINGetDMVT([
                'pMa_Cty'   => $this->companyId(),
                'pMa_vt'    => trim($maVt),
                'pStruct'   => null,
                'pLanguage' => $this->languageName(),
            ]);
        } catch (\Throwable) {
            return false;
        }

        return [] !== $this->normalizeRows($collection->all());
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
}

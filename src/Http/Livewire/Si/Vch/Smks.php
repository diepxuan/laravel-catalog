<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Si\Vch;

use Carbon\CarbonImmutable;
use Diepxuan\Catalog\Models\System;
use Diepxuan\Catalog\Models\SystemConfig;
use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AssiUpd_ks;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Livewire\Component;

class Smks extends Component
{
    public ?string $module = null;

    public ?string $kind = null;

    public ?string $slug = null;

    public string $maCty = '';

    public string $tenCty = '';

    public int $financialYear;

    public string $ngayKhoaSo = '';

    public ?string $statusMessage = null;

    public ?string $errorMessage = null;

    public function mount(?string $module = null, ?string $kind = null, ?string $slug = null): void
    {
        $this->module = $module;
        $this->kind = $kind;
        $this->slug = $slug;
        $this->financialYear = $this->currentFinancialYear();

        $this->loadData();
    }

    public function loadData(): void
    {
        $this->errorMessage = null;

        try {
            $system = $this->currentSystem();
            $config = $this->currentSystemConfig();
        } catch (\Throwable $exception) {
            $this->maCty = $this->companyCode();
            $this->tenCty = '';
            $this->ngayKhoaSo = $this->ngayKhoaSo ?: now()->toDateString();
            $this->errorMessage = 'Không nạp được cấu hình công ty hiện tại: ' . $exception->getMessage();

            return;
        }

        if (null === $system) {
            $this->maCty = $this->companyCode();
            $this->tenCty = '';
            $this->ngayKhoaSo = now()->toDateString();
            $this->errorMessage = 'Không nạp được cấu hình công ty hiện tại.';

            return;
        }

        $this->maCty = $this->companyCode();
        $this->tenCty = (string) ($system->ten_cty ?? $system->ten_cty2 ?? $system->ma_cty);

        $ngayKhoaSo = $config?->ngay_ks ?? null;
        if (null === $ngayKhoaSo) {
            $this->ngayKhoaSo = $this->ngayKhoaSo ?: now()->toDateString();
            $this->errorMessage = "Không tìm thấy cấu hình siSetup.ngay_ks cho công ty {$this->maCty}.";

            return;
        }

        $this->ngayKhoaSo = CarbonImmutable::parse($ngayKhoaSo)->format('Y-m-d');
    }

    public function updateKhoaSo(): void
    {
        $this->statusMessage = null;
        $this->errorMessage = null;

        Validator::make([
            'ngayKhoaSo' => $this->ngayKhoaSo,
        ], [
            'ngayKhoaSo' => ['required', 'date_format:Y-m-d'],
        ], [
            'ngayKhoaSo.required' => 'Ngày khóa sổ không được rỗng.',
            'ngayKhoaSo.date_format' => 'Ngày khóa sổ phải đúng định dạng Y-m-d.',
        ])->validate();

        try {
            $result = AssiUpd_ks::call([
                'pMa_cty'  => $this->maCty ?: $this->companyCode(),
                'pNgay_ks' => CarbonImmutable::createFromFormat('Y-m-d', $this->ngayKhoaSo)->startOfDay(),
            ]);

            $ret = (int) ($result->first()->pRet ?? 0);
            if (0 !== $ret) {
                $this->errorMessage = "Không cập nhật được ngày khóa sổ. assiUpd_ks trả pRet={$ret}.";

                return;
            }

            $this->loadData();
            $this->statusMessage = 'Đã cập nhật ngày khóa sổ.';
            $this->dispatch('action-message', ['on' => 'smks-updated']);
        } catch (\Throwable $exception) {
            $this->errorMessage = $exception->getMessage();
        }
    }

    public function render(): View
    {
        return view('catalog::si.vch.smks');
    }

    private function currentSystem(): ?System
    {
        $companyCode = $this->companyCode();

        $current = System::query()
            ->withoutGlobalScope('onlyFirstCompany')
            ->where('ma_cty', $companyCode)
            ->first()
        ;

        return $current;
    }

    private function currentSystemConfig(): ?SystemConfig
    {
        return SystemConfig::query()
            ->withoutGlobalScope('onlyFirstCompany')
            ->where('ma_cty', $this->companyCode())
            ->first()
        ;
    }

    private function companyCode(): string
    {
        $companyCode = session('selected_company');

        if (null !== $companyCode && '' !== $companyCode) {
            return $this->normalizeCompanyCode((string) $companyCode);
        }

        try {
            $company = \CatalogService::company();

            return $this->normalizeCompanyCode((string) ($company->ma_cty ?? $company->id ?? SModel::CTY));
        } catch (\Throwable) {
            return SModel::CTY;
        }
    }

    private function normalizeCompanyCode(string $companyCode): string
    {
        $companyCode = trim($companyCode);

        if (ctype_digit($companyCode) && \strlen($companyCode) < 3) {
            return str_pad($companyCode, 3, '0', STR_PAD_LEFT);
        }

        return $companyCode ?: SModel::CTY;
    }

    private function currentFinancialYear(): int
    {
        try {
            return (int) \CatalogService::year();
        } catch (\Throwable) {
            return (int) now()->year;
        }
    }
}

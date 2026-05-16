<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:28:06
 */

namespace Diepxuan\Catalog\Http\Livewire\System;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class CompanySelector extends Component
{
    public string $selectedCompany = '';
    public ?string $statusMessage  = null;

    public function mount(): void
    {
        $this->selectedCompany = (string) catalog()->company()->ma_cty;
    }

    public function selectCompany(string $companyCode): void
    {
        $company = $this->companies()->firstWhere('ma_cty', $companyCode);

        if (null === $company) {
            $this->addError('selectedCompany', __('Đơn vị không hợp lệ.'));

            return;
        }

        session(['selected_company' => $company->ma_cty]);

        $this->selectedCompany = (string) $company->ma_cty;
        $this->statusMessage   = __('Đã chọn đơn vị :company.', ['company' => $company->ten_cty ?? $company->ma_cty]);
    }

    public function render(): View
    {
        return view('catalog::system.company-selector', [
            'companies' => $this->companies(),
        ])->layout('catalog::layouts.app');
    }

    protected function companies(): Collection
    {
        return catalog()->companies();
    }
}

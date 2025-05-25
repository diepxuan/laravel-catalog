<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-05-24 18:26:12
 */

namespace Diepxuan\Catalog\Services;

use Diepxuan\Catalog\Models\SysLanguage;
use Illuminate\Support\Facades\Auth;

class CatalogService
{
    public function user()
    {
        return Auth::user();
    }

    public function simbaUser()
    {
        return $this->user()->getSimbaUser();
    }

    public function language()
    {
        return SysLanguage::current()->first();
    }

    public function company()
    {
        $ma_cty = session('selected_company');
        if ($ma_cty) {
            $company = $this->companies()->firstWhere('ma_cty', $ma_cty);
        } else {
            $company = $this->companies()->first();
        }

        session(['selected_company' => $company->ma_cty]);

        return $company;
    }

    public function companies()
    {
        return $this->simbaUser()->companies;
    }
}

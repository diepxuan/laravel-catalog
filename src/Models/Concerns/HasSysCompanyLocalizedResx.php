<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 *
 * @lastupdate 2026-06-22
 */

namespace Diepxuan\Catalog\Models\Concerns;

use Diepxuan\Simba\Models\SysLanguage;

/**
 * Concern: truy vấn resource theo ngôn ngữ trên SysCompany.
 *
 * Tách từ `Diepxuan\Simba\Models\SysCompany` (Phase 2 refactor) vì:
 * - Lọc resource theo SysLanguage là helper nghiệp vụ Catalog.
 * - Simba giữ quan hệ raw `resx()`; Catalog bổ sung `resxByLanguage()`.
 *
 * SysCompanyResx thuộc cùng namespace `Diepxuan\Catalog\Models` nên không
 * cần import; khi trait được dùng trong một Model thuộc namespace đó,
 * `SysCompanyResx` sẽ tự resolve.
 */
trait HasSysCompanyLocalizedResx
{
    /**
     * Resource theo ngôn ngữ.
     */
    public function resxByLanguage(SysLanguage $language)
    {
        return $this->hasMany(\Diepxuan\Catalog\Models\SysCompanyResx::class, 'ma_cty', 'ma_cty')
            ->where('language', $language->Name);
    }
}
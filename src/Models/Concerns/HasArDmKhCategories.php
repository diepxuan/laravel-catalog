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

use Illuminate\Database\Eloquent\Builder;

/**
 * Concern: phân loại đối tượng ArDmKh theo nghiệp vụ Portal.
 *
 * Tách từ `Diepxuan\Simba\Models\ArDmKh` (Phase 2 refactor) để giữ
 * Simba Models chỉ chứa behavior sát schema.
 *
 * Ngữ nghĩa KH/NCC/NV là quyết định nghiệp vụ Portal, không thuộc
 * bảng `ArDmKh` thô của Simba.
 */
trait HasArDmKhCategories
{
    /**
     * Scope: chỉ lấy khách hàng (isKh = true).
     */
    public function scopeLaKhachHang(Builder $query): Builder
    {
        return $query->where('isKh', true);
    }

    /**
     * Scope: chỉ lấy nhà cung cấp (isNcc = true).
     */
    public function scopeLaNhaCungCap(Builder $query): Builder
    {
        return $query->where('isNcc', true);
    }

    /**
     * Scope: chỉ lấy nhân viên (isNv = true).
     */
    public function scopeLaNhanVien(Builder $query): Builder
    {
        return $query->where('isNv', true);
    }

    /**
     * Accessor: kiểm tra có phải khách hàng không.
     */
    public function getIsKhachHangAttribute(): bool
    {
        return (bool) $this->isKh;
    }

    /**
     * Accessor: kiểm tra có phải nhà cung cấp không.
     */
    public function getIsNhaCungCapAttribute(): bool
    {
        return (bool) $this->isNcc;
    }

    /**
     * Accessor: kiểm tra có phải nhân viên không.
     */
    public function getIsNhanVienAttribute(): bool
    {
        return (bool) $this->isNv;
    }
}
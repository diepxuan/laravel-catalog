<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-05 20:11:16
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Simba\Models\ArDmKh as SimbaModel;
use Diepxuan\Simba\SModel\SModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model ArDmKh - Danh mục khách hàng (catalog layer).
 *
 * Mở rộng từ Simba ArDmKh với relationships và helper methods.
 */
class ArDmKh extends SimbaModel
{
    /**
     * Company constant.
     */
    public const CTY = SModel::CTY;

    /**
     * Quan hệ với GlCt (chi tiết sổ cái).
     */
    public function glCts(): HasMany
    {
        return $this->hasMany(GlCt::class, 'ma_kh', 'ma_kh');
    }

    /**
     * Quan hệ với nhóm khách hàng.
     */
    public function nhomKhachHang(): BelongsTo
    {
        return $this->belongsTo(ArDmNhKh::class, 'ma_nhkh', 'ma_nhkh');
    }

    /**
     * Quan hệ với phân loại khách hàng 1.
     */
    public function phanLoaiKhachHang1(): BelongsTo
    {
        return $this->belongsTo(ArDmPlKh::class, 'ma_plkh1', 'ma_plkh');
    }

    /**
     * Quan hệ với phân loại khách hàng 2.
     */
    public function phanLoaiKhachHang2(): BelongsTo
    {
        return $this->belongsTo(ArDmPlKh::class, 'ma_plkh2', 'ma_plkh');
    }

    /**
     * Quan hệ với phân loại khách hàng 3.
     */
    public function phanLoaiKhachHang3(): BelongsTo
    {
        return $this->belongsTo(ArDmPlKh::class, 'ma_plkh3', 'ma_plkh');
    }

    /**
     * Lấy số dư công nợ khách hàng.
     *
     * @param array $params tham số: pMa_Cty, pMa_Kh, pTk, pNgay
     */
    public function getSoduKh(array $params): float
    {
        $params = [
            'pMa_Cty' => $params['pMa_Cty'] ?? static::CTY,
            'pMa_Kh'  => $params['pMa_Kh'] ?? $this->ma_kh,
            'pTk'     => $params['pTk'],
            'pNgay'   => $params['pNgay'] ?? now()->toDateString(),
        ];

        return static::AsGetSoDuKh($params);
    }

    /**
     * Gọi stored procedure asGetSoDuKh để lấy số dư khách hàng.
     */
    public static function AsGetSoDuKh(array $params): float
    {
        return parent::AsGetSoDuKh($params);
    }

    /**
     * Kiểm tra khách hàng có giao dịch liên quan không.
     *
     * Dùng để quyết định có cho phép xóa/sửa mã khách hàng không.
     */
    public function hasTransactions(): bool
    {
        return $this->glCts()->exists();
    }

    /**
     * Boot model và thêm global scopes.
     */
    protected static function booted(): void
    {
        parent::boot();

        static::addGlobalScope('orderByMaKh', static function ($query): void {
            $query->orderBy('ma_kh');
        });
    }
}

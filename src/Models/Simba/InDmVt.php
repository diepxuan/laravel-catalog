<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-22 21:07:55
 */

namespace Diepxuan\Catalog\Models\Simba;

use Diepxuan\Currency\Formatter;
use Diepxuan\Simba\Models\InDmVt as SimbaModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InDmVt extends SimbaModel
{
    /**
     * Scope a query to include the quantity of products.
     *
     * @var array
     *
     * @param mixed $query
     * @param mixed $flag
     */
    public function scopeWithQuantity($query, $flag = true)
    {
        if (!$flag) {
            return $query;
        }
        // Lấy danh sách cột từ bảng InDmVt, tránh cột tính toán và kiểu dữ liệu không hỗ trợ GROUP BY
        // $connect        = DB::connection((new static())->getConnectionName());
        $columns        = Schema::connection($this->connection)->getColumnListing($this->table);
        $groupByColumns = array_diff($columns, ['quantity', 'Anh']); // Bỏ cột TEXT, NTEXT, IMAGE
        $groupByColumns = array_map(fn ($col) => "{$this->table}.{$col}", $groupByColumns);

        // Danh sách cột SELECT
        $selectColumns = array_merge(
            ["{$this->table}.ma_vt", "{$this->table}.ten_vt"],
            $groupByColumns,
            [
                DB::raw('COALESCE(SUM(incdvt.so_luong), 0) + COALESCE(SUM(inct.sl_nhap_qd - inct.sl_xuat_qd), 0) AS quantity'),
            ]
        );

        return $query->leftJoin('incdvt', function ($join): void {
            $join->on("{$this->table}.ma_vt", '=', 'incdvt.ma_vt')
                ->where('incdvt.nam', '=', DB::raw('YEAR(GETDATE())'))
            ;
        })
            ->leftJoin('inct', function ($join): void {
                $join->on("{$this->table}.ma_vt", '=', 'inct.ma_vt')
                    ->where('inct.ngay_ct', '>=', DB::raw('DATEADD(YEAR, DATEDIFF(YEAR, 0, GETDATE()), 0)'))
                ;
            })
            ->where("{$this->table}.ma_cty", '001') // Chỉ lấy dữ liệu của công ty 001
            ->select($selectColumns)
            ->groupBy($groupByColumns)
        ;
    }

    protected function tenVt(): Attribute
    {
        return Attribute::get(
            static function ($value, array $attributes) {
                $value ??= $attributes['ten_vt'] ?? $attributes['ten_Vt'];

                switch ($value) {
                    case '#SMRpt_TC':
                        return 'Tổng cộng';

                    default:
                        return $value;
                }
            }
        );
    }

    protected function cdate(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => optional(Carbon::parse($value ?? $attributes['cdate'] ?? $attributes['cDate'] ?? null))->format('d/m/y')
        );
    }

    protected function ldate(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => optional(Carbon::parse($value ?? $attributes['ldate'] ?? $attributes['lDate'] ?? null))->format('d/m/y')
        );
    }

    protected function cuser(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => $value ?? $attributes['cuser'] ?? $attributes['cUser']
        );
    }

    protected function luser(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => $value ?? $attributes['luser'] ?? $attributes['lUser']
        );
    }

    protected function ksd(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => ($value ?? $attributes['ksd'] ?? $attributes['KSD'] ?? false) ? '✔' : ''
        );
    }

    protected function vatTu(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => ($value ?? $attributes['vat_tu'] ?? false) ? '✔' : ''
        );
    }

    protected function slMin(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => (float) ($value ?? $attributes['sl_min'] ?? 0)
        );
    }

    protected function slMax(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => (float) ($value ?? $attributes['sl_max'] ?? 0)
        );
    }

    protected function hsDvtban(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => (float) ($value ?? $attributes['hs_dvtban'] ?? 0)
        );
    }

    protected function hsDvtmua(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => (float) ($value ?? $attributes['hs_dvtmua'] ?? 0)
        );
    }

    protected function maVt(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => ($value ?? $attributes['ma_vt'] ?? $attributes['Ma_vt'] ?? '')
        );
    }

    protected function soLuong(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => (float) ($value ?? $attributes['so_luong'] ?? '')
        );
    }

    protected function tienFm(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => Formatter::format($attributes['tien'] ?? '')
        );
    }
}

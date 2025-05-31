<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-05-31 09:09:15
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Catalog\Observers\ProductObserver;
use Diepxuan\Simba\Models\Product as Model;

#[ObservedBy([ProductObserver::class])]
class Product extends Model
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
}

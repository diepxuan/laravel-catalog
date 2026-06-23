# Catalog Models — `Simba/` wrappers

Thư mục `src/Models/Simba/` chứa **29 wrapper/merge class** ở lớp 3 (Catalog) trong
pattern **3-layer Model** (`SModel` / `Simba\Models` / `Catalog\Models\Simba`).

## Vai trò

Phần lớn file trong thư mục này là Catalog Model **mỏng**, kế thừa trực tiếp
một class ở `Diepxuan\Simba\Models\*` (lớp 2). Riêng `Zsysmenu` kế thừa
`Catalog\Models\Simba\SysMenu` vì `zsysmenu` và `sysmenu` cùng struct, cần dùng
extend để dễ merge logic với `SysMenu`. Wrapper giữ:

- Concern trait nghiệp vụ (`HasArDmKhCategories`, `HasInDmKhoInventoryOperations`,
  `HasPoCt1PurchaseMetrics`, `HasSoCt1SalesMetrics`, `HasSysCompanyLocalizedResx`).
- Quan hệ (relation) phục vụ màn hình Portal/Catalog
  (`nhomKhachHang`, `phanLoaiKhachHang`, `systemConfig`, `khoMacDinh`...).
- Scope query theo ngữ nghĩa KH/NCC/NV hoặc workflow Catalog
  (`scopeWithQuantity`, `scopeWhereNgayCt`...).
- Accessor/mutator mang ngữ nghĩa nghiệp vụ Portal.
- Helper gọi stored procedure hoặc truy vấn phục vụ Livewire component.

Wrapper **không** được phép khai báo `$table`, `$primaryKey`, `$fillable`,
`$casts` trực tiếp — schema lấy từ SModel qua Simba Model cha.

## Quy tắt alias `SimbaModel`

Vì class trong `Catalog\Models\Simba\<X>` trùng tên với class cha
`Simba\Models\<X>`, mọi wrapper đều **bắt buộc** alias `as SimbaModel`:

```php
namespace Diepxuan\Catalog\Models\Simba;

use Diepxuan\Catalog\Models\Concerns\HasArDmKhCategories;
use Diepxuan\Simba\Models\ArDmKh as SimbaModel;

/**
 * Model ArDmKh - Danh mục khách hàng (catalog layer).
 */
class ArDmKh extends SimbaModel
{
    use HasArDmKhCategories;

    public function nhomKhachHang(): BelongsTo
    {
        return $this->belongsTo(Simba\Models\ArDmNhKh::class, ...);
    }
}
```

Không alias → PHP resolve `extends ArDmKh` về chính class đang khai báo
(`Catalog\Models\Simba\ArDmKh`) → **fatal error**.

Hai trường hợp đặc biệt (`InventoryTicket`, `InventoryTicketItem`) extend
class cha tên khác (`PhieuChuyenKho`, `PhieuChuyenKhoCT`) nhưng vẫn dùng
`as SimbaModel` để code đồng nhất trong cả thư mục.

`Zsysmenu` là trường hợp merge-wrapper: không alias `SimbaModel`, mà `extends SysMenu`
trong cùng namespace `Catalog\Models\Simba` để dùng chung contract/logic với
`SysMenu` và chỉ override `$table = 'zsysmenu'`.

## Danh sách 29 wrapper/merge class

| File | Class cha `Simba\Models\*` | Behavior |
|---|---|---|
| `ArDmKh.php` | `ArDmKh` | concern `HasArDmKhCategories`, nhiều relation |
| `ArDmNhKh.php` | `ArDmNhKh` | wrapper const `CTY = SModel::CTY` |
| `ArDmPlKh.php` | `ArDmPlKh` | wrapper const `CTY = SModel::CTY` |
| `CaCt2.php` | `CaCt2` | rỗng (passthrough) |
| `CaCt3.php` | `CaCt3` | rỗng (passthrough) |
| `CaPh2.php` | `CaPh2` | rỗng (passthrough) |
| `CaPh3.php` | `CaPh3` | rỗng (passthrough) |
| `GlCdTk.php` | `GlCdTk` | 1 scope |
| `GlCt.php` | `GlCt` | 4 scope/relation |
| `GlDmTk.php` | `GlDmTk` | 2 accessor |
| `InDmKho.php` | `InDmKho` | concern, 1 method, 6 accessor |
| `InDmNhvt.php` | `InDmNhvt` | 1 method, 6 accessor, custom cast `CategoryMagento` |
| `InDmVt.php` | `InDmVt` | nhiều scope |
| `InventoryTicket.php` | `PhieuChuyenKho` | 2 scope, 1 relation |
| `InventoryTicketItem.php` | `PhieuChuyenKhoCT` | 1 relation |
| `PoCt1.php` | `PoCt1` | concern `HasPoCt1PurchaseMetrics` |
| `Product.php` | `Product` | 1 scope phức tạp |
| `SiDmBp.php` | `SiDmBp` | rỗng |
| `SiDmNt.php` | `SiDmNt` | rỗng |
| `SiSetup.php` | `SiSetup` | 1 relation, 1 accessor |
| `SoCt1.php` | `SoCt1` | concern `HasSoCt1SalesMetrics` |
| `SoPh3.php` | `SoPh3` | 1 method, 1 accessor |
| `SysCompany.php` | `SysCompany` | concern `HasSysCompanyLocalizedResx` |
| `SysCompanyResx.php` | `SysCompanyResx` | rỗng |
| `SysDictionaryInfo.php` | `SysDictionaryInfo` | rỗng |
| `SysLanguage.php` | `SysLanguage` | 1 accessor |
| `SysMenu.php` | `SysMenu` | rỗng |
| `SysUserInfo.php` | `SysUserInfo` | 1 relation |
| `Zsysmenu.php` | `Catalog\Models\Simba\SysMenu` | merge-wrapper, override `$table = 'zsysmenu'` |

## Quan hệ với `src/Models/` (top-level)

Một số class ở `src/Models/` (top-level) **extend** wrapper trong `Simba/`:

| File top-level | Extend wrapper |
|---|---|
| `System.php` | `Simba\SysCompany` |
| `SystemConfig.php` | `Simba\SiSetup` |

Cập nhật `SimbaModelLayerResponsibilityTest::$CATALOG_EXTENDS_WHITELIST`
tương ứng khi thêm wrapper mới hoặc thay đổi mối quan hệ.

## Quy tắc cập nhật

- **Không tự đặt tên bảng/cột/SP.** Nguồn sự thật là `simba-docs/tables`
  và các tài liệu Simba readonly liên quan.
- Trước khi thêm behavior vào wrapper, kiểm tra:
  1. Nếu thuần Simba table/query → đặt trong `diepxuan/laravel-simba/src/Models`.
  2. Nếu đã mang ngữ cảnh Portal/Catalog/UI/workflow → giữ trong wrapper này.
  3. Nếu là concern nghiệp vụ dùng chung → tách ra `../Concerns/` và `use` trait.
- Không override lại `$table`, `$primaryKey`, `$fillable`, `$casts` —
  schema lấy từ SModel qua Simba Model cha.

## Verify

```bash
# php -l toàn bộ wrapper
for f in diepxuan/laravel-catalog/src/Models/Simba/*.php; do php -l "$f"; done

# Test gate 3-layer (assert whitelist System/SystemConfig trỏ đúng Simba\...)
vendor/bin/phpunit tests/Unit/Packages/Simba/SimbaModelLayerResponsibilityTest.php
```

## Tóm tắt

`Simba/` = 29 wrapper/merge class ở lớp 3. Hầu hết extend `Diepxuan\Simba\Models\*`
qua alias `SimbaModel`; riêng `Zsysmenu` extend `SysMenu` cùng namespace để dùng
chung logic vì cùng table struct. Chứa business logic nghiệp vụ phục vụ Portal/Catalog UI.
Concern nghiệp vụ dùng chung tách vào `../Concerns/`.

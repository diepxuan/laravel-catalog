# Catalog Models - `Simba/` wrappers

Thư mục `src/Models/Simba/` chứa **445 wrapper/merge class** ở lớp 3 (Catalog) trong
pattern **3-layer Model** (`SModel` / `Simba\Models` / `Catalog\Models\Simba`).
Mỗi model ở `diepxuan/laravel-simba/src/Models` phải có một wrapper tương ứng ở
Catalog, trừ các alias/merge-wrapper đặc biệt đã ghi rõ bên dưới.

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

Wrapper passthrough **không** được phép khai báo `$table`, `$primaryKey`,
`$fillable`, `$casts` trực tiếp — schema lấy từ SModel qua Simba Model cha.
Wrapper có behavior riêng chỉ khai báo metadata khi đã có lý do Catalog rõ ràng
như `Zsysmenu` merge table hoặc custom cast của `InDmNhvt`.

## Quy tắc alias `SimbaModel`

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

Hai trường hợp đặc biệt (`InventoryTicket`, `InventoryTicketItem`) extend class
cha tên khác (`PhieuChuyenKho`, `PhieuChuyenKhoCT`) nhưng vẫn dùng `as
SimbaModel` để code đồng nhất trong cả thư mục.

`Zsysmenu` là trường hợp merge-wrapper: không alias `SimbaModel`, mà `extends SysMenu`
trong cùng namespace `Catalog\Models\Simba` để dùng chung contract/logic với
`SysMenu` và chỉ override `$table = 'zsysmenu'`.

## Coverage

`Catalog\Models\Simba` phải cover toàn bộ model lớp 2 trong `Simba\Models`.
Các wrapper passthrough chỉ chứa namespace, alias `SimbaModel`, và `extends
SimbaModel`; không khai báo lại schema.

Alias/merge-wrapper:

| Simba model | Catalog wrapper | Ghi chú |
|---|---|---|
| `PhieuChuyenKho` | `InventoryTicket` | Tên Catalog theo domain phiếu kho |
| `PhieuChuyenKhoCT` | `InventoryTicketItem` | Tên Catalog theo domain dòng phiếu kho |
| `zsysmenu` | `Zsysmenu` | Merge-wrapper dùng logic chung với `SysMenu` |

## Wrapper có behavior riêng

| File | Class cha | Behavior |
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

`Simba/` = wrapper/merge class lớp 3 cover đủ model lớp 2. Hầu hết extend
`Diepxuan\Simba\Models\*` qua alias `SimbaModel`; riêng `Zsysmenu` extend
`SysMenu` cùng namespace để dùng chung logic vì cùng table struct. Chỉ các file
có behavior riêng mới chứa business logic phục vụ Portal/Catalog UI. Concern
nghiệp vụ dùng chung tách vào `../Concerns/`.

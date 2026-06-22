# Catalog Models layer

Thư mục này chứa các model Eloquent ở lớp 3 (Catalog/Portal) trong pattern
**3-layer Model** (`SModel` / `Simba\Models` / `Catalog\Models`).

## Vai trò

`Catalog\Models` là lớp trên cùng, dùng cho **Portal/Catalog UI và business
logic phục vụ use-case nghiệp vụ**. Model ở đây extend `Diepxuan\Simba\Models\*`
(lớp 2), **không bao giờ extend trực tiếp `Diepxuan\Simba\SModel\*`** (lớp 1).

```php
use Diepxuan\Simba\Models\ArDmKh as SimbaModel;

class ArDmKh extends SimbaModel
{
    use HasArDmKhCategories;   // business concern từ src/Models/Concerns
    // relations / scopes / accessors phục vụ Portal/Catalog
}
```

Catalog Models là nơi duy nhất được phép chứa:

- Quan hệ (relation) phục vụ màn hình Portal (nhomKhachHang, phanLoaiKhachHang...).
- Helper nghiệp vụ Portal (getSoduKh, hasTransactions...).
- Scope/accessor/mutator mang ngữ nghĩa KH/NCC/NV hoặc workflow Catalog.
- Global scope đặc thù Portal (`orderByMaKh`...).

## Cấu trúc thư mục

```
src/Models/
├── AbstractModel.php              # Base class cho model Catalog không map Simba
├── Concerns/                      # Business concerns (xem README riêng)
├── <TableName>.php                # Model extend Simba\Models\<TableName>
└── ...
```

### `AbstractModel.php`

Base class cho các model **không** map trực tiếp một bảng Simba
(ví dụ: `System`, `SystemConfig`, `UserLink`, `Params`, `InventoryTicket`,
`InventoryTicketItem`, `Product`).

- Khai báo `$table`, `$primaryKey`, `$fillable`, `$hidden`, `$guarded`, `$connection`
  trống — model con override theo schema thật của mình.
- Bật `HasFactory` để hỗ trợ factory trong test/seed.
- Constructor inject connection từ `config('database.default')`.
- `boot()` đăng ký hook `creating/created/updating/updated/deleting/deleted`
  làm placeholder cho các Catalog con cần override.

Các Catalog Model **không** map bảng Simba đều extend `AbstractModel`.

### `Concerns/`

Các business concern tách riêng để giữ model chính gọn và reuse giữa các
Catalog Model. Xem [`Concerns/README.md`](Concerns/README.md).

## Được phép đặt tại đây

- Extend `Diepxuan\Simba\Models\*` (lớp 2). **Không extend trực tiếp `SModel\*`.**
- Quan hệ (relation) nghiệp vụ Portal giữa các model Simba/Catalog.
- Scope query theo ngữ nghĩa KH/NCC/NV, workflow, hoặc policy Portal.
- Accessor/mutator có domain meaning nghiệp vụ Portal.
- Helper gọi stored procedure có tham số phục vụ màn hình/Livewire component.
- Business method phục vụ use-case (tính số dư, kiểm tra ràng buộc trước khi xóa...).
- Global scope đặc thù Portal.
- Extend `AbstractModel` cho model không map bảng Simba.

## Không đặt tại đây

- Schema metadata trừ khi nằm trong whitelist có lý do
  (`Params`, `System`, `SystemConfig`, `UserLink`, `InDmNhvt`).
- Raw schema từ SQL Server — thuộc `diepxuan/laravel-simba/src/SModel`.
- Query helper sát bảng Simba, không phụ thuộc use-case Catalog — thuộc
  `diepxuan/laravel-simba/src/Models`.
- Logic UI, route, request/session/auth, Livewire component — thuộc `Http/`.
- Service orchestration — thuộc `src/Services/`.

## Quy tắc cập nhật

- **Không tự đặt tên bảng/cột/SP.** Nguồn sự thật là `simba-docs/tables`
  và các tài liệu Simba readonly liên quan. Đối với bảng mới hoặc trường
  không có trong docs, dừng và hỏi Sếp.
- Trước khi thêm behavior vào Catalog Model, kiểm tra:
  1. Nếu thuần Simba table/query → đặt trong `diepxuan/laravel-simba/src/Models`.
  2. Nếu đã mang ngữ cảnh Portal/Catalog/UI/workflow → đặt ở đây.
  3. Nếu là concern nghiệp vụ dùng chung → tách ra `Concerns/` và `use` trait.
- Không override lại `$table`, `$primaryKey`, `$fillable`, `$casts` nếu Simba
  Model gốc đã có đủ.
- Khi thêm Catalog Model cần lệch rule (whitelist), **bắt buộc** thêm entry
  kèm comment lý do trong:
  - `SimbaModelLayerResponsibilityTest::$CATALOG_EXTENDS_WHITELIST`
  - `SimbaModelLayerResponsibilityTest::$CATALOG_DB_METADATA_WHITELIST`
- Mọi Model phải đi qua test gate trước khi merge:
  `php scripts/audit-catalog-model-layer.php` và
  `vendor/bin/phpunit tests/Unit/Packages/Simba/SimbaModelLayerResponsibilityTest.php`.

## Phân loại Catalog Model

Audit script `scripts/audit-catalog-model-layer.php` phân loại Catalog Model
theo 4 nhóm:

| Classification | Đặc điểm | Ví dụ |
|---|---|---|
| `concern_business` | Có dùng trait từ `Concerns/` | `ArDmKh`, `InDmKho`, `PoCt1`, `SoCt1`, `SysCompany` |
| `inline_business` | Có business method inline (không qua concern) | model có `getInventory`, `getSoduKh`, `getTotalPurchase`... |
| `catalog_utility` | Relation/scope/accessor nghiệp vụ, không có business method | `SoPh3`, `GlCt`, `ArDmNhKh`, `User`, `SysUserInfo` |
| `passthrough` | Class rỗng, chỉ kế thừa | `CaCt2`, `CaPh2`, `CaPh3`, `Zsysmenu`, `ArDmPlKh` |

Mục tiêu dài hạn: chuyển `inline_business` sang `concern_business` để reuse
và giữ Catalog Model chính gọn.

## Verify

```bash
# Audit Catalog layer
php scripts/audit-catalog-model-layer.php

# Test gate 3-layer
vendor/bin/phpunit tests/Unit/Packages/Simba/SimbaModelLayerResponsibilityTest.php
```

## Tóm tắt

`Catalog\Models` = lớp 3 trong pattern 3-layer, chứa business logic nghiệp vụ
phục vụ Portal/Catalog UI. Extend `Diepxuan\Simba\Models\*`, không extend
trực tiếp `SModel\*`. Business concern dùng chung tách vào `Concerns/`.
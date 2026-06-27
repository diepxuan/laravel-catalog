# Catalog Models layer

Thư mục `src/Models/` chứa các Catalog Model **không thuộc wrapper Simba**.

Sau khi tách cấu trúc (xem `Simba/README.md`), wrapper extend
`Diepxuan\Simba\Models\*` được dời hết sang `src/Models/Simba/`. Thư mục này
chỉ còn **6 file PHP top-level** + `Casts/` + `Concerns/`.

## Cấu trúc thư mục

```
src/Models/
├── AbstractModel.php              # Base class cho Catalog Model không map Simba
├── Params.php                     # Standalone Eloquent (không map Simba)
├── User.php                       # Extend App\Models\User (Laravel auth)
├── UserLink.php                   # Extend AbstractModel, dùng Simba\Models\SysUserInfo cho relation
├── System.php                     # Extend Simba\SysCompany + thêm systemConfig relation
├── SystemConfig.php               # Extend Simba\SiSetup + thêm system relation
├── Casts/                         # Custom Eloquent cast
│   └── CategoryMagento.php
├── Concerns/                      # Business concern trait (xem Concerns/README.md)
│   ├── HasArDmKhCategories.php
│   ├── HasInDmKhoInventoryOperations.php
│   ├── HasPoCt1PurchaseMetrics.php
│   ├── HasSoCt1SalesMetrics.php
│   ├── HasSysCompanyLocalizedResx.php
│   └── README.md
└── Simba/                         # wrapper extend/merge Simba\Models\* (xem Simba/README.md)
    └── ...
```

## 6 file top-level

| File | Extend | Vai trò |
|---|---|---|
| `AbstractModel.php` | `Illuminate\Database\Eloquent\Model` | Base class cho Catalog Model không map bảng Simba |
| `Params.php` | `Illuminate\Database\Eloquent\Model` | Bảng tiện ích không có SModel tương ứng |
| `User.php` | `App\Models\User` | Extend user Laravel auth, thêm relation nghiệp vụ |
| `UserLink.php` | `AbstractModel` | Bridge user ↔ SysUserInfo (qua relation) |
| `System.php` | `Simba\SysCompany` | Thêm `systemConfig` relation + `khoaSo` accessor |
| `SystemConfig.php` | `Simba\SiSetup` | Thêm `system` relation |

## `AbstractModel.php`

Base class cho các model **không** map trực tiếp một bảng Simba (hiện tại:
`Params`, `UserLink` — và trước đây là `System`, `SystemConfig`,
`InventoryTicket`, `InventoryTicketItem`, `Product` đã chuyển sang `Simba/`).

- Khai báo `$fillable`, `$guarded`, `$incrementing`, `$timestamps`
  mặc định — model con override theo schema thật.
- Bật `HasFactory` để hỗ trợ factory trong test/seed.
- `boot()` đăng ký hook `creating/created/updating/updated/deleting/deleted`
  làm placeholder cho các Catalog con cần override.

## `Casts/`

Custom Eloquent cast phục vụ riêng cho Catalog:

- `CategoryMagento.php` — cast cho cột phân loại Magento trong `InDmNhvt`.

## `Concerns/`

Các business concern trait tách riêng để giữ wrapper chính gọn và reuse:

- `HasArDmKhCategories` — relation nhóm KH + phân loại KH.
- `HasInDmKhoInventoryOperations` — tính tồn kho, nhập xuất tồn.
- `HasPoCt1PurchaseMetrics` — chỉ số mua hàng (PO).
- `HasSoCt1SalesMetrics` — chỉ số bán hàng (SO).
- `HasSysCompanyLocalizedResx` — đa ngôn ngữ resx cho SysCompany.

Xem chi tiết tại [`Concerns/README.md`](Concerns/README.md).

## `Simba/`

445 wrapper/merge class extend `Diepxuan\Simba\Models\*` hoặc wrapper Simba cùng schema.
Phần lớn là passthrough mỏng; 29 file có behavior riêng được liệt kê tại
[`Simba/README.md`](Simba/README.md).

## Quy tắc cập nhật

- **Không tự đặt tên bảng/cột/SP.** Nguồn sự thật là `simba-docs/tables`.
- Trước khi thêm Catalog Model mới, xác định rõ:
  - Extend `Simba\Models\*` → đặt trong `Simba/`.
  - Extend `Illuminate\Database\Eloquent\Model` / `AbstractModel` / `App\Models\User` →
    đặt tại top-level `src/Models/`.
- Khi thêm file lệch rule (whitelist), bắt buộc cập nhật:
  - `SimbaModelLayerResponsibilityTest::$CATALOG_EXTENDS_WHITELIST`
  - `SimbaModelLayerResponsibilityTest::$CATALOG_DB_METADATA_WHITELIST`

## Verify

```bash
# Audit toàn bộ Catalog layer (chỉ scan top-level *.php)
php scripts/audit-catalog-model-layer.php

# Test gate 3-layer
vendor/bin/phpunit tests/Unit/Packages/Simba/SimbaModelLayerResponsibilityTest.php
```

## Tóm tắt

`src/Models/` = 6 Catalog Model đặc biệt + `Casts/` + `Concerns/`. Wrapper/merge
class Simba nằm trong `Simba/`. Pattern 3-layer
(`SModel` / `Simba\Models` / `Catalog\Models`) vẫn giữ nguyên — chỉ tách
vật lý cho dễ audit và phân quyền.

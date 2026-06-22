# Catalog Models Concerns

Thư mục này chứa các **business concern** (trait) dùng chung cho Catalog Model
trong `diepxuan/laravel-catalog/src/Models`.

## Vai trò

Concern là **trait đóng gói business logic** nghiệp vụ Portal/Catalog, tách
khỏi model chính để:

- Giữ `Catalog\Models\<TableName>` gọn, chỉ còn quan hệ và helper đặc thù.
- Tái sử dụng logic giữa các model cùng nhóm nghiệp vụ
  (vd: nhiều model cần phân loại KH/NCC/NV).
- Tránh phình `Simba\Models` lớp 2 với logic đã thuộc về Portal/Catalog.

Concern chỉ làm việc khi model `use` trait tương ứng. Trait không tự đăng ký
toàn cục — phạm vi áp dụng do model quyết định.

## Danh sách concern hiện tại

| Trait | Mục đích | Model dùng |
|---|---|---|
| `HasArDmKhCategories` | Phân loại đối tượng KH/NCC/NV qua `isKh/isNcc/isNv` | `ArDmKh` |
| `HasInDmKhoInventoryOperations` | Helper nghiệp vụ tồn kho (receipt rate, inventory value...) | `InDmKho` |
| `HasPoCt1PurchaseMetrics` | Báo cáo/chỉ số mua hàng (`getPORpt`, `getTotalPurchase`...) | `PoCt1` |
| `HasSoCt1SalesMetrics` | Báo cáo/chỉ số bán hàng (`getSORpt`, `getTotalRevenue`...) | `SoCt1` |
| `HasSysCompanyLocalizedResx` | Đa ngôn ngữ resx cho SysCompany | `SysCompany` |

## Được phép đặt tại đây

- Scope nghiệp vụ Portal (`scopeLaKhachHang`, `scopeLaNhaCungCap`...).
- Accessor/mutator có domain meaning nghiệp vụ Portal.
- Helper tính toán business (inventory value, số dư, chỉ số báo cáo...).
- Gọi stored procedure có tham số phục vụ nghiệp vụ, **kèm fallback rõ ràng**.
- DocBlock mô tả rõ ngữ nghĩa nghiệp vụ và lý do tách concern.

## Không đặt tại đây

- Schema metadata (`$table`, `$primaryKey`, `$fillable`, `$casts`) — thuộc
  `Simba\SModel\*` hoặc `Simba\Models\*`.
- Logic raw query sát bảng Simba — thuộc `Simba\Models\*`.
- Logic UI, route, request/session/auth, Livewire — thuộc `Http/`.
- Logic không phụ thuộc model (service thuần) — thuộc `src/Services/`.
- Trait phục vụ test/dev (factory, fake...) — đặt trong `tests/` hoặc
  dùng package dev riêng.

## Quy tắc đặt tên

- Tên trait phải bắt đầu bằng `Has` (tiền tố chuẩn Laravel cho concern):
  `HasArDmKhCategories`, `HasInDmKhoInventoryOperations`.
- Một concern = một nhóm nghiệp vụ gắn với một model chính. Không gom
  nhiều concern của các model khác nhau vào cùng một trait.
- Đặt tên method rõ ràng, có comment PHPDoc giải thích ngữ nghĩa nghiệp vụ.

## Quy tắc cập nhật

- **Không tự đặt tên bảng/cột/SP.** Nguồn sự thật là `simba-docs/tables`.
  Khi cần truy cập field mới, đọc docs trước, nếu không có dừng và hỏi Sếp.
- Trước khi tạo concern mới, kiểm tra:
  1. Logic đó có được dùng chung cho ≥2 model, hoặc làm model chính phình
     quá nhiều? → Tách thành concern.
  2. Logic đó thuộc về lớp nào trong 3-layer? (`SModel`/`Simba\Models`/`Catalog`)
     → Đặt đúng lớp.
- Khi thêm/sửa concern phải cập nhật audit classification
  `scripts/audit-catalog-model-layer.php` cho các model `use` trait đó.
- Mọi thay đổi phải đi qua:
  ```bash
  php scripts/audit-catalog-model-layer.php
  vendor/bin/phpunit tests/Unit/Packages/Simba/SimbaModelLayerResponsibilityTest.php
  ```

## Tóm tắt

`Concerns` = trait business nghiệp vụ Portal/Catalog, dùng chung cho Catalog
Model. Phạm vi = một nhóm nghiệp vụ của một model. Không chứa schema,
không chứa logic UI/service.
# BALANCE TRANSFER INTEGRATION DOCUMENTATION

## Ngày: 2026-02-26
## Người thực hiện: Bột
## Mục tiêu: Tích hợp stored procedure `asGLChuyenSdTk` vào trang `hethong/balance/transfer`

---

## 1. Tổng quan

Đã tích hợp stored procedure `asGLChuyenSdTk` vào trang chuyển số dư (`hethong/balance/transfer`) để thực hiện chuyển số dư từ năm hiện tại sang năm mới.

## 2. Các thay đổi đã thực hiện

### 2.1. Livewire Component: `Transfer.php`
**File:** `/portal/diepxuan/laravel-catalog/src/Http/Livewire/System/Balance/Transfer.php`

**Thay đổi:**
- Thêm các properties: `$currentYear`, `$newYear`, `$transferDate`, `$note`, `$message`, `$messageType`, `$isProcessing`, `$previewData`
- Thêm validation rules cho form
- Thêm method `mount()` để khởi tạo giá trị mặc định
- Thêm method `loadPreviewData()` để tải dữ liệu xem trước
- Thêm method `executeTransfer()` để gọi stored procedure `asGLChuyenSdTk`
- Thêm method `getErrorMessage()` để xử lý các mã lỗi từ stored procedure

**Logic chính trong `executeTransfer()`:**
1. Validate form data
2. Kiểm tra năm mới > năm hiện tại
3. Kiểm tra ngày chuyển là ngày cuối năm hiện tại
4. Gọi stored procedure `AsGLChuyenSdTk::call()` với các tham số:
   - `pMa_cty`: '001' (mã công ty mặc định)
   - `pNgay_cnt`: Ngày cuối năm + ' 23:59:59'
5. Xử lý kết quả và hiển thị thông báo

### 2.2. Blade View: `transfer.blade.php`
**File:** `/portal/diepxuan/laravel-catalog/resources/views/system/balance/transfer.blade.php`

**Thay đổi:**
- Tích hợp Livewire form với `wire:submit.prevent="executeTransfer"`
- Thêm binding dữ liệu với `wire:model` cho các trường input
- Thêm hiển thị lỗi validation với `@error`
- Thêm loading state khi xử lý
- Cập nhật hiển thị dữ liệu xem trước từ `$previewData`
- Thêm thông tin về stored procedure

## 3. Stored Procedure: `asGLChuyenSdTk`

### 3.1. Thông tin cơ bản
- **Tên:** `asGLChuyenSdTk`
- **Mục đích:** Chuyển số dư khách hàng và tài khoản sang năm sau
- **Vị trí class:** `Diepxuan\Simba\StoredProcedures\AsGLChuyenSdTk`
- **Vị trí SQL:** `SimbaSql/dbo/StoredProcedures/asGLChuyenSdTk.sql`

### 3.2. Tham số
1. `@pMa_cty` (NVARCHAR(3)): Mã công ty
2. `@pNgay_cnt` (SMALLDATETIME): Ngày cuối năm trước
3. `@pRet` (INT, OUTPUT): Kết quả trả về (0 = thành công)

### 3.3. Logic chính
1. Kiểm tra ngày kế sách, nếu ngày đầu năm sau ≤ ngày kế sách → lỗi 50012
2. Xóa dữ liệu số dư năm tiếp theo trong `arcdkh` và `glcdtk`
3. Tổng hợp số dư từ `arcdkh` và `glct` vào các bảng tạm
4. Chuyển số dư âm thành số dư bên có
5. Chèn số dư mới vào `arcdkh` và `glcdtk` cho năm mới
6. Cập nhật số dư tài khoản công nợ

## 4. Cách sử dụng

### 4.1. Truy cập trang
- URL: `/hethong/balance/transfer`
- Route: `system.balance.transfer`

### 4.2. Các bước thực hiện
1. Kiểm tra năm hiện tại và năm mới (tự động điền)
2. Chọn ngày chuyển (mặc định là 31/12 của năm hiện tại)
3. Nhập ghi chú (tùy chọn)
4. Nhấn "Thực hiện chuyển số dư"
5. Hệ thống sẽ gọi stored procedure và hiển thị kết quả

### 4.3. Xử lý lỗi
- **Lỗi 50012:** Ngày đầu năm sau ≤ ngày kế sách → không thể chuyển
- **Lỗi validation:** Năm mới ≤ năm hiện tại, ngày chuyển không phải cuối năm
- **Lỗi hệ thống:** Hiển thị thông báo lỗi chi tiết

## 5. Kiểm tra

### 5.1. Kiểm tra class tồn tại
```bash
php artisan tinker --execute="echo class_exists('Diepxuan\Simba\StoredProcedures\AsGLChuyenSdTk') ? 'OK' : 'NOT FOUND';"
```

### 5.2. Kiểm tra route
```bash
php artisan route:list | grep balance.transfer
```

## 6. Lưu ý quan trọng

1. **Quyền hạn:** Chỉ người dùng có quyền mới được thực hiện chuyển số dư
2. **Sao lưu:** Nên sao lưu dữ liệu trước khi thực hiện
3. **Thời điểm:** Chỉ thực hiện vào cuối năm tài chính
4. **Kiểm tra:** Kiểm tra kỹ số dư trước và sau khi chuyển

## 7. Tài liệu tham khảo

1. **Stored procedure gốc:** `SimbaSql/dbo/StoredProcedures/asGLChuyenSdTk.sql`
2. **PHP class:** `portal/diepxuan/laravel-simba/src/StoredProcedures/AsGLChuyenSdTk.php`
3. **Follow script:** `SimbaSql/Follow/ChuyenSoDuTKSangNamSau.sql`
4. **Livewire documentation:** https://laravel-livewire.com/

---

## Kết luận

Đã hoàn thành tích hợp stored procedure `asGLChuyenSdTk` vào trang chuyển số dư. Hệ thống cho phép:
- Nhập thông tin chuyển số dư
- Gọi stored procedure để thực hiện chuyển số dư
- Hiển thị kết quả và xử lý lỗi
- Xem trước số dư sẽ được chuyển

Tất cả tuân thủ đúng quy trình Git workflow và documentation rule trong SOUL.md.
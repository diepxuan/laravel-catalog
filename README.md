# Laravel Catalog

Package catalog cho DXPanel.

## 📚 Documentation

- **Project Docs:** [Model Architecture](../../docs/project/model-architecture.md) - 3-layer Model pattern
- **Project Docs:** [Package Namespace Conventions](../../docs/project/package-namespace-conventions.md)

## Mô tả

Catalog package cho DXPanel (lấy từ composer.json).

## Chức năng chính

- Quản lý sản phẩm (Product) và danh mục (Category)
- Quản lý tồn kho (Inventory)
- Quản lý hệ thống: công ty, ngôn ngữ, người dùng, thông tin người dùng
- Cung cấp các service: CatalogService, CatalogFunctions
- Cung cấp các command: CatalogSync, EnvTest, Scavenger, Categories, Products
- Tích hợp với các package khác: Magento, Simba, Ronaldjack, Currency, Log, Core, Charset
- Hỗ trợ kết nối SQL Server tùy chỉnh
- Cung cấp các component view: AppLayout, SysUserInfo, SysYear, SysLanguage, SysCompany
- Cung cấp các controller cho hệ thống, bán hàng, tồn kho, website

## Cài đặt / Sử dụng

```bash
composer require diepxuan/laravel-catalog
```

Package tự động đăng ký ServiceProvider.

Sử dụng helper `catalog()` để truy cập CatalogService, `catalog_functions()` để truy cập CatalogFunctions.

## Cấu trúc thư mục

```
laravel-catalog/
├── src/
│   ├── Commands/          # Các command Artisan
│   ├── Config/            # Configuration classes (TimerConfig)
│   ├── Connectors/        # Connector SQL Server tùy chỉnh
│   ├── Http/              # Controller, Livewire components và API
│   ├── Models/            # Các model Eloquent
│   ├── Observers/         # Observer cho model
│   ├── Providers/         # ServiceProvider
│   ├── Services/          # Các service chính (CatalogService)
│   └── View/Components/   # Các component Blade
├── database/              # Factory, seeders
├── helpers.php            # Global helpers
└── composer.json
```

## Timer System

Package cung cấp hệ thống timer để lọc báo cáo theo thời gian:

- **TimerConfig** (`Config/TimerConfig.php`): Model quản lý cấu hình timer
  - `timer()`: Tạo instance timer từ session
  - `setTimer()`: Thiết lập timer và lưu session
  - `getTimer()`: Lấy timer settings từ session
  - `calculateTimeRange()`: Tính toán date range từ timeId
  - Constants: `TIME_MONTHS`, `TIME_QUARTERS`, `TIME_HALF_YEARS`, `TIME_YEAR`, `TIME_CUSTOM`

- **CatalogService** methods:
  - `timer()`: Legacy wrapper cho TimerConfig
  - `timerFrom()`: Lấy ngày bắt đầu
  - `timerTo()`: Lấy ngày kết thúc
  - `year()`: Lấy năm hiện tại từ session

- **Time format**:
  - `t01-t12`: Tháng 01-12
  - `q1-q4`: Quý 1-4
  - `h1`: 6 tháng đầu năm
  - `h2`: 6 tháng cuối năm
  - `y`: Cả năm
  - `c`: Custom (từ ... đến ...)

## Liên kết đến các package liên quan

- diepxuan/laravel-core
- diepxuan/laravel-currency
- diepxuan/laravel-log
- diepxuan/laravel-magento
- diepxuan/laravel-ronaldjack
- diepxuan/laravel-simba
- diepxuan/php-charset

## Ghi chú

Package này là trung tâm của hệ thống catalog, tích hợp nhiều tính năng và phụ thuộc nhiều package khác.
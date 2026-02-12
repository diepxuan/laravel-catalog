# Laravel Catalog

Package catalog cho DXPanel.

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
│   ├── Connectors/        # Connector SQL Server tùy chỉnh
│   ├── Http/              # Controller và API
│   ├── Models/            # Các model Eloquent
│   ├── Observers/         # Observer cho model
│   ├── Providers/         # ServiceProvider
│   ├── Services/          # Các service chính
│   └── View/Components/   # Các component Blade
├── database/              # Factory, seeders
├── helpers.php            # Global helpers
└── composer.json
```

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
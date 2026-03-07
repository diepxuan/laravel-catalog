# Timer System Documentation

Hệ thống chọn thời gian cho báo cáo trong Portal Diepxuan.

---

## Tổng quan

Timer system cung cấp giao diện và logic để chọn khoảng thời gian cho báo cáo:

- **Tháng** (t01-t12): Tháng 01 đến Tháng 12
- **Quý** (q1-q4): Quý 1 đến Quý 4
- **6 tháng** (h1, h2): 6 tháng đầu/cuối năm
- **Năm** (y): Cả năm
- **Custom** (c): Tự chọn từ ngày ... đến ngày ...

---

## Kiến trúc

```
┌─────────────────────────────────────────────────────────────┐
│                    Timer Blade Component                     │
│              (resources/views/components/)                   │
│                                                              │
│  [Dropdown: t01-t12, q1-q4, h1, h2, y, c]                   │
│  [Date From] → [Date To] (disabled except custom)           │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼ wire:model.live
┌─────────────────────────────────────────────────────────────┐
│              Timer Livewire Component                        │
│    (src/Http/Livewire/Component/Timer.php)                  │
│                                                              │
│  Properties: $timerId, $timerFrom, $timerTo                  │
│  Methods: mount(), updatedTimerId(), updatedTimerFrom()      │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼ \CatalogService::
┌─────────────────────────────────────────────────────────────┐
│                   CatalogService                             │
│            (src/Services/CatalogService.php)                 │
│                                                              │
│  Methods:                                                    │
│  - getTimer(): Read from session                             │
│  - setTimer(array): Calculate & save to session              │
│  - calculateTimeRange(string): Internal date calculation     │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                    TimerConfig                               │
│           (src/Config/TimerConfig.php)                       │
│                                                              │
│  Constants: TIME_MONTHS, TIME_QUARTERS, etc.                 │
│  Methods: options(), isValid(), isMonth(), etc.              │
└─────────────────────────────────────────────────────────────┘
```

---

## File Structure

```
diepxuan/laravel-catalog/
├── src/
│   ├── Config/
│   │   └── TimerConfig.php          # Constants & validation
│   ├── Services/
│   │   └── CatalogService.php       # Business logic
│   └── Http/Livewire/Component/
│       └── Timer.php                # Livewire component
├── resources/views/components/
│   └── timer.blade.php              # UI template
└── tests/Unit/
    ├── Config/
    │   └── TimerConfigTest.php
    └── Services/
        └── CatalogServiceTimerTest.php
```

---

## Usage

### Trong Blade view

```blade
{{-- Include timer component --}}
<livewire:timer />

{{-- Or use in layout --}}
<div class="report-header">
    <label>Thời gian:</label>
    <livewire:timer />
</div>
```

### Trong PHP code

```php
// Get current timer settings (read-only)
$timer = \CatalogService::getTimer();
// Returns: ['id' => 't03', 'from' => '2026-03-01', 'to' => '2026-03-31']

// Set new timer settings
$timer = \CatalogService::setTimer([
    'id' => 'q1',
    // 'from' and 'to' will be calculated automatically
]);

// Custom date range
$timer = \CatalogService::setTimer([
    'id' => 'c',
    'from' => '2026-03-01',
    'to' => '2026-03-31',
]);

// Get date range for report query
$from = $timer['from']; // '2026-03-01'
$to = $timer['to'];     // '2026-03-31'

// Use in query
$reports = Report::whereBetween('ngay_ct', [$from, $to])->get();
```

### Access TimerConfig

```php
use Diepxuan\Catalog\Config\TimerConfig;

// Get all options for dropdown
$options = TimerConfig::options();

// Validate timeId
if (TimerConfig::isValid($timeId)) {
    // Process...
}

// Check specific type
if (TimerConfig::isMonth($timeId)) {
    $month = TimerConfig::getMonthFromTimeId($timeId);
}

if (TimerConfig::isQuarter($timeId)) {
    $quarter = TimerConfig::getQuarterFromTimeId($timeId);
}
```

---

## API Reference

### TimerConfig

| Method | Description | Returns |
|--------|-------------|---------|
| `options()` | Get all time period options | `array<string, string>` |
| `isValid(string $timeId)` | Check if timeId is valid | `bool` |
| `isMonth(string $timeId)` | Check if month period | `bool` |
| `isQuarter(string $timeId)` | Check if quarter period | `bool` |
| `isHalfYear(string $timeId)` | Check if half-year period | `bool` |
| `isYear(string $timeId)` | Check if year period | `bool` |
| `isCustom(string $timeId)` | Check if custom period | `bool` |
| `getMonthFromTimeId(string $timeId)` | Extract month (1-12) | `?int` |
| `getQuarterFromTimeId(string $timeId)` | Extract quarter (1-4) | `?int` |

### CatalogService

| Method | Description | Returns |
|--------|-------------|---------|
| `getTimer()` | Get current timer from session | `array{id, from, to}` |
| `setTimer(array $time)` | Set timer and update session | `array{id, from, to}` |
| `calculateTimeRange(string $timeId)` | Calculate date range (protected) | `array{from, to}` |

### Timer Livewire Component

| Property | Type | Description |
|----------|------|-------------|
| `$timerId` | `string` | Current period identifier |
| `$timerFrom` | `string` | Start date (YYYY-MM-DD) |
| `$timerTo` | `string` | End date (YYYY-MM-DD) |
| `$options` | `array` | Available options for dropdown |

| Method | Description |
|--------|-------------|
| `mount()` | Initialize component with session data |
| `updatedTimerId()` | Handle period change |
| `updatedTimerFrom()` | Handle from-date change (custom mode) |
| `updatedTimerTo()` | Handle to-date change (custom mode) |

---

## Session Keys

Timer system uses these session keys:

| Key | Type | Description |
|-----|------|-------------|
| `timeId` | `string` | Period identifier (t01-t12, q1-q4, etc.) |
| `timeStart` | `Carbon` | Start date object |
| `timeEnd` | `Carbon` | End date object |
| `year` | `int` | Fiscal year (default: current year) |

---

## Date Range Calculation

| timeId | From | To |
|--------|------|-----|
| `t01` | 2026-01-01 | 2026-01-31 |
| `t02` | 2026-02-01 | 2026-02-28 |
| ... | ... | ... |
| `t12` | 2026-12-01 | 2026-12-31 |
| `q1` | 2026-01-01 | 2026-03-31 |
| `q2` | 2026-04-01 | 2026-06-30 |
| `q3` | 2026-07-01 | 2026-09-30 |
| `q4` | 2026-10-01 | 2026-12-31 |
| `h1` | 2026-01-01 | 2026-06-30 |
| `h2` | 2026-07-01 | 2026-12-31 |
| `y` | 2026-01-01 | 2026-12-31 |
| `c` | User-defined | User-defined |

---

## Testing

### Run tests

```bash
cd /root/.openclaw/workspace/projects/portal
phpunit diepxuan/laravel-catalog/tests/Unit/Config/TimerConfigTest.php
phpunit diepxuan/laravel-catalog/tests/Unit/Services/CatalogServiceTimerTest.php
```

### Test coverage

- **TimerConfig**: All validation methods, options generation
- **CatalogService**: getTimer, setTimer, date calculation
- **Edge cases**: Invalid timeId, custom mode, session empty

---

## Migration Guide (from legacy code)

### Before (deprecated)

```php
// Old way
$timer = \CatalogService::timer();
$timer = \CatalogService::timer(['id' => 'q1']);
$from = \CatalogService::timerFrom();
$to = \CatalogService::timerTo();
```

### After (recommended)

```php
// New way
$timer = \CatalogService::getTimer();
$timer = \CatalogService::setTimer(['id' => 'q1']);
$from = \CatalogService::getTimer()['from'];
$to = \CatalogService::getTimer()['to'];
```

> **Note**: Legacy `timer()` method still works for backward compatibility but is deprecated.

---

## Troubleshooting

### Timer không lưu vào session

**Check**: Session driver configured correctly?
```php
// .env
SESSION_DRIVER=file  # or database, redis
```

### Date range sai năm

**Check**: Year session key set correctly?
```php
// In controller or middleware
session(['year' => 2026]);
```

### Custom mode không hoạt động

**Check**: Timer Livewire component wired correctly?
```blade
{{-- Ensure wire:model.live is used --}}
<x-input-date wire:model.live="timerFrom" :disabled="$timerId !== 'c'" />
```

### Dropdown không hiện đủ options

**Check**: TimerConfig::options() called correctly?
```php
// In Timer.php mount()
$this->options = TimerConfig::options();
```

---

## Design Decisions

### Why separate getTimer() and setTimer()?

- **Single Responsibility**: Read vs Write operations
- **Performance**: Avoid unnecessary session writes on read
- **Testability**: Easier to mock and test separately

### Why use constants in TimerConfig?

- **DRY**: Single source of truth for time IDs
- **Type Safety**: Prevent typos in string literals
- **Maintainability**: Change once, use everywhere

### Why Livewire component instead of plain JS?

- **Reactivity**: Auto-update date fields when period changes
- **Server-side State**: Session-synced across requests
- **Accessibility**: Built-in form handling

---

## Future Improvements

- [ ] Add timezone support
- [ ] Add fiscal year configuration
- [ ] Add date format localization
- [ ] Add preset shortcuts (last 7 days, last 30 days, YTD, etc.)
- [ ] Add date range validation (from <= to)

---

## Changelog

### 2026-03-06 - Refactor

- Created `TimerConfig` class with constants
- Split `CatalogService::timer()` into `getTimer()` / `setTimer()`
- Refactored Timer Livewire component
- Added keyboard navigation
- Added comprehensive tests
- Added documentation

### 2025-05-27 - Original Implementation

- Initial timer component
- Basic month/quarter/year support
- Session-based state management

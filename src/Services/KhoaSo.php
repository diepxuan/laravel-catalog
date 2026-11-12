<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-10-19
 */

namespace Diepxuan\Catalog\Services;

use Carbon\CarbonImmutable;
use Diepxuan\Catalog\Models\SystemConfig;
use Diepxuan\Simba\SModel\SModel;
use Throwable;

/**
 * Kiểm tra mốc khóa sổ liệu kỳ (SiSetup.ngay_ks) dùng chung.
 *
 * Quy tắc lấy từ SimbaERP (xem simba-docs), áp dụng cho mọi luồng
 * nhập/sửa/xóa/post chứng từ:
 *   - Chứng từ hợp lệ khi `ngay_ct > ngay_ks` (báo lỗi 50136 / 50012 gốc).
 *   - Kỳ báo cáo hợp lệ khi `ngay2 > ngay_ks`.
 *
 * Ví dụ nguyên văn trong simba-docs:
 *   - simba-docs/asia/ar/vouchers/ARTT.md:55 — "Không thể xóa chứng từ trước
 *     ngày khóa sổ", điều kiện `ngay_ct > ngay_ks`.
 *   - simba-docs/asia/ca/masters/CACDPC.md:171 — `ngay_ct <= ngay_ks` → 50136.
 *   - simba-docs/asia/fa/vouchers/FACalKHTS.md:89 — ngày kết thúc kỳ phải
 *     lớn hơn ngày khóa sổ, nếu không báo 50136.
 *
 * Ngày khóa sổ đọc từ SiSetup theo công ty hiện tại, không hardcode.
 */
class KhoaSo
{
    /**
     * Message lỗi dùng chung (giữ đúng tinh thần 50136 của SimbaERP).
     */
    public const MESSAGE_DOCUMENT_LOCKED = 'Không được nhập/sửa/xóa chứng từ trước ngày khóa sổ.';

    public const MESSAGE_PERIOD_LOCKED = 'Kỳ đã khóa sổ, không thể thực hiện tính toán/kết chuyển.';

    public const MESSAGE_MISSING_CONFIG = 'Không tìm thấy cấu hình ngày khóa sổ (SiSetup.ngay_ks) cho công ty :ma_cty.';

    /** @var array<string, string|null> */
    protected array $cache = [];

    /**
     * Ngày khóa sổ của công ty (Y-m-d), null nếu chưa cấu hình.
     */
    public function lockDate(?string $maCty = null): ?string
    {
        $maCty = $this->normalizeCompanyCode($maCty);

        if (\array_key_exists($maCty, $this->cache)) {
            return $this->cache[$maCty];
        }

        $ngayKs = null;

        try {
            $ngayKs = SystemConfig::query()
                ->withoutGlobalScope('onlyFirstCompany')
                ->where('ma_cty', $maCty)
                ->value('ngay_ks')
            ;
        } catch (Throwable) {
            $ngayKs = null;
        }

        if (null === $ngayKs || '' === $ngayKs) {
            return $this->cache[$maCty] = null;
        }

        try {
            return $this->cache[$maCty] = CarbonImmutable::parse($ngayKs)->format('Y-m-d');
        } catch (Throwable) {
            return $this->cache[$maCty] = null;
        }
    }

    /**
     * Chứng từ có ngày thuộc kỳ đã khóa sổ hay không.
     *
     * Trả false khi không có cấu hình ngày khóa sổ (không chặn nhầm).
     */
    public function isDocumentDateLocked(?string $ngayCt, ?string $maCty = null): bool
    {
        $ngayCt = $this->normalizeDate($ngayCt);
        if (null === $ngayCt) {
            return false;
        }

        $ngayKs = $this->lockDate($maCty);
        if (null === $ngayKs) {
            return false;
        }

        // Hợp lệ khi ngay_ct > ngay_ks.
        return $ngayCt <= $ngayKs;
    }

    /**
     * Kỳ (ngay1..ngay2) có bị khóa sổ hay không.
     *
     * Simba kiểm tra theo ngày cuối kỳ: hợp lệ khi `ngay2 > ngay_ks`.
     *
     * NGUỒN SỰ THẬT: rà toàn bộ `simba-docs/decompiled/asia/*` cho thấy chỉ có
     * 15 DLL dùng `ngay_ks`, và **không DLL nào là báo cáo** (`*Rpt*`). Tất cả
     * đều là hành động GHI dữ liệu theo kỳ:
     *
     *   - Tính toán: `FACalKHTS` (khấu hao), `INCalGiaNTXT` (giá FIFO),
     *     `CoCalcCost` (giá thành).
     *   - Kết chuyển/post: `GLPost2GL`, `GLPost2GLKC`, `GLPost2DGTG`,
     *     `CoPostCPTT2GL`, `CoDelPostCP2GL`, `FAPostCC2GL`, `FADelTS2GL`,
     *     `FADelCC2GL`.
     *   - Chứng từ: `SOTaOut`, `ARCDKH` (số dư đầu kỳ — nhập liệu), `SMKS`
     *     (chính là màn cập nhật `ngay_ks`).
     *
     * Vì vậy: chỉ dùng method này cho hành động ghi/tính theo kỳ. KHÔNG dùng để
     * chặn màn xem báo cáo/tra cứu — xem kỳ đã khóa sổ là hợp lệ và cần thiết.
     * Lưu ý thuật ngữ: "kỳ báo cáo" trong simba-docs là tham số đầu vào của
     * hành động tính/post (vd form `FACalKHTS` có `cboKyBc`), KHÔNG phải màn
     * hiển thị báo cáo.
     */
    public function isPeriodLocked(?string $ngay1, ?string $ngay2, ?string $maCty = null): bool
    {
        $ngayCuoiKy = $this->normalizeDate($ngay2) ?? $this->normalizeDate($ngay1);
        if (null === $ngayCuoiKy) {
            return false;
        }

        $ngayKs = $this->lockDate($maCty);
        if (null === $ngayKs) {
            return false;
        }

        return $ngayCuoiKy <= $ngayKs;
    }

    /**
     * Ném exception nếu chứng từ thuộc kỳ đã khóa sổ.
     *
     * @throws KhoaSoException
     */
    public function assertDocumentDateNotLocked(?string $ngayCt, ?string $maCty = null): void
    {
        if ($this->isDocumentDateLocked($ngayCt, $maCty)) {
            throw new KhoaSoException(self::MESSAGE_DOCUMENT_LOCKED);
        }
    }

    /**
     * Ném exception nếu kỳ báo cáo đã khóa sổ.
     *
     * @throws KhoaSoException
     */
    public function assertPeriodNotLocked(?string $ngay1, ?string $ngay2, ?string $maCty = null): void
    {
        if ($this->isPeriodLocked($ngay1, $ngay2, $maCty)) {
            throw new KhoaSoException(self::MESSAGE_PERIOD_LOCKED);
        }
    }

    /**
     * Công ty hiện tại, chuẩn hóa về dạng 3 ký tự có số 0 đầu (001/002).
     */
    public function currentCompanyCode(): string
    {
        return $this->normalizeCompanyCode(null);
    }

    protected function normalizeCompanyCode(?string $maCty): string
    {
        if (null === $maCty || '' === trim($maCty)) {
            try {
                $company = \CatalogService::company();
                $maCty   = (string) ($company->ma_cty ?? $company->id ?? SModel::CTY);
            } catch (Throwable) {
                $maCty = (string) (session('selected_company') ?: SModel::CTY);
            }
        }

        $maCty = trim($maCty);

        if (ctype_digit($maCty) && \strlen($maCty) < 3) {
            return str_pad($maCty, 3, '0', STR_PAD_LEFT);
        }

        return '' !== $maCty ? $maCty : SModel::CTY;
    }

    protected function normalizeDate(?string $value): ?string
    {
        if (null === $value || '' === trim($value)) {
            return null;
        }

        try {
            return CarbonImmutable::parse($value)->format('Y-m-d');
        } catch (Throwable) {
            return null;
        }
    }
}

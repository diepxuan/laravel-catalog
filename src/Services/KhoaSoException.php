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

use RuntimeException;

/**
 * Exception khi chứng từ/kỳ báo cáo thuộc kỳ đã khóa sổ.
 *
 * Message tiếng Việt, tương ứng mã lỗi 50136 / 50012 của SimbaERP.
 */
class KhoaSoException extends RuntimeException
{
}

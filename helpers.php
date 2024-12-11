<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-12-09 12:18:25
 */

if (!function_exists('dvite')) {
    /**
     * Convert Vietnamese encoding to another.
     *
     * @param mixed $string
     * @param mixed $from
     * @param mixed $to
     */
    function dvite(string $string, $from = 'unicode', $to = 'ascii'): string
    {
        // try {
        //     return (string) new VnEncoding($string, $from, $to);
        // } catch (Throwable $th) {
        //     return '';
        // }
    }
}

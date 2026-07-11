<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-07-11 14:35:01
 */

namespace Diepxuan\Catalog\Services;

use Diepxuan\Catalog\Config\TimerConfig;
use Diepxuan\Catalog\Models\Simba\SysCompany;
use Diepxuan\Catalog\Models\Simba\SysLanguage;
use Diepxuan\Catalog\Models\Simba\SysUserInfo;
use Diepxuan\Catalog\Models\User;
use Diepxuan\Simba\StoredProcedures\AsGLGetDMTK;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CatalogService
{
    protected SysLanguage $sysLanguage;
    protected User $user;
    protected SysUserInfo $simbaUser;
    protected ?SysCompany $company = null;
    protected string $maNt;
    protected string $id;
    protected array $glDmTks = [];

    public function __construct()
    {
        $this->id = uniqid('report_');
        // \Log::info("ReportService instance đã được khởi tạo với ID: {$this->id}");
        // \Debugbar::info('ReportService instance đã được khởi tạo với ID: ' . $this->id);
    }

    public function user()
    {
        return $this->user ?? $this->user = Auth::user();
    }

    /**
     * Get Simba user.
     */
    public function simbaUser(): SysUserInfo
    {
        return $this->simbaUser ?? $this->simbaUser = $this->user()->getSimbaUser();
    }

    public function language()
    {
        return $this->sysLanguage ?? $this->sysLanguage = SysLanguage::current()->first();
    }

    public function company()
    {
        if ($this->company) {
            return $this->company;
        }

        $ma_cty = session('selected_company', '001');
        if ($ma_cty) {
            $this->company = $this->companies()->firstWhere('ma_cty', $ma_cty);
        } else {
            $this->company = $this->companies()->first();
        }

        session(['selected_company' => $this->company->ma_cty]);

        return $this->company;
    }

    public function companies()
    {
        return $this->simbaUser()->companies;
    }

    public function year(?int $year = null): int
    {
        if (!is_numeric($year) || $year < 2_006 || $year > 2_100) {
            $year = session('year', now()->year) ?? now()->year;
        }

        session(['year' => $year]);

        return $year;
    }

    /**
     * Legacy timer method for backward compatibility.
     *
     * @param null|array|string $time Timer settings
     *
     * @return array{id: string, from: string, to: string}
     */
    public function timer(array|string|null $time = null): array
    {
        $time = match (true) {
            \is_array($time) => $time,
            null === $time   => [],
            default          => ['id' => $time],
        };

        return TimerConfig::timer($time ?: null);
    }

    /**
     * Get timer from date.
     */
    public function timerFrom(): string
    {
        return $this->timer()['from'];
    }

    /**
     * Get timer to date.
     */
    public function timerTo(): string
    {
        return $this->timer()['to'];
    }

    public function ma_Nt()
    {
        return $this->maNt ?? $this->maNt = ($this->company()->siSetup->ma_nt0 ?? 'VND');
    }

    public function glDmTks(?string $pTk = null, ?string $pStruct = null): Collection
    {
        $maCty = $this->company()->id;
        $key   = implode('|', [$maCty, $pStruct ?? '']);

        $this->glDmTks[$key] ??= AsGLGetDMTK::call([
            'pMa_cty' => $maCty,
            'pTk'     => null,
            'pStruct' => $pStruct,
        ]);

        return $this->glDmTks[$key]
            ->filter(static fn ($item) => null === $pTk || (string) $item->tk === $pTk)
            ->values();
    }
}

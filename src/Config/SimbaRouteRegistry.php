<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 09:35:04
 */

namespace Diepxuan\Catalog\Config;

final class SimbaRouteRegistry
{
    public const TYPE_DICTIONARY = 'dictionary';
    public const TYPE_VOUCHER    = 'voucher';
    public const TYPE_REPORT     = 'report';
    public const TYPE_CUSTOM     = 'custom';

    /**
     * Route metadata for implemented Portal screens mapped to SimbaERP sources.
     *
     * @return array<string, array<string, string>>
     */
    public static function routes(): array
    {
        return self::routesWithoutProcesses() + self::documentedProcessRoutes();
    }

    /**
     * @return array<string, array<string, string>>
     */
    public static function routesWithoutProcesses(): array
    {
        $routes = [
            'gl.taikhoan' => [
                'module'          => 'GL',
                'menuid'          => '02.90.02',
                'source_type'     => self::TYPE_DICTIONARY,
                'dictionary_key'  => 'TK',
                'simba_code_name' => 'GLDMTK',
            ],
            'gl.vch.gl1' => [
                'module'      => 'GL',
                'menuid'      => '02.10.02',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'GL1',
            ],
            'gl.vch.nb1' => [
                'module'      => 'GL',
                'menuid'      => '02.10.17',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'NB1',
            ],
            'gl.rpt.ctgs01' => [
                'module'      => 'GL',
                'menuid'      => '02.20.11',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.ctgs02' => [
                'module'      => 'GL',
                'menuid'      => '02.20.14',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.ctgs03' => [
                'module'      => 'GL',
                'menuid'      => '02.20.17',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.ctgs05' => [
                'module'      => 'GL',
                'menuid'      => '02.20.20',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.nkc01' => [
                'module'      => 'GL',
                'menuid'      => '02.25.02',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.nkc02' => [
                'module'      => 'GL',
                'menuid'      => '02.25.05',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.nkc03' => [
                'module'      => 'GL',
                'menuid'      => '02.25.08',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.nkc03-chi' => [
                'module'      => 'GL',
                'menuid'      => '02.25.11',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.nkc04' => [
                'module'      => 'GL',
                'menuid'      => '02.25.14',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.nkc05' => [
                'module'      => 'GL',
                'menuid'      => '02.25.17',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.nkc06' => [
                'module'      => 'GL',
                'menuid'      => '02.25.20',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.nkc07' => [
                'module'      => 'GL',
                'menuid'      => '02.25.23',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.bk02' => [
                'module'      => 'GL',
                'menuid'      => '02.25.26',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.th01' => [
                'module'      => 'GL',
                'menuid'      => '02.25.29',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.bct01' => [
                'module'      => 'GL',
                'menuid'      => '02.40.02',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.bct02' => [
                'module'      => 'GL',
                'menuid'      => '02.40.05',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.bct03' => [
                'module'      => 'GL',
                'menuid'      => '02.40.08',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.bct04' => [
                'module'      => 'GL',
                'menuid'      => '02.40.14',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.bct05' => [
                'module'      => 'GL',
                'menuid'      => '02.40.17',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.bct06' => [
                'module'      => 'GL',
                'menuid'      => '02.40.20',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.bctc01' => [
                'module'      => 'GL',
                'menuid'      => '02.50.02',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.bctc02' => [
                'module'      => 'GL',
                'menuid'      => '02.50.05',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.bctc03' => [
                'module'      => 'GL',
                'menuid'      => '02.50.08',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.bctc04' => [
                'module'      => 'GL',
                'menuid'      => '02.50.11',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.tmbctc' => [
                'module'      => 'GL',
                'menuid'      => '02.50.20',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.bctccdtb02' => [
                'module'      => 'GL',
                'menuid'      => '02.55.02',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.bctccdtf02' => [
                'module'      => 'GL',
                'menuid'      => '02.55.05',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.bctccdtf03a' => [
                'module'      => 'GL',
                'menuid'      => '02.55.08',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.bctccdtf03b' => [
                'module'      => 'GL',
                'menuid'      => '02.55.11',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.bctccdtb03' => [
                'module'      => 'GL',
                'menuid'      => '02.55.14',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.bctccdtf03c' => [
                'module'      => 'GL',
                'menuid'      => '02.55.17',
                'source_type' => self::TYPE_REPORT,
            ],
            'gl.rpt.bctccdtf03d' => [
                'module'      => 'GL',
                'menuid'      => '02.55.20',
                'source_type' => self::TYPE_REPORT,
            ],
            'fa.rpt.bcts01' => [
                'module'      => 'FA',
                'menuid'      => '20.20.02',
                'source_type' => self::TYPE_REPORT,
            ],
            'fa.rpt.bcts08' => [
                'module'      => 'FA',
                'menuid'      => '20.20.05',
                'source_type' => self::TYPE_REPORT,
            ],
            'fa.rpt.bcts09' => [
                'module'      => 'FA',
                'menuid'      => '20.20.08',
                'source_type' => self::TYPE_REPORT,
            ],
            'fa.rpt.bcts04' => [
                'module'      => 'FA',
                'menuid'      => '20.20.11',
                'source_type' => self::TYPE_REPORT,
            ],
            'fa.rpt.bcts05' => [
                'module'      => 'FA',
                'menuid'      => '20.20.14',
                'source_type' => self::TYPE_REPORT,
            ],
            'fa.rpt.bcts06' => [
                'module'      => 'FA',
                'menuid'      => '20.20.17',
                'source_type' => self::TYPE_REPORT,
            ],
            'fa.rpt.bcts07' => [
                'module'      => 'FA',
                'menuid'      => '20.20.20',
                'source_type' => self::TYPE_REPORT,
            ],
            'fa.rpt.bcpt10' => [
                'module'      => 'FA',
                'menuid'      => '20.30.02',
                'source_type' => self::TYPE_REPORT,
            ],
            'fa.rpt.bkn04' => [
                'module'      => 'FA',
                'menuid'      => '20.30.08',
                'source_type' => self::TYPE_REPORT,
            ],
            'fa.rpt.thn01' => [
                'module'      => 'FA',
                'menuid'      => '20.30.11',
                'source_type' => self::TYPE_REPORT,
            ],
            'fa.rpt.bkx04' => [
                'module'      => 'FA',
                'menuid'      => '20.30.14',
                'source_type' => self::TYPE_REPORT,
            ],
            'fa.rpt.thx01' => [
                'module'      => 'FA',
                'menuid'      => '20.30.17',
                'source_type' => self::TYPE_REPORT,
            ],
            'fa.dmnv' => [
                'module'          => 'FA',
                'menuid'          => '20.90.02',
                'source_type'     => self::TYPE_DICTIONARY,
                'dictionary_key'  => 'MA_NV',
                'simba_code_name' => 'FADMNV',
            ],
            'fa.dmbpsd' => [
                'module'          => 'FA',
                'menuid'          => '20.90.08',
                'source_type'     => self::TYPE_DICTIONARY,
                'dictionary_key'  => 'MA_BPSD',
                'simba_code_name' => 'FADMBPSD',
            ],
            'co.rpt.bk02' => [
                'module'      => 'CO',
                'menuid'      => '17.60.02',
                'source_type' => self::TYPE_REPORT,
            ],
            'co.rpt.th01' => [
                'module'      => 'CO',
                'menuid'      => '17.60.05',
                'source_type' => self::TYPE_REPORT,
            ],
            'co.rpt.khcp' => [
                'module'      => 'CO',
                'menuid'      => '17.60.08',
                'source_type' => self::TYPE_REPORT,
            ],
            'co.rpt.gt01' => [
                'module'      => 'CO',
                'menuid'      => '18.20.02',
                'source_type' => self::TYPE_REPORT,
            ],
            'co.rpt.gt02' => [
                'module'      => 'CO',
                'menuid'      => '18.20.05',
                'source_type' => self::TYPE_REPORT,
            ],
            'co.rpt.bkn04' => [
                'module'      => 'CO',
                'menuid'      => '18.20.14',
                'source_type' => self::TYPE_REPORT,
            ],
            'co.rpt.thn01' => [
                'module'      => 'CO',
                'menuid'      => '18.20.17',
                'source_type' => self::TYPE_REPORT,
            ],
            'co.rpt.thn02' => [
                'module'      => 'CO',
                'menuid'      => '18.20.18',
                'source_type' => self::TYPE_REPORT,
            ],
            'co.rpt.bkx04' => [
                'module'      => 'CO',
                'menuid'      => '18.20.20',
                'source_type' => self::TYPE_REPORT,
            ],
            'co.rpt.thx01' => [
                'module'      => 'CO',
                'menuid'      => '18.20.23',
                'source_type' => self::TYPE_REPORT,
            ],
            'co.rpt.thx02' => [
                'module'      => 'CO',
                'menuid'      => '18.20.26',
                'source_type' => self::TYPE_REPORT,
            ],
            'co.rpt.gt03' => [
                'module'      => 'CO',
                'menuid'      => '18.25.02',
                'source_type' => self::TYPE_REPORT,
            ],
            'co.rpt.gt03-dutoan' => [
                'module'      => 'CO',
                'menuid'      => '18.25.08',
                'source_type' => self::TYPE_REPORT,
            ],
            'co.rpt.gt03-lailo' => [
                'module'      => 'CO',
                'menuid'      => '18.25.11',
                'source_type' => self::TYPE_REPORT,
            ],
            'co.rpt.bccn04' => [
                'module'      => 'CO',
                'menuid'      => '18.25.14',
                'source_type' => self::TYPE_REPORT,
            ],
            'co.dmsp' => [
                'module'          => 'CO',
                'menuid'          => '18.90.02',
                'source_type'     => self::TYPE_DICTIONARY,
                'dictionary_key'  => 'MA_SP',
                'simba_code_name' => 'CODMSPCT',
            ],
            'co.dmspct' => [
                'module'          => 'CO',
                'menuid'          => '18.90.05',
                'source_type'     => self::TYPE_DICTIONARY,
                'dictionary_key'  => 'MA_SPCT',
                'simba_code_name' => 'CODMSPCT',
            ],
            'co.dmnhspct' => [
                'module'          => 'CO',
                'menuid'          => '18.90.08',
                'source_type'     => self::TYPE_DICTIONARY,
                'dictionary_key'  => 'MA_NHSPCT',
                'simba_code_name' => 'CODMNHSPCT',
            ],
            'si.dmct' => [
                'module'          => 'SI',
                'menuid'          => '90.10.02',
                'source_type'     => self::TYPE_DICTIONARY,
                'dictionary_key'  => 'SIDMCT',
                'simba_code_name' => 'SIDMCT',
            ],
            'si.dmphi' => [
                'module'          => 'SI',
                'menuid'          => '90.10.20',
                'source_type'     => self::TYPE_DICTIONARY,
                'dictionary_key'  => 'MA_PHI',
                'simba_code_name' => 'SIDMPHI',
            ],
            'si.dmnt' => [
                'module'          => 'SI',
                'menuid'          => '90.10.26',
                'source_type'     => self::TYPE_DICTIONARY,
                'dictionary_key'  => 'MA_NT',
                'simba_code_name' => 'SIDMNT',
            ],
            'system.balance.index' => [
                'module'      => 'SI',
                'menuid'      => '90.40.14',
                'source_type' => self::TYPE_CUSTOM,
            ],
            'system.company' => [
                'module'      => 'SI',
                'menuid'      => '90.40.17',
                'source_type' => self::TYPE_CUSTOM,
            ],
            'system.user.index' => [
                'module'      => 'SI',
                'menuid'      => '90.20.02',
                'source_type' => self::TYPE_CUSTOM,
            ],
            'profile.show' => [
                'module'      => 'SI',
                'menuid'      => '90.20.08',
                'source_type' => self::TYPE_CUSTOM,
            ],
            'system.year' => [
                'module'      => 'SI',
                'menuid'      => '90.30.02',
                'source_type' => self::TYPE_CUSTOM,
            ],
            'system.balance.account-opening' => [
                'module'      => 'GL',
                'menuid'      => '02.10.14',
                'source_type' => self::TYPE_CUSTOM,
            ],
            'system.balance.transfer' => [
                'module'      => 'GL',
                'menuid'      => '02.10.20',
                'source_type' => self::TYPE_CUSTOM,
            ],
            'system.balance.accounts-receivable' => [
                'module'      => 'SO',
                'menuid'      => '06.10.40',
                'source_type' => self::TYPE_CUSTOM,
            ],
            'system.balance.accounts-payable' => [
                'module'      => 'PO',
                'menuid'      => '10.10.44',
                'source_type' => self::TYPE_CUSTOM,
            ],
            'system.balance.inventory-opening' => [
                'module'      => 'IN',
                'menuid'      => '14.10.20',
                'source_type' => self::TYPE_CUSTOM,
            ],
            'system.balance.inventory-opening-ntxt' => [
                'module'      => 'IN',
                'menuid'      => '14.10.23',
                'source_type' => self::TYPE_CUSTOM,
            ],
            'system.balance.inventory-transfer' => [
                'module'      => 'IN',
                'menuid'      => '14.10.38',
                'source_type' => self::TYPE_CUSTOM,
            ],
            'system.balance.work-in-progress' => [
                'module'      => 'CO',
                'menuid'      => '18.10.11',
                'source_type' => self::TYPE_CUSTOM,
            ],
            'system.balance.cumulative-transactions' => [
                'module'      => 'CO',
                'menuid'      => '18.10.14',
                'source_type' => self::TYPE_CUSTOM,
            ],
            'ar.rpt.bk01' => [
                'module'      => 'AR',
                'menuid'      => '06.30.02',
                'source_type' => self::TYPE_REPORT,
            ],
            'ar.rpt.bccn01' => [
                'module'      => 'AR',
                'menuid'      => '06.30.14',
                'source_type' => self::TYPE_REPORT,
            ],
            'ar.rpt.bccn01a' => [
                'module'      => 'AR',
                'menuid'      => '06.30.17',
                'source_type' => self::TYPE_REPORT,
            ],
            'ar.rpt.bccn02' => [
                'module'      => 'AR',
                'menuid'      => '06.30.20',
                'source_type' => self::TYPE_REPORT,
            ],
            'ar.rpt.bccn03' => [
                'module'      => 'AR',
                'menuid'      => '06.30.23',
                'source_type' => self::TYPE_REPORT,
            ],
            'ar.rpt.bccn05' => [
                'module'      => 'AR',
                'menuid'      => '06.30.26',
                'source_type' => self::TYPE_REPORT,
            ],
            'ar.rpt.bccn04' => [
                'module'      => 'AR',
                'menuid'      => '06.30.35',
                'source_type' => self::TYPE_REPORT,
            ],
            'ar.rpt.bccn01sl' => [
                'module'      => 'AR',
                'menuid'      => '06.30.38',
                'source_type' => self::TYPE_REPORT,
            ],
            'ar.rpt.bccn01a-cont' => [
                'module'      => 'AR',
                'menuid'      => '06.30.41',
                'source_type' => self::TYPE_REPORT,
            ],
            'ar.rpt.bccn03-open' => [
                'module'      => 'AR',
                'menuid'      => '06.30.44',
                'source_type' => self::TYPE_REPORT,
            ],
            'ar.rpt.bccn03-close' => [
                'module'      => 'AR',
                'menuid'      => '06.30.47',
                'source_type' => self::TYPE_REPORT,
            ],
            'ar.rpt.bccn05-detail' => [
                'module'      => 'AR',
                'menuid'      => '06.30.50',
                'source_type' => self::TYPE_REPORT,
            ],
            'ar.rpt.bccn06' => [
                'module'      => 'AR',
                'menuid'      => '06.30.32',
                'source_type' => self::TYPE_REPORT,
            ],
            'ap.rpt.bk01' => [
                'module'      => 'AP',
                'menuid'      => '10.30.02',
                'source_type' => self::TYPE_REPORT,
            ],
            'ap.rpt.bccn01' => [
                'module'      => 'AP',
                'menuid'      => '10.30.11',
                'source_type' => self::TYPE_REPORT,
            ],
            'ap.rpt.bccn01a' => [
                'module'      => 'AP',
                'menuid'      => '10.30.14',
                'source_type' => self::TYPE_REPORT,
            ],
            'ap.rpt.bccn02' => [
                'module'      => 'AP',
                'menuid'      => '10.30.17',
                'source_type' => self::TYPE_REPORT,
            ],
            'ap.rpt.bccn03' => [
                'module'      => 'AP',
                'menuid'      => '10.30.20',
                'source_type' => self::TYPE_REPORT,
            ],
            'ap.rpt.bccn01sl' => [
                'module'      => 'AP',
                'menuid'      => '10.30.23',
                'source_type' => self::TYPE_REPORT,
            ],
            'ap.rpt.bccn05' => [
                'module'      => 'AP',
                'menuid'      => '10.30.26',
                'source_type' => self::TYPE_REPORT,
            ],
            'ap.rpt.bccn01a-cont' => [
                'module'      => 'AP',
                'menuid'      => '10.30.27',
                'source_type' => self::TYPE_REPORT,
            ],
            'ap.rpt.bccn04' => [
                'module'      => 'AP',
                'menuid'      => '10.30.35',
                'source_type' => self::TYPE_REPORT,
            ],
            'ap.rpt.bccn03-open' => [
                'module'      => 'AP',
                'menuid'      => '10.30.44',
                'source_type' => self::TYPE_REPORT,
            ],
            'ap.rpt.bccn03-close' => [
                'module'      => 'AP',
                'menuid'      => '10.30.47',
                'source_type' => self::TYPE_REPORT,
            ],
            'ap.rpt.bccn05-detail' => [
                'module'      => 'AP',
                'menuid'      => '10.30.50',
                'source_type' => self::TYPE_REPORT,
            ],
            'ca.tienmat.thu' => [
                'module'      => 'CA',
                'menuid'      => '04.10.02',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'CA1',
            ],
            'ca.tienmat.chi' => [
                'module'      => 'CA',
                'menuid'      => '04.10.05',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'CA2',
            ],
            'ca.nganhang.baoco' => [
                'module'      => 'CA',
                'menuid'      => '04.10.08',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'CA3',
            ],
            'ca.nganhang.baono' => [
                'module'      => 'CA',
                'menuid'      => '04.10.11',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'CA4',
            ],
            'ca.tienmat.quy' => [
                'module'      => 'CA',
                'menuid'      => '04.20.02',
                'source_type' => self::TYPE_REPORT,
            ],
            'ca.nganhang.quy' => [
                'module'      => 'CA',
                'menuid'      => '04.20.05',
                'source_type' => self::TYPE_REPORT,
            ],
            'ca.thu' => [
                'module'      => 'CA',
                'menuid'      => '04.20.08',
                'source_type' => self::TYPE_REPORT,
            ],
            'ca.chi' => [
                'module'      => 'CA',
                'menuid'      => '04.20.11',
                'source_type' => self::TYPE_REPORT,
            ],
            'ca.rpt.tmnh06' => [
                'module'      => 'CA',
                'menuid'      => '04.20.14',
                'source_type' => self::TYPE_REPORT,
            ],
            'ca.rpt.bk01' => [
                'module'      => 'CA',
                'menuid'      => '04.20.29',
                'source_type' => self::TYPE_REPORT,
            ],
            'ca.rpt.tmnh01a' => [
                'module'      => 'CA',
                'menuid'      => '04.20.32',
                'source_type' => self::TYPE_REPORT,
            ],
            'ar.ph.hdbh' => [
                'module'      => 'SO',
                'menuid'      => '06.10.08',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'SO3',
            ],
            'ar.vch.ar4' => [
                'module'      => 'AR',
                'menuid'      => '06.10.29',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'AR4',
            ],
            'banhang.so1' => [
                'module'      => 'SO',
                'menuid'      => '06.10.02',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'SO1',
            ],
            'banhang.so4' => [
                'module'      => 'SO',
                'menuid'      => '06.10.11',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'SO4',
            ],
            'banhang.so5' => [
                'module'      => 'SO',
                'menuid'      => '06.10.14',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'SO5',
            ],
            'so.rpt.bk01' => [
                'module'      => 'SO',
                'menuid'      => '06.20.02',
                'source_type' => self::TYPE_REPORT,
            ],
            'so.rpt.bk02' => [
                'module'      => 'SO',
                'menuid'      => '06.20.05',
                'source_type' => self::TYPE_REPORT,
            ],
            'so.rpt.th01' => [
                'module'      => 'SO',
                'menuid'      => '06.20.08',
                'source_type' => self::TYPE_REPORT,
            ],
            'so.rpt.th02' => [
                'module'      => 'SO',
                'menuid'      => '06.20.11',
                'source_type' => self::TYPE_REPORT,
            ],
            'so.rpt.bcpt03' => [
                'module'      => 'SO',
                'menuid'      => '06.20.20',
                'source_type' => self::TYPE_REPORT,
            ],
            'so.rpt.bcpt04' => [
                'module'      => 'SO',
                'menuid'      => '06.20.23',
                'source_type' => self::TYPE_REPORT,
            ],
            'so.rpt.bcpt06' => [
                'module'      => 'SO',
                'menuid'      => '06.20.26',
                'source_type' => self::TYPE_REPORT,
            ],
            'so.rpt.lailo' => [
                'module'      => 'SO',
                'menuid'      => '06.20.47',
                'source_type' => self::TYPE_REPORT,
            ],
            'so.rpt.nkc04' => [
                'module'      => 'SO',
                'menuid'      => '06.20.50',
                'source_type' => self::TYPE_REPORT,
            ],
            'so.rpt.bcpt03-net' => [
                'module'      => 'SO',
                'menuid'      => '06.20.83',
                'source_type' => self::TYPE_REPORT,
            ],
            'so.rpt.bcpt04-nv' => [
                'module'      => 'SO',
                'menuid'      => '06.20.86',
                'source_type' => self::TYPE_REPORT,
            ],
            'so.rpt.lailo-th' => [
                'module'      => 'SO',
                'menuid'      => '06.20.89',
                'source_type' => self::TYPE_REPORT,
            ],
            'ar.khachhang' => [
                'module'          => 'SO',
                'menuid'          => '06.90.02',
                'source_type'     => self::TYPE_DICTIONARY,
                'dictionary_key'  => 'MA_KH',
                'simba_code_name' => 'ARDMKH',
            ],
            'po.cungcap' => [
                'module'          => 'PO',
                'menuid'          => '10.90.22',
                'source_type'     => self::TYPE_DICTIONARY,
                'dictionary_key'  => 'MA_NCC',
                'simba_code_name' => 'ARDMKH',
            ],
            'ar.cungcap' => [
                'module'          => 'PO',
                'menuid'          => '10.90.22',
                'source_type'     => self::TYPE_DICTIONARY,
                'dictionary_key'  => 'MA_NCC',
                'simba_code_name' => 'ARDMKH',
            ],
            'po.dmcp' => [
                'module'          => 'PO',
                'menuid'          => '10.90.14',
                'source_type'     => self::TYPE_DICTIONARY,
                'dictionary_key'  => 'MA_CP',
                'simba_code_name' => 'PODMCP',
            ],
            'ap.vch.ap4' => [
                'module'      => 'AP',
                'menuid'      => '10.10.38',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'AP4',
            ],
            'muahang.hoadonmua' => [
                'module'      => 'PO',
                'menuid'      => '10.10.14',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'PO3',
            ],
            'muahang.po1' => [
                'module'      => 'PO',
                'menuid'      => '10.10.05',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'PO1',
            ],
            'muahang.po4' => [
                'module'      => 'PO',
                'menuid'      => '10.10.20',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'PO4',
            ],
            'muahang.po5' => [
                'module'      => 'PO',
                'menuid'      => '10.10.23',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'PO5',
            ],
            'muahang.po6' => [
                'module'      => 'PO',
                'menuid'      => '10.10.26',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'PO6',
            ],
            'muahang.po7' => [
                'module'      => 'PO',
                'menuid'      => '10.10.17',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'PO7',
            ],
            'po.rpt.bk01' => [
                'module'      => 'PO',
                'menuid'      => '10.20.02',
                'source_type' => self::TYPE_REPORT,
            ],
            'po.rpt.bk02' => [
                'module'      => 'PO',
                'menuid'      => '10.20.05',
                'source_type' => self::TYPE_REPORT,
            ],
            'po.rpt.th01' => [
                'module'      => 'PO',
                'menuid'      => '10.20.08',
                'source_type' => self::TYPE_REPORT,
            ],
            'po.rpt.th02' => [
                'module'      => 'PO',
                'menuid'      => '10.20.11',
                'source_type' => self::TYPE_REPORT,
            ],
            'po.rpt.bk03' => [
                'module'      => 'PO',
                'menuid'      => '10.20.20',
                'source_type' => self::TYPE_REPORT,
            ],
            'po.rpt.th03' => [
                'module'      => 'PO',
                'menuid'      => '10.20.23',
                'source_type' => self::TYPE_REPORT,
            ],
            'po.rpt.bcpt03' => [
                'module'      => 'PO',
                'menuid'      => '10.20.26',
                'source_type' => self::TYPE_REPORT,
            ],
            'po.rpt.nkc05' => [
                'module'      => 'PO',
                'menuid'      => '10.20.32',
                'source_type' => self::TYPE_REPORT,
            ],
            'in.dmvt' => [
                'module'          => 'IN',
                'menuid'          => '14.90.02',
                'source_type'     => self::TYPE_DICTIONARY,
                'dictionary_key'  => 'MA_VT',
                'simba_code_name' => 'INDMVT',
            ],
            'in.dmnhvt' => [
                'module'          => 'IN',
                'menuid'          => '14.90.05',
                'source_type'     => self::TYPE_DICTIONARY,
                'dictionary_key'  => 'MA_NHVT',
                'simba_code_name' => 'INDMNHVT',
            ],
            'in.khohang' => [
                'module'          => 'IN',
                'menuid'          => '14.90.14',
                'source_type'     => self::TYPE_DICTIONARY,
                'dictionary_key'  => 'MA_KHO',
                'simba_code_name' => 'INDMKHO',
            ],
            'in.vch.in1' => [
                'module'      => 'IN',
                'menuid'      => '14.10.02',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'IN1',
            ],
            'in.vch.in2' => [
                'module'      => 'IN',
                'menuid'      => '14.10.05',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'IN2',
            ],
            'in.vch.in3' => [
                'module'      => 'IN',
                'menuid'      => '14.10.08',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'IN3',
            ],
            'in.vch.in4' => [
                'module'      => 'IN',
                'menuid'      => '14.10.08',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'IN4',
            ],
            'in.vch.in5' => [
                'module'      => 'IN',
                'menuid'      => '14.10.11',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'IN5',
            ],
            'in.vch.in6' => [
                'module'      => 'IN',
                'menuid'      => '14.10.14',
                'source_type' => self::TYPE_VOUCHER,
                'ma_ct'       => 'IN6',
            ],
            'in.tonkho' => [
                'module'      => 'IN',
                'menuid'      => '14.20.11',
                'source_type' => self::TYPE_REPORT,
            ],
            'in.rpt.ctvt01' => [
                'module'      => 'IN',
                'menuid'      => '14.20.02',
                'source_type' => self::TYPE_REPORT,
            ],
            'in.rpt.ctvt02' => [
                'module'      => 'IN',
                'menuid'      => '14.20.05',
                'source_type' => self::TYPE_REPORT,
            ],
            'in.rpt.cd01' => [
                'module'      => 'IN',
                'menuid'      => '14.20.08',
                'source_type' => self::TYPE_REPORT,
            ],
            'in.rpt.bkn04' => [
                'module'      => 'IN',
                'menuid'      => '14.20.14',
                'source_type' => self::TYPE_REPORT,
            ],
            'in.rpt.thn01' => [
                'module'      => 'IN',
                'menuid'      => '14.20.17',
                'source_type' => self::TYPE_REPORT,
            ],
            'in.rpt.thn02' => [
                'module'      => 'IN',
                'menuid'      => '14.20.18',
                'source_type' => self::TYPE_REPORT,
            ],
            'in.rpt.bkx04' => [
                'module'      => 'IN',
                'menuid'      => '14.20.20',
                'source_type' => self::TYPE_REPORT,
            ],
            'in.rpt.thx01' => [
                'module'      => 'IN',
                'menuid'      => '14.20.23',
                'source_type' => self::TYPE_REPORT,
            ],
            'in.rpt.thx02' => [
                'module'      => 'IN',
                'menuid'      => '14.20.24',
                'source_type' => self::TYPE_REPORT,
            ],
            'in.rpt.bk01' => [
                'module'      => 'IN',
                'menuid'      => '14.20.26',
                'source_type' => self::TYPE_REPORT,
            ],
            'in.rpt.banggiatb' => [
                'module'      => 'IN',
                'menuid'      => '14.20.56',
                'source_type' => self::TYPE_REPORT,
            ],
        ];

        $routes += self::documentedDictionaryRoutes($routes);

        return $routes + self::documentedReportRoutes($routes);
    }

    /**
     * @return null|array<string, string>
     */
    public static function get(string $routeName): ?array
    {
        return self::routes()[$routeName] ?? null;
    }

    /**
     * @param array<string, array<string, string>> $existing
     *
     * @return array<string, array<string, string>>
     */
    private static function documentedReportRoutes(array $existing): array
    {
        $routes       = [];
        $knownMenuIds = [];
        foreach ($existing as $metadata) {
            $knownMenuIds[$metadata['menuid']] = true;
        }

        foreach (SimbaReportRegistry::reports() as $route => $metadata) {
            if (isset($existing[$route]) || isset($knownMenuIds[$metadata['menuid']])) {
                continue;
            }

            $routes[$route] = [
                'module'      => $metadata['module'],
                'menuid'      => $metadata['menuid'],
                'source_type' => self::TYPE_REPORT,
            ];
        }

        return $routes;
    }

    /**
     * @param array<string, array<string, string>> $existing
     *
     * @return array<string, array<string, string>>
     */
    private static function documentedDictionaryRoutes(array $existing): array
    {
        $routes       = [];
        $knownMenuIds = [];
        foreach ($existing as $metadata) {
            $knownMenuIds[$metadata['menuid']] = true;
        }

        foreach (SimbaDictionaryRegistry::dictionaries() as $route => $metadata) {
            if (isset($existing[$route]) || isset($knownMenuIds[$metadata['menuid']])) {
                continue;
            }

            $routes[$route] = [
                'module'          => $metadata['module'] ?? strtoupper(strtok($route, '.')),
                'menuid'          => $metadata['menuid'],
                'source_type'     => self::TYPE_DICTIONARY,
                'dictionary_key'  => $metadata['code_name'],
                'simba_code_name' => $metadata['table'],
            ];
            if (isset($metadata['source_menuid'])) {
                $routes[$route]['source_menuid'] = $metadata['source_menuid'];
            }
        }

        return $routes;
    }

    /**
     * @return array<string, array<string, string>>
     */
    private static function documentedProcessRoutes(): array
    {
        $routes = [];
        foreach (SimbaProcessRegistry::processes() as $route => $metadata) {
            $routes[$route] = [
                'module'      => $metadata['module'],
                'menuid'      => $metadata['menuid'],
                'source_type' => self::TYPE_CUSTOM,
            ];
        }

        return $routes;
    }
}

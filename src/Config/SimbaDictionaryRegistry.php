<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:27:47
 */

namespace Diepxuan\Catalog\Config;

use Diepxuan\Catalog\Services\SimbaDocsDataRepository;
use Diepxuan\Catalog\Services\SimbaDocsMenuRepository;

final class SimbaDictionaryRegistry
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function dictionaries(): array
    {
        $dictionaries = [
            'gl.taikhoan' => [
                'code_name' => 'TK',
                'table'     => 'GLDMTK',
                'menuid'    => '02.90.02',
                'pk'        => ['MA_CTY', 'TK'],
                'fields'    => ['ma_cty', 'tk', 'ten_tk', 'ma_nt', 'chi_tiet', 'tk_me', 'bac_tk', 'tk_sc', 'tk_cn', 'pp_tinh_tggs_no', 'pp_tinh_tggs_co', 'kieu_sd', 'so_tk', 'ma_ngh', 'ten_ngh', 'tinh_tp'],
            ],
            'ar.khachhang' => [
                'code_name' => 'MA_KH',
                'table'     => 'ARDMKH',
                'menuid'    => '06.90.02',
                'pk'        => ['MA_CTY', 'MA_KH'],
                'fields'    => ['ma_kh', 'loai', 'ten_kh', 'ma_so_thue', 'dia_chi', 'tel', 'fax', 'email', 'home_page', 'nguoi_gd', 'so_tk_nh', 'ten_nh', 'tinh_tp_nh', 'tk', 'ma_plkh1', 'ma_plkh2', 'ma_plkh3', 'ma_nhkh', 'ma_tt', 'ma_httt', 'ma_httt_po', 'gh_no', 'han_ck', 'tl_ck', 'han_tt', 'ls_qh', 'ghi_chu', 'tinh_dt_nb', 'iskh', 'isncc', 'isnv'],
            ],
            'po.cungcap' => [
                'code_name' => 'MA_NCC',
                'table'     => 'ARDMKH',
                'menuid'    => '10.90.22',
                'pk'        => ['MA_CTY', 'MA_KH'],
                'fields'    => ['ma_cty', 'ma_kh', 'loai', 'ten_kh', 'ma_so_thue', 'dia_chi', 'tel', 'fax', 'email', 'home_page', 'nguoi_gd', 'so_tk_nh', 'ten_nh', 'tinh_tp_nh', 'tk', 'ma_plkh1', 'ma_plkh2', 'ma_plkh3', 'ma_nhkh', 'ma_tt', 'ma_httt', 'ma_httt_po', 'gh_no', 'han_ck', 'tl_ck', 'han_tt', 'ls_qh', 'ghi_chu', 'tinh_dt_nb', 'iskh', 'isncc', 'isnv'],
            ],
            'ar.cungcap' => [
                'code_name' => 'MA_NCC',
                'table'     => 'ARDMKH',
                'menuid'    => '10.90.22',
                'pk'        => ['MA_CTY', 'MA_KH'],
                'fields'    => ['ma_cty', 'ma_kh', 'loai', 'ten_kh', 'ma_so_thue', 'dia_chi', 'tel', 'fax', 'email', 'home_page', 'nguoi_gd', 'so_tk_nh', 'ten_nh', 'tinh_tp_nh', 'tk', 'ma_plkh1', 'ma_plkh2', 'ma_plkh3', 'ma_nhkh', 'ma_tt', 'ma_httt', 'ma_httt_po', 'gh_no', 'han_ck', 'tl_ck', 'han_tt', 'ls_qh', 'ghi_chu', 'tinh_dt_nb', 'iskh', 'isncc', 'isnv'],
            ],
            'po.dmcp' => [
                'code_name' => 'MA_CP',
                'table'     => 'PODMCP',
                'menuid'    => '10.90.14',
                'pk'        => ['MA_CTY', 'MA_CP'],
                'fields'    => ['ma_cp', 'ten_cp', 'tt_pb'],
            ],
            'fa.dmnv' => [
                'code_name' => 'MA_NV',
                'table'     => 'FADMNV',
                'menuid'    => '20.90.02',
                'pk'        => ['MA_CTY', 'MA_NV'],
                'fields'    => ['ma_cty', 'ma_nv', 'ten_nv'],
            ],
            'fa.dmbpsd' => [
                'code_name' => 'MA_BPSD',
                'table'     => 'FADMBPSD',
                'menuid'    => '20.90.08',
                'pk'        => ['MA_CTY', 'MA_BPSD'],
                'fields'    => ['ma_bpsd', 'ten_bpsd'],
            ],
            'co.dmsp' => [
                'code_name' => 'MA_SP',
                'table'     => 'CODMSPCT',
                'menuid'    => '18.90.02',
                'pk'        => ['MA_CTY', 'MA_SPCT'],
                'fields'    => ['ma_cty', 'ma_spct', 'ten_spct', 'dvt', 'san_pham', 'ngay_kc', 'ngay_kt', 'du_toan', 'du_toan_nt', 'dv_tc', 'ghi_chu', 'ma_nhspct'],
            ],
            'co.dmspct' => [
                'code_name' => 'MA_SPCT',
                'table'     => 'CODMSPCT',
                'menuid'    => '18.90.05',
                'pk'        => ['MA_CTY', 'MA_SPCT'],
                'fields'    => ['ma_cty', 'ma_spct', 'ten_spct', 'dvt', 'san_pham', 'ngay_kc', 'ngay_kt', 'du_toan', 'du_toan_nt', 'dv_tc', 'ghi_chu', 'ma_nhspct'],
            ],
            'co.dmnhspct' => [
                'code_name' => 'MA_NHSPCT',
                'table'     => 'CODMNHSPCT',
                'menuid'    => '18.90.08',
                'pk'        => ['MA_CTY', 'MA_NHSPCT'],
                'fields'    => ['ma_nhspct', 'ten_nhspct', 'cap', 'stt_nhom', 'nhom_me', 'cong_sl'],
            ],
            'si.dmct' => [
                'code_name' => 'SIDMCT',
                'table'     => 'SIDMCT',
                'menuid'    => '90.10.02',
                'pk'        => ['MA_CTY', 'MA_CT'],
                'fields'    => ['ma_cty', 'ma_ct', 'phan_he', 'ma_ct_me', 'ten_ct', 'tk_no', 'tk_co', 'ma_nt', 'so_lien', 'stt_nkc', 'stt_ntxt', 'ct_dc', 'loc_nsd', 'vv', 'spct', 'phi', 'bp', 'lo', 'sp_post', 'sp_process', 'ph', 'ct', 'sd', 'nxt', 'menuid', 'vn_prefix', 'vn_sequence', 'vn_pattern', 'vn_width', 'phfieldlist2in', 'ctfieldlist2in'],
            ],
            'si.dmphi' => [
                'code_name' => 'MA_PHI',
                'table'     => 'SIDMPHI',
                'menuid'    => '90.10.20',
                'pk'        => ['MA_CTY', 'MA_PHI'],
                'fields'    => ['ma_cty', 'ma_phi', 'ten_phi'],
            ],
            'si.dmnt' => [
                'code_name' => 'MA_NT',
                'table'     => 'SIDMNT',
                'menuid'    => '90.10.26',
                'pk'        => ['MA_CTY', 'MA_NT'],
                'fields'    => ['ma_cty', 'ma_nt', 'ten_nt'],
            ],
            'in.dmvt' => [
                'code_name' => 'MA_VT',
                'table'     => 'INDMVT',
                'menuid'    => '14.90.02',
                'pk'        => ['MA_CTY', 'MA_VT'],
                'fields'    => ['ma_vt', 'loai', 'ten_vt', 'part_no', 'dvt', 'dvt_ban', 'hs_dvtban', 'dvt_mua', 'hs_dvtmua', 'ton_kho', 'gia_ton', 'tk_vt', 'tk_dt', 'tk_dtnb', 'tk_gv', 'tk_tl', 'tk_ck', 'tk_km', 'tk_dd', 'tk_cpnvl', 'ma_thue', 'tinh_gt', 'ma_nhvt', 'ma_plvt1', 'ma_plvt2', 'ma_plvt3', 'ma_nhvat', 'sl_min', 'sl_max', 'ts_nk', 'ts_xk', 'ts_ttdb', 'ma_kho', 'ma_vitri'],
            ],
            'in.dmnhvt' => [
                'code_name' => 'MA_NHVT',
                'table'     => 'INDMNHVT',
                'menuid'    => '14.90.05',
                'pk'        => ['MA_CTY', 'MA_NHVT'],
                'fields'    => ['ma_cty', 'ma_nhvt', 'ten_nhvt', 'cap', 'stt_nhom', 'nhom_me', 'cong_sl', 'tk_vt', 'tk_dt', 'tk_dtnb', 'tk_gv', 'tk_tl', 'tk_ck'],
            ],
            'in.khohang' => [
                'code_name' => 'MA_KHO',
                'table'     => 'INDMKHO',
                'menuid'    => '14.90.14',
                'pk'        => ['MA_CTY', 'MA_KHO'],
                'fields'    => ['ma_kho', 'ten_kho', 'tk_dl', 'stt_ntxt', 'dia_chi', 'nguoi_lh', 'tel', 'fax', 'email'],
            ],
        ];

        $dictionaries += self::documentedDictionaries($dictionaries);

        return $dictionaries + self::documentedMenuCodeNameDictionaries($dictionaries);
    }

    /**
     * @return null|array<string, mixed>
     */
    public static function get(string $routeName): ?array
    {
        return self::dictionaries()[$routeName] ?? null;
    }

    /**
     * @param array<string, array<string, mixed>> $existing
     *
     * @return array<string, array<string, mixed>>
     */
    private static function documentedDictionaries(array $existing): array
    {
        $data  = new SimbaDocsDataRepository();
        $menus = (new SimbaDocsMenuRepository(null, $data))->activeMenus()
            ->mapWithKeys(static fn ($menu): array => [$menu->menuid => $menu])
        ;

        $knownMenuIds = [];
        foreach ($existing as $metadata) {
            $knownMenuIds[$metadata['menuid']] = true;
        }

        $dictionaries = [];
        foreach ($data->table('sysDictionaryInfo.md') as $row) {
            $menuid = trim((string) ($row['menuid'] ?? ''));
            if ('' === $menuid || isset($knownMenuIds[$menuid]) || !$menus->has($menuid)) {
                continue;
            }

            $codeName = trim((string) ($row['code_name'] ?? ''));
            if ('' === $codeName) {
                continue;
            }

            $menu  = $menus->get($menuid);
            $route = self::generatedRouteName((string) $menu->moduleid, (string) ($menu->dllName ?: $codeName));
            while (isset($existing[$route]) || isset($dictionaries[$route])) {
                $route .= '-' . str_replace('.', '-', $menuid);
            }

            $dictionaries[$route] = [
                'code_name' => $codeName,
                'table'     => (string) ($row['table_name'] ?? ''),
                'menuid'    => $menuid,
                'pk'        => self::splitCsv((string) ($row['PK'] ?? '')),
                'fields'    => self::splitCsv(strtolower((string) ($row['carry_field_list'] ?? ''))),
            ];
            $knownMenuIds[$menuid] = true;
        }

        return $dictionaries;
    }

    /**
     * Active sysMenu rows sometimes carry a valid code_name while
     * sysDictionaryInfo has a blank or legacy menuid. Keep the active menu
     * anchor, but expose the source dictionary menuid separately.
     *
     * @param array<string, array<string, mixed>> $existing
     *
     * @return array<string, array<string, mixed>>
     */
    private static function documentedMenuCodeNameDictionaries(array $existing): array
    {
        $data = new SimbaDocsDataRepository();
        $rows = $data->table('sysDictionaryInfo.md')
            ->filter(static fn ($row): bool => '' !== trim((string) ($row['code_name'] ?? '')))
            ->keyBy('code_name')
        ;
        $rowsByTable = $data->table('sysDictionaryInfo.md')
            ->filter(static fn ($row): bool => '' !== trim((string) ($row['table_name'] ?? '')))
            ->groupBy('table_name')
        ;

        $knownMenuIds = [];
        foreach ($existing as $metadata) {
            $knownMenuIds[$metadata['menuid']] = true;
        }

        $dictionaries = [];
        foreach ((new SimbaDocsMenuRepository(null, $data))->activeMenus() as $menu) {
            if (!$menu->isMasterData()) {
                continue;
            }

            if (isset($knownMenuIds[$menu->menuid])) {
                continue;
            }

            $codeName = trim((string) $menu->code_name);
            $row      = '' !== $codeName ? $rows->get($codeName) : null;

            if (null === $row) {
                foreach (array_filter([(string) $menu->dllName, (string) $menu->code_name]) as $tableName) {
                    if (!$rowsByTable->has($tableName)) {
                        continue;
                    }

                    $row      = $rowsByTable->get($tableName)->first();
                    $codeName = (string) ($row['code_name'] ?? '');

                    break;
                }
            }

            if (null === $row || '' === $codeName) {
                continue;
            }

            $route = self::generatedRouteName((string) $menu->moduleid, (string) ($menu->dllName ?: $codeName));
            while (isset($existing[$route]) || isset($dictionaries[$route])) {
                $route .= '-' . str_replace('.', '-', (string) $menu->menuid);
            }

            $dictionaries[$route] = [
                'code_name'     => $codeName,
                'table'         => (string) ($row['table_name'] ?? ''),
                'menuid'        => (string) $menu->menuid,
                'source_menuid' => (string) ($row['menuid'] ?? ''),
                'pk'            => self::splitCsv((string) ($row['PK'] ?? '')),
                'fields'        => self::splitCsv(strtolower((string) ($row['carry_field_list'] ?? ''))),
            ];
            $knownMenuIds[$menu->menuid] = true;
        }

        return $dictionaries;
    }

    /**
     * @return list<string>
     */
    private static function splitCsv(string $value): array
    {
        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }

    private static function generatedRouteName(string $module, string $source): string
    {
        $slug = strtolower((string) preg_replace('/[^A-Za-z0-9]+/', '-', $source));
        $slug = trim($slug, '-');

        return strtolower($module) . '.dict.' . ($slug ?: 'menu');
    }
}

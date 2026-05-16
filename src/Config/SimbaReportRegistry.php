<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 09:35:03
 */

namespace Diepxuan\Catalog\Config;

use Diepxuan\Catalog\Services\SimbaDocsDataRepository;
use Diepxuan\Catalog\Services\SimbaDocsMenuRepository;

final class SimbaReportRegistry
{
    /**
     * @return array<string, array<string, string>>
     */
    public static function reports(): array
    {
        $reports = [
            'ca.tienmat.quy' => [
                'module'  => 'CA',
                'menuid'  => '04.20.02',
                'ma_mau'  => '01',
                'spname'  => 'asCARptTMNH01',
                'rptname' => 'CATMNH011.rpt',
            ],
            'ca.nganhang.quy' => [
                'module'  => 'CA',
                'menuid'  => '04.20.05',
                'ma_mau'  => '01',
                'spname'  => 'asCARptTMNH02',
                'rptname' => 'CATMNH021.rpt',
            ],
            'ca.thu' => [
                'module'  => 'CA',
                'menuid'  => '04.20.08',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptNKC03',
                'rptname' => 'GLNKC031.rpt',
            ],
            'ca.chi' => [
                'module'  => 'CA',
                'menuid'  => '04.20.11',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptNKC03',
                'rptname' => 'GLNKC032.rpt',
            ],
            'ca.rpt.tmnh06' => [
                'module'  => 'CA',
                'menuid'  => '04.20.14',
                'ma_mau'  => '01',
                'spname'  => 'asCARPTTMNH06',
                'rptname' => 'CATMNH061.rpt',
            ],
            'ca.rpt.bk01' => [
                'module'  => 'CA',
                'menuid'  => '04.20.29',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptBK02',
                'rptname' => 'GLBK011.rpt',
            ],
            'ca.rpt.tmnh01a' => [
                'module'  => 'CA',
                'menuid'  => '04.20.32',
                'ma_mau'  => '02',
                'spname'  => 'asCARptTMNH01a',
                'rptname' => 'CATMNH01a1.rpt',
            ],
            'gl.rpt.ctgs01' => [
                'module'  => 'GL',
                'menuid'  => '02.20.11',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptCTGS01',
                'rptname' => 'GLCTGS011.rpt',
            ],
            'gl.rpt.ctgs02' => [
                'module'  => 'GL',
                'menuid'  => '02.20.14',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptCTGS02',
                'rptname' => 'GLCTGS021.rpt',
            ],
            'gl.rpt.ctgs03' => [
                'module'  => 'GL',
                'menuid'  => '02.20.17',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptCTGS03',
                'rptname' => 'GLCTGS031.rpt',
            ],
            'gl.rpt.ctgs05' => [
                'module'  => 'GL',
                'menuid'  => '02.20.20',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptCTGS05',
                'rptname' => 'GLCTGS051.rpt',
            ],
            'gl.rpt.nkc01' => [
                'module'  => 'GL',
                'menuid'  => '02.25.02',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptNKC01',
                'rptname' => 'GLNKC011.rpt',
            ],
            'gl.rpt.nkc02' => [
                'module'  => 'GL',
                'menuid'  => '02.25.05',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptNKC02',
                'rptname' => 'GLNKC021.rpt',
            ],
            'gl.rpt.nkc03' => [
                'module'  => 'GL',
                'menuid'  => '02.25.08',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptNKC03',
                'rptname' => 'GLNKC031.rpt',
            ],
            'gl.rpt.nkc03-chi' => [
                'module'  => 'GL',
                'menuid'  => '02.25.11',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptNKC03',
                'rptname' => 'GLNKC032.rpt',
            ],
            'gl.rpt.nkc04' => [
                'module'  => 'GL',
                'menuid'  => '02.25.14',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptNKC04',
                'rptname' => 'GLNKC041.rpt',
            ],
            'gl.rpt.nkc05' => [
                'module'  => 'GL',
                'menuid'  => '02.25.17',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptNKC05',
                'rptname' => 'GLNKC051.rpt',
            ],
            'gl.rpt.nkc06' => [
                'module'  => 'GL',
                'menuid'  => '02.25.20',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptNKC06',
                'rptname' => 'GLNKC061.rpt',
            ],
            'gl.rpt.nkc07' => [
                'module'  => 'GL',
                'menuid'  => '02.25.23',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptNKC07',
                'rptname' => 'GLNKC071.rpt',
            ],
            'gl.rpt.bk02' => [
                'module'  => 'GL',
                'menuid'  => '02.25.26',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptBK02',
                'rptname' => 'GLBK011.rpt',
            ],
            'gl.rpt.th01' => [
                'module'  => 'GL',
                'menuid'  => '02.25.29',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptTH01',
                'rptname' => 'GLTH011.rpt',
            ],
            'gl.rpt.bct01' => [
                'module'  => 'GL',
                'menuid'  => '02.40.02',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptBCT01',
                'rptname' => 'GLBCT011.rpt',
            ],
            'gl.rpt.bct02' => [
                'module'  => 'GL',
                'menuid'  => '02.40.05',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptBCT02',
                'rptname' => 'GLBCT021.rpt',
            ],
            'gl.rpt.bct03' => [
                'module'  => 'GL',
                'menuid'  => '02.40.08',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptBCT03',
                'rptname' => 'GLBCT031.rpt',
            ],
            'gl.rpt.bct04' => [
                'module'  => 'GL',
                'menuid'  => '02.40.14',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptBCT04',
                'rptname' => 'GLBCT041.Rpt',
            ],
            'gl.rpt.bct05' => [
                'module'  => 'GL',
                'menuid'  => '02.40.17',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptBCT05',
                'rptname' => 'GLBCT051.Rpt',
            ],
            'gl.rpt.bct06' => [
                'module'  => 'GL',
                'menuid'  => '02.40.20',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptBCT06',
                'rptname' => 'GLBCT061.Rpt',
            ],
            'gl.rpt.bctc01' => [
                'module'  => 'GL',
                'menuid'  => '02.50.02',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptBCTC01',
                'rptname' => 'GLBCTC011.rpt',
            ],
            'gl.rpt.bctc02' => [
                'module'  => 'GL',
                'menuid'  => '02.50.05',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptBCTC02',
                'rptname' => 'GLBCTC021.rpt',
            ],
            'gl.rpt.bctc03' => [
                'module'  => 'GL',
                'menuid'  => '02.50.08',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptBCTC03',
                'rptname' => 'GLBCTC031.rpt',
            ],
            'gl.rpt.bctc04' => [
                'module'  => 'GL',
                'menuid'  => '02.50.11',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptBCTC04',
                'rptname' => 'GLBCTC041.rpt',
            ],
            'gl.rpt.tmbctc' => [
                'module'  => 'GL',
                'menuid'  => '02.50.20',
                'ma_mau'  => 'X',
                'spname'  => 'asGLRptBCTCTM',
                'rptname' => 'GLBCTC07.rpt',
            ],
            'gl.rpt.bctccdtb02' => [
                'module'  => 'GL',
                'menuid'  => '02.55.02',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptBCTCCDTB02',
                'rptname' => 'GLBCTCCDTB021.rpt',
            ],
            'gl.rpt.bctccdtf02' => [
                'module'  => 'GL',
                'menuid'  => '02.55.05',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptBCTCCDTf02',
                'rptname' => 'GLBCTCCDTF021.rpt',
            ],
            'gl.rpt.bctccdtf03a' => [
                'module'  => 'GL',
                'menuid'  => '02.55.08',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptBCTCCDTf03A',
                'rptname' => 'GLBCTCCDTF03A1.rpt',
            ],
            'gl.rpt.bctccdtf03b' => [
                'module'  => 'GL',
                'menuid'  => '02.55.11',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptBCTCCDTf03B',
                'rptname' => 'GLBCTCCDTF03B1.rpt',
            ],
            'gl.rpt.bctccdtb03' => [
                'module'  => 'GL',
                'menuid'  => '02.55.14',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptBCTCCDTB03',
                'rptname' => 'GLBCTCCDTB031.rpt',
            ],
            'gl.rpt.bctccdtf03c' => [
                'module'  => 'GL',
                'menuid'  => '02.55.17',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptBCTCCDTf03C',
                'rptname' => 'GLBCTCCDTF03C1.rpt',
            ],
            'gl.rpt.bctccdtf03d' => [
                'module'  => 'GL',
                'menuid'  => '02.55.20',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptBCTCCDTf03D',
                'rptname' => 'GLBCTCCDTF03D1.rpt',
            ],
            'fa.rpt.bcts01' => [
                'module'  => 'FA',
                'menuid'  => '20.20.02',
                'ma_mau'  => '01',
                'spname'  => 'asFARptBCTS01',
                'rptname' => 'FABCTS011.rpt',
            ],
            'fa.rpt.bcts08' => [
                'module'  => 'FA',
                'menuid'  => '20.20.05',
                'ma_mau'  => '01',
                'spname'  => 'asFARptBCTS08',
                'rptname' => 'FABCTS081.rpt',
            ],
            'fa.rpt.bcts09' => [
                'module'  => 'FA',
                'menuid'  => '20.20.08',
                'ma_mau'  => '01',
                'spname'  => 'asFARptBCTS09',
                'rptname' => 'FABCTS091.rpt',
            ],
            'fa.rpt.bcts04' => [
                'module'  => 'FA',
                'menuid'  => '20.20.11',
                'ma_mau'  => '01',
                'spname'  => 'asFARptBCTS04',
                'rptname' => 'FABCTS041.rpt',
            ],
            'fa.rpt.bcts05' => [
                'module'  => 'FA',
                'menuid'  => '20.20.14',
                'ma_mau'  => '01',
                'spname'  => 'asFARptBCTS05',
                'rptname' => 'FABCTS051.rpt',
            ],
            'fa.rpt.bcts06' => [
                'module'  => 'FA',
                'menuid'  => '20.20.17',
                'ma_mau'  => '01',
                'spname'  => 'asFARptBCTS06',
                'rptname' => 'FABCTS061.rpt',
            ],
            'fa.rpt.bcts07' => [
                'module'  => 'FA',
                'menuid'  => '20.20.20',
                'ma_mau'  => '01',
                'spname'  => 'asFARptBCTS07',
                'rptname' => 'FABCTS071.rpt',
            ],
            'fa.rpt.bcpt10' => [
                'module'  => 'FA',
                'menuid'  => '20.30.02',
                'ma_mau'  => '01',
                'spname'  => 'asFARptBCPT10',
                'rptname' => 'FABCPT101.rpt',
            ],
            'fa.rpt.bkn04' => [
                'module'  => 'FA',
                'menuid'  => '20.30.08',
                'ma_mau'  => '01',
                'spname'  => 'asINRptBKN04',
                'rptname' => 'INBKN041.rpt',
            ],
            'fa.rpt.thn01' => [
                'module'  => 'FA',
                'menuid'  => '20.30.11',
                'ma_mau'  => '01',
                'spname'  => 'asINRptTHN01',
                'rptname' => 'INTHN011.rpt',
            ],
            'fa.rpt.bkx04' => [
                'module'  => 'FA',
                'menuid'  => '20.30.14',
                'ma_mau'  => '01',
                'spname'  => 'asINRptBKX04',
                'rptname' => 'INBKX041.rpt',
            ],
            'fa.rpt.thx01' => [
                'module'  => 'FA',
                'menuid'  => '20.30.17',
                'ma_mau'  => '01',
                'spname'  => 'asINRptTHX01',
                'rptname' => 'INTHX011.rpt',
            ],
            'co.rpt.bk02' => [
                'module'  => 'CO',
                'menuid'  => '17.60.02',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptBK02',
                'rptname' => 'GLBK011.rpt',
            ],
            'co.rpt.th01' => [
                'module'  => 'CO',
                'menuid'  => '17.60.05',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptTH01',
                'rptname' => 'GLTH011.rpt',
            ],
            'co.rpt.khcp' => [
                'module'  => 'CO',
                'menuid'  => '17.60.08',
                'ma_mau'  => '01',
                'spname'  => 'asCoRptKHCPTT',
                'rptname' => 'CoKHCPTT.rpt',
            ],
            'co.rpt.gt01' => [
                'module'  => 'CO',
                'menuid'  => '18.20.02',
                'ma_mau'  => '01',
                'spname'  => 'asCORptGt01',
                'rptname' => 'COGt011.rpt',
            ],
            'co.rpt.gt02' => [
                'module'  => 'CO',
                'menuid'  => '18.20.05',
                'ma_mau'  => '01',
                'spname'  => 'asCORptGt02',
                'rptname' => 'COGt021.rpt',
            ],
            'co.rpt.bkn04' => [
                'module'  => 'CO',
                'menuid'  => '18.20.14',
                'ma_mau'  => '01',
                'spname'  => 'asINRptBKN04',
                'rptname' => 'INBKN041.rpt',
            ],
            'co.rpt.thn01' => [
                'module'  => 'CO',
                'menuid'  => '18.20.17',
                'ma_mau'  => '01',
                'spname'  => 'asINRptTHN01',
                'rptname' => 'INTHN011.rpt',
            ],
            'co.rpt.thn02' => [
                'module'  => 'CO',
                'menuid'  => '18.20.18',
                'ma_mau'  => '01',
                'spname'  => 'asINRptTHN02',
                'rptname' => 'INTHN021.rpt',
            ],
            'co.rpt.bkx04' => [
                'module'  => 'CO',
                'menuid'  => '18.20.20',
                'ma_mau'  => '01',
                'spname'  => 'asINRptBKX04',
                'rptname' => 'INBKX041.rpt',
            ],
            'co.rpt.thx01' => [
                'module'  => 'CO',
                'menuid'  => '18.20.23',
                'ma_mau'  => '01',
                'spname'  => 'asINRptTHX01',
                'rptname' => 'INTHX011.rpt',
            ],
            'co.rpt.thx02' => [
                'module'  => 'CO',
                'menuid'  => '18.20.26',
                'ma_mau'  => '01',
                'spname'  => 'asINRptTHX02',
                'rptname' => 'INTHX021.rpt',
            ],
            'co.rpt.gt03' => [
                'module'  => 'CO',
                'menuid'  => '18.25.02',
                'ma_mau'  => '01',
                'spname'  => 'asCORptGt03',
                'rptname' => 'COGt031.rpt',
            ],
            'co.rpt.gt03-dutoan' => [
                'module'  => 'CO',
                'menuid'  => '18.25.08',
                'ma_mau'  => '02',
                'spname'  => 'asCORptGt03',
                'rptname' => 'COGt032.rpt',
            ],
            'co.rpt.gt03-lailo' => [
                'module'  => 'CO',
                'menuid'  => '18.25.11',
                'ma_mau'  => '03',
                'spname'  => 'asCORptGt03',
                'rptname' => 'COGt033.rpt',
            ],
            'co.rpt.bccn04' => [
                'module'  => 'CO',
                'menuid'  => '18.25.14',
                'ma_mau'  => '01',
                'spname'  => 'asARRptBCCN04',
                'rptname' => 'ARBCCN041.rpt',
            ],
            'ar.rpt.bk01' => [
                'module'  => 'AR',
                'menuid'  => '06.30.02',
                'ma_mau'  => '01',
                'spname'  => 'asARRptBK01',
                'rptname' => 'ARBK011.rpt',
            ],
            'ar.rpt.bccn01' => [
                'module'  => 'AR',
                'menuid'  => '06.30.14',
                'ma_mau'  => '01',
                'spname'  => 'asARRptBCCN01',
                'rptname' => 'ARBCCN011.rpt',
            ],
            'ar.rpt.bccn01a' => [
                'module'  => 'AR',
                'menuid'  => '06.30.17',
                'ma_mau'  => '01',
                'spname'  => 'asARRptBCCN01a',
                'rptname' => 'ARBCCN01a1.rpt',
            ],
            'ar.rpt.bccn02' => [
                'module'  => 'AR',
                'menuid'  => '06.30.20',
                'ma_mau'  => '01',
                'spname'  => 'asARRptBCCN02',
                'rptname' => 'ARBCCN021.rpt',
            ],
            'ar.rpt.bccn03' => [
                'module'  => 'AR',
                'menuid'  => '06.30.23',
                'ma_mau'  => '01',
                'spname'  => 'asARRptBCCN03',
                'rptname' => 'ARBCCN031.rpt',
            ],
            'ar.rpt.bccn05' => [
                'module'  => 'AR',
                'menuid'  => '06.30.26',
                'ma_mau'  => '01',
                'spname'  => 'asARRptBCCN05',
                'rptname' => 'ARBCCN051.rpt',
            ],
            'ar.rpt.bccn04' => [
                'module'  => 'AR',
                'menuid'  => '06.30.35',
                'ma_mau'  => '01',
                'spname'  => 'asARRptBCCN04',
                'rptname' => 'ARBCCN041.rpt',
            ],
            'ar.rpt.bccn01sl' => [
                'module'  => 'AR',
                'menuid'  => '06.30.38',
                'ma_mau'  => '02',
                'spname'  => 'asARRptBCCN01SL',
                'rptname' => 'ARBCCN012.rpt',
            ],
            'ar.rpt.bccn01a-cont' => [
                'module'  => 'AR',
                'menuid'  => '06.30.41',
                'ma_mau'  => '02',
                'spname'  => 'asARRptBCCN01a',
                'rptname' => 'ARBCCN01a2.rpt',
            ],
            'ar.rpt.bccn03-open' => [
                'module'  => 'AR',
                'menuid'  => '06.30.44',
                'ma_mau'  => '02',
                'spname'  => 'asARRptBCCN03',
                'rptname' => 'ARBCCN032.rpt',
            ],
            'ar.rpt.bccn03-close' => [
                'module'  => 'AR',
                'menuid'  => '06.30.47',
                'ma_mau'  => '03',
                'spname'  => 'asARRptBCCN03',
                'rptname' => 'ARBCCN033.rpt',
            ],
            'ar.rpt.bccn05-detail' => [
                'module'  => 'AR',
                'menuid'  => '06.30.50',
                'ma_mau'  => '02',
                'spname'  => 'asARRptBCCN05',
                'rptname' => 'ARBCCN052.rpt',
            ],
            'ar.rpt.bccn06' => [
                'module'  => 'AR',
                'menuid'  => '06.30.32',
                'ma_mau'  => '01',
                'spname'  => 'asARRptBCCN06',
                'rptname' => 'ARBCCN061.rpt',
            ],
            'ap.rpt.bk01' => [
                'module'  => 'AP',
                'menuid'  => '10.30.02',
                'ma_mau'  => '01',
                'spname'  => 'asAPRptBK01',
                'rptname' => 'APBK011.rpt',
            ],
            'ap.rpt.bccn01' => [
                'module'  => 'AP',
                'menuid'  => '10.30.11',
                'ma_mau'  => '01',
                'spname'  => 'asARRptBCCN01',
                'rptname' => 'ARBCCN011.rpt',
            ],
            'ap.rpt.bccn01a' => [
                'module'  => 'AP',
                'menuid'  => '10.30.14',
                'ma_mau'  => '01',
                'spname'  => 'asARRptBCCN01a',
                'rptname' => 'ARBCCN01a1.rpt',
            ],
            'ap.rpt.bccn02' => [
                'module'  => 'AP',
                'menuid'  => '10.30.17',
                'ma_mau'  => '01',
                'spname'  => 'asARRptBCCN02',
                'rptname' => 'ARBCCN021.rpt',
            ],
            'ap.rpt.bccn03' => [
                'module'  => 'AP',
                'menuid'  => '10.30.20',
                'ma_mau'  => '01',
                'spname'  => 'asARRptBCCN03',
                'rptname' => 'ARBCCN031.rpt',
            ],
            'ap.rpt.bccn01sl' => [
                'module'  => 'AP',
                'menuid'  => '10.30.23',
                'ma_mau'  => '02',
                'spname'  => 'asARRptBCCN01SL',
                'rptname' => 'ARBCCN012.rpt',
            ],
            'ap.rpt.bccn05' => [
                'module'  => 'AP',
                'menuid'  => '10.30.26',
                'ma_mau'  => '01',
                'spname'  => 'asARRptBCCN05',
                'rptname' => 'ARBCCN051.rpt',
            ],
            'ap.rpt.bccn01a-cont' => [
                'module'  => 'AP',
                'menuid'  => '10.30.27',
                'ma_mau'  => '02',
                'spname'  => 'asARRptBCCN01a',
                'rptname' => 'ARBCCN01a2.rpt',
            ],
            'ap.rpt.bccn04' => [
                'module'  => 'AP',
                'menuid'  => '10.30.35',
                'ma_mau'  => '01',
                'spname'  => 'asARRptBCCN04',
                'rptname' => 'ARBCCN041.rpt',
            ],
            'ap.rpt.bccn03-open' => [
                'module'  => 'AP',
                'menuid'  => '10.30.44',
                'ma_mau'  => '02',
                'spname'  => 'asARRptBCCN03',
                'rptname' => 'ARBCCN032.rpt',
            ],
            'ap.rpt.bccn03-close' => [
                'module'  => 'AP',
                'menuid'  => '10.30.47',
                'ma_mau'  => '03',
                'spname'  => 'asARRptBCCN03',
                'rptname' => 'ARBCCN033.rpt',
            ],
            'ap.rpt.bccn05-detail' => [
                'module'  => 'AP',
                'menuid'  => '10.30.50',
                'ma_mau'  => '02',
                'spname'  => 'asARRptBCCN05',
                'rptname' => 'ARBCCN052.rpt',
            ],
            'in.tonkho' => [
                'module'  => 'IN',
                'menuid'  => '14.20.11',
                'ma_mau'  => '01',
                'spname'  => 'asINRptCD02',
                'rptname' => 'INCD021.rpt',
            ],
            'in.rpt.ctvt01' => [
                'module'  => 'IN',
                'menuid'  => '14.20.02',
                'ma_mau'  => '01',
                'spname'  => 'asINRptCTVT01',
                'rptname' => 'INCTVT011.rpt',
            ],
            'in.rpt.ctvt02' => [
                'module'  => 'IN',
                'menuid'  => '14.20.05',
                'ma_mau'  => '01',
                'spname'  => 'asINRptCTVT02',
                'rptname' => 'INCTVT021.rpt',
            ],
            'in.rpt.cd01' => [
                'module'  => 'IN',
                'menuid'  => '14.20.08',
                'ma_mau'  => '01',
                'spname'  => 'asINRptCD01',
                'rptname' => 'INCD011.rpt',
            ],
            'in.rpt.bkn04' => [
                'module'  => 'IN',
                'menuid'  => '14.20.14',
                'ma_mau'  => '01',
                'spname'  => 'asINRptBKN04',
                'rptname' => 'INBKN041.rpt',
            ],
            'in.rpt.thn01' => [
                'module'  => 'IN',
                'menuid'  => '14.20.17',
                'ma_mau'  => '01',
                'spname'  => 'asINRptTHN01',
                'rptname' => 'INTHN011.rpt',
            ],
            'in.rpt.thn02' => [
                'module'  => 'IN',
                'menuid'  => '14.20.18',
                'ma_mau'  => '01',
                'spname'  => 'asINRptTHN02',
                'rptname' => 'INTHN021.rpt',
            ],
            'in.rpt.bkx04' => [
                'module'  => 'IN',
                'menuid'  => '14.20.20',
                'ma_mau'  => '02',
                'spname'  => 'asINRptBKX04',
                'rptname' => 'INBKX041.rpt',
            ],
            'in.rpt.thx01' => [
                'module'  => 'IN',
                'menuid'  => '14.20.23',
                'ma_mau'  => '01',
                'spname'  => 'asINRptTHX01',
                'rptname' => 'INTHX011.rpt',
            ],
            'in.rpt.thx02' => [
                'module'  => 'IN',
                'menuid'  => '14.20.24',
                'ma_mau'  => '01',
                'spname'  => 'asINRptTHX02',
                'rptname' => 'INTHX021.rpt',
            ],
            'in.rpt.bk01' => [
                'module'  => 'IN',
                'menuid'  => '14.20.26',
                'ma_mau'  => '01',
                'spname'  => 'asINRptBK01',
                'rptname' => 'INBK001.rpt',
            ],
            'in.rpt.banggiatb' => [
                'module'  => 'IN',
                'menuid'  => '14.20.56',
                'ma_mau'  => '01',
                'spname'  => 'asINRptBangGiaTB',
                'rptname' => 'BangGiaTB01.rpt',
            ],
            'po.rpt.bk01' => [
                'module'  => 'PO',
                'menuid'  => '10.20.02',
                'ma_mau'  => '04',
                'spname'  => 'asPORptBK01',
                'rptname' => 'POBK014.rpt',
            ],
            'po.rpt.bk02' => [
                'module'  => 'PO',
                'menuid'  => '10.20.05',
                'ma_mau'  => '04',
                'spname'  => 'asPORptBK02',
                'rptname' => 'POBK024.rpt',
            ],
            'po.rpt.th01' => [
                'module'  => 'PO',
                'menuid'  => '10.20.08',
                'ma_mau'  => '04',
                'spname'  => 'asPORptTH01',
                'rptname' => 'POTH014.rpt',
            ],
            'po.rpt.th02' => [
                'module'  => 'PO',
                'menuid'  => '10.20.11',
                'ma_mau'  => '04',
                'spname'  => 'asPORptTH02',
                'rptname' => 'POTH024.rpt',
            ],
            'po.rpt.bk03' => [
                'module'  => 'PO',
                'menuid'  => '10.20.20',
                'ma_mau'  => '01',
                'spname'  => 'asPORptBK03',
                'rptname' => 'POBK031.Rpt',
            ],
            'po.rpt.th03' => [
                'module'  => 'PO',
                'menuid'  => '10.20.23',
                'ma_mau'  => '01',
                'spname'  => 'asPORptTH03',
                'rptname' => 'POTH031.rpt',
            ],
            'po.rpt.bcpt03' => [
                'module'  => 'PO',
                'menuid'  => '10.20.26',
                'ma_mau'  => '01',
                'spname'  => 'asPORptBCPT03',
                'rptname' => 'POBCPT031.rpt',
            ],
            'po.rpt.nkc05' => [
                'module'  => 'PO',
                'menuid'  => '10.20.32',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptNKC05',
                'rptname' => 'GLNKC051.rpt',
            ],
            'so.rpt.bk01' => [
                'module'  => 'SO',
                'menuid'  => '06.20.02',
                'ma_mau'  => '03',
                'spname'  => 'asSORptBK01',
                'rptname' => 'SOBK013.rpt',
            ],
            'so.rpt.bk02' => [
                'module'  => 'SO',
                'menuid'  => '06.20.05',
                'ma_mau'  => '03',
                'spname'  => 'asSORptBK02',
                'rptname' => 'SOBK023.rpt',
            ],
            'so.rpt.th01' => [
                'module'  => 'SO',
                'menuid'  => '06.20.08',
                'ma_mau'  => '03',
                'spname'  => 'asSORptTH01',
                'rptname' => 'SOTH011.rpt',
            ],
            'so.rpt.th02' => [
                'module'  => 'SO',
                'menuid'  => '06.20.11',
                'ma_mau'  => '03',
                'spname'  => 'asSORptTH02',
                'rptname' => 'SOTH021.rpt',
            ],
            'so.rpt.bcpt03' => [
                'module'  => 'SO',
                'menuid'  => '06.20.20',
                'ma_mau'  => '01',
                'spname'  => 'asSORptBCPT03',
                'rptname' => 'SOBCPT031.rpt',
            ],
            'so.rpt.bcpt04' => [
                'module'  => 'SO',
                'menuid'  => '06.20.23',
                'ma_mau'  => '01',
                'spname'  => 'asSORptBCPT04',
                'rptname' => 'SOBCPT041.rpt',
            ],
            'so.rpt.bcpt06' => [
                'module'  => 'SO',
                'menuid'  => '06.20.26',
                'ma_mau'  => '01',
                'spname'  => 'asSORptBCPT06',
                'rptname' => 'SOBCPT061.rpt',
            ],
            'so.rpt.lailo' => [
                'module'  => 'SO',
                'menuid'  => '06.20.47',
                'ma_mau'  => '01',
                'spname'  => 'asSORptLaiLoHopDong',
                'rptname' => 'SOBCPT111.rpt',
            ],
            'so.rpt.nkc04' => [
                'module'  => 'SO',
                'menuid'  => '06.20.50',
                'ma_mau'  => '01',
                'spname'  => 'asGLRptNKC04',
                'rptname' => 'GLNKC041.rpt',
            ],
            'so.rpt.bcpt03-net' => [
                'module'  => 'SO',
                'menuid'  => '06.20.83',
                'ma_mau'  => '02',
                'spname'  => 'asSORptBCPT03',
                'rptname' => 'SOBCPT032.rpt',
            ],
            'so.rpt.bcpt04-nv' => [
                'module'  => 'SO',
                'menuid'  => '06.20.86',
                'ma_mau'  => '02',
                'spname'  => 'asSORptBCPT04',
                'rptname' => 'SOBCPT041.rpt',
            ],
            'so.rpt.lailo-th' => [
                'module'  => 'SO',
                'menuid'  => '06.20.89',
                'ma_mau'  => '02',
                'spname'  => 'asSORptLaiLoHopDong02',
                'rptname' => 'SOBCPT112.rpt',
            ],
        ];

        $reports += self::documentedReports($reports);

        return $reports + self::documentedDrilldownReports($reports);
    }

    /**
     * @return null|array<string, string>
     */
    public static function get(string $routeName): ?array
    {
        return self::reports()[$routeName] ?? null;
    }

    /**
     * @param array<string, array<string, string>> $existing
     *
     * @return array<string, array<string, string>>
     */
    private static function documentedReports(array $existing): array
    {
        $data  = new SimbaDocsDataRepository();
        $menus = (new SimbaDocsMenuRepository(null, $data))->activeMenus()
            ->mapWithKeys(static fn ($menu): array => [$menu->menuid => $menu])
        ;

        $knownMenuIds = [];
        foreach ($existing as $metadata) {
            $knownMenuIds[$metadata['menuid']] = true;
        }
        $dictionaryMenuIds = $data->table('sysDictionaryInfo.md')
            ->pluck('menuid')
            ->filter()
            ->flip()
        ;
        $dictionaryCodeNames = $data->table('sysDictionaryInfo.md')
            ->pluck('code_name')
            ->filter()
            ->flip()
        ;

        $reports = [];
        foreach (['sysReportInfo.md', 'zSysReportInfo.md'] as $fileName) {
            foreach ($data->table($fileName) as $row) {
                $menuid = trim((string) ($row['menuid'] ?? ''));
                if ('' === $menuid || isset($knownMenuIds[$menuid]) || !$menus->has($menuid)) {
                    continue;
                }

                $menu = $menus->get($menuid);
                if (!$menu->isReport() && !$menu->isMasterData() && '1' !== (string) $menu->report) {
                    continue;
                }
                if ($menu->isMasterData() && $dictionaryMenuIds->has($menuid)) {
                    continue;
                }
                if ($menu->isMasterData() && $dictionaryCodeNames->has((string) $menu->code_name)) {
                    continue;
                }

                $route = self::generatedRouteName((string) $menu->moduleid, (string) ($menu->dllName ?: $row['spname'] ?: $menuid));
                while (isset($existing[$route]) || isset($reports[$route])) {
                    $route .= '-' . str_replace('.', '-', $menuid);
                }

                $reports[$route] = [
                    'module'  => (string) $menu->moduleid,
                    'menuid'  => $menuid,
                    'ma_mau'  => (string) ($row['ma_mau'] ?? ''),
                    'spname'  => (string) ($row['spname'] ?? ''),
                    'rptname' => (string) ($row['rptname'] ?? ''),
                ];
                $knownMenuIds[$menuid] = true;
            }
        }

        return $reports;
    }

    /**
     * @param array<string, array<string, string>> $existing
     *
     * @return array<string, array<string, string>>
     */
    private static function documentedDrilldownReports(array $existing): array
    {
        $data  = new SimbaDocsDataRepository();
        $menus = (new SimbaDocsMenuRepository(null, $data))->activeMenus()
            ->mapWithKeys(static fn ($menu): array => [$menu->menuid => $menu])
        ;

        $knownMenuIds = [];
        foreach ($existing as $metadata) {
            $knownMenuIds[$metadata['menuid']] = true;
        }

        $reports = [];
        foreach ($data->table('sysReportDrillDownInfo.md') as $row) {
            foreach (['drilldown_menuid', 'drilldown_menuid1', 'drilldown_menuid2', 'drilldown_menuid3', 'drilldown_menuid4'] as $field) {
                $menuid = trim((string) ($row[$field] ?? ''));
                if ('' === $menuid || isset($knownMenuIds[$menuid]) || !$menus->has($menuid)) {
                    continue;
                }

                $menu  = $menus->get($menuid);
                $route = self::generatedRouteName((string) $menu->moduleid, (string) ($menu->dllName ?: $row['dllName'] ?: $menuid));
                while (isset($existing[$route]) || isset($reports[$route])) {
                    $route .= '-' . str_replace('.', '-', $menuid);
                }

                $reports[$route] = [
                    'module'         => (string) $menu->moduleid,
                    'menuid'         => $menuid,
                    'ma_mau'         => (string) ($row['ma_mau'] ?? ''),
                    'spname'         => '',
                    'rptname'        => '',
                    'dll_name'       => (string) ($menu->dllName ?: $row['dllName'] ?? ''),
                    'command'        => (string) ($menu->command ?: $row['command'] ?? ''),
                    'source_menuid'  => (string) ($row['menuid'] ?? ''),
                    'press_key_name' => (string) ($row['press_key_name'] ?? ''),
                    'description'    => (string) ($row['description'] ?? ''),
                    'source_table'   => 'sysReportDrillDownInfo',
                ];
                $knownMenuIds[$menuid] = true;
            }
        }

        return $reports;
    }

    private static function generatedRouteName(string $module, string $source): string
    {
        $slug = strtolower((string) preg_replace('/[^A-Za-z0-9]+/', '-', $source));
        $slug = trim($slug, '-');

        return strtolower($module) . '.rpt.' . ($slug ?: 'menu');
    }
}

<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-04-06 19:13:35
 */

namespace Diepxuan\Catalog\Http\Controllers\Test;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Controller test encoding tiếng Việt cho SQL Server.
 *
 * Mục đích: Kiểm tra việc insert, update, select dữ liệu tiếng Việt có dấu.
 */
class VietnameseEncodingTestController extends Controller
{
    /**
     * Hiển thị form test.
     *
     * @return View
     */
    public function index()
    {
        return view('catalog::test.vietnamese-encoding');
    }

    /**
     * Test insert tiếng Việt vào bảng test.
     *
     * @return JsonResponse
     */
    public function testInsert(Request $request)
    {
        $testData = [
            'Thanh toán nhà cung cấp Nhung Gối',
            'Hóa đơn mua hàng',
            'Phiếu thu tiền mặt',
            'Báo cáo tài chính',
            'Kế toán trưởng',
            'Nguyễn Văn A',
            'Trần Thị B',
            'Lê Văn C',
            'Phạm Thị D',
            'Hoàng Văn E',
        ];

        $results = [];

        try {
            // Test 1: Insert với PDO::prepare
            $results['test_1_prepare'] = $this->testWithPrepare($testData);

            // Test 2: Insert với DB::statement
            $results['test_2_statement'] = $this->testWithStatement($testData);

            // Test 3: Insert với DB::table
            $results['test_3_eloquent'] = $this->testWithEloquent($testData);

            // Test 4: Select lại dữ liệu
            $results['test_4_select'] = $this->testSelect();

            // Test 5: Kiểm tra collation
            $results['test_5_collation'] = $this->testCollation();

            // Test 6: Kiểm tra kiểu dữ liệu column
            $results['test_6_column_type'] = $this->testColumnType();

            return response()->json([
                'success' => true,
                'message' => 'Test hoàn thành',
                'data'    => $results,
            ]);
        } catch (\Exception $e) {
            Log::error('Vietnamese Encoding Test Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage(),
                'data'    => $results,
            ], 500);
        }
    }

    /**
     * Cleanup table test.
     *
     * @return JsonResponse
     */
    public function cleanup()
    {
        try {
            $connection = DB::connection('sqlsrv');
            $deleted    = $connection->statement("DELETE FROM CATestVietnamese WHERE stt_rec LIKE 'TEST_%'");

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa dữ liệu test',
                'deleted' => $deleted,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tạo table test nếu chưa tồn tại.
     *
     * @return JsonResponse
     */
    public function createTestTable()
    {
        try {
            $connection = DB::connection('sqlsrv');

            // Tạo table test
            $connection->statement("
                IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[CATestVietnamese]') AND type in (N'U'))
                CREATE TABLE [dbo].[CATestVietnamese] (
                    [id] INT IDENTITY(1,1) PRIMARY KEY,
                    [stt_rec] NVARCHAR(50) NOT NULL,
                    [dien_giai] NVARCHAR(500) COLLATE SQL_Latin1_General_CP1_CI_AS,
                    [created_at] DATETIME DEFAULT GETDATE(),
                    [updated_at] DATETIME DEFAULT GETDATE()
                )
            ");

            return response()->json([
                'success' => true,
                'message' => 'Đã tạo table CATestVietnamese',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test với PDO::prepare.
     */
    private function testWithPrepare(array $testData): array
    {
        $connection = DB::connection('sqlsrv');
        $pdo        = $connection->getPdo();

        $results = [];

        foreach ($testData as $index => $text) {
            $stt_rec = 'TEST_' . time() . '_' . $index;

            // Prepare statement
            $stmt = $pdo->prepare('
                INSERT INTO CATestVietnamese (stt_rec, dien_giai, created_at)
                VALUES (:stt_rec, N:dien_giai, GETDATE())
            ');

            $stmt->execute([
                ':stt_rec'   => $stt_rec,
                ':dien_giai' => $text,
            ]);

            // Select lại để kiểm tra
            $stmt = $pdo->prepare('SELECT dien_giai FROM CATestVietnamese WHERE stt_rec = :stt_rec');
            $stmt->execute([':stt_rec' => $stt_rec]);
            $row = $stmt->fetch(\PDO::FETCH_OBJ);

            $original = $text;
            $stored   = $row->dien_giai ?? null;
            $match    = $original === $stored;

            $results[] = [
                'method'   => 'PDO::prepare',
                'original' => $original,
                'stored'   => $stored,
                'match'    => $match,
                'status'   => $match ? '✅ PASS' : '❌ FAIL',
            ];

            // Cleanup
            $pdo->prepare('DELETE FROM CATestVietnamese WHERE stt_rec = :stt_rec')
                ->execute([':stt_rec' => $stt_rec])
            ;
        }

        return $results;
    }

    /**
     * Test với DB::statement.
     */
    private function testWithStatement(array $testData): array
    {
        $connection = DB::connection('sqlsrv');
        $results    = [];

        foreach ($testData as $index => $text) {
            $stt_rec = 'TEST_' . time() . '_' . $index;

            // Insert với parameter binding
            $connection->statement('
                INSERT INTO CATestVietnamese (stt_rec, dien_giai, created_at)
                VALUES (?, N?, GETDATE())
            ', [$stt_rec, $text]);

            // Select lại
            $row = $connection->selectOne('
                SELECT dien_giai FROM CATestVietnamese WHERE stt_rec = ?
            ', [$stt_rec]);

            $original = $text;
            $stored   = $row->dien_giai ?? null;
            $match    = $original === $stored;

            $results[] = [
                'method'   => 'DB::statement',
                'original' => $original,
                'stored'   => $stored,
                'match'    => $match,
                'status'   => $match ? '✅ PASS' : '❌ FAIL',
            ];

            // Cleanup
            $connection->statement('DELETE FROM CATestVietnamese WHERE stt_rec = ?', [$stt_rec]);
        }

        return $results;
    }

    /**
     * Test với DB::table (Eloquent style).
     */
    private function testWithEloquent(array $testData): array
    {
        $connection = DB::connection('sqlsrv');
        $results    = [];

        foreach ($testData as $index => $text) {
            $stt_rec = 'TEST_' . time() . '_' . $index;

            // Insert
            $connection->table('CATestVietnamese')->insert([
                'stt_rec'    => $stt_rec,
                'dien_giai'  => $text,
                'created_at' => now(),
            ]);

            // Select lại
            $row = $connection->table('CATestVietnamese')
                ->where('stt_rec', $stt_rec)
                ->first()
            ;

            $original = $text;
            $stored   = $row->dien_giai ?? null;
            $match    = $original === $stored;

            $results[] = [
                'method'   => 'DB::table',
                'original' => $original,
                'stored'   => $stored,
                'match'    => $match,
                'status'   => $match ? '✅ PASS' : '❌ FAIL',
            ];

            // Cleanup
            $connection->table('CATestVietnamese')->where('stt_rec', $stt_rec)->delete();
        }

        return $results;
    }

    /**
     * Test select dữ liệu tiếng Việt.
     */
    private function testSelect(): array
    {
        $connection = DB::connection('sqlsrv');

        // Test select với N'...' prefix
        $result = $connection->selectOne("SELECT N'Thanh toán nhà cung cấp Nhung Gối' AS test_text");

        return [
            'query'    => "SELECT N'Thanh toán nhà cung cấp Nhung Gối' AS test_text",
            'result'   => $result->test_text ?? null,
            'expected' => 'Thanh toán nhà cung cấp Nhung Gối',
            'match'    => ($result->test_text ?? null) === 'Thanh toán nhà cung cấp Nhung Gối',
            'status'   => (($result->test_text ?? null) === 'Thanh toán nhà cung cấp Nhung Gối') ? '✅ PASS' : '❌ FAIL',
        ];
    }

    /**
     * Test kiểm tra collation của database.
     */
    private function testCollation(): array
    {
        $connection = DB::connection('sqlsrv');

        // Get database collation
        $dbCollation = $connection->selectOne("
            SELECT DATABASEPROPERTYEX(DB_NAME(), 'Collation') AS Collation
        ");

        // Get table collation
        $tableCollation = $connection->select("
            SELECT
                c.name AS ColumnName,
                c.collation_name AS Collation,
                ty.name AS DataType
            FROM sys.columns c
            JOIN sys.types ty ON c.user_type_id = ty.user_type_id
            WHERE c.object_id = OBJECT_ID('CATestVietnamese')
                AND ty.name IN ('varchar', 'nvarchar', 'char', 'nchar')
        ");

        return [
            'database_collation' => $dbCollation->Collation ?? 'N/A',
            'table_collation'    => $tableCollation,
            'recommended'        => 'SQL_Latin1_General_CP1_CI_AS',
            'status'             => (str_contains($dbCollation->Collation ?? '', 'Latin1_General')) ? '✅ OK' : '⚠️ CHECK',
        ];
    }

    /**
     * Test kiểm tra kiểu dữ liệu column.
     */
    private function testColumnType(): array
    {
        $connection = DB::connection('sqlsrv');

        $columns = $connection->select("
            SELECT
                c.name AS ColumnName,
                ty.name AS DataType,
                c.max_length AS MaxLength,
                c.is_nullable AS IsNullable
            FROM sys.columns c
            JOIN sys.types ty ON c.user_type_id = ty.user_type_id
            WHERE c.object_id = OBJECT_ID('CATestVietnamese')
            ORDER BY c.column_id
        ");

        $hasNvarchar = false;
        foreach ($columns as $col) {
            if (0 === stripos($col->DataType, 'n')) {
                $hasNvarchar = true;

                break;
            }
        }

        return [
            'columns'        => $columns,
            'has_nvarchar'   => $hasNvarchar,
            'recommendation' => $hasNvarchar
                ? '✅ Column dùng nvarchar (tốt cho Unicode)'
                : '⚠️ Nên dùng nvarchar thay vì varchar cho column chứa tiếng Việt',
        ];
    }
}

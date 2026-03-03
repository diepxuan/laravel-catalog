<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 *
 * @lastupdate 2026-03-03
 */

namespace Diepxuan\Catalog\Tests\Unit\Http\Livewire\Cash\Nganhang\Baono;

use Diepxuan\Catalog\Http\Livewire\Cash\Nganhang\Baono\Phieubaono;
use Diepxuan\Catalog\Models\ArDmKh;
use Diepxuan\Catalog\Models\CaCt3;
use Diepxuan\Catalog\Models\CaPh3;
use Diepxuan\Simba\Models\GlDmTk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Class PhieubaonoTest
 *
 * Unit tests cho component Phieubaono
 *
 * @package Diepxuan\Catalog\Tests\Unit\Http\Livewire\Cash\Nganhang\Baono
 */
class PhieubaonoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Tạo user test
        $this->user = \App\Models\User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * Test: Component khởi tạo đúng
     */
    public function test_component_mounts_successfully(): void
    {
        Livewire::test(Phieubaono::class)
            ->assertSet('pMa_Nt', 'VND')
            ->assertSet('pTy_Gia', 1)
            ->assertSet('pTk_Co', Phieubaono::DEFAULT_TK_CO)
            ->assertOk();
    }

    /**
     * Test: Constants được định nghĩa đúng
     */
    public function test_constants_are_defined(): void
    {
        $this->assertEquals('CA4', Phieubaono::MA_CT);
        $this->assertEquals(11217, Phieubaono::DEFAULT_TK_CO);
        $this->assertEquals('VND', Phieubaono::DEFAULT_MA_NT);
        $this->assertEquals(1, Phieubaono::DEFAULT_TY_GIA);
    }

    /**
     * Test: Thêm dòng mới
     */
    public function test_add_row(): void
    {
        Livewire::test(Phieubaono::class)
            ->call('addRow')
            ->assertSet('pCts', function ($cts) {
                return $cts->count() === 2; // 1 dòng mặc định + 1 dòng thêm
            });
    }

    /**
     * Test: Xóa dòng
     */
    public function test_remove_row(): void
    {
        Livewire::test(Phieubaono::class)
            ->call('addRow')
            ->call('removeRow', 0)
            ->assertSet('pCts', function ($cts) {
                return $cts->count() === 1; // Vẫn còn 1 dòng tối thiểu
            });
    }

    /**
     * Test: Tính tổng tiền
     */
    public function test_calculate_total(): void
    {
        Livewire::test(Phieubaono::class)
            ->set('pCts', collect([
                ['ma_tk' => '641', 'ps_no' => 1000000, 'ps_co' => 0],
                ['ma_tk' => '642', 'ps_no' => 500000, 'ps_co' => 0],
            ]))
            ->call('calculateTotal')
            ->assertSet('pTong_Ps_No', 1500000);
    }

    /**
     * Test: Tính ngoại tệ
     */
    public function test_calculate_foreign_currency(): void
    {
        Livewire::test(Phieubaono::class)
            ->set('pTy_Gia', 23000)
            ->set('pCts', collect([
                ['ma_tk' => '641', 'ps_no' => 100, 'ps_co' => 0, 'ps_no_nt' => 0, 'ps_co_nt' => 0],
            ]))
            ->call('calculateForeignCurrency')
            ->assertSet('pT_Tien_Nt', 2300000);
    }

    /**
     * Test: Xử lý nhấn Enter để thêm dòng
     */
    public function test_handle_enter_adds_row(): void
    {
        Livewire::test(Phieubaono::class)
            ->call('handleEnter', 0) // Nhấn Enter ở dòng cuối
            ->assertSet('pCts', function ($cts) {
                return $cts->count() === 2;
            });
    }

    /**
     * Test: Loại bỏ dòng rỗng trước khi lưu
     */
    public function test_remove_empty_rows(): void
    {
        Livewire::test(Phieubaono::class)
            ->set('pCts', collect([
                ['ma_tk' => '641', 'ps_no' => 1000000, 'ps_co' => 0],
                ['ma_tk' => '642', 'ps_no' => 0, 'ps_co' => 0], // Dòng rỗng
                ['ma_tk' => '643', 'ps_no' => 500000, 'ps_co' => 0],
            ]))
            ->call('removeEmptyRows')
            ->assertSet('pCts', function ($cts) {
                return $cts->count() === 2; // Chỉ còn 2 dòng có số tiền > 0
            });
    }

    /**
     * Test: Validation khi không chọn khách hàng
     */
    public function test_validation_requires_customer(): void
    {
        Livewire::test(Phieubaono::class)
            ->set('pMa_Kh', '')
            ->call('submit')
            ->assertHasErrors(['pMa_Kh' => 'required']);
    }

    /**
     * Test: Validation khi không chọn tài khoản
     */
    public function test_validation_requires_account(): void
    {
        Livewire::test(Phieubaono::class)
            ->set('pTk_Co', '')
            ->call('submit')
            ->assertHasErrors(['pTk_Co' => 'required']);
    }

    /**
     * Test: Validation khi tổng bằng 0
     */
    public function test_validation_rejects_zero_total(): void
    {
        Livewire::test(Phieubaono::class)
            ->set('pMa_Kh', 'KH001')
            ->set('pTk_Co', '11217')
            ->set('pCts', collect([
                ['ma_tk' => '641', 'ps_no' => 0, 'ps_co' => 0],
            ]))
            ->call('submit')
            ->assertHasErrors(['pCts']);
    }

    /**
     * Test: Cập nhật khách hàng tự động điền thông tin
     */
    public function test_update_khach_hang_auto_fills(): void
    {
        // Tạo khách hàng test
        $khachHang = ArDmKh::create([
            'ma_kh' => 'KH_TEST',
            'ten_kh' => 'Khách hàng Test',
            'dia_chi' => 'Hà Nội',
            'nguoi_gd' => 'Nguyễn Văn A',
        ]);

        Livewire::test(Phieubaono::class)
            ->set('pMa_Kh', 'KH_TEST')
            ->call('updateKhachHang')
            ->assertSet('pDia_Chi', 'Hà Nội')
            ->assertSet('pNguoi_Gd', 'Nguyễn Văn A');
    }

    /**
     * Test: Mode create và edit
     */
    public function test_create_and_edit_modes(): void
    {
        // Test mode create (mặc định)
        Livewire::test(Phieubaono::class)
            ->assertSet('pMode', 'create');

        // Test mode edit (khi có stt_rec)
        Livewire::test(Phieubaono::class, ['pStt_Rec' => 'TEST123'])
            ->assertSet('pMode', 'edit');
    }

    /**
     * Test: Validation messages tiếng Việt
     */
    public function test_vietnamese_validation_messages(): void
    {
        Livewire::test(Phieubaono::class)
            ->set('pMa_Kh', '')
            ->call('submit')
            ->assertHasErrors(['pMa_Kh' => 'required']);

        // Kiểm tra message tiếng Việt có trong rules
        $component = new Phieubaono();
        $this->assertArrayHasKey('pMa_Kh.required', $component->getRules());
    }

    /**
     * Test: Số dư được cập nhật khi thay đổi ngày
     */
    public function test_sodu_updates_on_date_change(): void
    {
        Livewire::test(Phieubaono::class)
            ->set('pNgay_Ct', '2026-03-01')
            ->call('updateSoDu')
            ->assertOk(); // Không throw exception
    }

    /**
     * Test: Tính toán tự động khi thay đổi property
     */
    public function test_auto_calculate_on_property_change(): void
    {
        Livewire::test(Phieubaono::class)
            ->set('pCts.0.ps_no', 1000000)
            ->assertSet('pTong_Ps_No', 1000000);
    }

    /**
     * Test: Render view successfully
     */
    public function test_render(): void
    {
        Livewire::test(Phieubaono::class)
            ->assertViewIs('catalog::cash.nganhang.baono.phieubaono')
            ->assertViewHas('arDmKhs');
    }
}

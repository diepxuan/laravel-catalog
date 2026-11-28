<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 */

namespace Diepxuan\Catalog\Http\Livewire\Si\Vch;

use Diepxuan\Simba\StoredProcedures\AsGetGroupInfo;
use Diepxuan\Simba\StoredProcedures\AsInsGroupInfo;
use Diepxuan\Simba\StoredProcedures\AsSiDelGroupInfo;
use Diepxuan\Simba\StoredProcedures\AsUpdGroupInfo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Quản lý nhóm người sử dụng — SIGroupInfo.dll (menu 90.20.05).
 *
 * NGUON SU THAT:
 *   - simba-docs/decompiled/asia/SIGroupInfo.dll/
 *       AsiaErp.UserInterface/frmSIGroupInfo.cs       (grid + nut)
 *       AsiaErp.DataAccess/MsSqlSiGroupInfoDao.cs     (payload SP)
 *   - simba-docs/tables/sysGroupInfo.md              (8 cot)
 *
 * SP (da doi chieu INFORMATION_SCHEMA.PARAMETERS):
 *   asGetGroupInfo       (1) pGroupName            — '' = tat ca
 *   asInsGroupInfo       (5) pGroupname, pFullname, pKsd, pGrand, pUser
 *   asUpdGroupInfo       (5) pGroupname, pFullname, pGrand, pKSD, pUser
 *   asSiDelGroupInfo     (2) pGroupname, pRet
 *
 * Cot grid theo form goc:  GroupName | fullname | grand | ksd
 *   GroupName -> "Tên nhóm"
 *   FullName  -> "Tên đầy đủ"
 *   Grand     -> "Phân lại quyền"
 *   KSD       -> "KSD"
 *
 * Nut theo form goc: Mới | Sửa | Xoá | Phân quyền | Thoát
 */
class Sigroupinfo extends Component
{
    use WithPagination;

    public string $search = '';

    /** Form them/sua dang mo? */
    public bool $showForm = false;

    /** Dang sua (true) hay them moi (false). */
    public bool $isEdit = false;

    public ?string $groupName = null;

    public string $fullName = '';

    public bool $grand = false;

    public bool $ksd = false;

    public ?string $errorMessage = null;

    public ?string $successMessage = null;

    /** Trang hien tai (WithPagination quản lý qua $page). */
    public int $page = 1;

    protected int $perPage = 50;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->errorMessage = null;
        $this->successMessage = null;
        $this->resetForm();
        $this->isEdit = false;
        $this->showForm = true;
    }

    public function openEdit(string $groupName): void
    {
        $this->errorMessage = null;
        $this->successMessage = null;
        $this->resetForm();

        $row = $this->findGroup($groupName);

        if (null === $row) {
            $this->errorMessage = 'Không tìm thấy nhóm ' . $groupName . '.';

            return;
        }

        $this->groupName = (string) $row['groupname'];
        $this->fullName = (string) ($row['fullname'] ?? '');
        $this->grand = (bool) ($row['grand'] ?? false);
        $this->ksd = (bool) ($row['ksd'] ?? false);
        $this->isEdit = true;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->errorMessage = null;
        $this->successMessage = null;

        $this->validate([
            'groupName' => ['required', 'string', 'max:20'],
            'fullName'  => ['required', 'string', 'max:50'],
        ], [
            'groupName.required' => 'Chưa nhập tên nhóm.',
            'groupName.max'      => 'Tên nhóm tối đa 20 ký tự.',
            'fullName.required'  => 'Chưa nhập tên đầy đủ.',
            'fullName.max'       => 'Tên đầy đủ tối đa 50 ký tự.',
        ]);

        $payload = [
            'pGroupname' => (string) $this->groupName,
            'pFullname'  => $this->fullName,
            'pKsd'       => $this->ksd ? 1 : 0,
            'pGrand'     => $this->grand ? 1 : 0,
            'pUser'      => (string) auth()->user()?->name,
        ];

        try {
            if ($this->isEdit) {
                AsUpdGroupInfo::call($payload);
                $this->successMessage = 'Đã cập nhật nhóm ' . $this->groupName . '.';
            } else {
                if (null !== $this->findGroup((string) $this->groupName)) {
                    $this->errorMessage = 'Nhóm ' . $this->groupName . ' đã tồn tại.';

                    return;
                }

                AsInsGroupInfo::call($payload);
                $this->successMessage = 'Đã thêm nhóm ' . $this->groupName . '.';
            }
        } catch (\Throwable $exception) {
            report($exception);
            $this->errorMessage = 'Không lưu được nhóm. Vui lòng kiểm tra lại dữ liệu.';

            return;
        }

        $this->showForm = false;
        $this->resetForm();
    }

    public function delete(string $groupName): void
    {
        $this->errorMessage = null;
        $this->successMessage = null;

        try {
            AsSiDelGroupInfo::call([
                'pGroupname' => $groupName,
                'pRet'       => 0,
            ]);
            $this->successMessage = 'Đã xoá nhóm ' . $groupName . '.';
        } catch (\Throwable $exception) {
            report($exception);
            $this->errorMessage = 'Không xoá được nhóm ' . $groupName . '.';

            return;
        }

        $this->resetForm();
    }

    public function cancel(): void
    {
        $this->showForm = false;
        $this->errorMessage = null;
        $this->successMessage = null;
        $this->resetForm();
    }

    public function render(): View
    {
        return view('catalog::si.vch.sigroupinfo', [
            'groups' => $this->getGroupsPaginated(),
        ])->layout('catalog::layouts.app');
    }

    protected function getGroupsPaginated(): LengthAwarePaginatorContract
    {
        $rows = $this->normalizeRows(
            AsGetGroupInfo::call(['pGroupName' => ''])->all()
        );

        if ('' !== trim($this->search)) {
            $needle = mb_strtolower(trim($this->search));

            $rows = $rows->filter(
                static fn (array $row): bool => str_contains(mb_strtolower($row['groupname']), $needle)
                    || str_contains(mb_strtolower($row['fullname']), $needle)
            )->values();
        }

        return $this->paginateCollection($rows);
    }

    /**
     * @param array<int, mixed> $rows
     *
     * @return Collection<int, array<string, mixed>>
     */
    protected function normalizeRows(array $rows): Collection
    {
        return collect($rows)->map(static function ($row): array {
            $data = \is_array($row) ? $row : (array) $row;

            return [
                'groupname' => (string) ($data['groupname'] ?? $data['GroupName'] ?? ''),
                'fullname'  => (string) ($data['fullname'] ?? $data['FullName'] ?? ''),
                'grand'     => (bool) ($data['grand'] ?? $data['Grand'] ?? false),
                'ksd'       => (bool) ($data['ksd'] ?? $data['KSD'] ?? false),
                'cuser'     => (string) ($data['cuser'] ?? ''),
                'luser'     => (string) ($data['luser'] ?? ''),
                'cdate'     => (string) ($data['cdate'] ?? ''),
                'ldate'     => (string) ($data['ldate'] ?? ''),
            ];
        })->values();
    }

    /**
     * @return null|array<string, mixed>
     */
    protected function findGroup(string $groupName): ?array
    {
        $rows = AsGetGroupInfo::call(['pGroupName' => $groupName])->all();

        foreach ($this->normalizeRows($rows) as $row) {
            if (mb_strtolower($row['groupname']) === mb_strtolower($groupName)) {
                return $row;
            }
        }

        return null;
    }

    /**
     * Xoa du lieu form.
     *
     * KHONG xoa $successMessage: save()/delete() set message TRUOC khi goi
     * ham nay — neu xoa o day thi nguoi dung khong bao gio thay thong bao.
     * Message chi bi xoa khi bat dau thao tac moi (openCreate/openEdit/save/delete).
     */
    protected function resetForm(): void
    {
        $this->groupName = null;
        $this->fullName = '';
        $this->grand = false;
        $this->ksd = false;
        $this->isEdit = false;
        $this->resetValidation();
    }

    /**
     * @param Collection<int, array<string, mixed>> $rows
     */
    protected function paginateCollection(Collection $rows): LengthAwarePaginatorContract
    {
        $page = (int) ($this->page ?: 1);

        return new LengthAwarePaginator(
            $rows->forPage($page, $this->perPage)->values(),
            $rows->count(),
            $this->perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
}

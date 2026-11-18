@props(['target' => '', 'message' => ''])

{{--
    Trang trung gian khi form ARDMKH không tìm thấy đối tượng.
    Không dùng $this->redirect() trong mount() vì Livewire 3.8 + Laravel 11 lỗi
    `abort(redirect($to))` (Redirector thay vì int). Xem ArdmkhForm::handleNotFound().
--}}
<div class="mx-auto max-w-2xl py-10">
    <div class="rounded-lg border border-amber-300 bg-amber-50 p-6 text-center">
        <p class="text-sm font-medium text-amber-900">{{ $message }}</p>
        <p class="mt-2 text-xs text-amber-700">Đang chuyển về danh sách…</p>
        <a href="{{ $target }}"
            class="mt-4 inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700">
            Về danh sách ngay
        </a>
    </div>
</div>

<script>
    (function () {
        var target = @js($target);
        if (typeof target === 'string' && target !== '') {
            window.location.replace(target);
        } else {
            // Fallback: target không hợp lệ thì để người dùng bấm link ở trên.
            console.warn('ardmkh-redirect: target rỗng, không tự chuyển trang.');
        }
    })();
</script>

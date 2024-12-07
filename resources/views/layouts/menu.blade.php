@php
    $menus = [
        [
            'name' => 'Bán hàng',
            'status' => Request::is('banhang', 'banhang/*'),
            'items' => [
                'Hoá đơn bán hàng' => 'sell.index',
                'space',
                // 'Danh sách hàng hoá vật tư' => 'catalog.index',
                // 'Nhóm hàng hoá vật tư' => 'category.index',
            ],
        ],
        [
            'name' => 'Mua hàng',
            'status' => Request::is('muahang', 'muahang/*'),
            'items' => [
                // 'Hoá đơn mua hàng' => 'purchase.index',
                'space',
                // 'Danh sách hàng hoá vật tư' => 'catalog.index',
                // 'Nhóm hàng hoá vật tư' => 'category.index',
            ],
        ],
        [
            'name' => 'Hàng tồn kho',
            'status' => Request::is('tonkho', 'tonkho/*', 'khohang', 'khohang/*'),
            'items' => [
                'Phiếu xuất điều chuyển kho' => 'inventory.index',
                'space',
                'Danh sách hàng hoá vật tư' => 'catalog.index',
                'Nhóm hàng hoá vật tư' => 'category.index',
            ],
        ],
        [
            'name' => 'Hệ Thống',
            'status' => Request::is('hethong', 'hethong/*'),
            'items' => [
                'Dashboard' => 'system.index',
                'Website' => 'systemwebsite.index',
            ],
        ],
    ];
@endphp
<style type="text/css">
</style>
<a class="d-flex align-items-center pb-3 text-decoration-none border-bottom nav-link" href={{ route('system.index') }}
    title="Điệp Xuân">
    <img src={{ $brand }} alt="Điệp Xuân" width="100%" class="d-inline-block align-text-top">
</a>
<ul class="list-unstyled ps-0" id="menu-collapse">
    @foreach (json_decode(json_encode($menus), false) as $menuId => $menu)
        <li class="mb-1">
            <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse"
                data-bs-target="#menu-collapse-{{ $menuId }}" aria-controls="menu-collapse-{{ $menuId }}"
                aria-expanded="{{ $menu->status }}">
                {{ $menu->name }}
            </button>
            <div @class(['collapse', 'accordion-collapse', 'show' => $menu->status]) id="menu-collapse-{{ $menuId }}" data-bs-parent="#menu-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal ps-4 pb-1 small">
                    @foreach ($menu->items as $title => $url)
                        @if ($url != 'space')
                            <li>
                                <a @class([
                                    'active' => $url === Route::currentRouteName(),
                                    'text-decoration-none',
                                    'rounded',
                                    'link-body-emphasis',
                                    'd-inline-flex',
                                ]) href={{ route($url) ?? '' }}>
                                    {{ $title }}
                                </a>
                            </li>
                        @else
                            <li class="border-top my-1"></li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </li>
    @endforeach
</ul>

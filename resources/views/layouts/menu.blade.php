@php
    $menus = [
        [
            'name' => 'Hàng tồn kho',
            'status' => Request::is('tonkho', 'tonkho/*', 'khohang', 'khohang/*'),
            'ins' => [
                'Phiếu xuất điều chuyển kho' => route('inventory.index'),
            ],
            'items' => [
                'Danh sách hàng hoá vật tư' => route('catalog.index'),
                'Nhóm hàng hoá vật tư' => route('category.index'),
            ],
        ],
        [
            'name' => 'Hệ Thống',
            'status' => Request::is('hethong'),
            'items' => [
                'Dashboard' => route('system.index'),
            ],
        ],
    ];
@endphp
<style type="text/css">
    ul.menu {
        display: block;
        float: left;
        clear: left;
        width: 300px;
        list-style: none;
        padding: 0;
        margin: 0;
        text-transform: uppercase;
    }

    .menu ul {
        display: block;
        float: left;
        clear: both;
        list-style: none;
        padding: 0 0 0 10px;
        margin: 0;
        width: calc(100% - 10px);
    }

    .menu>li {
        display: block;
        float: left;
        clear: both;
        width: 100%;
        border-bottom: 1px solid rgb(172, 170, 170);
        /* margin: 0 0 -1px; */
        /* padding: 0; */
        height: auto;
    }

    .menu label {
        display: block;
        cursor: pointer;
        width: 100%;
        display: block;
        padding: 4px 0 4px 4px;
    }

    .menu input[type=radio] {
        display: none;
    }

    .menu input+ul {
        display: none;
    }

    .menu input:checked+ul {
        display: block;
    }

    .menu li.space {
        display: block;
        width: 80%;
        height: 1;
        border-bottom: solid 1px #b7b7b7;
        margin: auto;
    }

    .menu a {
        text-decoration: none;
        display: block;
        width: 100%;
        padding: 4px 0 4px 4px;
    }

    /* unvisited link */
    .menu a:link {
        color: rgb(22, 22, 255);
    }

    /* visited link */
    .menu a:visited {
        color: rgb(22, 22, 255);
    }

    /* mouse over link */
    .menu a:hover {
        color: rgb(0, 115, 255);
    }

    /* selected link */
    .menu a:active {
        color: rgb(22, 22, 255);
    }
</style>
<ul class="menu">
    <li>
        <a class="logo" href={{ route('system.index') }} title="Điệp Xuân" aria-label="store logo">
            <img src="{{ $brand }}" title="Điệp Xuân" alt="Điệp Xuân" width="100%">
        </a>
    </li>
    @foreach (json_decode(json_encode($menus), false) as $menuId => $menu)
        <li>
            <label for="{{ $menuId }}">{{ $menu->name }}</label>
            <input type="radio" id="{{ $menuId }}" name="box" {{ $menu->status ? 'checked' : '' }} />
            <ul>
                @isset($menu->ins)
                    @foreach ($menu->ins as $title => $url)
                        <li>
                            <a href={{ $url }}>
                                {{ $title }}
                            </a>
                        </li>
                    @endforeach
                    <li class="space"></li>
                @endisset
                @foreach ($menu->items as $title => $url)
                    <li>
                        <a href={{ $url }}>
                            {{ $title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
    @endforeach
</ul>

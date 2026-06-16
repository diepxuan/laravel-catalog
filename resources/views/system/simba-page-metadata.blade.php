<div class="space-y-3">
    <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
        @switch($sourceType ?? '')
            @case('dictionary')
                Danh mục đã được gắn theo menu active, khóa và bảng từ sysDictionaryInfo. Form nhập liệu chỉ mở sau khi đối chiếu đủ payload, lookup và quyền sửa/xóa trong simba-docs.
                @break
            @case('report')
                Báo cáo đã được mở theo menu active và thông tin mẫu từ simba-docs. Phần filter, tham số và render dữ liệu chỉ mở sau khi đối chiếu đầy đủ SP/function trong tài liệu.
                @break
            @case('voucher')
                Chứng từ đã có metadata mã chứng từ từ simba-docs. Form nhập liệu và số dùng chung chỉ mở sau khi đối chiếu payload, quyền sửa/xóa.
                @break
            @default
                Menu này có DLL/command trong sysMenu nhưng chưa có metadata report, dictionary hoặc voucher đủ chuẩn. Portal chỉ mở shell tham chiếu; không execute, không ghi dữ liệu, không gọi SQL/SP cho đến khi audit đủ logic trong simba-docs.
        @endswitch
    </div>

    <dl class="grid gap-2 rounded-lg border border-gray-200 bg-white p-4 text-sm md:grid-cols-2">
        <div>
            <dt class="font-medium text-gray-600">Route</dt>
            <dd class="font-mono text-gray-900">{{ $routeName }}</dd>
        </div>
        <div>
            <dt class="font-medium text-gray-600">Menu</dt>
            <dd class="font-mono text-gray-900">{{ $metadata['menuid'] ?? '-' }}</dd>
        </div>
        <div>
            <dt class="font-medium text-gray-600">Source type</dt>
            <dd class="font-mono text-gray-900">{{ $sourceType ?? '-' }}</dd>
        </div>
        <div>
            <dt class="font-medium text-gray-600">Module</dt>
            <dd class="font-mono text-gray-900">{{ $metadata['module'] ?? '-' }}</dd>
        </div>

        @if ($sourceType === 'dictionary')
            <div>
                <dt class="font-medium text-gray-600">Code name</dt>
                <dd class="font-mono text-gray-900">{{ $metadata['code_name'] ?? '-' }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-600">Table</dt>
                <dd class="font-mono text-gray-900">{{ $metadata['table'] ?? '-' }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-600">Primary key</dt>
                <dd class="font-mono text-gray-900">{{ is_array($metadata['pk'] ?? null) ? implode(', ', $metadata['pk']) : '-' }}</dd>
            </div>
            @if (! empty($metadata['source_menuid']))
                <div>
                    <dt class="font-medium text-gray-600">Source menu</dt>
                    <dd class="font-mono text-gray-900">{{ $metadata['source_menuid'] ?: 'blank' }}</dd>
                </div>
            @endif
            <div>
                <dt class="font-medium text-gray-600">Carry fields</dt>
                <dd class="font-mono text-gray-900">{{ is_array($metadata['fields'] ?? null) ? implode(', ', $metadata['fields']) : '-' }}</dd>
            </div>
        @endif

        @if ($sourceType === 'report')
            <div>
                <dt class="font-medium text-gray-600">Stored procedure</dt>
                <dd class="font-mono text-gray-900">{{ $metadata['spname'] ?? '-' }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-600">Report file</dt>
                <dd class="font-mono text-gray-900">{{ $metadata['rptname'] ?? '-' }}</dd>
            </div>
            @if (! empty($metadata['ma_mau']))
                <div>
                    <dt class="font-medium text-gray-600">Mẫu</dt>
                    <dd class="font-mono text-gray-900">{{ $metadata['ma_mau'] }}</dd>
                </div>
            @endif
            @if (! empty($metadata['dll_name']) || ! empty($metadata['command']))
                <div>
                    <dt class="font-medium text-gray-600">DLL</dt>
                    <dd class="font-mono text-gray-900">{{ $metadata['dll_name'] ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-600">Command</dt>
                    <dd class="font-mono text-gray-900">{{ $metadata['command'] ?? '-' }}</dd>
                </div>
            @endif
            @if (! empty($metadata['source_menuid']))
                <div>
                    <dt class="font-medium text-gray-600">Source menu</dt>
                    <dd class="font-mono text-gray-900">{{ $metadata['source_menuid'] }}</dd>
                </div>
                @if (! empty($metadata['press_key_name']))
                    <div>
                        <dt class="font-medium text-gray-600">Key</dt>
                        <dd class="font-mono text-gray-900">{{ $metadata['press_key_name'] }}</dd>
                    </div>
                @endif
            @endif
        @endif

        @if ($sourceType === 'voucher')
            <div>
                <dt class="font-medium text-gray-600">Mã chứng từ</dt>
                <dd class="font-mono text-gray-900">{{ $metadata['ma_ct'] ?? '-' }}</dd>
            </div>
        @endif

        @if (in_array($sourceType, ['custom', null], true))
            @if (! empty($metadata['dll_name']) || ! empty($metadata['command']) || ! empty($metadata['code_name']))
                <div>
                    <dt class="font-medium text-gray-600">DLL</dt>
                    <dd class="font-mono text-gray-900">{{ $metadata['dll_name'] ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-600">Command</dt>
                    <dd class="font-mono text-gray-900">{{ $metadata['command'] ?? '-' }}</dd>
                </div>
                @if (! empty($metadata['code_name']))
                    <div>
                        <dt class="font-medium text-gray-600">Code name</dt>
                        <dd class="font-mono text-gray-900">{{ $metadata['code_name'] }}</dd>
                    </div>
                @endif
            @endif
        @endif
    </dl>

    @if (! empty($metadata['description']))
        <div class="rounded-lg border border-gray-200 bg-white p-4 text-sm text-gray-700">
            {{ $metadata['description'] }}
        </div>
    @endif
</div>

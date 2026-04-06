@extends('catalog::layouts.app')

@section('title', 'Test Encoding Tiếng Việt')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">
            🇻🇳 Test Encoding Tiếng Việt - SQL Server
        </h1>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-700">Mục đích</h2>
            <p class="text-gray-600 mb-4">
                Kiểm tra việc insert, update, select dữ liệu tiếng Việt có dấu vào SQL Server.
                Phát hiện vấn đề mất dấu (encoding) và đưa ra giải pháp.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="bg-blue-50 p-4 rounded">
                    <h3 class="font-semibold text-blue-800 mb-2">Triệu chứng lỗi</h3>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Input: <code class="bg-blue-100 px-1 rounded">Thanh toán nhà cung cấp</code></li>
                        <li>• Output: <code class="bg-red-100 px-1 rounded">Thanh toán nhà cung c?p</code></li>
                        <li>• Các ký tự: á, à, ả, ã, ạ, ă, â, ê, ô, ơ, ư, đ bị mất dấu</li>
                    </ul>
                </div>

                <div class="bg-green-50 p-4 rounded">
                    <h3 class="font-semibold text-green-800 mb-2">Giải pháp</h3>
                    <ul class="text-sm text-green-700 space-y-1">
                        <li>• Dùng <code class="bg-green-100 px-1 rounded">nvarchar</code> thay vì <code class="bg-green-100 px-1 rounded">varchar</code></li>
                        <li>• Collation: <code class="bg-green-100 px-1 rounded">SQL_Latin1_General_CP1_CI_AS</code></li>
                        <li>• PDO Encoding: <code class="bg-green-100 px-1 rounded">UTF8</code></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-4 mb-6">
            <button
                onclick="createTestTable()"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200"
            >
                📋 Tạo Table Test
            </button>

            <button
                onclick="runTest()"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200"
            >
                ▶️ Chạy Test
            </button>

            <button
                onclick="cleanup()"
                class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200"
            >
                🗑️ Xóa Dữ Liệu Test
            </button>

            <button
                onclick="location.reload()"
                class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200"
            >
                🔄 Refresh
            </button>
        </div>

        <!-- Loading Indicator -->
        <div id="loading" class="hidden text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
            <p class="text-gray-600 mt-4">Đang xử lý...</p>
        </div>

        <!-- Results -->
        <div id="results" class="space-y-6"></div>
    </div>
</div>

<script>
function createTestTable() {
    showLoading();
    fetch('/catalog/test/vietnamese-encoding/create-table', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {
        hideLoading();
        alert(data.message);
    })
    .catch(err => {
        hideLoading();
        alert('Lỗi: ' + err.message);
    });
}

function runTest() {
    showLoading();
    fetch('/catalog/test/vietnamese-encoding/run', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {
        hideLoading();
        displayResults(data);
    })
    .catch(err => {
        hideLoading();
        alert('Lỗi: ' + err.message);
    });
}

function cleanup() {
    if (!confirm('Bạn có chắc muốn xóa tất cả dữ liệu test?')) return;

    showLoading();
    fetch('/catalog/test/vietnamese-encoding/cleanup', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {
        hideLoading();
        alert(data.message);
    })
    .catch(err => {
        hideLoading();
        alert('Lỗi: ' + err.message);
    });
}

function showLoading() {
    document.getElementById('loading').classList.remove('hidden');
    document.getElementById('results').innerHTML = '';
}

function hideLoading() {
    document.getElementById('loading').classList.add('hidden');
}

function displayResults(data) {
    const resultsDiv = document.getElementById('results');
    let html = '';

    if (!data.success) {
        html = `
            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-red-800 mb-2">❌ Lỗi</h3>
                <p class="text-red-700">${data.message}</p>
            </div>
        `;
        resultsDiv.innerHTML = html;
        return;
    }

    // Test 1: PDO::prepare
    if (data.data.test_1_prepare) {
        html += renderTestSection('Test 1: PDO::prepare', data.data.test_1_prepare);
    }

    // Test 2: DB::statement
    if (data.data.test_2_statement) {
        html += renderTestSection('Test 2: DB::statement', data.data.test_2_statement);
    }

    // Test 3: DB::table
    if (data.data.test_3_eloquent) {
        html += renderTestSection('Test 3: DB::table (Eloquent)', data.data.test_3_eloquent);
    }

    // Test 4: Select
    if (data.data.test_4_select) {
        html += `
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Test 4: Select UTF-8</h3>
                ${renderResultRow(data.data.test_4_select)}
            </div>
        `;
    }

    // Test 5: Collation
    if (data.data.test_5_collation) {
        const collation = data.data.test_5_collation;
        html += `
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Test 5: Collation</h3>
                <div class="space-y-2">
                    <p><strong>Database Collation:</strong>
                        <code class="bg-gray-100 px-2 py-1 rounded">${collation.database_collation}</code>
                        <span class="ml-2">${collation.status}</span>
                    </p>
                    <p><strong>Recommended:</strong>
                        <code class="bg-gray-100 px-2 py-1 rounded">${collation.recommended}</code>
                    </p>
                </div>
            </div>
        `;
    }

    // Test 6: Column Type
    if (data.data.test_6_column_type) {
        const colType = data.data.test_6_column_type;
        html += `
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Test 6: Column Data Types</h3>
                <p class="mb-4">${colType.recommendation}</p>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Column</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Data Type</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Max Length</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            ${colType.columns.map(col => `
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900">${col.ColumnName}</td>
                                    <td class="px-4 py-2 text-sm ${col.DataType.toLowerCase().includes('n') ? 'text-green-600 font-semibold' : 'text-red-600'}">${col.DataType}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500">${col.MaxLength}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        `;
    }

    resultsDiv.innerHTML = html;
}

function renderTestSection(title, tests) {
    const passCount = tests.filter(t => t.match).length;
    const totalCount = tests.length;
    const allPass = passCount === totalCount;

    return `
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                ${title}
                <span class="text-sm ${allPass ? 'text-green-600' : 'text-red-600'}">
                    (${passCount}/${totalCount} pass)
                </span>
            </h3>
            <div class="space-y-2">
                ${tests.map(test => renderResultRow(test)).join('')}
            </div>
        </div>
    `;
}

function renderResultRow(test) {
    return `
        <div class="flex items-center justify-between p-3 ${test.match ? 'bg-green-50' : 'bg-red-50'} rounded border ${test.match ? 'border-green-200' : 'border-red-200'}">
            <div class="flex-1">
                <p class="text-sm text-gray-600 mb-1">${test.method || 'Test'}</p>
                <p class="text-sm"><strong>Original:</strong> <code class="bg-white px-2 py-1 rounded">${escapeHtml(test.original)}</code></p>
                <p class="text-sm"><strong>Stored:</strong> <code class="bg-white px-2 py-1 rounded">${escapeHtml(test.stored)}</code></p>
            </div>
            <div class="ml-4 text-lg font-bold ${test.match ? 'text-green-600' : 'text-red-600'}">
                ${test.status}
            </div>
        </div>
    `;
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
@endsection

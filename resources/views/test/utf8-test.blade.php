{{-- Test UTF-8 Blade Template --}}
{{-- Use this file to test if Blade renders Vietnamese correctly --}}

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test UTF-8 - Tiếng Việt</title>
</head>
<body>
    <h1>Test Encoding Tiếng Việt</h1>

    <h2>1. Test trực tiếp trong Blade</h2>
    <p>
        <strong>Input:</strong> Thanh toán nhà cung cấp Nhung Gối<br>
        <strong>Render:</strong> {{ 'Thanh toán nhà cung cấp Nhung Gối' }}
    </p>

    <h2>2. Test các ký tự đặc biệt</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>Ký tự</th>
            <th>Test</th>
        </tr>
        <tr>
            <td>à, á, ả, ã, ạ</td>
            <td>à á ả ã ạ</td>
        </tr>
        <tr>
            <td>ă, ằ, ắ, ẳ, ẵ, ặ</td>
            <td>ă ằ ắ ẳ ẵ ặ</td>
        </tr>
        <tr>
            <td>â, ầ, ấ, ẩ, ẫ, ậ</td>
            <td>â ầ ấ ẩ ẫ ậ</td>
        </tr>
        <tr>
            <td>è, é, ẻ, ẽ, ẹ</td>
            <td>è é ẻ ẽ ẹ</td>
        </tr>
        <tr>
            <td>ê, ề, ế, ể, ễ, ệ</td>
            <td>ê ề ế ể ễ ệ</td>
        </tr>
        <tr>
            <td>ì, í, ỉ, ĩ, ị</td>
            <td>ì í ỉ ĩ ị</td>
        </tr>
        <tr>
            <td>ò, ó, ỏ, õ, ọ</td>
            <td>ò ó ỏ õ ọ</td>
        </tr>
        <tr>
            <td>ô, ồ, ố, ổ, ỗ, ộ</td>
            <td>ô ồ ố ổ ỗ ộ</td>
        </tr>
        <tr>
            <td>ơ, ờ, ớ, ở, ỡ, ợ</td>
            <td>ơ ờ ớ ở ỡ ợ</td>
        </tr>
        <tr>
            <td>ù, ú, ủ, ũ, ụ</td>
            <td>ù ú ủ ũ ụ</td>
        </tr>
        <tr>
            <td>ư, ừ, ứ, ử, ữ, ự</td>
            <td>ư ừ ứ ử ữ ự</td>
        </tr>
        <tr>
            <td>ỳ, ý, ỷ, ỹ, ỵ</td>
            <td>ỳ ý ỷ ỹ ỵ</td>
        </tr>
        <tr>
            <td>đ</td>
            <td>đ</td>
        </tr>
    </table>

    <h2>3. Test câu tiếng Việt</h2>
    <ul>
        <li>Hóa đơn mua hàng</li>
        <li>Phiếu thu tiền mặt</li>
        <li>Báo cáo tài chính</li>
        <li>Kế toán trưởng</li>
        <li>Nguyễn Văn A</li>
        <li>Trần Thị B</li>
        <li>Lê Văn C</li>
        <li>Phạm Thị D</li>
        <li>Hoàng Văn E</li>
    </ul>

    <h2>4. Test với variable</h2>
    @php
        $vietnameseText = 'Thanh toán nhà cung cấp Nhung Gối';
        $specialChars = 'àáảãạăằắẳẵặâầấẩẫậêềếểễệôồốổỗộơờớởỡợưừứửữựđ';
    @endphp
    <p>
        <strong>Variable:</strong> {{ $vietnameseText }}<br>
        <strong>Special chars:</strong> {{ $specialChars }}
    </p>

    <h2>5. Test JSON encode</h2>
    @php
        $data = [
            'text' => 'Thanh toán nhà cung cấp',
            'name' => 'Nguyễn Văn A',
            'address' => 'Số 123, đường Lê Lợi, phường Bến Nghé, quận 1, TP.HCM'
        ];
    @endphp
    <pre>{{ json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>

    <h2>6. Test database connection info</h2>
    @php
        $connection = config('database.connections.sqlsrv');
    @endphp
    <pre>
Driver: {{ $connection['driver'] ?? 'N/A' }}
Charset: {{ $connection['charset'] ?? 'N/A' }}
Options: {{ json_encode($connection['options'] ?? [], JSON_PRETTY_PRINT) }}
    </pre>

    <hr>
    <p><em>Nếu tất cả các ký tự trên hiển thị đúng → Blade encoding OK ✅</em></p>
    <p><em>Nếu có ký tự bị lỗi → Kiểm tra charset của file và HTTP headers ❌</em></p>
</body>
</html>

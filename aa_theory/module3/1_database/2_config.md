<style>
    img {
        max-width: 600px;
    }
</style>

# Thiết lập cấu hình kết nối Database Laravel
- Laravel quản lý kết nối database thông qua hai file quan trọng:
    - `.env` – Cấu hình nhanh với biến môi trường.

    - `config/database.php` – Chứa tất cả thông tin kết nối.

## Laravel đọc cấu hình
Khi ứng dụng Laravel khởi chạy:
1. Laravel đọc file config/database.php.

2. Các giá trị trong config/database.php gọi đến .env (thông qua env()).
    - Nếu .env có giá trị, Laravel sẽ sử dụng.

    - Nếu .env không có giá trị, Laravel sẽ dùng giá trị mặc định trong config/database.php.

## Thiết lập trong file `.env`
- Giúp cấu hình database một cách linh hoạt, giúp dễ thay đổi mà không cần sửa code.

- Là cách nhanh chóng và phổ biến nhất để thiết lập database.

```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=database_name
DB_USERNAME=user
DB_PASSWORD=pass
```

## Thiết lập trong file config/database.php
- Laravel cho phép thiết lập kết nối database trong file config/database.php.

- Chúng chứa tất cả thông tin cần thiết lập

- File này hỗ trợ nhiều loại database như:
    - sqlite

    - mysql

    - pgsql

    - sqlsrv

- Ví dụ: Cấu hình MySQL:
    ```php
    'mysql' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'database_name'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => null,
    ],
    ```

- Laravel gọi các biến môi trường từ `.env` bằng `env('DB_HOST')`.

- Nếu biến `.env` không tồn tại, Laravel sẽ dùng giá trị mặc định (ví dụ: '`127.0.0.1`').

- Chúng có gọi đến nội dung các biến môi trường của file .env.
    - Nếu như không muốn thiết lập ở .env thì ta có thể thiết lập trực tiếp ở đây (cần phải xóa giá trị ở .env và đưa giá trị mặc định ứng với key tương ứng ở đây)

- Có thể bỏ qua .env và truyền trực tiếp thông tin vào config/database.php như sau:

    ```php
    'mysql' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'database_name',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => null,
    ],
    ```

    - Không nên làm như vậy vì:
        - Khi thay đổi thông tin database, bạn phải sửa trực tiếp trong code, gây rủi ro bảo mật.

        - Nếu deploy lên môi trường khác (production, staging), bạn phải chỉnh lại code.

- Luôn ưu tiên dùng .env để dễ quản lý.

- Lưu ý phần config:
    - Khi biên dịch sẽ mất thời gian phân giải từ host_name sang IP nên:
        - Phần DB_HOST, ta hạn chế để là localhost, mà phải để địa chỉ IP.

## Laravel hỗ trợ kiến trúc đa máy chủ.
- Laravel hỗ trợ kiến trúc đa máy chủ. Ví dụ tách riêng việc đọc ghi dữ liệu ở hai máy chủ khác nhau

    ```php
    'mysql' => [
        'read' => [
            'host' => '192.168.1.1',
        ],
        'write' => [
            'host' => '196.168.1.2',
        ],
        'driver' => 'mysql',
        'url' => env('DATABASE_URL'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'forge'),
        'username' => env('DB_USERNAME', 'forge'),
        'password' => env('DB_PASSWORD', ''),
        'unix_socket' => env('DB_SOCKET', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => null,
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        ]) : [],
    ],
    ```

- Nhận xét:
    - Ngoài các cấu hình cũ, ta thêm 2 phần tử của mảng là read và write, sau đó dẫn đến một host như trên

## Laravel hỗ trợ kết nối với nhiều CSDL bằng cách sử dụng connection() method
- Giả sử trong trường hợp nào đó ta có 2 csdl mysql, ta sẽ làm như sau:
    - Mở file config/database.php

    - Nhân bản 2 key mysql, đổi tên key. (Tên drive giữ nguyên)

        ```php
        'mysql1' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE1', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'mysql2' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE2', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        ```

    - Dựa vào key của mảng này để ta đưa vào phương thức connection()

        ```php
        $user = DB::connection('connection_name')->select('...');
        ```
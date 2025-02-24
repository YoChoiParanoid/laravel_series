<style>
    img {
        max-width: 600px;
    }
</style>

# Giới thiệu về sử dụng SQL thuần trong Laravel
- Trong PHP Core, bạn thường phải dùng các lệnh SQL trực tiếp để truy vấn database.

- Laravel cũng có thể làm tương tự, nhưng bên cạnh đó, nó còn cung cấp sẵn các hàm, ta chỉ cần đưa câu lệnh SQL thuần vào thôi.
    - `DB::select()`, `DB::insert()`, `DB::update()`, `DB::delete()`,...

- Kết quả trả về tùy thuộc vào hàm chúng ta sử dụng

- Laravel sử dụng binding (?) để ngăn chặn SQL Injection.

```php
use Illuminate\Support\Facades\DB;

// Chạy một câu lệnh SQL thuần
DB::statement("CREATE TABLE users (id INT PRIMARY KEY, name VARCHAR(255), email VARCHAR(255))");

// Lấy dữ liệu
$users = DB::select("SELECT * FROM users WHERE id = ?", [1]);

foreach ($users as $user) {
    echo $user->name;
}

//  Thêm dữ liệu
DB::insert("INSERT INTO users (name, email) VALUES (?, ?)", ['John Doe', 'john@example.com']);

// Cập nhật dữ liệu
DB::update("UPDATE users SET email = ? WHERE id = ?", ['newemail@example.com', 1]);

// Xóa dữ liệu
DB::delete("DELETE FROM users WHERE id = ?", [1]);
```
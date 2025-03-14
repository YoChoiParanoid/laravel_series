# Passing Data to view
### Truyền dữ liệu từ route
- Có thể truyền dữ liệu cho view thông qua tham số thứ hai của hàm `view`.

- `routes/web.php`
```php
Route::get('/home', function () {
    return view('home', ['name' => 'Lê Chí Huy']);
});
```

### Nhận dữ liệu từ view file
- Để nhận giá trị name này thì:
    - Tại view `home` ta chỉ cần khai báo `$name`.

- Để lấy dữ liệu được truyền tới view:
    - Chỉ cần khai báo biến có tên trùng với tên `key` trong mảng dữ liệu.

- `resources/views/home.php`
```php
// ...
<h2>Welcome, <?php echo $name; ?></h2>
```

### with() method
- Có thể sử dụng method `with` để truyền dữ liệu thay cho cách trên:

- Phương thức with này có thể truyền theo 2 cách:
    - Truyền một key: `with($key, $value)`

    - Truyền một mảng dữ liệu: `with($data)`

```php
Route::get('/home', function () {
    return view('home')->with('name', 'Lê Chí Huy');
});
```

### share() method
- Có thể truyền dữ liệu cho tất cả các view có trong source code với method share trong view facade.

- Phương thức này sẽ được khi báo ở `app/Providers/AppServiceProvider` tại method `boot()`

```php
public function boot()
{
    View::share('key', 'value');
}
```

- Tất cả các file view đều có thể gọi $key với giá trị là value.
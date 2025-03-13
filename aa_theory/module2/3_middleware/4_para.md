# Tham số middleware
- Middleware có thể nhận các tham số tùy chọn.

- Ví dụ:
    - Mình muốn trước khi xử lý request `/post` thì phải kiểm tra xem user có phải là tác giả bài viết không.

    - Nếu có thì mới xuất ra màn hình.

- Đầu tiên ta khởi tạo một middle Role bằng lệnh Artisan:

```
php artisan make:middleware Role
```

- Đăng ký middleware vừa tạo tại `$routeMiddleware`

```php
protected $routeMiddleware = [
    // ...
    
    'role' => \App\Http\Middleware\Role::class,
];
```

- Định nghĩa route:

```php
Route::get('/post', function () {
    return 'Body post';
})->middleware('role:editor');
```

- Tại method middleware:
    - Tham số truyền vào không phải là tên `alias` của route middleware nữa mà có thêm dấu `:` cùng với giá trị `editor`

    - Ta đã truyền thành công tham số vào middleware Role

    - Việc cần làm cuối cùng là lấy giá trị `editor` đó ở middleware và code xử lý thôi.

- Code tham khảo ở middleware Role:
```php
public function handle($request, Closure $next, $role)
{
    if ($role != 'editor') {
        return response('Bạn không đủ quyền truy cập');
    }

    return $next($request);
}
```

- Tại method handle, ta sẽ khai báo thêm `$role` để nhận giá trị tham số được truyền từ route.

- Việc còn lại là test logic
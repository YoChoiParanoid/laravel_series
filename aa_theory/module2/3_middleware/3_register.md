# Đăng ký Middleware
- Laravel cho phép đăng ký các middleware ở file app/Http/Kernel.php.

- Lưu ý:
    - Phải thêm dấu \ trước namespace mỗi class khi khai báo.
    
    - Vì các middleware không cùng cấp với App\Http\Kernel:class.

## Global Middleware
- Sẽ được autoload khi có một HTTP request gửi đến, không cần phải khai báo ở route.

- Có thể liệt kê danh sách các global middleware ở $middleware.

```php
protected $middleware = [
    // \App\Http\Middleware\TrustHosts::class,
    \App\Http\Middleware\TrustProxies::class,
    \Illuminate\Http\Middleware\HandleCors::class,
    \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
    \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
    \App\Http\Middleware\TrimStrings::class,
    \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
];
```

- Trên đây, Laravel đã liệt kê sẵn một số middleware cốt lõi:
    - `CheckForMaintenanceMode`: kiểm tra xem có đang ở chế độ bảo trì

    - `TrimStrings`: trim string các request

    - `ConvertEmptyStringsToNull`: chuyển chuỗi trống sang null

    - ...

## Route Middleware
- Nếu global middleware được load sau mỗi request thì đối với route middleware chỉ được gọi khi request đi vào route tương ứng.

- Mặc định, các route middleware được liệt kê ở `$routeMiddleware`.

- cấu trúc mảng nó có khác biệt so với `$middleware`.

```php
protected $routeMiddleware = [
    'auth' => \App\Http\Middleware\Authenticate::class,
    'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
    'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
    'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
    'can' => \Illuminate\Auth\Middleware\Authorize::class,
    'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
    'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
];
```

- Cú pháp này khá giống trong mảng `aliases` ở `config/app.php`

- Ta có thể alias các namespace dài này thành nhưng tên ngắn gọn để dễ dàng đăng ký ở route.

- Thay vì đăng ký như thế này:

```php
use App\Http\Middleware\CheckAge;

Route::get('age/{age}', function ($age) {
    return $age;
})->middleware(CheckAge::class);
```

- Ta có thể:

```php
Route::get('age/{age}', function ($age) {
    return $age;
})->middleware('CheckAge');
```

- Khai báo middleware `CheckAge` vào trong `$routeMiddleware`

```php
protected $routeMiddleware = [
    // ..
    
    'CheckAge' => \App\Http\Middleware\CheckAge::class,
];
```

## Group Middleware
- Gom các middleware một nhóm dưới dạng key chung để dễ dàng đăng ký cho route.

- Tất cả việc bạn cần làm là khai báo chúng trong $middlewareGroups.

```php
/**
 * The application's route middleware groups.
 *
 * @var array
 */
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],

    'api' => [
        'throttle:60,1',
        'auth:api',
    ],
];
```

- Laravel cung cấp sẵn cho chúng ta 2 nhóm middleware đó là web và api.
    - web: giao diện người dùng

    - api: REST API

- Cú pháp đăng ký nhóm middleware cho route cũng tương tự như đăng ký một middleware riêng lẻ.

```php
Route::get('/', function () {
    //
})->middleware('web');

Route::group(['middleware' => ['web']], function () {
    //
});
```

- Lưu ý:
    - Mặc định `RouteServiceProvider` đã đăng ký middleware web cho tất cả các route trong routes/web.php.

## Sorting Middleware
- Đôi khi các middleware của bạn cần được gọi theo thứ tự, nhưng khi đăng ký trong route lại không có tác vụ này.

- Laravel cung cấp cho chúng ta `$middlewarePriority` để sort các middleware theo thứ tự ưu tiên xử lý từ trên xuống.

```php
/**
 * The priority-sorted list of middleware.
 *
 * This forces non-global middleware to always be in the given order.
 *
 * @var array
 */
protected $middlewarePriority = [
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    \App\Http\Middleware\Authenticate::class,
    \Illuminate\Session\Middleware\AuthenticateSession::class,
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
    \Illuminate\Auth\Middleware\Authorize::class,
];
```
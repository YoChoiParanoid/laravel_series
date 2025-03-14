# Đăng ký view composer
- Đăng ký view composer trong service container như là một service provider.

- Đầu tiên ta khởi tạo provider `ViewComposerProvider` với lệnh `Artisan` sau:

```
php artisan make:provider ViewComposerProvider
```

- Liệt kệ `ViewComposerProvider` vừa khởi tạo trong mảng `providers` ở `config/app.php`.

```php
'providers' => [
    // ..
    App\Providers\ViewComposerProvider::class,
],
```

- Mở file `app/Providers/ViewComposerProvider.php` lên và thay đổi nội dung nó thành:

```php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Registering composer with Class
        View::composer(
            'profile', 'App\Http\View\Composers\ProfileComposer'
        );

        // Registering composer with Closure
        View::composer('dashboard', function ($view) {
            //
        });
    }
}
```

## Phân tích đoạn code:
- Use `View` facade vào class này để có thể thực hiện composer

- Nếu việc binding được viết ở method `register` thì `composer` lại được viết ở `boot`.

- Quan sát đoạn code trong method boot, sẽ thấy có hai cách để đăng ký view composer.

### Đăng ký composer với class
- Ở cách 1
    - Tham số thứ nhất đó chính là tên của view cần truyền dữ liệu.

    - Tham số thứ hai sẽ chứa namespace class thực hiện việc compose

- Mặc định Laravel không tạo thư mục chứa các file composer mà để chúng ta tự tổ chức.

- Có thể tạo cấp thư mục như sau để chứa các composer:
```
app/
├── Http/
|   ├── View/
|   |   ├── Composers/
|   |   |   |   ProfileComposer.php 
```

- Trong file `ProfileComposer.php`:

```php
<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;

class ProfileComposer
{
    public function compose(View $view)
    {
        $view->with('name', 'Lê Chí Huy');
    }
}
```

- Bắt buộc trong file composer phải định nghĩa method compose

- Và inject cho nó class Illuminate\View\View

- Từ đó ta mới có thể dùng phương thức with để truyền dữ liệu cho view đã đăng ký ở ViewComposerProvider.

- Cách hoạt động của hình thức đăng ký composer với class:
    - Khi một view tương ứng chuẩn bị render thì:
        - Gọi phương thức compose của class đã đăng ký chung với view đó.
        
    - Trong trường hợp trên thì khi view profile sắp render thì `ProfileComposer@compose` sẽ được thực thi để truyền các dữ liệu.

- Tạo thử view `resources/views/profile.php`:

```
<h1>Profile <?php echo $name ?></h1>
```

- Đăng ký route để render view `profile`:

```php
Route::get('/profile', function () {
    return view('profile');
});
```

- Ta không truyền bất cứ dữ liệu nào vào hàm view cả.

- Thử nạp server và kết quả thu được là:

![alt text](image/image-1.png)

- Lưu ý:
    - Tất cả các file composer đều resolve thông qua service container

    - Vì vậy ta có thể type-hint bất kỳ dependency class nào cần thiết trong phương thức `__construct`.

### Đăng ký composer với closure
- Ngắn gọn hơn so với đăng ký theo class vì chúng ta không cần phải khởi tạo file composer mà chỉ cần truyền dữ liệu trực tiếp thông qua Closure.

- Tạo view `resources/views/dashboard.php` với nội dung:
```php
<h1>Welcome, <?php echo $name; ?></h1>
```

- Trong `app/Providers/ViewComposerProvider.php`

- Truyền dữ liệu cho view dashboard thông qua $view của Closure.
```php
View::composer('dashboard', function ($view) {
    $view->with('name', 'Lê Chí Huy');
});
```

- Đăng ký route, `routes/web.php`
```php
Route::get('/', function () {
    return view('dashboard');
});
```

- Nạp server và kiểm chứng

- Có thể đăng ký một view composer cho nhiều view khác nhau bằng cách:
    - Chuyển tham số thứ nhất về dạng mảng và liệt kê các view cần thiết.

```php
View::composer(
    ['profile', 'dashboard'],
    'App\Http\View\Composers\ProfileComposer'
);
```

- Nếu muốn tất cả các view đều được truyền một dữ liệu chung:
    - Có thể sử dụng ký tự `*` để thay thế tên view
    
    - Framework sẽ hiểu là ta đã chọn tất cả các view đang có trong source code.

```php
View::composer('*', function ($view) {
    //
});
```

### Lựa chọn từng cách đăng ký
- Ý kiến riêng
    - Đối với các view mà sẽ nhận dữ liệu từ database thì nên đăng ký composer theo class để dễ dàng type-hint các dependency class cần thiết cho việc lấy dữ liệu.

    - Nếu chỉ là truyền một số dữ liệu đơn giản, thao tác nhanh thì nên cân nhắc để chọn cách đăng ký composer theo closure để giảm tốn thời gian, rườm rà.


# Một số phương thức view
- Cũng như các component khác, `View` facade cung cấp cho chúng ta một số method để có thể tương tác với component này.

### Determining if a view exists
- Kiểm tra xem view có tồn tại hay không.

- View facade cung cấp cho chúng ta method exists để làm điều này

- Nếu tồn tại view sẽ trả về true.

```php
use Illuminate\Support\Facades\View;

if (View::exists('admin.setting')) {
    //
}
```

### The first available view
- Có thể tùy chỉnh thứ tự xuất hiện một tập hợp cái template thông qua method `first()`.

- Method này sẽ nhận một mảng các view được ưu tiên xuất hiện theo thứ tự từ trái qua phải

```php
Route::get('/', function () {
    return view()->first(['no_exist', 'welcome']);
});
```

- Ở đây global helper function view sẽ trả về một `object` nên ta có thể tham chiếu tiếp đến method `first`.

- View `no_exist` không hề tồn tại trong source code, đang ở phần tử đầu tiên trong mảng.

- Laravel sẽ lần lượt kiểm tra các view này từ trái qua phải:
    - Nếu view nào không tồn tại thì nó sẽ bỏ qua.
    
- Sau khi chạy [đường dẫn](http://127.0.0.1:8000) thì chúng ta nhận được view welcome.

- Có thể sử dụng `View` facade để thay cho cú pháp trên

```php
use Illuminate\Support\Facades\View;

return View::first(['no_exist', 'welcome']);
```

- Thông thường ta hay ứng dụng phương thức này cho các hệ thống có thể tùy chỉnh hoặc ghi đè giao diện.
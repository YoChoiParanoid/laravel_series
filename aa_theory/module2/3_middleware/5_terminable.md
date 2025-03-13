# Terminable Middleware
- Đôi khi một middleware cần thực hiện công việc gì đó sau khi HTTP response được trả về.

- Ví dụ:
    - middleware session trong Laravel lưu dữ liệu session sau khi response được trả về trình duyệt.

- Nếu định nghĩa phương thức terminate trong middleware và server web có sử dụng FastCGI (tìm hiểu trên google)
    - terminate sẽ tự động được gọi sau khi response được trả về.

```php
<?php

namespace Illuminate\Session\Middleware;

use Closure;

class StartSession
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        // Store the session data...
    }
}
```

- Phương thức terminate này sẽ nhận cả request và response.

- Khi đã khởi tạo một terminable middleware, nên đăng ký nó ở route hoặc global middleware.

- Khi gọi method terminate trong middleware:
    - framework sẽ resolve một middleware instance mới từ service container.
    
    - Nếu muốn sử dụng một middleware instance khi các method handle và terminate được gọi thì nên đăng ký middleware với container thông qua method singleton.
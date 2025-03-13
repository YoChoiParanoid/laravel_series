# Giới thiệu về Middleware Laravel
- Cung cấp cơ chế dễ dàng để có thể lọc các request HTTP từ ứng dụng.

### Ví dụ:
- Ứng dụng có chức năng login.

- Khi client vào đường dẫn `/setting` thì hệ thống sẽ kiểm tra xem đã tồn tại session/cookie user chưa (tức là đã đăng nhập hay chưa).
    - Nếu rồi thì tiếp tục cho request thực thi và xuất ra trang `setting`.

    - Nếu không thì redirect về `/login`.

- Công việc này sẽ do các middleware đảm nhận.

- Các core middeware của Laravel, kể cả các middleware tự tạo đều nằm trong thư mục app/Http/Middleware
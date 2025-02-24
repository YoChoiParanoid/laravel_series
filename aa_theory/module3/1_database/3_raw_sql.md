# Raw Query
- Là việc dùng câu lệnh SQL thuần để thực hiện truy vấn dữ liệu trong Database

- Để sử dụng Raw Query trong Laravel, ta sử dụng lớp Facade DB

- Lớp DB cung cấp các phương thức để truy vấn database:
    - insert()

    - select()

    - update()

    - delete()

    - statement()

    - ...

- Lớp này sử dụng namespace, nên trước khi dùng ta phải use nó

## Phương thức select()
- Trả về một mảng

- Mỗi kết quả trong mảng là một đối tượng stdClass trong PHP

## Phương thức insert()
- Phương thức này sẽ thực hiện thêm dữ liệu vào Database

    ```php
    DB::insert('INSERT INTO user (id, name) VALUES (?, ?)', [1, 'Unicode Academy']);
    ```
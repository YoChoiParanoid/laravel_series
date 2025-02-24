<style>
    img {
        max-width: 600px;
    }
</style>

# Ví dụ về thức select()
## Bước 1
- Tạo view `resources/views/clients/home.blade.php`, copy đoạn mã sau vào:
    ```php
    <div>
        <h2><?php echo $title ?></h2>
        <h3><?php echo $message ?></h3>
    </div>
    ```

- Tạo basic controller HomeController

    ![alt text](image/image10.png)

    ```php
    <?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    class HomeController extends Controller
    {
        //
    }
    ```

- Thêm đoạn code sau đây vào file `app/Http/controllers/HomeController.php`
    ```php
    <?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    class HomeController extends Controller
    {
        public $data = [];
        public function index() {
            $this->data['title'] = 'Đào tạo lập trình web';

            $this->data['message'] = 'Đăng ký tài khoản thành công';

            return view('clients.home', $this->data);
        }
    }
    ```

- Đăng ký route tại `routes/web.php`
    ```php
    <?php

    use Illuminate\Support\Facades\Route;
    use namespace App\Http\Controllers\HomeController;

    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider and all of them will
    | be assigned to the "web" middleware group. Make something great!
    |
    */

    Route::get('home', [HomeController::class, 'index']);
    ```

- Kết quả thu được:

    ![alt text](image/image11.png)

## Bước 2:
- Thêm namespace DB cho controller `app/Http/Controllers/HomeController.php` để sử dụng lớp
    ```php
    use Illuminate\Support\Facades\DB;
    ```

    - hoặc

    ```php
    use DB;
    ```

- Tạo bảng trong phpMyAdmin:

    ![alt text](image/image12.png)

    ![alt text](image/image13.png)

- Insert dữ liệu thủ công

    ![alt text](image/image14.png)

- Test phương thức `select()` với 1 tham số
    - Dán code này vào file app/Http/Controllers/HomeController.php
        ```php
        <?php

        namespace App\Http\Controllers;

        use Illuminate\Http\Request;

        use Illuminate\Support\Facades\DB;

        class HomeController extends Controller
        {
            public $data = [];
            public function index() {
                $this->data['title'] = 'Đào tạo lập trình web';

                $this->data['message'] = 'Đăng ký tài khoản thành công';

                $user = DB::select('select * from user');

                dd($user);

                // return view('clients.home', $this->data);
            }
        }
        ```

    - Kết quả thu được:

        ![alt text](image/image15.png)

    - Nhận xét: trả về một mảng, mỗi phần tử trong mảng là một đối tượng. Nên ta không thể gọi dữ liệu theo cách thông thường là ngoặc vuông của mảng. Ta phải sử dụng cách gọi của object.


    - Insert thêm 3 dữ liệu nữa:

        ![alt text](image/image16.png)

    - Tải lại trang, kết quả thu được:

        ![alt text](image/image17.png)

- Test phương thức `select()` với 1 tham số (chuỗi truy vấn) và có điều kiện
    - Thay đổi đoạn code:
        ```php
        $user = DB::select('SELECT * FROM user WHERE id > 1');
        ```

    - Kết quả thu được

        ![alt text](image/image18.png)

- Test phương thức `select()` với 2 tham số (chuỗi truy vấn và tham số truyền vào chuỗi đó) và có điều kiện
    - Thay đổi đoạn code:
        ```php
        $user = DB::select('SELECT * FROM user WHERE id > ?', [2]);
        ```

    - Kết quả thu được

        ![alt text](image/image19.png)

    - Nhận xét:
        - Cách truyền dữ liệu trên khác giống truyền PDO. Laravel cũng dùng PDO

- Truyền tham số cho chuỗi truy vấn dưới dạng có key
    - Thay đổi đoạn code:
        ```php
        $user = DB::select('SELECT * FROM user WHERE email=:mail',[
            'mail' => 'hoaiphu.web@gmail.com',
        ]);
        ```

    - Kết quả thu được:
        
        ![alt text](image/image20.png)
# Triển khai website

## Trước khi triển khai

- Chỉ đẩy mã nguồn; không đẩy `.env`, mật khẩu hay khóa API. `.env` trên máy cá nhân được Git bỏ qua và Docker loại khỏi image.
- Nếu khóa SheetDB hoặc mật khẩu admin từng nằm trong Git, tạo giá trị mới rồi cập nhật trong Render.
- Kiểm tra các liên kết, ảnh, thông tin liên hệ và nội dung trong SheetDB trước khi công bố.

## Cấu hình Render

Service dùng Docker và lắng nghe cổng 10000. Trong **Environment**, đặt:

| Tên | Nội dung |
| --- | --- |
| `SHEETDB_API_LINK` | URL gốc của API SheetDB đang dùng |
| `ADMIN_PASSWORD` | Mật khẩu admin mới, dài và riêng cho website này |

`ADMIN_PASSWORD_HASH` vẫn được hỗ trợ nếu không đặt `ADMIN_PASSWORD`.

Các trang dùng SheetDB có bản sao dữ liệu công khai tại `data/sheets/` để tiếp tục hiển thị khi API hoặc biến `SHEETDB_API_LINK` thiếu. Bản sao chỉ phản ánh nội dung tại thời điểm lưu vào Git; đặt `SHEETDB_API_LINK` trong Render để nhận nội dung mới nhất.

Không lưu nội dung quản trị trong filesystem của Render Free để sử dụng lâu dài: file ghi trên instance sẽ mất khi service ngủ, khởi động lại hoặc deploy. Cấu hình Supabase trước khi bắt đầu nhập dữ liệu thật.

## Lưu dữ liệu và ảnh trên Render Free bằng Supabase

1. Tạo project Supabase. Trong **SQL Editor**, chạy nội dung `supabase-setup.sql`.
2. Trong **Storage**, tạo bucket công khai `site-media`, chỉ cho phép JPG, PNG, WebP và GIF, mỗi file tối đa 5 MB.
3. Trong **Render → Environment**, đặt `SUPABASE_URL` và `SUPABASE_SECRET_KEY` lấy từ Supabase. Chỉ dùng **secret key** phía máy chủ; không đưa key vào mã nguồn, trình duyệt hay URL.
4. Deploy lại. Lần đọc đầu tiên sẽ sao chép dữ liệu mẫu `data/posts.json`, `data/team.json`, `data/settings.json` vào Supabase nếu chưa có hàng tương ứng. Ảnh admin tải lên sau đó sẽ vào Storage.
5. Kiểm tra thêm, sửa, xóa một hồ sơ thử và một bài thử; sau khi deploy lại, xác nhận chúng vẫn còn.

Nếu chỉ đặt một trong hai biến Supabase, ứng dụng sẽ báo lỗi cấu hình thay vì ghi dữ liệu tạm lên Render. Supabase Free cũng có giới hạn và có thể tạm dừng project ít hoạt động; cần theo dõi và sao lưu dữ liệu nếu website được dùng thật.

Nếu chuyển sang Render trả phí và gắn persistent disk tại `/var/data`, đặt thêm:

| Tên | Giá trị |
| --- | --- |
| `APP_DATA_DIR` | `/var/data/content` |
| `APP_UPLOAD_DIR` | `/var/data/uploads` |

Ứng dụng sẽ sao chép dữ liệu mẫu từ `data/` vào disk khi file chưa tồn tại. Ảnh mới được phục vụ qua `media.php`.

## Gắn tên miền sau khi đã có tên miền

1. Thêm tên miền tại **Render → service → Settings → Custom Domains**.
2. Tạo bản ghi DNS theo đúng giá trị Render hiển thị cho tên miền cụ thể.
3. Bấm **Verify** và đợi HTTPS hoạt động.
4. Chọn `www` hoặc tên miền gốc làm địa chỉ chính, kiểm tra địa chỉ còn lại chuyển hướng đúng.
5. Kiểm tra trang chủ, Blog, Giới thiệu, Sinh viên, ảnh, các trang chi tiết và admin qua tên miền mới.

Render tự cấp chứng chỉ HTTPS cho tên miền đã xác minh. Đừng mua hoặc thay đổi DNS khi chưa có tên miền và quyền quản lý DNS.

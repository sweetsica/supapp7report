# Log Cập Nhật Sản Phẩm

---

## [2026-04-29] File Manager Dashboard — Quản lý file dạng Windows Explorer / Google Drive

### Mô tả
Xây dựng giao diện quản trị file tại `/dashboard` với đầy đủ chức năng CRUD, cấu trúc thư mục dạng cây, hỗ trợ ẩn/hiện file, xem thông tin chi tiết (kích thước, loại tệp, chủ sở hữu, thời gian).

### Files đã tạo mới

| File | Mô tả |
|------|-------|
| `database/migrations/2026_04_29_000000_add_file_manager_fields_to_report_uploads_table.php` | Migration thêm cột `parent_id`, `is_folder`, `is_hidden`, `size` vào bảng `report_uploads` |
| `app/Http/Controllers/FileManagerController.php` | Controller quản lý file: list, upload, tạo folder, đổi tên, di chuyển, ẩn/hiện, xóa, xem info, download, thống kê |
| `resources/views/dashboard.blade.php` | Giao diện File Manager (Tailwind CSS + Alpine.js) |

### Files đã chỉnh sửa

| File | Thay đổi |
|------|----------|
| `app/Models/ReportUpload.php` | Thêm fillable (`parent_id`, `is_folder`, `is_hidden`, `size`), casts, relationships (`parent`, `children`), scopes (`folders`, `files`, `visible`, `inFolder`), accessors (`formatted_size`, `breadcrumb`) |
| `app/Http/Controllers/Api/ReportUpload.php` | Thêm `size => $fileSize` vào `store()` để lưu kích thước file khi upload qua API cũ |
| `routes/web.php` | Thêm route `/dashboard` và group `/fm/*` (11 endpoints cho file manager) |

### API Endpoints (prefix `/fm`)

| Method | URI | Chức năng |
|--------|-----|-----------|
| GET | `/fm/files?parent_id=&show_hidden=&search=` | Danh sách file/folder |
| GET | `/fm/folder-tree` | Cây thư mục cho sidebar |
| POST | `/fm/folder` | Tạo thư mục mới |
| POST | `/fm/upload` | Upload file (hỗ trợ nhiều file) |
| PUT | `/fm/rename/{id}` | Đổi tên |
| PUT | `/fm/move/{id}` | Di chuyển file/folder |
| PUT | `/fm/toggle-visibility/{id}` | Ẩn/hiện |
| DELETE | `/fm/delete/{id}` | Xóa (recursive nếu là folder) |
| GET | `/fm/info/{id}` | Thông tin chi tiết |
| GET | `/fm/download/{id}` | Tải file |
| GET | `/fm/stats` | Thống kê tổng (số file, folder, dung lượng) |

### Tính năng UI Dashboard

- **Sidebar trái**: Cây thư mục 3 cấp, giống Windows Explorer
- **Grid / List view**: Chuyển đổi hiển thị dạng lưới hoặc danh sách
- **Breadcrumb**: Điều hướng theo đường dẫn thư mục
- **Context menu (chuột phải)**: Mở, xem, đổi tên, ẩn/hiện, xóa, copy link, tải xuống
- **Panel thông tin (phải)**: Kích thước, loại file, chủ sở hữu (token), ngày tạo/cập nhật, URL
- **Upload**: Modal upload + kéo thả trực tiếp vào vùng nội dung, progress bar
- **Tìm kiếm**: Tìm file/folder theo tên
- **Ẩn/hiện**: Toggle visibility từng file, checkbox hiển thị file ẩn
- **Preview**: Xem trước ảnh, video, PDF trong modal
- **Toast**: Thông báo thao tác thành công/lỗi

### Database Schema thay đổi (bảng `report_uploads` → `vaults`)

| Cột | Kiểu | Mô tả |
|-----|------|-------|
| `parent_id` | unsignedBigInteger, nullable | FK tự tham chiếu, tạo cấu trúc thư mục |
| `is_folder` | boolean, default false | Phân biệt file và thư mục |
| `is_hidden` | boolean, default false | Trạng thái ẩn/hiện |
| `size` | bigInteger, nullable | Kích thước file (bytes) |

---

## [2026-04-29] Large Icons View — Xem trước hình ảnh dạng thumbnail lớn

### Thay đổi
- Thêm chế độ hiển thị **Large Icons** (mặc định) với thumbnail ảnh lớn `aspect-square`
- Video hiển thị poster + overlay nút play
- Grid view cũng hiển thị thumbnail nhỏ cho ảnh (12x12)
- Thêm nút chuyển đổi 3 chế độ: Large Icons / Grid / List

### File chỉnh sửa
| File | Thay đổi |
|------|----------|
| `resources/views/dashboard.blade.php` | Thêm Large Icons view, cập nhật Grid view có thumbnail, thêm nút view mode |

---

## [2026-04-29] Rename Module: report → vault

### Mô tả
Đổi tên toàn bộ module lưu trữ từ "report" sang "vault" — chuyên nghiệp hơn, phù hợp với hệ thống storage.

### Thay đổi tổng quan

| Trước | Sau |
|-------|-----|
| Bảng `report_uploads` | Bảng `vaults` |
| Model `ReportUpload` | Model `Vault` |
| Controller `Api\ReportUpload` | Controller `Api\VaultController` |
| Storage path `public/report/` | Storage path `public/vault/` |
| API route `/api/report/*` | API route `/api/vault/*` |

### Files đã tạo mới

| File | Mô tả |
|------|-------|
| `database/migrations/2026_04_29_140000_rename_report_uploads_to_vaults.php` | Migration đổi tên bảng `report_uploads` → `vaults` |
| `app/Models/Vault.php` | Model chính mới, chứa toàn bộ logic (fillable, casts, relationships, scopes, accessors) |
| `app/Http/Controllers/Api/VaultController.php` | API controller mới thay thế `ReportUpload`, dùng model `Vault`, storage path `vault/` |

### Files đã chỉnh sửa

| File | Thay đổi |
|------|----------|
| `app/Models/ReportUpload.php` | Chuyển thành alias: `class ReportUpload extends Vault` (backward compatible) |
| `app/Http/Controllers/FileManagerController.php` | Đổi `use ReportUpload` → `use Vault`, tất cả `ReportUpload::` → `Vault::`, storage path `report/` → `vault/`, validation `exists:vaults,id` |
| `routes/api.php` | Đổi `use ReportUpload` → `use VaultController`, routes `/report/*` → `/vault/*` |
| `routes/web.php` | Đổi upload route sang `VaultController::store` |

### API Endpoints (cập nhật)

| Method | URI | Chức năng |
|--------|-----|-----------|
| GET | `/api/vault/getfile` | Lấy link file |
| POST | `/api/vault/upload` | Upload file qua API |

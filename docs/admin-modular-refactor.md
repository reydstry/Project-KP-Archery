# Admin Modular Refactor (Laravel 12)

## Scope
Refactor hanya untuk area Admin. Tidak mengubah:
- Public site
- Coach module
- Member module (non-admin)
- Database schema
- Business logic
- Eloquent models
- Existing tests

## 1) Struktur Folder Admin Final (Tree)

### App Modules

```text
app/
└── Modules/
    └── Admin/
        ├── Dashboard/
        │   ├── Controllers/
        │   ├── Services/
        │   └── Requests/
        ├── Member/
        │   ├── Controllers/
        │   │   ├── MemberController.php
        │   │   └── MemberPageController.php
        │   ├── Services/
        │   │   └── MemberManagementService.php
        │   ├── Requests/
        │   │   ├── StoreMemberRequest.php
        │   │   └── UpdateMemberRequest.php
        │   └── DTO/
        ├── Training/
        │   ├── Controllers/
        │   ├── Services/
        │   └── Requests/
        ├── Attendance/
        │   ├── Controllers/
        │   ├── Services/
        │   └── Requests/
        ├── Package/
        │   ├── Controllers/
        │   ├── Services/
        │   └── Requests/
        ├── Coach/
        │   ├── Controllers/
        │   ├── Services/
        │   └── Requests/
        ├── WhatsApp/
        │   ├── Controllers/
        │   ├── Services/
        │   └── Requests/
        └── Report/
            ├── Controllers/
            ├── Services/
            ├── Requests/
            └── DTO/
```

### Views

```text
resources/views/admin/
├── app.blade.php
├── dashboard/
│   ├── dashboard.blade.php
│   ├── news.blade.php
│   └── achievements.blade.php
├── member/
│   ├── members.blade.php
│   └── member-packages.blade.php
├── training/
│   ├── training-sessions.blade.php
│   ├── training-sessions-create.blade.php
│   ├── training-sessions-edit-meta.blade.php
│   └── slots-coach-assignment.blade.php
├── attendance/
│   ├── attendance-management.blade.php
│   └── sessions-attendance-input.blade.php
├── package/
│   └── packages.blade.php
├── coach/
│   └── coaches.blade.php
├── whatsapp/
│   ├── wa-blast.blade.php
│   ├── wa-logs.blade.php
│   ├── wa-api-settings.blade.php
│   └── reminder-settings.blade.php
└── report/
    ├── monthly-recap.blade.php
    └── export-excel.blade.php
```

## 2) Mapping File Lama → Lokasi Baru

### Controllers (Admin)
- `app/Http/Controllers/Admin/MemberController.php` → `app/Modules/Admin/Member/Controllers/MemberController.php` (DONE, legacy class jadi shim kompatibilitas)
- `app/Http/Controllers/Admin/MemberPackageController.php` → `app/Modules/Admin/Member/Controllers/MemberPackageController.php` (DONE, wrapper kompatibilitas)
- `app/Http/Controllers/Admin/AdminUiController.php` → deprecated (dipertahankan untuk transisi; route web sudah pakai page controller modular)
- `app/Http/Controllers/Admin/DashboardController.php` → `app/Modules/Admin/Dashboard/Controllers/DashboardController.php` (DONE, wrapper kompatibilitas)
- `app/Http/Controllers/Admin/NewsController.php` → `app/Modules/Admin/Dashboard/Controllers/NewsController.php` (DONE, wrapper kompatibilitas)
- `app/Http/Controllers/Admin/AchievementController.php` → `app/Modules/Admin/Dashboard/Controllers/AchievementController.php` (DONE, wrapper kompatibilitas)
- `app/Http/Controllers/Admin/TrainingSessionController.php` → `app/Modules/Admin/Training/Controllers/TrainingSessionController.php` (DONE, wrapper kompatibilitas)
- `app/Http/Controllers/Admin/AttendanceController.php` → `app/Modules/Admin/Attendance/Controllers/AttendanceController.php` (DONE, wrapper kompatibilitas)
- `app/Http/Controllers/Admin/PackageController.php` → `app/Modules/Admin/Package/Controllers/PackageController.php` (DONE, wrapper kompatibilitas)
- `app/Http/Controllers/Admin/CoachController.php` → `app/Modules/Admin/Coach/Controllers/CoachController.php` (DONE, wrapper kompatibilitas)
- `app/Http/Controllers/Admin/WhatsAppController.php` → `app/Modules/Admin/WhatsApp/Controllers/WhatsAppController.php` (DONE, wrapper kompatibilitas)
- `app/Http/Controllers/Admin/WhatsAppSettingsController.php` → `app/Modules/Admin/WhatsApp/Controllers/WhatsAppSettingsController.php` (DONE, wrapper kompatibilitas)
- `app/Http/Controllers/Admin/ReminderSettingsController.php` → `app/Modules/Admin/WhatsApp/Controllers/ReminderSettingsController.php` (DONE, wrapper kompatibilitas)
- `app/Http/Controllers/Admin/ReportController.php` → `app/Modules/Admin/Report/Controllers/ReportController.php` (DONE, wrapper kompatibilitas)

### Services (Admin)
- `app/Services/Admin/MemberManagementService.php` → `app/Modules/Admin/Member/Services/MemberManagementService.php` (DONE, legacy class jadi shim kompatibilitas)
- `app/Services/Admin/AdminDashboardService.php` → `app/Modules/Admin/Dashboard/Services/AdminDashboardService.php` (DONE, wrapper kompatibilitas)
- `app/Services/Admin/ContentManagementService.php` → `app/Modules/Admin/Dashboard/Services/ContentManagementService.php` (DONE, wrapper kompatibilitas)
- `app/Services/Admin/TrainingManagementService.php` → `app/Modules/Admin/Training/Services/TrainingManagementService.php` (DONE, wrapper kompatibilitas)
- `app/Services/Admin/AttendanceService.php` → `app/Modules/Admin/Attendance/Services/AttendanceService.php` (DONE, wrapper kompatibilitas)
- `app/Services/Admin/PackageManagementService.php` → `app/Modules/Admin/Package/Services/PackageManagementService.php` (DONE, wrapper kompatibilitas)
- `app/Services/Admin/CoachManagementService.php` → `app/Modules/Admin/Coach/Services/CoachManagementService.php` (DONE, wrapper kompatibilitas)
- `app/Services/Admin/WhatsAppService.php` → `app/Modules/Admin/WhatsApp/Services/WhatsAppService.php` (DONE, wrapper kompatibilitas)
- `app/Services/Admin/ReportService.php` → `app/Modules/Admin/Report/Services/ReportService.php` (DONE, wrapper kompatibilitas)

### Requests (Member Domain)
- Validasi inline di MemberController lama →
  - `app/Modules/Admin/Member/Requests/StoreMemberRequest.php`
  - `app/Modules/Admin/Member/Requests/UpdateMemberRequest.php`

### Views (Admin)
Semua view di `resources/views/components/dashboards/admin/*` telah disalin ke `resources/views/admin/*` sesuai domain.

## 3) Contoh Refactor Controller (Domain Member)

- Controller modular baru: `App\Modules\Admin\Member\Controllers\MemberController`
- Service modular baru: `App\Modules\Admin\Member\Services\MemberManagementService`
- Request modular baru: `StoreMemberRequest`, `UpdateMemberRequest`
- Legacy kompatibilitas:
  - `app/Http/Controllers/Admin/MemberController.php` mewarisi controller modular
  - `app/Services/Admin/MemberManagementService.php` mewarisi service modular

Dengan pola ini, behavior endpoint tetap sama, tapi struktur menjadi modular per domain.

## 4) Contoh Route Group Baru (Admin + Member Domain)

### API (`routes/api.php`)
- Tetap di bawah `prefix('admin')` + `middleware('role:admin')`
- Import controller admin API telah diarahkan ke namespace modular `App\Modules\Admin\...\Controllers\...`
- Endpoint `news` dan `achievements` kini juga memakai controller modular dashboard.
- Endpoint member package (`member-packages`, `assign-package`, `members/{member}/packages`) kini memakai controller modular member.
- Member endpoint digroup di `prefix('members')` dengan URI tetap:
  - `GET /api/admin/members`
  - `POST /api/admin/members`
  - `GET /api/admin/members/{member}`
  - `PUT/PATCH /api/admin/members/{member}`
  - `DELETE /api/admin/members/{member}`
  - `POST /api/admin/members/{id}/restore`

### Web (`routes/web.php`)
- Tetap di bawah `prefix('admin')` + `middleware('role:admin')`
- Ditambahkan subgroup member:
  - `/admin/member/members`
  - `/admin/member/packages`
- Binding page controller admin sudah dipisah per domain modular:
  - Training: `App\Modules\Admin\Training\Controllers\TrainingPageController`
  - Attendance: `App\Modules\Admin\Attendance\Controllers\AttendancePageController`
  - WhatsApp: `App\Modules\Admin\WhatsApp\Controllers\WhatsAppPageController`
  - Report: `App\Modules\Admin\Report\Controllers\ReportPageController`
- Rute lama tetap dipertahankan untuk kompatibilitas:
  - `/admin/members`
  - `/admin/member-packages`

## 5) Risiko yang Perlu Diwaspadai
- Namespace typo saat autoload kelas modular baru.
- Ketidaksinkronan view lama vs view baru jika keduanya diedit paralel.
- Route order pada grup dinamis (`/{member}`) bisa konflik bila menambah endpoint statis baru tanpa urutan yang benar.
- Jika ada pemanggilan class langsung ke namespace lama di luar route/container, perlu dicek saat refactor domain berikutnya.

## 6) Checklist Migrasi Aman
- [x] Hanya area Admin yang disentuh.
- [x] Tidak ada perubahan database/migration.
- [x] Tidak ada perubahan model Eloquent.
- [x] Tidak ada perubahan business logic (hanya reposisi namespace + request class).
- [x] Route admin tetap berjalan dengan `prefix('admin')`.
- [x] View admin sudah tersedia di struktur `resources/views/admin/*`.
- [x] PSR-4 namespace valid di bawah `App\` (`app/Modules/...`).
- [x] Test regresi admin terfokus lulus (53 passed pada batch validasi terakhir).

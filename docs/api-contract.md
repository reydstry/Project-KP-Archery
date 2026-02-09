# Club Panahan API Contract (v1)

Last updated: 2026-02-07

## Base

- Base URL: `/api`
- Content-Type: `application/json`
- Auth: Laravel Sanctum Personal Access Token
  - Header: `Authorization: Bearer <token>`

## Roles

Role values (used by middleware):

- `admin`
- `coach`
- `member`

> Catatan: Label UI seperti “Pelatih” tidak dipakai di API. Middleware mengecek `user.role.value`.

## Standard Responses

### Validation error (422)
Laravel `ValidationException` biasanya menghasilkan:

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field": ["Error message"]
  }
}
```

Beberapa endpoint mengembalikan message custom + `errors` (contoh: achievements).

### Unauthenticated (401)
```json
{ "message": "Unauthenticated." }
```

### Forbidden (403)
```json
{ "message": "Forbidden. Anda tidak memiliki akses." }
```

### Pagination shape
Endpoint yang memakai `paginate(...)` mengembalikan format pagination standar Laravel:

```json
{
  "current_page": 1,
  "data": [],
  "first_page_url": "...",
  "from": 1,
  "last_page": 10,
  "last_page_url": "...",
  "links": [],
  "next_page_url": "...",
  "path": "...",
  "per_page": 10,
  "prev_page_url": null,
  "to": 10,
  "total": 100
}
```

---

## Authentication

### POST `/register`
Register user baru (role default: `member`) dan mengembalikan token.

**Auth**: Public

**Body**
- `name` (string, required, max 255)
- `email` (string, required, email, unique)
- `password` (string, required, min 8, confirmed)
- `password_confirmation` (string, required)
- `phone` (string, nullable, max 20)

**201 Response**
```json
{
  "message": "Registrasi Berhasil",
  "data": {
    "user": { "id": 1, "name": "...", "email": "...", "role": "member" },
    "token": "...",
    "token_type": "Bearer"
  }
}
```

### POST `/login`
Login dan mengembalikan token. Token lama dihapus.

**Auth**: Public

**Body**
- `email` (string, required, email)
- `password` (string, required)

**200 Response**
```json
{
  "message": "Login berhasil",
  "data": {
    "user": { "id": 1, "name": "...", "email": "...", "role": "admin" },
    "token": "...",
    "token_type": "Bearer"
  }
}
```

**422 Response** (credentials salah)
- Field error: `email`

### POST `/logout`
Logout dan revoke token saat ini.

**Auth**: Required

**200 Response**
```json
{ "message": "Logout berhasil" }
```

### GET `/me`
Profil user login.

**Auth**: Required

**200 Response**
```json
{
  "data": {
    "user": {
      "id": 1,
      "name": "...",
      "email": "...",
      "role": "member",
      "phone": "..."
    }
  }
}
```

### POST `/change-password`
Ganti password user; semua token akan dihapus (force re-login).

**Auth**: Required

**Body**
- `current_password` (string, required)
- `password` (string, required, min 8, confirmed)
- `password_confirmation` (string, required)

**200 Response**
```json
{ "message": "Password berhasil diubah. Silakan login kembali." }
```

---

## Public (Company Profile)

### GET `/news`
List news yang sudah published.

**Auth**: Public

**Query**: none

**200 Response**: Pagination shape (data berisi array News)

### GET `/news/{news}`
Detail news.

**Auth**: Public

**200 Response**
```json
{ "data": { /* News model */ } }
```

**404 Response**
Jika `publish_date` masih future.

### GET `/achievements`
List achievements yang sudah published.

**Auth**: Public

**Query**
- `type` (optional): `member` | `club`

**200 Response**: Pagination shape (data berisi array Achievement)

### GET `/achievements/{achievement}`
Detail achievement.

**Auth**: Public

**200 Response**
```json
{ "data": { /* Achievement model */ } }
```

**404 Response**
Jika `date` masih future.

---

## Member API

Semua endpoint di bawah:

- **Auth**: Required (`auth:sanctum`)
- **Role**: `member` (`role:member`)
- Prefix: `/member`

### GET `/member/dashboard`
Member dashboard.

**200 Response**
```json
{
  "member": { "id": 1, "name": "...", "status": "pending" },
  "quota": {
    "package_name": "...",
    "total_sessions": 10,
    "used_sessions": 2,
    "remaining_sessions": 8,
    "start_date": "YYYY-MM-DD",
    "end_date": "YYYY-MM-DD",
    "days_remaining": 20
  },
  "attendance": {
    "history": [
      {
        "id": 1,
        "session_date": "YYYY-MM-DD",
        "session_time": "...",
        "coach_name": "...",
        "package_name": "...",
        "attendance_status": "present",
        "validated_at": "...",
        "notes": "..."
      }
    ],
    "statistics": {
      "total_attended": 0,
      "total_absent": 0
    }
  },
  "achievements": [
    {
      "id": 1,
      "type": "member",
      "title": "...",
      "description": "...",
      "date": "YYYY-MM-DD",
      "photo_path": "..."
    }
  ]
}
```

**404 Response**
Jika member profile (self) belum ada.

### POST `/member/register-self`
Daftar sebagai member dewasa (self).

**Body**
- `name` (string, required)
- `phone` (string, nullable)

**201 Response**
```json
{
  "message": "Pendaftaran member berhasil. Menunggu verifikasi admin.",
  "data": { /* Member model + relations */ }
}
```

**422 Response**
Jika sudah pernah register self.

### POST `/member/register-child`
Daftarkan anak (is_self = false).

**Body**
- `name` (string, required)
- `phone` (string, nullable)

**201 Response**
```json
{
  "message": "Pendaftaran anak berhasil. Menunggu verifikasi admin.",
  "data": { /* Member model + relations */ }
}
```

**422 Response**
Jika belum register self.

### GET `/member/my-members`
List member yang terdaftar oleh user ini (self + anak).

**200 Response**
```json
{ "message": "Data member berhasil diambil", "data": [ /* Member[] */ ] }
```

### GET `/member/bookings`
List booking milik member (berdasarkan package milik member self).

**Query**
- `status` (optional): mis. `confirmed`, `cancelled`

**200 Response**: Pagination shape (per_page = 15)

### POST `/member/bookings`
Buat booking training session.

**Body**
- `training_session_id` (int, required, exists)
- `member_package_id` (int, required, exists)
- `notes` (string, nullable, max 500)

**201 Response**
```json
{
  "message": "Session booked successfully",
  "data": { /* SessionBooking + relations */ },
  "remaining_sessions": 10
}
```

**422 Response**
Contoh kasus:
- package tidak aktif / expired
- quota habis
- session tidak `open` / sudah lewat / penuh
- sudah booking session yang sama

### GET `/member/bookings/{sessionBooking}`
Detail booking.

**200 Response**
Model booking (tanpa wrapper `data`).

**403 Response**
Jika booking bukan milik member tersebut.

### POST `/member/bookings/{sessionBooking}/cancel`
Cancel booking (hanya future session).

**200 Response**
```json
{ "message": "Booking cancelled successfully", "data": { /* booking */ } }
```

---

## Coach API

Semua endpoint di bawah:

- **Auth**: Required (`auth:sanctum`)
- **Role**: `coach` (`role:coach`)
- Prefix: `/coach`

### GET `/coach/dashboard`
Coach dashboard.

**200 Response**
```json
{
  "coach": { "id": 1, "name": "...", "phone": "..." },
  "statistics": {
    "today_sessions": 0,
    "upcoming_sessions": 0,
    "total_sessions": 0
  },
  "today_sessions": [
    {
      "id": 1,
      "date": "YYYY-MM-DD",
      "status": "open",
      "max_participants": 10,
      "session_time_id": 1
    }
  ]
}
```

Jika coach profile tidak ada: `coach = null`, stats 0, list kosong.

### GET `/coach/training-sessions`
List training sessions milik coach.

**Query (optional)**
- `status` (string)
- `start_date` (date, `YYYY-MM-DD`)
- `end_date` (date, `YYYY-MM-DD`)

**200 Response**: Pagination shape (per_page = 15)

### POST `/coach/training-sessions`
Create training session.

**Body**
- `session_time_id` (int, required, exists)
- `date` (date, required, after_or_equal:today)
- `max_participants` (int, required, min 1, max 50)

**201 Response**
```json
{ "message": "Training session created successfully", "data": { /* TrainingSession + relations */ } }
```

**422 Response**
Jika session untuk `session_time_id` + `date` sudah ada.

### GET `/coach/training-sessions/{trainingSession}`
Detail training session (harus milik coach).

**200 Response**
Model training session (tanpa wrapper `data`).

### PATCH `/coach/training-sessions/{trainingSession}/quota`
Update quota.

**Body**
- `max_participants` (int, required, min 1, max 50)

**200 Response**
```json
{ "message": "Quota updated successfully", "data": { /* TrainingSession */ } }
```

### POST `/coach/training-sessions/{trainingSession}/open`
Open session.

**200 Response**
```json
{ "message": "Training session opened successfully", "data": { /* TrainingSession */ } }
```

### POST `/coach/training-sessions/{trainingSession}/close`
Close session.

**200 Response**
```json
{ "message": "Training session closed successfully", "data": { /* TrainingSession */ } }
```

### POST `/coach/training-sessions/{trainingSession}/cancel`
Cancel session.

**200 Response**
```json
{ "message": "Training session canceled successfully", "data": { /* TrainingSession */ } }
```

### GET `/coach/training-sessions/{trainingSession}/bookings`
List bookings pada session tersebut + status attendance.

**200 Response**
```json
{
  "session": {
    "id": 1,
    "date": "YYYY-MM-DD",
    "session_time": "...",
    "status": "open"
  },
  "bookings": [
    {
      "id": 1,
      "member_name": "...",
      "member_id": 1,
      "has_attendance": false,
      "attendance_status": null,
      "validated_at": null,
      "notes": null
    }
  ],
  "total_bookings": 0,
  "attended": 0,
  "absent": 0,
  "not_validated": 0
}
```

### POST `/coach/bookings/{sessionBooking}/attendance`
Validasi attendance.

**Body**
- `status` (required): `present` | `absent`
- `notes` (nullable, max 500)

**201 Response**
```json
{
  "message": "Attendance validated successfully",
  "attendance": {
    "id": 1,
    "booking_id": 1,
    "member_name": "...",
    "status": "present",
    "validated_at": "...",
    "notes": "..."
  },
  "remaining_sessions": 9
}
```

### PATCH `/coach/bookings/{sessionBooking}/attendance`
Update attendance yang sudah ada.

**Body**
- `status` (required): `present` | `absent`
- `notes` (nullable)

**200 Response**
Sama seperti validate, tetapi status 200.

---

## Admin API

Semua endpoint di bawah:

- **Auth**: Required (`auth:sanctum`)
- **Role**: `admin` (`role:admin`)
- Prefix: `/admin`

### GET `/admin/dashboard`
Admin dashboard.

**200 Response**
```json
{
  "statistics": {
    "pending_members": 0,
    "active_members": 0,
    "total_members": 0,
    "total_coaches": 0,
    "total_packages": 0,
    "total_news": 0,
    "total_achievements": 0
  },
  "recent": {
    "pending_members": [
      { "id": 1, "name": "...", "phone": "...", "status": "pending", "created_at": "..." }
    ]
  }
}
```

### Packages (`/admin/packages`)

- GET `/admin/packages`
  - 200: `{ message, data: Package[] }`
- POST `/admin/packages`
  - 201: `{ message, data: Package }`
  - Body: `name` (req), `description` (opt), `price` (req, numeric), `duration_days` (req, int), `session_count` (req, int)
- GET `/admin/packages/{package}`
  - 200: `{ message, data: Package }`
- PUT `/admin/packages/{package}`
  - 200: `{ message, data: Package }`
- DELETE `/admin/packages/{package}`
  - 200: `{ message }`

### News (`/admin/news`)

- GET `/admin/news`
  - 200: Pagination shape
- POST `/admin/news`
  - 201: `{ message, data: News }`
  - Body: `title` (req), `content` (req), `publish_date` (req, date), `photo_path` (opt)
- GET `/admin/news/{news}`
  - 200: `{ data: News }`
- PUT/PATCH `/admin/news/{news}`
  - 200: `{ message, data: News }`
- DELETE `/admin/news/{news}`
  - 200: `{ message }`

### Achievements (`/admin/achievements`)

- GET `/admin/achievements`
  - 200: Pagination shape
- POST `/admin/achievements`
  - 201: `{ message, data: Achievement }`
  - Body: `type` (req: `member|club`), `member_id` (req jika type=member), `title` (req), `description` (req), `date` (req), `photo_path` (opt)
- GET `/admin/achievements/{achievement}`
  - 200: `{ data: Achievement }`
- PUT/PATCH `/admin/achievements/{achievement}`
  - 200: `{ message, data: Achievement }`
- DELETE `/admin/achievements/{achievement}`
  - 200: `{ message }`

### Coaches (`/admin/coaches`)

> Catatan: resource ini menggunakan model `User` tetapi diperlakukan sebagai coach. Jika `role != coach`, API akan return 404.

- GET `/admin/coaches`
  - 200: `{ message, data: User[] }`
- POST `/admin/coaches`
  - 201: `{ message, data: User }`
  - Body: `name`, `email`, `password`, `password_confirmation`, `phone?`
- GET `/admin/coaches/{coach}`
  - 200: `{ message, data: User }`
- PUT `/admin/coaches/{coach}`
  - 200: `{ message, data: User }`
  - Body: `name`, `email`, `password?`, `password_confirmation?`, `phone?`
- DELETE `/admin/coaches/{coach}`
  - 200: `{ message }`

### Members (`/admin/members`)

- GET `/admin/members`
  - 200: `{ message, data: Member[] }`
- POST `/admin/members`
  - 201: `{ message, data: Member }`
  - Body: `user_id` (req), `registered_by?`, `name` (req), `phone?`, `is_self?`, `is_active?`
- GET `/admin/members/{member}`
  - 200: `{ message, data: Member }`
- PUT/PATCH `/admin/members/{member}`
  - 200: `{ message, data: Member }`
- DELETE `/admin/members/{member}`
  - 200: `{ message }` (soft delete: set `is_active=false`)

### POST `/admin/members/{id}/restore`
Restore member inactive: set `is_active=true`.

**200 Response**
```json
{ "message": "Member berhasil diaktifkan kembali", "data": { /* Member */ } }
```

### Member Packages

- GET `/admin/member-packages`
  - 200: Pagination shape (data berisi array MemberPackage)
- GET `/admin/member-packages/{memberPackage}`
  - 200: MemberPackage model (tanpa wrapper)
- POST `/admin/members/{member}/assign-package`
  - 201: `{ message, data: MemberPackage }`
  - Body: `package_id` (req), `start_date` (req, date)
- GET `/admin/members/{member}/packages`
  - 200: `MemberPackage[]` (tanpa wrapper)

### GET `/admin/pending-members`
List pending members (untuk approval list).

**200 Response**
```json
{ "message": "Data member pending berhasil diambil", "data": [ /* Member[] */ ] }
```

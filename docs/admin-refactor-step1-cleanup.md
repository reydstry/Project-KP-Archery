# Admin Refactor — STEP 1 (Cleanup Terkontrol)

Tanggal: 2026-02-17

## Kandidat Deprecated (ditandai sebelum hapus)

- `GET /api/admin/pending-members` + `RegistrationController::pendingMembers()`
  - Status: **Deprecated (sementara dipertahankan)**
  - Alasan: tidak direferensikan UI admin saat ini, namun masih dipakai test existing (`tests/Feature/Member/RegistrationTest.php`).

- Legacy admin training-session blades (digantikan oleh `training-sessions*`):
  - `resources/views/components/dashboards/admin/sessions.blade.php`
  - `resources/views/components/dashboards/admin/sessions-create.blade.php`
  - `resources/views/components/dashboards/admin/sessions-edit.blade.php`
  - `resources/views/components/dashboards/admin/placeholder.blade.php`
  - Status: **Deprecated → Removed**
  - Alasan: tidak ada route/controller aktif yang merender file-file tersebut.

## Checklist Cleanup STEP 1

- [x] Referensi booking di area UI-route comment dibersihkan
- [x] Blade orphan dihapus
- [x] Endpoint pending verification ditandai deprecated (belum dihapus untuk menjaga test existing tetap lulus)

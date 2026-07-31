# Task Implementasi Web Admin KALIWEDI

Status: ✅ = selesai, ⏭ = di-skip (alasan), 🔶 = sebagian

## Fase 0 — Persiapan
- [x] **1.** Buat direktori `app/Http/Controllers/Admin/`
- [x] **2.** Buat migrasi `report_categories` (name, icon, sort_order, is_active)
- [x] **3.** Buat migrasi `alert_levels` (name, color, min_height, max_height, is_active)
- [x] **4.** Buat model `ReportCategory` + `AlertLevel`
- [x] **5.** Jalankan `php artisan migrate` + seeder awal kategori & level siaga
- [x] **6.** Grup route `/admin` + `AdminMiddleware` untuk semua halaman admin

## Fase 1 — Dashboard dinamis
- [x] **7.** Buat `Admin\DashboardController` dengan query statistik real
- [x] **8.** Ganti angka "Laporan Hari Ini" dengan count DB
- [x] **9.** Kartu "Tinggi Maksimal" diganti **"Laporan Kritis (belum selesai)"** ⏭ *tidak ada kolom tinggi di tabel reports*
- [x] **10.** Ganti "Laporan Pending" dengan count status pending
- [x] **11.** Ganti "Shelter Aktif" dengan count + kapasitas real
- [x] **12.** Ganti "Aktivitas Laporan Terbaru" dari query DB
- [x] **13.** Render marker peta dashboard dari data real reports
- [x] **14.** Tampilkan marker shelter di peta dashboard
- [x] **15.** Tambah grafik tren laporan 7 hari (Chart.js)
- [ ] **16.** Dropdown filter wilayah di dashboard ⏭ *tidak ada field wilayah; filter ada di halaman laporan*

## Fase 2 — Sidebar & layout
- [x] **17.** Wire "Laporan Banjir" → `admin.reports.index`
- [x] **18.** Wire "Shelter Evakuasi" → `admin.shelters.index`
- [x] **19.** Wire "Zona Rawan Banjir" → `admin.zones.index`
- [x] **20.** Wire "Kontak Darurat" → `admin.contacts.index`
- [x] **21.** Wire "Konfigurasi Aplikasi" → `admin.app-info.edit`
- [x] **22.** Tambah link FAQ, Pengguna, Kategori Laporan, Level Siaga
- [x] **23.** Highlight active state pada semua menu nav

## Fase 3 — Shelter Evakuasi
- [x] **24.** Route `admin.shelters.index` + controller `index()` (list + pagination + search + filter status)
- [x] **25.** View `admin/shelters/index.blade.php` (tabel + status badge + progress bar)
- [x] **26.** Route `admin.shelters.create` + view form
- [x] **27.** Route `admin.shelters.store` + validasi (name, lat/lng, address, capacity, occupancy)
- [x] **28.** Route `admin.shelters.edit` + view edit dengan data lama
- [x] **29.** Route `admin.shelters.update` + validasi update
- [x] **30.** Route `admin.shelters.destroy` (DELETE + CSRF + confirm)
- [x] **31.** Peta Leaflet sebagai lat/lng picker (klik + drag marker)
- [x] **32.** Progress bar kapasitas vs occupancy
- [x] **33.** Validasi enum status + pesan error Bahasa Indonesia
- [x] **34.** Search & filter shelter by status

## Fase 4 — Zona Rawan Banjir
- [x] **35.** Route `admin.zones.index` + controller `index()` (+ search + filter risk)
- [x] **36.** View `admin/zones/index.blade.php` (tabel + badge risk_level)
- [x] **37.** Route `admin.zones.create` + view form
- [x] **38.** Route `admin.zones.store` + validasi (name, polygon, risk_level)
- [x] **39.** Route `admin.zones.edit` + view edit dengan polygon lama
- [x] **40.** Route `admin.zones.update` + validasi update
- [x] **41.** Route `admin.zones.destroy`
- [x] **42.** Integrasi `Leaflet.draw` untuk menggambar polygon
- [x] **43.** Parse & simpan polygon sebagai GeoJSON; tampilkan ulang saat edit
- [x] **44.** Warna map overlay sesuai risk_level (low→hijau, critical→merah)

## Fase 5 — Kontak Darurat
- [x] **45.** Route `admin.contacts.index` + controller terurut sort_order
- [x] **46.** View `admin/contacts/index.blade.php` (tabel + toggle aktif)
- [x] **47.** Route `admin.contacts.create` + view form
- [x] **48.** Route `admin.contacts.store` + validasi (type, title, value, icon)
- [x] **49.** Route `admin.contacts.edit` + view edit
- [x] **50.** Route `admin.contacts.update`
- [x] **51.** Route `admin.contacts.destroy`
- [x] **52.** Dropdown type + preview ikon live
- [x] **53.** Reorder urutan (SortableJS drag-drop)

## Fase 6 — FAQ
- [x] **54.** Route `admin.faqs.index` + controller terurut sort_order
- [x] **55.** View `admin/faqs/index.blade.php`
- [x] **56.** Route `admin.faqs.create` + view form
- [x] **57.** Route `admin.faqs.store` + validasi (question, answer)
- [x] **58.** Route `admin.faqs.edit` + view edit
- [x] **59.** Route `admin.faqs.update`
- [x] **60.** Route `admin.faqs.destroy`
- [x] **61.** Toggle is_active cepat dari index

## Fase 7 — Konfigurasi Aplikasi
- [x] **62.** Route `admin.app-info.edit` + controller ambil single row (auto-create bila kosong)
- [x] **63.** View `admin/app-info/edit.blade.php` (semua field)
- [x] **64.** Route `admin.app-info.update` + validasi (website/email format)
- [x] **65.** Nilai lama ditampilkan sebagai value (bukan placeholder)
- [x] **66.** Flash message sukses setelah update

## Fase 8 — Kategori Laporan
- [x] **67.** Route `admin.categories.index` + controller
- [x] **68.** View `admin/categories/index.blade.php`
- [x] **69.** Route `admin.categories.create` + view (name + ikon FontAwesome)
- [x] **70.** Route `admin.categories.store` + validasi name unik
- [x] **71.** Route `admin.categories.edit` + view edit
- [x] **72.** Route `admin.categories.update`
- [x] **73.** Route `admin.categories.destroy`
- [x] **74.** Toggle is_active + kolom sort_order 🔶 *toggle ✅, reorder via field sort_order (tanpa drag-drop)*

## Fase 9 — Level Siaga
- [x] **75.** Route `admin.alert-levels.index` + controller terurut min_height
- [x] **76.** View `admin/alert-levels/index.blade.php` (badge warna + skala visual)
- [x] **77.** Route `admin.alert-levels.create` + view form
- [x] **78.** Route `admin.alert-levels.store` + validasi min/max
- [x] **79.** Route `admin.alert-levels.edit` + view edit
- [x] **80.** Route `admin.alert-levels.update`
- [x] **81.** Route `admin.alert-levels.destroy`
- [x] **82.** Color picker + validasi rentang 🔶 *color picker ✅, validasi gte min→max ✅, cek overlap antar-level belum*

## Fase 10 — Manajemen Pengguna
- [x] **83.** Route `admin.users.index` + controller (list + search + filter role)
- [x] **84.** View `admin/users/index.blade.php` (avatar, role badge, status)
- [x] **85.** Route `admin.users.edit` + view form
- [x] **86.** Ganti role user (user/admin/superadmin) dengan validasi
- [x] **87.** Toggle aktif/nonaktif akun (field is_active baru + migrasi)
- [x] **88.** Reset password manual (generate + tampilkan sekali)
- [x] **89.** Proteksi: admin tidak bisa menonaktifkan/menurunkan akun sendiri
- [x] **90.** Filter list by role
- [x] **91.** Statistik jumlah laporan per user di index

## Fase 11 — Moderasi Laporan
- [x] **92.** Route `admin.reports.index` + controller (filter status/severity/kategori/tanggal)
- [x] **93.** View `admin/reports/index.blade.php` (tabel + foto thumbnail)
- [x] **94.** Route `admin.reports.show` + view detail
- [x] **95.** Foto laporan + peta Leaflet lokasi di detail
- [x] **96.** Tombol validasi → `verified`
- [x] **97.** Tombol tolak → `rejected`
- [x] **98.** Tombol tandai selesai → `resolved`
- [x] **99.** Filter laporan by tanggal & kategori
- [x] **100.** Export data laporan (CSV + BOM Excel) + dashboard pakai data real

## Verifikasi
- ✅ `php artisan migrate` — 3 migrasi baru sukses
- ✅ `php artisan db:seed --class=MasterDataSeeder` — kategori & level siaga terisi
- ✅ `php artisan view:cache` — semua Blade terkompilasi tanpa error
- ✅ `php artisan route:list --path=admin` — 62 route terdaftar
- ✅ `npm run build` — aset Tailwind/Vite ter-build
- ✅ Smoke test HTTP — login admin, 16 halaman list/create, 8 halaman edit/show = HTTP 200
- ✅ Smoke test write — store shelter, update status laporan, update app-info tersimpan di DB
- ✅ `pint` — gaya kode diperbaiki otomatis


---

## 🧭 **1. Gambaran Umum Proyek**

Tujuan: Sistem berbasis web untuk mencatat, memverifikasi, dan melaporkan data kecacatan barang kain.
Peran utama:

* **User (QC Operator, Admin, Verifikator)**
* **DataCacat (catatan utama kecacatan)**
* **Verifikasi (validasi dari QC/atasan)**
* **Laporan (rekap dan analisis)**
* **DashboardQC (statistik & grafik)**

---

## 🏗️ **2. Struktur Modul Laravel**

| Modul          | Deskripsi                                                          | Route Prefix  | Penanggung Jawab                                   | Checklist |
| -------------- | ------------------------------------------------------------------ | ------------- | -------------------------------------------------- | --------- |
| **Auth**       | Login, Logout, Register (inisiasi awal sistem & super admin setup) | `/auth`       | **Febriansah Dirgantara**                          | ☐ Not Yet |
| **User**       | Manajemen pengguna dan peran                                       | `/users`      | **Rizal Maulana**                                  | ☐ Not Yet |
| **DataCacat**  | CRUD data kecacatan                                                | `/data-cacat` | **Rifqii Fauzi Anwar**                             | ☐ Not Yet |
| **Verifikasi** | Proses validasi data cacat                                         | `/verifikasi` | **Fajri Lukman**                                   | ☐ Not Yet |
| **Laporan**    | Rekap data dan export PDF/Excel                                    | `/laporan`    | **Rizal Maulana** & **Fajri Lukman**               | ☐ Not Yet |
| **Dashboard**  | Statistik visual (grafik per shift, jenis cacat, mesin bermasalah) | `/dashboard`  | **Febriansah Dirgantara** & **Rifqii Fauzi Anwar** | ☐ Not Yet |

---

## 🗂️ **3. Database Workflow (Dari ERD)**

Buat migrasi sesuai tabel-tabel di diagram:

### Tabel 1. `users`

```php
Schema::create('users', function (Blueprint $table) {
    $table->id('id_user');
    $table->string('nama');
    $table->string('username')->unique();
    $table->string('password');
    $table->string('role'); // admin, qc, verifikator
    $table->timestamps();
});
```

### Tabel 2. `jenis_cacat`

```php
Schema::create('jenis_cacat', function (Blueprint $table) {
    $table->id('id_jenis');
    $table->string('nama_jenis');
});
```

### Tabel 3. `data_cacat`

```php
Schema::create('data_cacat', function (Blueprint $table) {
    $table->id('id_cacat');
    $table->date('tanggal');
    $table->string('shift');
    $table->string('jenis_kain')->nullable();
    $table->string('lokasi_mesin');
    $table->string('jenis_cacat');
    $table->string('foto_bukti')->nullable();
    $table->boolean('status_verifikasi')->default(false);
    $table->foreignId('id_user')->constrained('users');
    $table->foreignId('id_jenis')->constrained('jenis_cacat');
    $table->timestamps();
});
```

### Tabel 4. `verifikasi`

```php
Schema::create('verifikasi', function (Blueprint $table) {
    $table->id('id_verifikasi');
    $table->foreignId('id_cacat')->constrained('data_cacat');
    $table->foreignId('qc_id')->constrained('users');
    $table->date('tanggal_verifikasi');
    $table->boolean('valid');
    $table->text('catatan')->nullable();
});
```

### Tabel 5. `laporan`

```php
Schema::create('laporan', function (Blueprint $table) {
    $table->id('id_laporan');
    $table->string('periode');
    $table->integer('total_cacat');
    $table->string('jenis_cacat_terbanyak');
    $table->string('mesin_bermasalah');
    $table->timestamps();
});
```

---

## ⚙️ **4. Workflow Proses Sistem**

### 🔹 **A. Login & Role**

1. User login → middleware cek `role`.
2. Role menentukan akses:

   * Admin: semua modul.
   * QC Operator: input data cacat.
   * Verifikator: validasi data cacat.

---

### 🔹 **B. Pencatatan Data (QC Operator)**

1. QC Operator buka form `/data-cacat/create`.
2. Isi: tanggal, shift, jenis kain, lokasi mesin, jenis cacat, foto bukti.
3. Simpan → `status_verifikasi = false`.

---

### 🔹 **C. Verifikasi (Verifikator)**

1. Verifikator buka `/verifikasi`.
2. Lihat data cacat dengan `status_verifikasi = false`.
3. Klik “Verifikasi”:

   * Isi catatan & validasi.
   * Jika valid, ubah `status_verifikasi = true`.
   * Simpan ke tabel `verifikasi`.

---

### 🔹 **D. Laporan Otomatis (Admin)**

1. Admin buka `/laporan`.
2. Sistem hitung otomatis:

   * Total cacat per periode.
   * Jenis cacat terbanyak.
   * Mesin paling bermasalah.
3. Tombol Export:

   * `generatePDF()`
   * `generateExcel()`

---

### 🔹 **E. DashboardQC**

Menampilkan:

* Grafik jumlah cacat per shift.
* Jenis cacat tertinggi.
* Mesin dengan masalah terbanyak.

Gunakan **Chart.js atau ApexCharts** untuk visualisasi.

---

## 🧩 **5. Workflow Laravel (Langkah Implementasi)**

1. **Inisialisasi Proyek**

   ```bash
   laravel new qc-monitoring
   cd qc-monitoring
   php artisan make:auth
   ```

2. **Buat Model & Controller**

   ```bash
   php artisan make:model DataCacat -mcr
   php artisan make:model Verifikasi -mcr
   php artisan make:model Laporan -mcr
   php artisan make:model JenisCacat -mcr
   ```

3. **Tambahkan Relasi antar Model**
   Contoh di `User.php`:

   ```php
   public function dataCacat() {
       return $this->hasMany(DataCacat::class, 'id_user');
   }
   ```

4. **Seed Data Awal (Jenis Cacat & Role)**

   * Gunakan seeder untuk membuat data awal seperti “Sobek”, “Noda”, “Benang Tarik”.

5. **Middleware Role**

   * Buat middleware `CheckRole` agar route bisa dibatasi (Admin/QC/Verifikator).

6. **Upload File Handling**

   * Gunakan Laravel Storage (`storage/app/public`) untuk simpan foto bukti.
   * Gunakan `Intervention/Image` jika ingin resize otomatis.

7. **Export Laporan**

   * PDF: gunakan `barryvdh/laravel-dompdf`
   * Excel: gunakan `maatwebsite/excel`

8. **Grafik Dashboard**

   * Ambil data agregat dari `data_cacat`.
   * Render dengan `Chart.js`.

---

## 🧠 **6. Bonus — Alur Logika Simplified**

```text
QC Operator
   ↓
[Input DataCacat] ──────► status_verifikasi = false
   ↓
Verifikator
   ↓
[Verifikasi DataCacat]
   ↓
status_verifikasi = true
   ↓
Admin
   ↓
[Generate Laporan + Dashboard]
```

---


# 🧭 **1. Gambaran Umum Proyek**

**Tujuan:**
Sistem berbasis web untuk mencatat, memverifikasi, dan melaporkan data kecacatan kain secara efisien, dengan **notifikasi WhatsApp otomatis** untuk mempercepat koordinasi antar tim.

**Peran utama:**

* **User:** Petugas QC, Operator Produksi, dan Manager Produksi, Super Admin
* **DataCacat:** Catatan utama data cacat
* **Verifikasi:** Validasi data oleh QC/atasan
* **Laporan:** Rekapitulasi dan analisis otomatis
* **DashboardQC:** Statistik visual
* **Notification Queue (WhatsApp):** Otomatisasi pengiriman pesan berbasis antrian

---

# 🏗️ **2. Struktur Modul Laravel**

| Modul                  | Deskripsi                                                                | Route Prefix     | Penanggung Jawab                     | Status        |
| ---------------------- | ------------------------------------------------------------------------ | ---------------- | ------------------------------------ | ------------- |
| **Auth**               | Login, logout, dan setup awal super admin                                | `/auth`          | **Febriansah Dirgantara**            | ✅ Done        |
| **User**               | Manajemen pengguna, peran, dan WhatsApp ID                               | `/users`         | **Rizal Maulana**                    | ☐ In Progress |
| **DataCacat**          | CRUD data kecacatan kain                                                 | `/data-cacat`    | **Rifqii Fauzi Anwar**               | ☐ In Progress |
| **Verifikasi**         | Proses validasi & konfirmasi data cacat                                  | `/verifikasi`    | **Fazri Lukman**                     | ✅ Done|
| **Laporan**            | Rekap data, perhitungan, dan export PDF/Excel                            | `/laporan`       | **Rizal Maulana**                     | ☐ Planned     |
| **Dashboard**          | Visualisasi statistik data cacat dan kinerja mesin                       | `/dashboard`     | **Febriansah Dirgantara**            | ✅ Done        |
| **Notification Queue** | Antrian pengiriman pesan WhatsApp otomatis (via Fonnte API atau sejenis) | `/notifications` | **Febriansah Dirgantara**            | ✅ Done        |

---

# 🗂️ **3. Database Struktur**

### 🧍‍♂️ **Tabel 1: `users`**

```php
Schema::create('users', function (Blueprint $table) {
    $table->id('id_user');
    $table->string('nama');
    $table->string('username')->unique();
    $table->string('email')->unique();
    $table->string('whatsapp')->unique()->nullable();
    $table->string('password');
    $table->string('role'); // admin, qc, verifikator
    $table->timestamps();
});
```

---

### 🧵 **Tabel 2: `jenis_cacat`**

```php
Schema::create('jenis_cacat', function (Blueprint $table) {
    $table->id('id_jenis');
    $table->string('nama_jenis');
});
```

---

### 📋 **Tabel 3: `data_cacat`**

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

---

### ✅ **Tabel 4: `verifikasi`**

```php
Schema::create('verifikasi', function (Blueprint $table) {
    $table->id('id_verifikasi');
    $table->foreignId('id_cacat')->constrained('data_cacat');
    $table->foreignId('qc_id')->constrained('users');
    $table->date('tanggal_verifikasi');
    $table->boolean('valid');
    $table->text('catatan')->nullable();
    $table->timestamps();
});
```

---

### 📊 **Tabel 5: `laporan`**

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

### 💬 **Tabel 6: `whatsapp_notifications` (Baru)**

```php
Schema::create('whatsapp_notifications', function (Blueprint $table) {
    $table->id('id_notif');
    $table->string('nomor_tujuan');
    $table->text('pesan');
    $table->enum('status', ['pending', 'terkirim', 'gagal'])->default('pending');
    $table->timestamp('sent_at')->nullable();
    $table->timestamps();
});
```

---

# ⚙️ **4. Workflow Sistem**

### 🔹 A. Login & Role Access

* Middleware `CheckRole` membatasi akses antar role.
* Role menentukan akses halaman dan fitur.

---

### 🔹 B. Input DataCacat (QC Operator)

1. Form input `/data-cacat/create`.
2. Submit data → status_verifikasi = false.
3. Sistem otomatis membuat entri baru di **`whatsapp_notifications`**:

   ```
   nomor_tujuan = nomor verifikator
   pesan = "Data cacat baru menunggu verifikasi."
   status = pending
   ```
4. Queue Worker mengirimkan pesan ke Fonnte API → update `status` ke `terkirim` atau `gagal`.

---

### 🔹 C. Petugas QC (Verifikator)

1. Verifikator melihat daftar data `status_verifikasi = false`.
2. Klik “Verifikasi”.
3. Jika disetujui:

   * `status_verifikasi = true`
   * Catatan disimpan
   * Notifikasi dikirim ke Manager Produksi, Petugas QC lain (self also), dan Super Admin melalui queue:

---

### 🔹 D. Laporan & Dashboard

1. Manager Produksi & Petugas QC dapat membuka `/laporan`.
2. Sistem hitung agregasi otomatis (cacat per mesin, jenis, dan periode).
3. Data dikirim ke Dashboard → divisualisasikan dengan **Chart.js / ApexCharts**.

---

### 🔹 E. Notification Queue (Fonnte Integration)

1. Worker Laravel Queue (`php artisan queue:work`) memantau tabel `whatsapp_notifications`.
2. Setiap `status = pending`, sistem kirim pesan via Fonnte API.
3. Setelah terkirim:

   * Update status ke `sent`.
   * Simpan `sent_at` timestamp.

---

# 🧩 **5. Struktur Model & Relasi**

| Model                  | Relasi                                     |
| ---------------------- | ------------------------------------------ |
| `User`                 | hasMany(`DataCacat`)                       |
| `DataCacat`            | belongsTo(`User`), hasOne(`Verifikasi`)    |
| `Verifikasi`           | belongsTo(`DataCacat`)                     |
| `WhatsappNotification` | standalone (dipanggil oleh event/observer) |

---

# 🔁 **6. Workflow Queue WhatsApp**

```mermaid
flowchart LR
A[DataCacat Created / Updated] --> B{WhatsappNotification already exists?}
B -->|Yes| C[Skip creation]
C --> I[Worker cek status perubahan]
I --> J{Status berubah?}
J -->|Yes| E[Laravel Queue Worker]
J -->|No| K[Do nothing]
B -->|No| D[Create WhatsappNotification - pending]
D --> E[Laravel Queue Worker]
E --> F{API Fonnte Response}
F -->|Success| G[Update status=sent + sent_at]
F -->|Failed| H[Update status=gagal]
H --> L{Next retry / closed?}
L -->|Retry| E
L -->|Closed| M[Stop]

```

---

# 💼 **7. Jobdesk Akhir Tim**

| Nama                      | Role / Jobdesk                         | Modul / Area Tanggung Jawab         |
| ------------------------- | -------------------------------------- | ----------------------------------- |
| **Febriansah Dirgantara** | System Architect & Fullstack Developer | Auth, Dashboard, Notification Queue |
| **Rizal Maulana**         | Backend Developer                      | User Management, Laporan            |
| **Rifqii Fauzi Anwar**    | Fullstack Developer                    | DataCacat, Dashboard Graph          |
| **Fajri Lukman**          | Backend Developer                      | Verifikasi & Validasi Data          |
| **Semua Tim**             | Testing, Review, Documentation         | —                                   |

---

# 🧠 **8. Ringkasan Logika Proses (Simplified)**

```text
QC Operator
  ↓
[Input DataCacat]
  ↓
Trigger WhatsApp (notif ke verifikator)
  ↓
Verifikator
  ↓
[Verifikasi & Catatan]
  ↓
Trigger WhatsApp (notif ke admin)
  ↓
Manager Produksi, Pegawai QC, Super Admin
  ↓
[Laporan & Dashboard Visual]
```

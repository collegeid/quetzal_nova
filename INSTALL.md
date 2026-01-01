# 📝 Panduan Instalasi: Qual Nova QC System

**Qual Nova** adalah platform sistem kendali mutu (Quality Control) modern yang dikembangkan oleh **Quetzal Team**. Sistem ini dirancang untuk memantau, mencatat, dan menganalisis klasifikasi kecacatan produksi kain secara *real-time* menggunakan analisis data dan regresi logistik.

---

## 🛠 Prasyarat Sistem

Sebelum memulai, pastikan perangkat kamu sudah terinstall:

* **PHP** >= 8.2
* **Composer** (Dependency Manager PHP)
* **MySQL** atau **MariaDB**
* **Node.js & NPM** (Untuk kompilasi aset Vite)
* **Git**

---

## 🚀 Langkah Instalasi

### 1. Kloning Repositori

Buka terminal (Linux) atau CMD/PowerShell (Windows) dan jalankan:

```bash
git clone https://github.com/collegeid/quetzal_nova.git
#### (Jika kamu sudah ada zip nya maka langsung masuk ke folder hasil ekstraksi)

cd quetzal_nova


```

### 2. Instalasi Dependency

Instal library PHP dan aset frontend:

```bash
# Instal library Laravel
composer install

# Instal library Frontend
npm install
npm run build

```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

* **Windows:** `copy .env.example .env`
* **Linux:** `cp .env.example .env`

Buka file `.env` dan sesuaikan konfigurasi database kamu:

```env
DB_CONNECTION=mysql // atau psql 
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=qualnova_qc
DB_USERNAME=usernamedatabasekamu
DB_PASSWORD=passworddatabasekamu  # Sesuaikan dengan password database kamu

```

*Pastikan kamu sudah membuat database bernama `qualnova_qc` di phpMyAdmin atau MySQL.*

### 4. Generate App Key

```bash
php artisan key:generate

```

### 5. Konfigurasi Folder Penyimpanan (Storage) 📂

Sistem membutuhkan folder khusus untuk menyimpan aset gambar, bukti verifikasi, dan video demo. Jalankan perintah berikut:

**Linux:**

```bash
mkdir -p storage/app/public/images
mkdir -p storage/app/public/bukti
mkdir -p storage/app/public/videos
chmod -R 775 storage bootstrap/cache

```

**Windows (Manual):**

1. Buka folder proyek.
2. Masuk ke `storage` -> `app` -> `public`.
3. Buat folder baru bernama: `images`, `bukti`, dan `videos`.

### 6. Hubungkan Storage (Symlink)

Agar file di dalam folder storage bisa diakses melalui browser, jalankan:

```bash
php artisan storage:link

```

### 7. Migrasi Database & Seeding

Jalankan migrasi untuk membuat tabel dan mengisi data awal (seperti data jenis cacat):

```bash
php artisan migrate --seed

```

---

## 🏃‍♂️ Menjalankan Aplikasi

Setelah semua langkah di atas selesai, kamu bisa menjalankan server lokal:

```bash
php artisan serve

```

Akses aplikasi di browser melalui URL: `http://127.0.0.1:8000`

---


## 👥 Quetzal Team

* **Febriansah Dirgantara** - *Team Lead*
* **Rizal Maulana** - *Team Member*
* **Rifqii Fauzi A.** - *Team Member*
* **Fazri Lukman** - *Team Member*

---

**© 2025 Qual Nova QC System.** Developed with ❤️ by **Quetzal Team**.

--

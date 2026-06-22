# PPKD Hotel Management System (HMS)

Sistem Manajemen Hotel komprehensif berbasis **Laravel**, dirancang khusus untuk memfasilitasi operasional hotel mulai dari pemesanan (reservasi), manajemen kamar, layanan tamu, hingga laporan keuangan bulanan.

Aplikasi ini menggunakan antarmuka modern yang dikustomisasi dari _Voler Admin Dashboard Template_, menawarkan User Experience (UX) yang rapi, cepat, dan terpusat.

---

## 🌟 Fitur Utama

### 1. Manajemen Hak Akses Terpusat (RBAC)

Sistem ini menggunakan pembatasan akses ketat berdasarkan peran staf hotel:

- **Administrator**: Memiliki akses penuh ke seluruh sistem, master data, pengaturan pajak, dan manajemen pengguna.
- **Front Office (Resepsionis)**: Menangani pendaftaran tamu, _check-in/check-out_, pembuatan invoice, pemesanan layanan, dan kalender okupansi.
- **Housekeeping**: Akses khusus ke modul kebersihan untuk memantau kamar kotor dan menandainya setelah dibersihkan.
- **Food & Beverage (FnB)**: Menangani pesanan makanan dan minuman dari kamar tamu.

### 2. Kalender Okupansi Visual

Tabel kalender dinamis (1-2 minggu ke depan) yang ada di Dashboard Utama untuk memudahkan pengecekan _availability_ kamar secara _real-time_. Kotak reservasi dapat diklik untuk meluncur langsung ke halaman detail transaksi tamu.

### 3. Siklus Reservasi Lengkap

- Pengecekan kamar kosong (_Vacant Ready, Vacant Clean, dll_).
- Pendaftaran tamu beserta nomor identitas (KTP/Paspor).
- Manajemen deposit dan pelunasan bertahap.
- Perhitungan otomatis Pajak (Tax) dan _Service Charge_ berdasarkan pengaturan dinamis hotel.
- Validasi sistem ketat: Tamu tidak bisa _Check-In_ jika tagihan belum berstatus _Paid Fully_.
- **Cetak Invoice (Struk)** bawaan _browser_ yang dioptimalkan dengan CSS `@media print`.

### 4. Modul Housekeeping Terintegrasi

Panel khusus untuk staf kebersihan. Kamar yang baru ditinggalkan tamu (_Check-Out_) akan otomatis berstatus _Dirty_. Staf _housekeeping_ dapat mengklik tombol "Tandai Bersih" agar kamar kembali bisa disewa oleh Front Office.

### 5. Layanan Tambahan (FnB & Laundry)

Tamu dapat memesan makanan/minuman (FnB) dan jasa cuci (Laundry). Tagihan dari layanan tambahan ini akan langsung terakumulasi ke _Grand Total_ tagihan kamar tamu secara presisi.

### 6. Laporan Lanjutan & Ekspor Data (Advanced Reports)

Admin dapat memfilter riwayat transaksi dan pendapatan hotel berdasarkan:

- Hari ini, 7 Hari Terakhir, Bulan Ini, atau Rentang Waktu (Custom Range).
  Laporan dapat diekspor menjadi dokumen `.csv` untuk keperluan audit keuangan.

### 7. Pengaturan Hotel Dinamis

Daripada memodifikasi kode (_hardcode_), Admin dapat mengatur:

- Nama Hotel, Kontak, dan Alamat.
- Persentase **Pajak Hotel** (misal 10% atau 11%).
- Persentase **Service Charge** (misal 5%).
- Waktu standar _Check-In_ dan _Check-Out_.

---

## 🛠 Teknologi yang Digunakan

- **Framework**: Laravel (PHP)
- **Database**: MySQL
- **Frontend**: Blade Templating Engine, Bootstrap 4, Voler Admin Dashboard
- **Icons**: Feather Icons
- **Fitur Spesifik**: Spatie Permission (RBAC), Chart.js (Grafik)

---

## 🚀 Cara Instalasi & Menjalankan (Lokal)

Jika Anda ingin menjalankan proyek ini di lingkungan komputer lokal Anda (seperti XAMPP / MAMP), ikuti langkah-langkah berikut:

1. **Clone repositori ini atau salin ke folder lokal Anda:**

    ```bash
    cd /Applications/XAMPP/xamppfiles/htdocs
    git clone <url-repo> hotel
    cd hotel
    ```

2. **Instal seluruh _dependencies_ Composer:**

    ```bash
    composer install
    ```

3. **Salin `.env.example` ke `.env` dan atur konfigurasi Database Anda:**

    ```bash
    cp .env.example .env
    # Buka file .env dan sesuaikan DB_DATABASE, DB_USERNAME, dan DB_PASSWORD
    ```

4. **Generate App Key:**

    ```bash
    php artisan key:generate
    ```

5. **Jalankan Migrasi & Seeder:**
   _(Langkah ini akan mengisi database dengan struktur tabel yang dibutuhkan, akun admin/staf, dan beberapa data dummy untuk percobaan)._

    ```bash
    php artisan migrate --seed
    ```

6. **Jalankan _Local Development Server_:**
    ```bash
    php artisan serve
    ```
    Aplikasi dapat diakses melalui `http://127.0.0.1:8000` atau melalui URL Apache bawaan `http://localhost/hotel/public`.

---

## 🔐 Akun Akses Default

Karena sistem ini telah disertakan _database seeder_, Anda dapat langsung masuk (Login) menggunakan kredensial percobaan berikut:

- **Admin Utama:**
    - Email: `admin@hotel.com`
    - Password: `password`
- **Front Office (Resepsionis):**
    - Email: `frontoffice@hotel.com`
    - Password: `password`
- **Housekeeping:**
    - Email: `housekeeping@hotel.com`
    - Password: `password`
- **Food & Beverage:**
    - Email: `fnb@hotel.com`
    - Password: `password`

---

_Dikembangkan untuk menunjang operasional digital industri perhotelan moderen. &copy; 2026 PPKD Hotel Management System._

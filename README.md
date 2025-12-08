# EventTicket – Laravel Multi-Role E-Ticketing Platform

EventTicket adalah aplikasi web e-ticketing berbasis **Laravel** dengan dukungan multi-role:

- **Admin**
- **Event Organizer**
- **Registered User**
- **Guest**

Proyek ini fokus pada:
- Manajemen event & tiket,
- Manajemen booking & e-ticket PDF,
- Approval organizer & booking,
- Dashboard analytics interaktif,
- UI modern, elegan, dan responsif.

---

## 1. Tech Stack

### Backend

- **PHP** 8.2+
- **Laravel** 10/11 (dalam log error muncul 12.x, tapi struktur kompatibel 10/11)
- **MySQL** (atau kompatibel, misal MariaDB)
- **Laravel Breeze** (auth + scaffolding dasar)

### Frontend

- **Vite** (asset bundler)
- **Tailwind CSS**
- **DaisyUI** (Tailwind component plugin)
- **Flowbite** (komponen UI tambahan)
- **Tailwind Typography**
- **Animate.css**
- **Animista** (via CDN – untuk animasi khusus)
- **Alpine.js** (interaksi ringan)
- **AOS.js** (scroll animation)
- **Swiper.js** (slider/hero carousel – opsional per halaman)
- **Chart.js** (analytics dashboard)
- **Flatpickr** (date picker)
- **SweetAlert2** (alert & konfirmasi modern)
- **Day.js** (format tanggal di frontend)

---

## 2. Fitur Utama

### 2.1. Role & Akses

#### 1) Guest (tanpa login)

- Melihat **landing page / homepage** yang modern (welcome page).
- Melihat **daftar event** (card grid).
- Menggunakan **search & filter**:
  - Kata kunci (nama event),
  - Lokasi,
  - Tanggal mulai,
  - Kategori (drop-down dari kategori yang tersedia).
- Melihat detail event penting di card (gambar, judul, tanggal, lokasi, harga ringkas).
- Jika klik **Book Ticket**, diarahkan ke halaman **login / register** karena booking hanya boleh untuk user yang sudah login.

#### 2) Registered User (`role = registered_user`)

- **User Dashboard**
  - Menampilkan **daftar event** yang tersedia (tanpa harus search dulu).
  - Search & filter event (keyword, lokasi, tanggal mulai, kategori).
  - Card event dengan:
    - Gambar besar event (image),
    - Nama event, tanggal, waktu, lokasi,
    - Kategori,
    - Deskripsi singkat.
  - Aksi pada event:
    - **Lihat & Booking** → masuk ke flow booking event tersebut.
    - **Tambah ke Favorite** → masuk ke daftar favorit user.
- **Booking**
  - Membuat booking baru untuk sebuah event (pilih ticket type & quantity).
  - Status awal booking: `pending`.
  - Melihat **riwayat booking**:
    - Tabel/daftar booking: event, ticket type, quantity, status, tanggal.
    - **Download E-Ticket (PDF)** jika status `approved`.
    - **Cancel booking** (mengubah status & mengembalikan kuota jika sebelumnya `approved`).
- **Favorite**
  - Menambah event ke **favorite (wishlist)**.
  - Melihat halaman **Favorite Events**:
    - Card event favorit dengan gambar, informasi event, dan tombol **hapus dari favorit**.
- **Notifikasi**
  - Melihat daftar notifikasi yang diterima:
    - Misalnya: booking disetujui, booking dibatalkan, dll.
  - Menandai notifikasi sebagai **read**.

#### 3) Event Organizer (`role = organizer`)

> Organizer harus melalui flow **approval** oleh admin.

- **Dashboard Organizer**
  - Ringkasan event milik organizer (total event, tiket terjual, sisa kuota, total revenue).
  - Grafik penjualan per bulan (Chart.js).
- **Manajemen Event (hanya event miliknya)**
  - Membuat event baru.
  - Mengedit / menghapus event miliknya.
  - Mengupload gambar event.
  - Mengelola **ticket types** untuk masing-masing event (quota, harga, dsb).
- **Manajemen Booking (untuk event miliknya)**
  - Melihat booking yang masuk untuk event yang ia buat.
  - **Approve / Cancel booking**.
  - Ketika approve/cancel, kuota tiket akan terupdate dan notifikasi dikirim ke user.
- **Notifikasi Organizer**
  - Mendapat notifikasi ketika:
    - Ada booking baru untuk event miliknya,
    - Booking dibatalkan, dsb.

#### 4) Admin (`role = admin`)

- **Dashboard Admin**
  - Overview global sistem:
    - Total users, total organizers, total events, total bookings.
  - Chart analytics global (booking & revenue per bulan) dengan **Chart.js**.
  - UI dashboard dengan desain **solid + glassmorphism** (modern, interaktif, animatif).

- **User Management**
  - Melihat daftar semua user (row/card style, dengan badge role & status).
  - Menyetujui (`approve`) organizer baru (`status: pending → approved`).
  - Menolak (`reject`) organizer (`status: pending → rejected`).
  - Menghapus user (kecuali dirinya sendiri).

- **Category Management**
  - CRUD kategori event.
  - Kategori digunakan sebagai filter & penandaan event di UI.

- **Event Management**
  - Melihat dan mengelola semua event (bukan hanya milik satu organizer).
  - CRUD event.

- **Booking Management**
  - Melihat semua booking.
  - Approve / cancel booking (global).

- **Notifications**
  - Mendapat notifikasi saat:
    - Ada organizer baru yang mendaftar,
    - Ada event baru yang dibuat organizer (sesuai implementasi notifikasi).

- **Analytics API (JSON)**
  - Endpoint JSON statistik global di `admin.analytics.json` untuk dipakai Chart.js di dashboard admin.

---

## 3. Struktur Role & Flow Bisnis

### 3.1. Flow Registrasi & Approval Organizer

1. User melakukan **register** di halaman `/register`.
2. User memilih ingin menjadi:
   - `registered_user` biasa, atau
   - `organizer` (ingin menyelenggarakan event).
3. Jika memilih **organizer**:
   - `role = organizer`,
   - `status = pending`.
4. Admin masuk ke **Admin → User Management**:
   - Jika approve → `status = approved`, organizer dapat membuat event.
   - Jika reject → `status = rejected`, organizer tidak bisa membuat event.

### 3.2. Flow Booking Tiket

1. User login sebagai **registered_user**.
2. Di **User Dashboard**, pilih event lalu klik **Lihat & Booking**.
3. Pada halaman booking:
   - Pilih **Ticket Type**,
   - Isi quantity.
4. Booking tersimpan dengan `status = pending`.
5. Admin / Organizer melihat daftar booking:
   - Klik **Approve**:
     - `status` → `approved`,
     - Kuota tiket berkurang,
     - User mendapat notifikasi,
     - E-ticket PDF bisa didownload user.
   - Klik **Cancel**:
     - `status` → `cancelled`,
     - Jika sebelumnya `approved`, kuota dikembalikan,
     - User mendapat notifikasi.

---

## 4. Instalasi & Setup

### 4.1. Clone & Install Dependencies

```bash
# Clone repo (ganti URL dengan repository kamu)
git clone https://github.com/username/event-ticket.git
cd event-ticket

# Install dependency PHP
composer install

# Install dependency frontend (NPM)
npm install

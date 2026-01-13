# Activity Analytics Dashboard

Dashboard analitik aktivitas user berbasis Laravel untuk menampilkan:

-   Total aktivitas
-   User aktif
-   Rata-rata aktivitas per hari
-   Most action
-   Visualisasi chart (activity per day, activity by action, top user)

Project ini menggunakan:

-   Laravel
-   Blade
-   Chart.js
-   jQuery (AJAX)
-   Vite (asset bundler)

---

## 🚀 Requirement

-   PHP >= 8.1
-   Composer
-   Node.js >= 18
-   NPM / Yarn
-   MySQL

---

## 📦 Instalasi

### 1️⃣ Clone Repository

```bash
git clone <repository-url>
cd <project-folder>
```

### 2️⃣ Install Dependency Backend

```bash
composer install
```

### 3️⃣ Copy Environment File

```bash
cp .env.example .env
```

Lalu sesuaikan konfigurasi database di file `.env`.

### 4️⃣ Generate Application Key

```bash
php artisan key:generate
```

### 5️⃣ Install Dependency Frontend

```bash
npm install
```

### 6️⃣ Migrasi & Seeder Database

```bash
php artisan migrate --seed
```

#### 🔐 Akun Admin Testing

| Field    | Value          |
| -------- | -------------- |
| Username | admin          |
| Email    | admin@test.com |
| Password | admin123       |

### 7️⃣ Jalankan Aplikasi

Backend:

```bash
php artisan serve
```

Frontend (Vite):

```bash
npm run dev
```

---

## 📊 Fitur Dashboard

-   Filter tanggal (AJAX, tanpa reload)
-   Filter action
-   Chart interaktif
-   Statistik realtime berdasarkan filter
-   Optimized query dengan base filter

---

## 🧠 Catatan Teknis

-   Backend sebagai source of truth
-   Chart hanya visualisasi
-   Statistik dihitung langsung dari database

---

## 📌 Author

Developed for technical test & analytics dashboard showcase.

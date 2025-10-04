# Financial Management System

Aplikasi manajemen keuangan berbasis Laravel dengan Vue.js dan Inertia.js untuk mengelola transaksi, chart of accounts, kategori, dan laporan keuangan.

## 🚀 Fitur Utama

- **Manajemen Transaksi**: Input, edit, dan hapus transaksi keuangan
- **Chart of Accounts**: Kelola akun-akun keuangan
- **Kategori**: Organisasi transaksi berdasarkan kategori
- **Laporan**: Generate laporan profit & loss
- **Export Data**: Export laporan ke Excel
- **Autentikasi**: Sistem login/register yang aman
- **Dashboard**: Overview keuangan yang informatif

## 📋 Persyaratan Sistem

- PHP >= 8.2
- Composer
- Node.js >= 18.x
- NPM atau Yarn
- Database (MySQL/PostgreSQL/SQLite)

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel 12.x
- **Frontend**: Vue.js 3 + Inertia.js
- **Styling**: Tailwind CSS
- **Database**: MySQL/PostgreSQL/SQLite
- **Export**: Laravel Excel (Maatwebsite)
- **Icons**: Heroicons
- **Notifications**: SweetAlert2

## 📦 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/IsnaNurul/finance-app.git
cd financial-management-system
```

### 2. Install Dependencies PHP

```bash
composer install
```

### 3. Install Dependencies Node.js

```bash
npm install
```

### 4. Konfigurasi Environment

```bash
cp .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Jalankan Migrasi Database

```bash
php artisan migrate
```

### 7. Seed Database (Opsional)

```bash
php artisan db:seed
```

### 8. Build Assets

```bash
npm run build
```

### 9. Jalankan Aplikasi

```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## 🔧 Development

### Menjalankan dalam Mode Development

```bash
# Terminal 1: Laravel Server
php artisan serve

# Terminal 2: Vite Dev Server
npm run dev

# Atau jalankan keduanya sekaligus
composer run dev
```

## 📁 Struktur Proyek

```
├── app/
│   ├── Http/Controllers/     # Controller untuk handling request
│   ├── Models/              # Eloquent models
│   ├── Services/            # Business logic services
│   └── Exports/             # Export classes untuk Excel
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
├── resources/
│   ├── js/
│   │   ├── Components/     # Vue components
│   │   ├── Pages/          # Inertia pages
│   │   └── Layouts/        # Layout components
│   └── views/              # Blade templates
└── routes/
    └── web.php             # Web routes
```

## 🗄️ Database Schema

### Tabel Utama

- **users**: Data pengguna
- **categories**: Kategori transaksi
- **chart_of_accounts**: Chart of accounts
- **transactions**: Data transaksi keuangan

## 👤 Default User

Setelah menjalankan seeder, Anda dapat login dengan:

- **Email**: admin@test.com
- **Password**: password

## 🔐 Autentikasi

Aplikasi menggunakan Laravel Breeze untuk autentikasi dengan fitur:

- Login/Register
- Email verification
- Password reset
- Profile management

## 🆘 Troubleshooting

### Error: Class not found
```bash
composer dump-autoload
```

### Error: Permission denied
```bash
# Linux/Mac
chmod -R 755 storage bootstrap/cache

# Windows
# Pastikan folder storage dan bootstrap/cache dapat diakses
```

### Error: Database connection
- Pastikan database server berjalan
- Periksa konfigurasi di file `.env`
- Pastikan database sudah dibuat

### Error: Node modules
```bash
rm -rf node_modules package-lock.json
npm install
```

## 📞 Support

Jika mengalami masalah atau memiliki pertanyaan, silakan:

1. Buat issue di GitHub repository
2. Periksa dokumentasi Laravel: https://laravel.com/docs
3. Periksa dokumentasi Vue.js: https://vuejs.org/guide/

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - PHP Framework
- [Vue.js](https://vuejs.org) - JavaScript Framework
- [Inertia.js](https://inertiajs.com) - Modern monolith approach
- [Tailwind CSS](https://tailwindcss.com) - CSS Framework
- [Laravel Breeze](https://laravel.com/docs/starter-kits#laravel-breeze) - Authentication scaffolding
# 🌐 Website Pemerintah Kabupaten Madiun 🌐

**Website Pemerintah Kabupaten Madiun** adalah sebuah platform berbasis web yang dibangun menggunakan Laravel, Vue.js, MySQL, dan PHPMyAdmin, yang dijalankan dalam lingkungan Docker. Website ini bertujuan untuk menyediakan informasi dan layanan publik kepada masyarakat Kabupaten Madiun.

---

## 📁 Daftar Isi
1. [Persyaratan Sistem](#-persyaratan-sistem)
2. [Instalasi](#-instalasi)
3. [Cara Menjalankan](#-cara-menjalankan)
4. [Import Tabel Users](#-import-tabel-users)
5. [Kredit](#-kredit)

---

## 💻 Persyaratan Sistem

### Sistem Operasi
- **OS**: Windows 10/11, macOS, atau Linux (Ubuntu/Debian/CentOS)  
- **Docker**: Docker Desktop 4.38.0

### Versi Software
- **PHP**: 8.2
- **Laravel**: 11.31
- **Node.js**: 18.x
- **Nginx**: Alpine latest
- **MySQL**: 8.0
- **PHPMyAdmin**: latest
- **Composer**: latest

### Replikasi
- **Database Replication**: MySQL Master-Slave replication (opsional)  
- **Storage**: Volume Docker untuk MySQL data (`mysql_data`)  

### Database
- **MySQL**: Versi 8.0  
- **PHPMyAdmin**: Untuk manajemen database  

### Patch/Extension
- **PHP**: `pdo_mysql`, `mbstring`, `exif`, `pcntl`, `bcmath`, `gd`  
- **Node.js**: `npm` sudah terinstal secara default

### Port
- **Laravel App**: 8000  
- **MySQL**: 3307  
- **PHPMyAdmin**: 8080  

### RAM & CPU
- **RAM**: Minimal 4GB  
- **CPU**: Minimal 2 core  

### Domain
- **Local**: `localhost`  

### Storage
- **MySQL Data**: Volume Docker (`mysql_data`)  

### User SQL
- **MySQL User**: `kp` dengan password `kp`  
---

## 🏗️ Instalasi

1. **Clone Repository**  
   ```bash
   git clone https://github.com/username/repository.git
   cd repository
   ```

2. **Buat File `.env`**  
   Salin file `.env.example` ke `.env` dan sesuaikan konfigurasi database:  
   ```env
   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=berita
   DB_USERNAME=kp
   DB_PASSWORD=kp
   ```

3. **Jalankan Docker Compose**  
   ```bash
   docker-compose up --build
   ```
   **Catatan**: Semua perintah seperti `composer install`, `npm install`, `php artisan key:generate`, dan `php artisan migrate` sudah otomatis dijalankan melalui `docker-entrypoint.sh`.

---

## 🚀 Cara Menjalankan

1. **Start Container**  
   ```bash
   docker-compose up
   ```

2. **Stop Container**  
   ```bash
   docker-compose down
   ```

3. **Restart Container**  
   ```bash
   docker-compose restart
   ```

---

## 📥 Import Tabel Users

Untuk mengimpor tabel `users`, Anda dapat menggunakan salah satu dari dua cara berikut:

### Cara 1: Menggunakan Command Line
1. Masuk ke container MySQL:  
   ```bash
   docker exec -it mysql_db bash
   ```

2. Salin file `users.sql` ke dalam container:  
   ```bash
   docker cp /path/to/users.sql mysql_db:/tmp/users.sql
   ```

3. Import tabel `users` ke database:  
   ```bash
   mysql -u root -p
   use berita;
   DROP TABLE IF EXISTS users;
   mysql -u root -p berita < /tmp/users.sql
   exit
   ```

### Cara 2: Menggunakan PHPMyAdmin
1. Buka PHPMyAdmin di browser:  
   `http://localhost:8080`

2. Login dengan username `kp` dan password `kp`.

3. Pilih database `berita`.

4. Import file `users.sql` secara manual.

---

## 🏅 Kredit

Proyek ini dikembangkan oleh:

| Nama                        | Departemen                 | Institusi |
|-----------------------------|---------------------------|-----------|
| Mutiara Nurhaliza           | Teknologi Informasi       | ITS       |
| Etha Felisya Br Purba       | Teknologi Informasi       | ITS       |
| Rehana Putri Salsabilla     | Teknologi Informasi       | ITS       |
| Salsabila Amalia Harjanto   | Teknologi Informasi       | ITS       |

---


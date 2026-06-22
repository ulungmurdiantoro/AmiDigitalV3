# Deploy — VPS Debian 13 (Nginx + PHP-FPM + MariaDB)

Aplikasi Laravel 12 (PHP ≥ 8.2). Aset sudah ter-compile & ter-commit (Laravel Mix), `QUEUE_CONNECTION=sync` & `SESSION_DRIVER=file` → **tidak perlu** queue worker / Node build di server.

> Deploy rutin cukup jalankan **`bash deploy.sh`**. Bagian di bawah ini untuk **setup awal** server (sekali saja).

---

## 1. Paket sistem (sekali)

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx mariadb-server git unzip curl \
  php8.4-fpm php8.4-cli php8.4-mysql php8.4-mbstring php8.4-xml \
  php8.4-curl php8.4-zip php8.4-gd php8.4-bcmath php8.4-intl

# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

> Ekstensi **zip, xml, gd, mbstring** wajib: seeder membaca `database/data/*.xlsx` (PhpSpreadsheet) dan laporan PDF (mPDF). **`pdftotext` tidak diperlukan** di server — `database/data/lamdik.json` sudah ikut di-commit (pdftotext hanya dipakai saat regenerasi data di lokal).

## 2. Database (sekali)

```bash
sudo mysql <<'SQL'
CREATE DATABASE ami_digital CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'ami'@'localhost' IDENTIFIED BY 'GANTI_PASSWORD_KUAT';
GRANT ALL PRIVILEGES ON ami_digital.* TO 'ami'@'localhost';
FLUSH PRIVILEGES;
SQL
```

## 3. Ambil kode + konfigurasi (sekali)

```bash
sudo mkdir -p /var/www && sudo chown "$USER":www-data /var/www
cd /var/www
git clone <URL_REPO> ami && cd ami

cp .env.example .env   # jika .env.example tidak ada, buat .env manual
composer install --no-dev --optimize-autoloader
php artisan key:generate
```

Edit `.env` (minimal):

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.ac.id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ami_digital
DB_USERNAME=ami
DB_PASSWORD=GANTI_PASSWORD_KUAT

SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

Inisialisasi skema + data master + kriteria akreditasi (BAN-PT/LAMEMBA/LAMDIK dari `database/data/`):

```bash
php artisan migrate --force
php artisan db:seed --force      # buat user admin, prodi, kriteria, dll
php artisan storage:link
```

> Login awal hasil seeder: **admin / password** (segera ganti). Akun lain lihat `database/seeds/UserSeeder.php`.

## 4. Izin folder (sekali)

```bash
sudo chown -R www-data:www-data /var/www/ami/storage /var/www/ami/bootstrap/cache
sudo chmod -R ug+rwX /var/www/ami/storage /var/www/ami/bootstrap/cache
```

## 5. Nginx (sekali)

`/etc/nginx/sites-available/ami` (root menunjuk ke `public/`):

```nginx
server {
    listen 80;
    server_name domain-anda.ac.id;
    root /var/www/ami/public;
    index index.php;

    client_max_body_size 50M;   # upload dokumen

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.4-fpm.sock;
    }

    location ~ /\.(?!well-known).* { deny all; }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/ami /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

HTTPS (disarankan): `sudo apt install -y certbot python3-certbot-nginx && sudo certbot --nginx -d domain-anda.ac.id`.

---

## 6. Deploy rutin

Dari `/var/www/ami`:

```bash
bash deploy.sh
# atau set service FPM agar OPcache di-reload otomatis:
FPM_SERVICE=php8.4-fpm bash deploy.sh
```

`deploy.sh` melakukan: maintenance ON → `git reset --hard origin/master` → `composer install --no-dev` → `migrate --force` → rebuild cache (`optimize`) → `storage:link` → izin → maintenance OFF.

- Butuh isi/refresh data seeder saat deploy: `bash deploy.sh --seed` (idempoten; aman diulang, tidak menggandakan).
- Catatan: script memakai `git reset --hard` — server **tidak boleh** menyimpan perubahan kode lokal (`.env`, `storage/` tetap aman karena di-`.gitignore`).

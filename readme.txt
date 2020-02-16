-------------------------------------------------------------
---            CiFireCMS - Makes You Feel Home            ---
-------------------------------------------------------------

CiFireCMS adalah platform CMS open source gratis Indonesia dibuat menggunakan framework CodeIgniter3. Dengan konsep yang menarik dan mudah digunakan oleh siapa saja.



-------------------------------------------------
---            PERSYARATAN SYSTEM             ---
-------------------------------------------------

Web server   : Apache 2.4.x 
Versi PHP    : PHP 7.3.x dan PHP 5.6.x (PHP 5.6-Native Not recommended).
MySQL        : versi 5.7 keatas.
MariaDB      : versi 10.3 keatas.

Ekstensi PHP yang harus diperhatikan.
- pdo_mysql  = ON
- pdo_sqlite = ON
- json       = ON
- fileinfo   = ON
- intl       = ON



-------------------------------------------------
---                INSTALASI                  ---
-------------------------------------------------

1. Download source code CiFireCMS dari github atau dari situs resmi https://www.alweak.com
2. Extract file "cifirecms.zip" di directory web Anda. Pastikan file ".htaccess" ter-copy dengan baik.
3. Buat database baru untuk menampung semua tabel konfigurasi CiFireCMS.
4. Jalankan browser dan masuk ke alamat web anda.
   Jika tidak ada kesalahan, anda akan langsung di arahkan ke halaman instalasi.
5. Ikuti dengan benar prosedur dan langkah-langkah instalasi.
6. Jika instalasi sudah selesai dan berhasil,
   jangan lupa untuk menghapus folder "install" dan file-file lainnya kecuali file "index.php" 
   dan ".htaccess".
7. CiFireCMS siap digunakan.




-------------------------------------------------
---           KONFIGURASI LANJUTAN            ---
-------------------------------------------------

## Permission
Ubah user permission folder-folder berikut menjadi 775.

folder-web-anda
├── content
│   ├── temp    --> 775
│   ├── thumbs  --> 775
└── └── uploads --> 775




-------------------------------------------------
---                 REDIRECT                  ---
-------------------------------------------------

Konfigurasi file ".htaccess" seperti berikut.

RewriteEngine On
RewriteCond $1 !^(index\.php|resources|robots\.txt)
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L,QSA]


Untuk menentukan web anda di akses dengan alamat "http://" atau "https://" silahkan ubah konfigurasi file ".htaccess" dan tambahkan kode berikut di bawah baris kode "RewriteEngine On".


# Redirect HTTP to HTTPS - non-www to www.
RewriteCond %{HTTPS} off [OR]
RewriteCond %{HTTP_HOST} !^www\. [NC]
RewriteCond %{HTTP_HOST} ^(?:www\.)?(.+)$ [NC]
RewriteRule ^ https://www.%1%{REQUEST_URI} [L,NE,R=301]


# Redirect HTTP to HTTPS - www to non-www.
RewriteCond %{HTTPS} off [OR]
RewriteCond %{HTTP_HOST} ^www\. [NC]
RewriteCond %{HTTP_HOST} ^(?:www\.)?(.+)$ [NC]
RewriteRule ^ https://%1%{REQUEST_URI} [L,NE,R=301]


# Redirect HTTPS to HTTP -  non-www to www.
RewriteCond %{HTTPS} on [OR]
RewriteCond %{HTTP_HOST} !^www\. [NC]
RewriteCond %{HTTP_HOST} ^(?:www\.)?(.+)$ [NC]
RewriteRule ^ http://www.%1%{REQUEST_URI} [L,NE,R=301]


# Redirect HTTPS to HTTP -  www to non-www.
RewriteCond %{HTTPS} on [OR]
RewriteCond %{HTTP_HOST} ^www\. [NC]
RewriteCond %{HTTP_HOST} ^(?:www\.)?(.+)$ [NC]
RewriteRule ^ http://%1%{REQUEST_URI} [L,NE,R=301]



-------------------------------------------------
---                PRODUCTION                 ---
-------------------------------------------------

Jika web sudah siap di online-kan silahkan ubah kode pada "index.php"

- Cari baris kode berikut :
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');

- Ubah menjadi seperti berikut :
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'production');



-------------------------------------------------
---              AKSES HALAMAN                ---
-------------------------------------------------

- Login halaman administrator : http://nama-web-anda/l-admin
- Masukkan data login seperti yg telah diinputkan pada saat proses instalasi.

- Login halaman member : http://nama-web-anda/l-member
- Register member : http://nama-web-anda/l-member/register



-------------------------------------------------
---                   MAIL                    ---
-------------------------------------------------

 protocol    = SMTP
 smtp_host   = ssl://nama.smtp.host
 smtp_port   = 465


-------------------------------------------------
---            Official Links                 ---
-------------------------------------------------
## Official Links
Website        : https://www.alweak.com
GitHub         : https://github.com/CiFireCMS
Facebook       : https://web.facebook.com/cifirecms
Facebook group : https://web.facebook.com/groups/cifirecms/



-------------------------------------------------
---            Terima Kasih Kepada            ---
-------------------------------------------------

1. Tuhan Yang Maha Esa.
2. Semua rekan-rekan yang berkontribusi untuk CiFireCMS.
3. Codeigniter3 sebagai core engine CiFireCMS.
4. Cizthemes sebagai pembuat template frontend versi 1.0.0.
5. SemiColonWeb sebagai pembuat template frontend versi 1.1.0.
6. Kopyov sebagai pembuat template backend.
7. Creative-tim sebagai pembuat template dasbor member.
8. Easy Menu Manager sebagai pembuat component menu manager.
9. Jquery, Bootstrap dan semua plugins jquery yang dipakai pada CiFireCMS.
10. DwiraSurvivor PopojiCMS untuk inspirasi, saran serta rekomendasi sehingga engine CiFireCMS bisa rilis.


## License
CiFireCMS is licensed under the MIT License.
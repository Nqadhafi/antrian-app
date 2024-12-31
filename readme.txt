Penggunaan dalam Localhost

1. Install Xampp
2. Pindahkan folder ini ke /xampp/htdocs/ganti_jadi_folder_anda
3. Buka phpmyadmin
4. import database antrian-app.sql yang ada di folder database
5. masukkan setting file .env seperti berikut
    APP_NAME=NamaAplikasiAnda
    APP_ENV=local
    APP_URL=http://localhost/ganti_jadi_folder_anda

    PUSHER_APP_ID=local-app-id
    PUSHER_APP_KEY=local-app-key
    PUSHER_APP_SECRET=local-app-secret
    PUSHER_HOST=127.0.0.1
    PUSHER_PORT=6001
    PUSHER_SCHEME=http
    PUSHER_APP_CLUSTER=mt1

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=antrian-app
    DB_USERNAME=root
    DB_PASSWORD=

    BROADCAST_DRIVER=pusher
6. pastikan anda menginstall composer
7. jalankan perintah "composer upgrade" di folder aplikasi anda
8. buka xampp\apache\conf\extra\httpd-vhost.conf menggunakan notepad
9. masukkan konfigurasi berikut kemudian save

<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/ganti_jadi_folder_anda/public"
    ServerName antrian.local
    <Directory "C:/xampp/htdocs/ganti_jadi_folder_anda/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

10. buka notepad dengan administrator, kemudian buka file C:\Windows\System32\drivers\etc\host
tambahkan konfigurasi berikut kemudian save

	127.0.0.1       domainlokalanda.local

11. Saya asumsikan sudah terinstall print thermal di komputer server anda
12. share printer thermal tersebut
13. buka file app/Http/Controller/PrintController.php
$connector = new WindowsPrintConnector("POS-58"); <<ganti dengan nama printer anda

14. Buka folder /resource/views
15. pada file user/index , user/display, dan filament/pages/antrian ganti konfigurasi wsHost:'127.0.0.1' menjadi IP statis komputer server
16. Kemudian bisa langsung digunakan, dengan start xampp dan mysql, lalu akses ip dari komputer server.
17. Untuk dispay ada di http://ipserver/display
18. Untuk admin ada di http://ipserver/admin
19. Jangan lupa untuk membuat user di admin dengan menggunakan perintah "php artisan make:filament-user"
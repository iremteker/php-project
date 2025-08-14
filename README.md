# PHP Projesi Kurulum ve Çalıştırma Talimatları

Bu proje PHP ve MySQL kullanılarak geliştirilmiştir. Aşağıdaki adımları izleyerek projeyi kendi bilgisayarınızda çalıştırabilirsiniz.

## 1. Gereksinimler
- PHP 7.4 veya üzeri
- MySQL veritabanı
- Apache sunucusu (XAMPP, WAMP, Laragon vb.)

## 2. Proje Dosyalarının Yerleştirilmesi
- İndirdiğiniz proje klasörünü `htdocs` (XAMPP kullanıyorsanız) dizinine kopyalayın.
  Örnek yol: `C:\xampp\htdocs\php_project`

## 3. Veritabanı Kurulumu
1. Tarayıcınızda `http://localhost/phpmyadmin` adresine gidin.
2. Yeni bir veritabanı oluşturun. Örnek isim: `php_project_db`
3. Proje klasörü ile birlikte verilen `php_project.sql` dosyasını içe aktarın.

## 4. Veritabanı Bağlantısı
- Gerekirse `test_db.php` dosyasında veya proje içindeki bağlantı dosyalarında veritabanı adı, kullanıcı adı ve şifreyi kendi sisteminize göre güncelleyin.

## 5. Projeyi Başlatma
- Tarayıcınızda aşağıdaki adrese gidin:
  http://localhost/php_project/index.php
- Buradan kullanıcı kaydı yapabilir veya giriş yapabilirsiniz.

## 6. Admin Paneli
- Yönetici olarak giriş yaptıktan sonra `admin.php` dosyası üzerinden içerik ekleme, düzenleme ve silme işlemleri yapılabilir.

## Notlar
- Oturumlar SESSION ile yönetilmektedir.
- "Beni hatırla" seçeneği COOKIE ile çalışmaktadır.
- Şifreler güvenli şekilde `password_hash()` fonksiyonu ile saklanmaktadır.

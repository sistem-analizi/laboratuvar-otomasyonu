# 🔬 Laboratuvar Otomasyonu

Çanakkale Onsekiz Mart Üniversitesi (ÇOMÜ) - Sistem Analizi dersi kapsamında geliştirilmiş bir laboratuvar yönetim sistemidir.

## 🚀 Proje Hakkında
Bu proje, bir laboratuvarın günlük işleyişini dijital ortama taşıyarak süreçleri hızlandırmayı, karmaşayı önlemeyi ve kayıtları düzenli tutmayı amaçlamaktadır. Cihaz zimmetleri, arıza takibi, sarf malzeme stok durumu ve ödünç verme işlemleri gibi kritik süreçler tek bir otomasyon üzerinden güvenle yönetilmektedir.

## 🛠 Kullanılan Teknolojiler
* **Backend:** PHP / Laravel
* **Veritabanı:** SQLite / MySQL
* **Frontend:** HTML, CSS, Bootstrap 
* **Versiyon Kontrol:** Git & GitHub

## 📌 Temel Modüller ve Özellikler
Sistem, birbirine tam entegre çalışan 18 farklı veritabanı tablosu üzerine inşa edilmiştir. Öne çıkan modüller şunlardır:

* **📦 Demirbaş ve Kalıcı Zimmet Yönetimi:** Laboratuvardaki kalıcı cihazların detaylı kayıtları, konumları ve zimmet geçmişi.
* **🧪 Sarf Malzeme ve Stok Takibi:** Sürekli tüketilen malzemelerin anlık stok durumları, marka/kategori bazlı listelenmesi ve kullanım geçmişleri.
* **🤝 Ödünç İşlemleri:** Ekipmanların öğrencilere veya personele ödünç verilmesi, teslimat süreleri ve takibi.
* **⚠️ Arıza ve Talep Yönetimi:** Arızalanan cihazların bildirilmesi, onarım süreçleri ve teknik destek talepleri.
* **👥 Kullanıcı ve Rol Yönetimi:** Güvenli oturum yönetimi ve yetkilendirilmiş erişim (Yönetici, Personel, Öğrenci).

## ⚙️ Kurulum ve Çalıştırma

Bu proje Laravel tabanlı bir laboratuvar otomasyon sistemidir. Uygulama internete açık bir sunucuda çalıştırılacaksa aşağıdaki adımlar izlenmelidir.

### 1. Sunucu Gereksinimleri

Sunucuda aşağıdaki bileşenlerin kurulu olması gerekir:

- PHP
- Composer
- MySQL veya MariaDB
- Web sunucusu: Apache, Nginx, cPanel, Plesk veya benzeri
- Gerekli PHP eklentileri:
    - PDO
    - Mbstring
    - OpenSSL
    - Tokenizer
    - XML
    - Ctype
    - Fileinfo
    - cURL

> Not: Gerekli PHP sürümü kullanılan Laravel sürümüne göre değişebilir. Bu nedenle `composer.json` dosyasındaki Laravel sürümü kontrol edilmelidir.

### 2. Projeyi Sunucuya Yükleyin

Projeyi GitHub üzerinden sunucuya klonlayın:

```
git clone https://github.com/sistem-analizi/laboratuvar-otomasyonu.git
cd laboratuvar-otomasyonu
```
veya proje dosyalarını FTP / dosya yöneticisi ile sunucuya yükleyin.

### 3. Composer Bağımlılıklarını Kurun

Canlı ortam için gerekli PHP paketlerini kurun:

```
composer install --no-dev --optimize-autoloader
```

### 4. Ortam Dosyasını Hazırlayın

.env.example dosyasını .env olarak kopyalayın:
```
cp .env.example .env
```
Ardından .env dosyasını canlı sunucu bilgilerine göre düzenleyin:
```
APP_NAME="Laboratuvar Otomasyonu"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://alanadiniz.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=veritabani_adi
DB_USERNAME=veritabani_kullanici_adi
DB_PASSWORD=veritabani_sifresi

SESSION_EXPIRE_ON_CLOSE=true
```

Uygulama anahtarını oluşturun:

```
php artisan key:generate
```

### 5. Veritabanını Hazırlayın

Sunucu panelinden veya MySQL üzerinden bir veritabanı oluşturun. Daha sonra tabloları oluşturmak için aşağıdaki komutu çalıştırın:

```
php artisan migrate --force
```


### 6. Dosya İzinlerini Ayarlayın

Laravel’in çalışabilmesi için storage ve bootstrap/cache klasörlerinin yazılabilir olması gerekir:

```
chmod -R 775 storage bootstrap/cache
```

Gerekirse dosya sahibi web sunucusu kullanıcısına göre ayarlanmalıdır.

### 7. Public Klasörünü Yayına Açın

Laravel projelerinde internete açılması gereken klasör sadece public klasörüdür.

Alan adınızın document root / web root değeri şu klasöre yönlendirilmelidir:

```
/laboratuvar-otomasyonu/public
```

### 8. Storage Link Oluşturun

Uygulamada dosya yükleme veya görsel işlemleri varsa aşağıdaki komut çalıştırılmalıdır:

```
php artisan storage:link
```

### 9. Cache ve Optimizasyon Komutlarını Çalıştırın

Canlı ortamda performans için Laravel cache komutları çalıştırılabilir:

```
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

Güncelleme sonrası sorun yaşanırsa cache temizlemek için:

```
php artisan optimize:clear
```

### 10. Frontend Dosyalarını Derleyin

Projede package.json dosyası varsa frontend bağımlılıklarını kurup derleyin:
```
npm install
npm run build
```

### 11. Yayına Alma Kontrol Listesi

Canlıya almadan önce aşağıdaki ayarlar kontrol edilmelidir:

- `APP_ENV=production` olarak ayarlanmalı.
- `APP_DEBUG=false` olmalı.
- `APP_URL` gerçek domain adresi olmalı.

- Veritabanı bilgileri doğru girilmeli
- Domain sadece public klasörüne yönlenmeli
- .env dosyası GitHub’a yüklenmemeli
- storage ve bootstrap/cache yazılabilir olmalı
- Canlı ortamda migrate:fresh kullanılmamalı

### 🌐 Canlı Kullanım

Kurulum tamamlandıktan sonra uygulamaya projenin yayınlanacağı gerçek domain adresi üzerinden erişilebilir:

```txt
https://alanadiniz.com
```

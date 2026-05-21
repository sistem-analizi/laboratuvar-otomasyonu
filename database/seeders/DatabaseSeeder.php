<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. ADIM: İnatçı Yabancı Anahtar Kısıtlamalarını Geçici Olarak Devre Dışı Bırak
        Schema::disableForeignKeyConstraints();

        // 2. ADIM: Sadece İskelet Tabloları Tamamen Sıfırla
        DB::table('roller')->truncate();
        DB::table('kategoriler')->truncate();
        DB::table('alt_kategori')->truncate();
        DB::table('konumlar')->truncate();
        DB::table('markalar')->truncate();
        DB::table('kullanicilar')->truncate();
        DB::table('urun_tipleri')->truncate();
        DB::table('urun_katalogu')->truncate();

        // Ürünlerini eklediğinde bu iki yorum satırını da açarsın:
        // DB::table('urun_tipleri')->truncate();
        // DB::table('urun_katalogu')->truncate();

        // 3. ADIM: İlişkilerin bozulmaması için kısıtlamaları tekrar aç
        Schema::enableForeignKeyConstraints();

        // 4. ADIM: ROLLER
        DB::table('roller')->insert([
            ['rol_id' => 1, 'rol_adi' => 'Ogrenci'],
            ['rol_id' => 2, 'rol_adi' => 'Laboratuvar Sorumlusu'],
            ['rol_id' => 3, 'rol_adi' => 'Personel'],
        ]);

        // 5. ADIM: ANA KATEGORİLER
        DB::table('kategoriler')->insert([
            ['kategori_id' => 1, 'kategori_adi' => 'Elektronik Donanımlar'],
            ['kategori_id' => 2, 'kategori_adi' => 'Bağlantı Elemanları'],
            ['kategori_id' => 3, 'kategori_adi' => 'Fiziksel Aletleri'],
            ['kategori_id' => 4, 'kategori_adi' => 'Sarf Malzemeleri'],
            ['kategori_id' => 5, 'kategori_adi' => 'Elektronik Test Donanımları'],
        ]);

        // 6. ADIM: ALT KATEGORİLER
        DB::table('alt_kategori')->insert([
            ['alt_kategori_id' => 1, 'alt_kategori_adi' => 'Mikrodenetleyiciler', 'kategori_id' => 1],
            ['alt_kategori_id' => 7, 'alt_kategori_adi' => 'Sensorler', 'kategori_id' => 1],
            ['alt_kategori_id' => 8, 'alt_kategori_adi' => 'Jumper Kablolar', 'kategori_id' => 2],
            ['alt_kategori_id' => 9, 'alt_kategori_adi' => 'Lehimleme Urunleri', 'kategori_id' => 3],
            ['alt_kategori_id' => 10, 'alt_kategori_adi' => '3D Yazici Filamentleri', 'kategori_id' => 4],
            ['alt_kategori_id' => 11, 'alt_kategori_adi' => 'Geliştirme Kartları', 'kategori_id' => 5],
            ['alt_kategori_id' => 15, 'alt_kategori_adi' => 'Breadboard', 'kategori_id' => 4],
            ['alt_kategori_id' => 16, 'alt_kategori_adi' => 'Motorlar', 'kategori_id' => 1],
            ['alt_kategori_id' => 17, 'alt_kategori_adi' => 'Görüntüleme Birimleri', 'kategori_id' => 1],
            ['alt_kategori_id' => 18, 'alt_kategori_adi' => 'Ledler', 'kategori_id' => 1],
        ]);

        // 7. ADIM: KONUMLAR
        DB::table('konumlar')->insert([
            ['konum_id' => 1, 'dolap_adi' => 'Ana Malzeme Dolabi', 'raf_numarasi' => 'Raf 1'],
            ['konum_id' => 2, 'dolap_adi' => 'Ana Malzeme Dolabi', 'raf_numarasi' => 'Raf 2'],
            ['konum_id' => 3, 'dolap_adi' => 'Sarf Malzeme Cekmecesi', 'raf_numarasi' => 'Cekmece A'],
            ['konum_id' => 4, 'dolap_adi' => 'Test Masasi', 'raf_numarasi' => 'Masa Ustu'],
        ]);

        // EKSİK OLAN ADIM BURASI: SABİT MARKALAR
        DB::table('markalar')->insert([
            ['marka_id' => 2, 'marka_adi' => 'Expressive'],
            ['marka_id' => 3, 'marka_adi' => 'Ardunio'],
            ['marka_id' => 4, 'marka_adi' => 'Creality'],
            ['marka_id' => 5, 'marka_adi' => 'RoHs'],
            ['marka_id' => 6, 'marka_adi' => 'Sunline'],
            ['marka_id' => 7, 'marka_adi' => 'OEM'],
            ['marka_id' => 8, 'marka_adi' => 'Tower Pro'],
            ['marka_id' => 9, 'marka_adi' => 'Raspberry Pi Foundation'],
        ]);

        // 8. ADIM: KULLANICILAR (Şifreler düz metin olarak değil, güvenli HASH ile eklendi!)
        DB::table('kullanicilar')->insert([
            ['kullanici_id' => 1, 'ad' => 'Kamil', 'soyad' => 'AKGÜN', 'email' => 'kamilakgun@mail.com', 'okul_no' => '2024001', 'sifre' => Hash::make('123456'), 'rol_id' => 2],
            ['kullanici_id' => 2, 'ad' => 'Murat', 'soyad' => 'SARI', 'email' => null, 'okul_no' => '2024002', 'sifre' => Hash::make('123456'), 'rol_id' => 3],
            ['kullanici_id' => 3, 'ad' => 'Ebutalib', 'soyad' => 'ÇELİK', 'email' => null, 'okul_no' => '2124001', 'sifre' => Hash::make('123456'), 'rol_id' => 3],
            ['kullanici_id' => 4, 'ad' => 'Melike', 'soyad' => 'TÜRK', 'email' => 'mely@gmail.com', 'okul_no' => '240928065', 'sifre' => Hash::make('860860'), 'rol_id' => 1],
            ['kullanici_id' => 5, 'ad' => 'Feyza', 'soyad' => 'ÇAL', 'email' => null, 'okul_no' => '240928025', 'sifre' => Hash::make('123'), 'rol_id' => 1],
            ['kullanici_id' => 6, 'ad' => 'Aysu Elmas', 'soyad' => 'ÇAKIR', 'email' => null, 'okul_no' => '240928023', 'sifre' => Hash::make('123'), 'rol_id' => 1],
            ['kullanici_id' => 7, 'ad' => 'Deniz', 'soyad' => 'YILDIRIM', 'email' => 'dyildirim@mail.com', 'okul_no' => '240928068', 'sifre' => Hash::make('282828'), 'rol_id' => 1],
        ]);

        // 9. ADIM: ÜRÜN TİPLERİ VE ÜRÜN KATALOĞU

        // Ürün Tipleri Ekleme
        DB::table('urun_tipleri')->insert([
            ['tip_id' => 1, 'tip_adi' => 'Demirbaş'],
            ['tip_id' => 2, 'tip_adi' => 'Sarf Malzemesi'],
        ]);

        // Ürün Kataloğu Ekleme
        DB::table('urun_katalogu')->insert([
            [
                'urun_adi' => 'Ardunio Deney Seti', // <-- Burayı dolduracaksın
                'alt_kategori_id' => 1,
                'teknik_detay' => 'ATmega328P mikrodenetleyici tabanlı geliştirme kartı.',
                'tip_id' => 1,
                'urun_kodu' => '10101-016',
                'dolap_konumu' => null,
                'resim_yolu' => null,
                'marka_id' => null
            ],
            [
                'urun_adi' => 'Creality Beyaz PLA Filament', // <-- Burayı dolduracaksın
                'alt_kategori_id' => 10,
                'teknik_detay' => '1.75mm, 1kg Beyaz renkli PLA.',
                'tip_id' => 2,
                'urun_kodu' => '20410-017',
                'dolap_konumu' => null,
                'resim_yolu' => null,
                'marka_id' => null
            ],
            [
                'urun_adi' => 'Esp-32', // <-- Burayı dolduracaksın
                'alt_kategori_id' => 1,
                'teknik_detay' => '...',
                'tip_id' => 1,
                'urun_kodu' => '10101-018',
                'dolap_konumu' => null,
                'resim_yolu' => null,
                'marka_id' => null
            ],
            [
                'urun_adi' => 'Arduino Uno R3 (CH340 Çipli Klon)', // <-- Burayı dolduracaksın
                'alt_kategori_id' => 11,
                'teknik_detay' => 'Çalışma gerilimi 5V, önerilen besleme gerilimi 7-12V...',
                'tip_id' => 1,
                'urun_kodu' => '10511-024',
                'dolap_konumu' => null,
                'resim_yolu' => null,
                'marka_id' => 3
            ],
            [
                'urun_adi' => 'HC-SR04 Ultrasonik Mesafe Sensörü', // <-- Burayı dolduracaksın
                'alt_kategori_id' => 7,
                'teknik_detay' => '2 cm ile 400 cm arasındaki mesafeleri temassız olara...',
                'tip_id' => 1,
                'urun_kodu' => '10107-025',
                'dolap_konumu' => null,
                'resim_yolu' => null,
                'marka_id' => 5
            ],
            [
                'urun_adi' => '40 Pin Ayrılabilen Erkek-Erkek Jumper Kablo (20 cm)', // <-- Burayı dolduracaksın
                'alt_kategori_id' => 8,
                'teknik_detay' => 'Breadboard (devre tahtası) ve prototipleme projeleri...',
                'tip_id' => 2,
                'urun_kodu' => '20208-026',
                'dolap_konumu' => null,
                'resim_yolu' => null,
                'marka_id' => 5
            ],
            [
                'urun_adi' => 'Isı Ayarlı Dijital Havya İstasyonu (60W)', // <-- Burayı dolduracaksın
                'alt_kategori_id' => 9,
                'teknik_detay' => '200°C ile 480°C arasında hassas ısı ayarı yapılabile...',
                'tip_id' => 1,
                'urun_kodu' => '10309-027',
                'dolap_konumu' => null,
                'resim_yolu' => null,
                'marka_id' => 6
            ],
            [
                'urun_adi' => '400 Pin Breadboard (Yarım Boy Devre Tahtası)', // <-- Burayı dolduracaksın
                'alt_kategori_id' => 15,
                'teknik_detay' => 'Lehim yapmadan geçici devre kurmayı sağlar. Ortadaki...',
                'tip_id' => 2,
                'urun_kodu' => '20107-028',
                'dolap_konumu' => null,
                'resim_yolu' => null,
                'marka_id' => 7
            ],
            [
                'urun_adi' => 'SG90 Mini Servo Motor', // <-- Burayı dolduracaksın
                'alt_kategori_id' => 16,
                'teknik_detay' => '5V gerilim ile çalışır, plastik dişlilere sahiptir. ...',
                'tip_id' => 1,
                'urun_kodu' => '10107-029',
                'dolap_konumu' => null,
                'resim_yolu' => null,
                'marka_id' => 8
            ],
            [
                'urun_adi' => 'DHT11 Isı ve Nem Sensör Modülü', // <-- Burayı dolduracaksın
                'alt_kategori_id' => 7,
                'teknik_detay' => 'Ortamdaki sıcaklık (0-50°C) ve nem (%20-90) değerler...',
                'tip_id' => 1,
                'urun_kodu' => '10107-030',
                'dolap_konumu' => null,
                'resim_yolu' => null,
                'marka_id' => 7
            ],
            [
                'urun_adi' => '5V Aktif Buzzer Modülü', // <-- Burayı dolduracaksın
                'alt_kategori_id' => 7,
                'teknik_detay' => '5V DC gerilim ile çalışır. Sadece güç verilerek tek ...',
                'tip_id' => 2,
                'urun_kodu' => '20107-031',
                'dolap_konumu' => null,
                'resim_yolu' => null,
                'marka_id' => 7
            ],
            [
                'urun_adi' => '16x2 Karakter LCD Ekran (I2C Modüllü)', // <-- Burayı dolduracaksın
                'alt_kategori_id' => 17,
                'teknik_detay' => '16 sütun ve 2 satırdan oluşan, mavi arka planlı liki...',
                'tip_id' => 1,
                'urun_kodu' => '10107-032',
                'dolap_konumu' => null,
                'resim_yolu' => null,
                'marka_id' => 7
            ],
            [
                'urun_adi' => '5V RGB LED Modülü', // <-- Burayı dolduracaksın
                'alt_kategori_id' => 18,
                'teknik_detay' => 'Tek bir LED paketi içinden kırmızı, yeşil ve mavi re...',
                'tip_id' => 1,
                'urun_kodu' => '10118-034',
                'dolap_konumu' => null,
                'resim_yolu' => null,
                'marka_id' => 7
            ]
        ]);
    }
}

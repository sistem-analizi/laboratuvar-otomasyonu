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

        // 2. ADIM: Tabloları Tamamen Sıfırla (Mükerrer kaydı kökten engeller)
        DB::table('roller')->truncate();
        DB::table('kullanicilar')->truncate();
        // Eğer cihazlar veya kategoriler için de seeder yazacaksan onları da buraya ekleyebilirsin:
        // DB::table('cihazlar')->truncate();

        // 3. ADIM: İlişkilerin bozulmaması için kısıtlamaları tekrar aç
        Schema::enableForeignKeyConstraints();

        // 4. ADIM: Sistem Rollerini Yeniden İnşa Et
        $roller = [
            ['rol_id' => 1, 'rol_adi' => 'Öğrenci'],
            ['rol_id' => 2, 'rol_adi' => 'Laboratuvar Sorumlusu'],
            ['rol_id' => 3, 'rol_adi' => 'Personel'],
        ];

        foreach ($roller as $rol) {
            DB::table('roller')->insert($rol);
        }

        // 5. ADIM: Varsayılan Sorumlu Kullanıcıyı Güvenli Şekilde Ekle
        // firstOrCreate e-posta adresine bakar; eğer canlıda varsa tekrar eklemez!
        User::firstOrCreate(
            ['email' => 'kamil@kamilakgun.com.tr'], // Benzersiz kontrol alanı
            [
                'ad' => 'Kamil',
                'soyad' => 'Akgün',
                'okul_no' => '2024001',
                'rol_id' => 2, // Laboratuvar Sorumlusu
                'sifre' => Hash::make('123456'),
            ]
        );

        // 6. ADIM: Varsayılan Öğrenci Kullanıcıyı Güvenli Şekilde Ekle
        User::firstOrCreate(
            ['email' => 'mely@gmail.com'], // Fotoğraftaki öğrenci e-postası
            [
                'ad' => 'Melike',
                'soyad' => 'Türk',
                'okul_no' => '240928065',
                'rol_id' => 1, // Öğrenci
                'sifre' => Hash::make('860860'),
            ]
        );
    }
}

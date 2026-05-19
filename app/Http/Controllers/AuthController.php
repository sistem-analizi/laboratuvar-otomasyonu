<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Giriş Sayfasını Göster (GET)
    public function giris_sayfasi() {
        return view('login');
    }

    // Giriş Yapma İşlemi (POST)
    public function giris_yap(Request $request) {

        $kullanici = DB::table('kullanicilar')
            ->where('okul_no', $request->okul_no)
            ->where('sifre', $request->sifre)
            ->first();

        // Kullanıcı bulunduysa giriş yap
        if ($kullanici) {

            // Hata veren Auth satırını tamamen sildik.
            // Sadece senin kusursuz çalışan session mantığını kullanıyoruz.
            // ... Session atama kısımları aynı kalıyor ...
            session([
                'kullanici_id' => $kullanici->kullanici_id,
                'ad_soyad' => $kullanici->ad . ' ' . $kullanici->soyad,
                'rol_id' => $kullanici->rol_id
            ]);

            // DİKKAT: 1 numara Öğrenci ise direkt profile, değilse (2 veya 3 ise) ana sayfaya (Kataloğa) git
            if ($kullanici->rol_id == 1) {
                return redirect('profil');
            } else {
                return redirect('/');
            }

        } else {
            return back()->with('hata', 'Numara veya şifre hatalı. Lütfen tekrar deneyin.');
        }
    }


    // Kayıt Sayfasını Göster (GET)
    public function kayit_sayfasi() {
        return view('kayit');
    }

    // Kayıt Olma İşlemi (POST)
    public function kayit_ol(Request $request) {
        $var_mi = DB::table('kullanicilar')->where('okul_no', $request->okul_no)->first();
        if ($var_mi) {
            return back()->with('hata','Bu okul numarası zaten kayıtlı!');
        }

        DB::table('kullanicilar')->insert([
            'ad' => $request->ad,
            'soyad' => $request->soyad,
            'okul_no' => $request->okul_no,
            'sifre' => $request->sifre,
            'rol_id' => 1
        ]);
        return redirect('giris');
    }

    // Çıkış Yap
    public function cikis() {
        session()->flush();
        return redirect('giris');
    }
}

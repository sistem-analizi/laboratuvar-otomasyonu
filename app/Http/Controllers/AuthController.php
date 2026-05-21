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

        // 1. ADIM: Kullanıcıyı ŞİFRESİZ, sadece okul numarasına göre arıyoruz
        $kullanici = DB::table('kullanicilar')
            ->where('okul_no', $request->okul_no)
            ->first();

        // 2. ADIM: Kullanıcı var mı VE girilen şifre veritabanındaki şifreli halini doğruluyor mu?
        if ($kullanici && \Illuminate\Support\Facades\Hash::check($request->sifre, $kullanici->sifre)) {

            // Giriş başarılı! Senin yazdığın kusursuz session mantığı aynen çalışıyor:
            session([
                'kullanici_id' => $kullanici->kullanici_id, // Eğer anahtar sütunun id ise 'id' yapabilirsin
                'ad_soyad' => $kullanici->ad . ' ' . $kullanici->soyad,
                'rol_id' => $kullanici->rol_id
            ]);

            // Rol yönlendirmelerin de harika, aynen koruyoruz:
            if ($kullanici->rol_id == 1) {
                return redirect('profil');
            } else {
                return redirect('/');
            }

        } else {
            // Kullanıcı yoksa veya şifre hash kontrolünden geçemediyse:
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

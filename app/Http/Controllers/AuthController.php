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

        // Formdan gelen bilgiyi değişkene alıyoruz (Okul no veya e-posta olabilir)
        $giris_verisi = $request->giris_bilgisi;

        // 1. ADIM: Kullanıcıyı ŞİFRESİZ, okul numarasına VEYA e-postaya göre arıyoruz
        $kullanici = DB::table('kullanicilar')
            ->where('okul_no', $giris_verisi)
            ->orWhere('email', $giris_verisi)
            ->first();

        // 2. ADIM: Kullanıcı var mı VE girilen şifre veritabanındaki şifreli halini doğruluyor mu?
        if ($kullanici && \Illuminate\Support\Facades\Hash::check($request->sifre, $kullanici->sifre)) {

            // Giriş başarılı! Senin yazdığın kusursuz session mantığı aynen çalışıyor:
            session([
                'kullanici_id' => $kullanici->kullanici_id,
                'ad_soyad' => $kullanici->ad . ' ' . $kullanici->soyad,
                'rol_id' => $kullanici->rol_id
            ]);

            // Rol yönlendirmelerin aynen korundu:
            if ($kullanici->rol_id == 1) {
                return redirect('profil');
            } else {
                return redirect('/');
            }

        } else {
            // Kullanıcı yoksa veya şifre hash kontrolünden geçemediyse:
            return back()->with('hata', 'Giriş bilgileri veya şifre hatalı. Lütfen tekrar deneyin.');
        }
    }


    // Kayıt Sayfasını Göster (GET)
    public function kayit_sayfasi() {
        return view('kayit');
    }

    // Kayıt Olma İşlemi (POST)
    public function kayit_ol(Request $request) {

        // 1. Okul numarası zaten kayıtlı mı kontrolü (Senin yazdığın gibi kaldı)
        $var_mi = DB::table('kullanicilar')->where('okul_no', $request->okul_no)->first();

        if ($var_mi) {
            return back()->with('hata','Bu okul numarası zaten kayıtlı!');
        }

        // 2. Veritabanına kayıt ekleme işlemi
        DB::table('kullanicilar')->insert([
            'ad' => $request->ad,
            'soyad' => $request->soyad,
            'okul_no' => $request->okul_no,
            'email' => $request->email, // E-posta ile de giriş yapabilmen için bu sütunu ekledik
            'sifre' => \Illuminate\Support\Facades\Hash::make($request->sifre), // EN KRİTİK KISIM: Şifre artık şifrelenerek kaydediliyor
            'rol_id' => 1
        ]);

        return redirect('giris')->with('basari', 'Kaydınız başarıyla oluşturuldu! Sisteme giriş yapabilirsiniz.');
    }
    // Çıkış Yap
    public function cikis() {
        session()->flush();
        return redirect('giris');
    }
}

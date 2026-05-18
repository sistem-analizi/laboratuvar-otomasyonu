<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KullaniciController extends Controller
{
    public function kullanici_detay($id) {
        // 1. Öğrenci Bilgilerini Çek
        $kullanici = DB::table('kullanicilar')
            ->leftJoin('roller', 'kullanicilar.rol_id', '=', 'roller.rol_id')
            ->where('kullanici_id', $id)
            ->first();

        if (!$kullanici) {
            return redirect()->back()->withErrors('Kullanıcı bulunamadı!');
        }

        // 2. AKTİF ÖDÜNÇLER (join yerine leftJoin kullandık ki ilişkiler kopsa bile veriyi görelim)
        $aktif_oduncler = DB::table('oduncler')
            ->leftJoin('demirbaslar', 'oduncler.demirbas_id', '=', 'demirbaslar.demirbas_id')
            ->leftJoin('urun_katalogu', 'demirbaslar.urun_id', '=', 'urun_katalogu.urun_id')
            ->where('oduncler.kullanici_id', $id)
            ->where(function($query) {
                // Türkçe karakter sorunu olmasın diye LIKE ve % jokerleri ekledik
                $query->where('oduncler.durum', 'LIKE', 'Kullan%mda')
                    ->orWhere('oduncler.durum', 'Gecikti');
            })
            ->select('oduncler.*', 'urun_katalogu.urun_adi', 'demirbaslar.seri_no')
            ->orderBy('oduncler.planlanan_iade_tarihi', 'asc')
            ->get();

        // 3. İADE GEÇMİŞİ (SADECE 'İade Edildi' olanlar)
        $gecmis_oduncler = DB::table('oduncler')
            ->leftJoin('demirbaslar', 'oduncler.demirbas_id', '=', 'demirbaslar.demirbas_id')
            ->leftJoin('urun_katalogu', 'demirbaslar.urun_id', '=', 'urun_katalogu.urun_id')
            ->where('oduncler.kullanici_id', $id)
            ->where('oduncler.durum', 'LIKE', '%ade Edildi%') // Kusurlu iade kaldırıldı, İade kelimesi esnek arandı
            ->select('oduncler.*', 'urun_katalogu.urun_adi', 'demirbaslar.seri_no')
            ->orderBy('oduncler.gerceklesen_iade_tarihi', 'desc')
            ->get();

        // 4. SARF KULLANIM GEÇMİŞİ (TÜKETİM RAPORU)
        $sarf_gecmisi = DB::table('sarf_kullanim_gecmisi')
            ->join('urun_katalogu', 'sarf_kullanim_gecmisi.urun_id', '=', 'urun_katalogu.urun_id')
            ->where('sarf_kullanim_gecmisi.kullanici_id', $id)
            ->select('sarf_kullanim_gecmisi.*', 'urun_katalogu.urun_adi')
            ->orderBy('sarf_kullanim_gecmisi.islem_tarihi', 'desc')
            ->get();

        // return view satırını güncellemeyi unutma:
        return view('kullanici_detay', compact('kullanici', 'aktif_oduncler', 'gecmis_oduncler', 'sarf_gecmisi'));

        return view('kullanici_detay', compact('kullanici', 'aktif_oduncler', 'gecmis_oduncler'));
    }

    public function kullanici_listesi() {
        // Sadece Öğrencileri çek
        $ogrenciler = DB::table('kullanicilar')
            ->leftJoin('roller', 'kullanicilar.rol_id', '=', 'roller.rol_id')
            ->where('roller.rol_adi', 'Ogrenci')
            ->orderBy('kullanicilar.ad', 'asc')
            ->get();

        // Sadece Personelleri çek (Lab Sorumlusu otomatik olarak dışarıda kalır)
        $personeller = DB::table('kullanicilar')
            ->leftJoin('roller', 'kullanicilar.rol_id', '=', 'roller.rol_id')
            ->where('roller.rol_adi', 'Personel')
            ->orderBy('kullanicilar.ad', 'asc')
            ->get();

        return view('kullanici_listesi', compact('ogrenciler', 'personeller'));
    }


    // Profil sayfasını görme metodu

    public function profil_sayfasi() {
        $kullanici_id = session('kullanici_id');
        $rol_id = session('rol_id');

        if (!$kullanici_id) {
            return redirect('/')->with('hata', 'Oturum bulunamadı.');
        }

        $user = DB::table('kullanicilar')->where('kullanici_id', $kullanici_id)->first();

        // 1. ÜZERİMDEKİ CİHAZLAR (Role göre değişir)
        if ($rol_id == 2) {
            // LAB SORUMLUSU İÇİN: Kalıcı Zimmetler
            $aktif_cihazlar = DB::table('kalici_zimmetler')
                ->join('demirbaslar', 'kalici_zimmetler.demirbas_id', '=', 'demirbaslar.demirbas_id')
                ->join('urun_katalogu', 'demirbaslar.urun_id', '=', 'urun_katalogu.urun_id')
                ->where('kalici_zimmetler.kullanici_id', $kullanici_id)
                ->get();

            $iade_gecmisi = collect(); // Kalıcı zimmette iade geçmişi olmaz
        } else {
            // ÖĞRENCİ (1) VE PERSONEL (3) İÇİN: Süreli Ödünçler tablosu

            // 1. Henüz iade edilmeyenler (gerceklesen_iade_tarihi boş olanlar)
            $aktif_cihazlar = DB::table('oduncler')
                ->join('demirbaslar', 'oduncler.demirbas_id', '=', 'demirbaslar.demirbas_id')
                ->join('urun_katalogu', 'demirbaslar.urun_id', '=', 'urun_katalogu.urun_id')
                ->where('oduncler.kullanici_id', $kullanici_id)
                ->whereNull('oduncler.gerceklesen_iade_tarihi') // BURASI DÜZELDİ
                ->get();

            // 2. İade edilenlerin geçmişi (gerceklesen_iade_tarihi dolu olanlar)
            $iade_gecmisi = DB::table('oduncler')
                ->join('demirbaslar', 'oduncler.demirbas_id', '=', 'demirbaslar.demirbas_id')
                ->join('urun_katalogu', 'demirbaslar.urun_id', '=', 'urun_katalogu.urun_id')
                ->where('oduncler.kullanici_id', $kullanici_id)
                ->whereNotNull('oduncler.gerceklesen_iade_tarihi') // BURASI DÜZELDİ
                ->get();
        }

        // 2. SARF TÜKETİM GEÇMİŞİ (Herkes için aynı)
        $sarf_gecmisi = DB::table('sarf_kullanim_gecmisi')
            ->join('urun_katalogu', 'sarf_kullanim_gecmisi.urun_id', '=', 'urun_katalogu.urun_id')
            ->where('sarf_kullanim_gecmisi.kullanici_id', $kullanici_id)
            ->get();

        return view('profil', compact('user', 'aktif_cihazlar', 'iade_gecmisi', 'sarf_gecmisi', 'rol_id'));
    }
    // Düzenleme sayfasını açan metot
    public function profil_duzenle_sayfasi() {
        $kullanici_id = session('kullanici_id');
        $user = DB::table('kullanicilar')->where('kullanici_id', $kullanici_id)->first();

        return view('profil_duzenle', compact('user'));
    }

    // Bilgileri güncelleyen metot (Zorunlulukları kaldırdık)
    public function profil_guncelle(Request $request) {
        $kullanici_id = session('kullanici_id');

        if (!$kullanici_id) {
            return redirect('/')->with('hata', 'Önce giriş yapmalısınız.');
        }

        $guncellenecek = [];
        // Sadece doldurduğun alanlar güncellenir
        if ($request->filled('ad')) $guncellenecek['ad'] = $request->ad;
        if ($request->filled('soyad')) $guncellenecek['soyad'] = $request->soyad;
        if ($request->filled('email')) $guncellenecek['email'] = $request->email;
        if ($request->filled('sifre')) $guncellenecek['sifre'] = $request->sifre;

        if (!empty($guncellenecek)) {
            DB::table('kullanicilar')
                ->where('kullanici_id', $kullanici_id)
                ->update($guncellenecek);

            // Eğer isim değiştiyse menüdeki ismin de anlık değişmesi için session'ı yeniliyoruz
            if (isset($guncellenecek['ad']) || isset($guncellenecek['soyad'])) {
                session(['ad_soyad' => ($request->ad ?? '') . ' ' . ($request->soyad ?? '')]);
            }
        }

        return redirect('/profil')->with('basari', 'Profil bilgileriniz güncellendi.');
    }




}

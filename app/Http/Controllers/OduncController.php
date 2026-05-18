<?php

namespace App\Http\Controllers;

use App\Models\Odunc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OduncController extends Controller
{
    public function cihaz_cikis_yap(Request $request)
    {
        // ==========================================
        // 0. DOĞRULAMA (VALIDATION) KONTROLÜ
        // ==========================================
        $request->validate([
            'urun_id' => 'required|integer',
            'kullanici_id' => 'required|integer',
            'miktar' => 'required|integer|min:1',
            'planlanan_iade_tarihi' => 'nullable|date' // Sarf için boş gelebileceğini sisteme söylüyoruz
        ], [
            'urun_id.required' => 'Lütfen katalogdan bir cihaz veya malzeme seçin.',
            'kullanici_id.required' => 'Lütfen işlemi yapacağınız kişiyi seçin.',
            'miktar.required' => 'Lütfen eklenecek miktarı girin.',
            'miktar.min' => 'Miktar en az 1 olmalıdır.'
        ]);

        // Formdan gelen temel verileri alıyoruz
        $urun_id = $request->input('urun_id');
        $kullanici_id = $request->input('kullanici_id');
        $verilen_miktar = $request->input('miktar', 1);

        // Ürünün tipini katalogdan bul
        $urun = DB::table('urun_katalogu')->where('urun_id', $urun_id)->first();

        if (!$urun) {
            return redirect()->back()->withErrors('Seçilen ürün katalogda bulunamadı.');
        }

        // ==========================================
        // DURUM A: ÜRÜN BİR "SARF MALZEMESİ" İSE (tip_id = 2)
        // ==========================================
        if ($urun->tip_id == 2) {

            // Önce stoğu kontrol et
            $stok = DB::table('sarf_stok_durumu')->where('urun_id', $urun_id)->first();

            if (!$stok || $stok->toplam_miktar < $verilen_miktar) {
                return redirect()->back()->withErrors('Hata: Yeterli stok yok! Mevcut stok: ' . ($stok->toplam_miktar ?? 0));
            }

            // Stoktan düş
            DB::table('sarf_stok_durumu')
                ->where('urun_id', $urun_id)
                ->decrement('toplam_miktar', $verilen_miktar);

            // Sarf kullanım geçmişine (tüketim) kaydet
            DB::table('sarf_kullanim_gecmisi')->insert([
                'urun_id' => $urun_id,
                'kullanici_id' => $kullanici_id,
                'kullanilan_miktar' => $verilen_miktar,
                'islem_tarihi' => \Carbon\Carbon::now(),
                'aciklama' => $request->input('aciklama', 'Laboratuvar çalışması için verildi.')
            ]);

            // YENİ: Başarı mesajının key'ini 'basari' yaptık (ön yüzdeki alert için)
            return redirect()->back()->with('basari', $verilen_miktar . ' adet Sarf Malzemesi stoktan düşüldü ve işlendi.');
        }

        // ==========================================
        // DURUM B: ÜRÜN BİR "DEMİRBAŞ" İSE (tip_id = 1)
        // ==========================================
        else if ($urun->tip_id == 1) {

            // EKSTRA GÜVENLİK: Demirbaş seçildiyse tarih zorunludur!
            if (!$request->filled('planlanan_iade_tarihi')) {
                return redirect()->back()->withErrors('Hata: Demirbaş cihazlar için Planlanan İade Tarihi seçilmesi zorunludur!');
            }

            // Formdan gelen miktarı alıyoruz
            $istenen_miktar = (int) $request->input('miktar', 1);

            // İstenen miktar kadar 'Bosta' olan cihazı bul
            $bos_cihazlar = DB::table('demirbaslar')
                ->where('urun_id', $urun_id)
                ->where('durum', 'Bosta')
                ->take($istenen_miktar)
                ->get();

            // Yeterli stok var mı kontrolü
            if ($bos_cihazlar->count() < $istenen_miktar) {
                return redirect()->back()->withErrors('Hata: İstenen miktarda boşta cihaz yok! Mevcut: ' . $bos_cihazlar->count());
            }

            // Döngüye gir ve istenen miktar kadar cihazı tek tek zimmetle
            foreach ($bos_cihazlar as $cihaz) {
                // 1. Ödünç tablosuna yaz
                DB::table('oduncler')->insert([
                    'demirbas_id' => $cihaz->demirbas_id,
                    'kullanici_id' => $kullanici_id,
                    'veren_yetkili_id' => auth()->user()->kullanici_id ?? 1,
                    'verilis_tarihi' => \Carbon\Carbon::now(),
                    'planlanan_iade_tarihi' => \Carbon\Carbon::parse($request->input('planlanan_iade_tarihi')),
                    'durum' => 'Kullanimda'
                ]);

                // 2. Demirbaş tablosunda durumu güncelle (Stoktan düşmesi için)
                DB::table('demirbaslar')
                    ->where('demirbas_id', $cihaz->demirbas_id)
                    ->update(['durum' => 'Zimmette']);
            }

            return redirect()->back()->with('basari', $istenen_miktar . ' adet cihaz başarıyla zimmetlendi.');
        }
    }
    public function odunc_ver_sayfasi() {
        $kullanicilar = DB::table('kullanicilar')
            ->leftJoin('roller', 'kullanicilar.rol_id', '=', 'roller.rol_id')
            ->orderBy('ad', 'asc')
            ->get();

        $urun_katalogu = DB::table('urun_katalogu')->orderBy('urun_adi', 'asc')->get();

        foreach($urun_katalogu as $urun) {
            if ($urun->tip_id == 2) {
                // SARF MALZEMESİ: sarf_stok_durumu tablosuna bakıyoruz
                $stok = DB::table('sarf_stok_durumu')->where('urun_id', $urun->urun_id)->first();
                $urun->bostaki_stok = $stok ? (int) $stok->toplam_miktar : 0;
            } else {
                // DEMİRBAŞ: demirbaslar tablosunda durumu sadece 'Bosta' olanları sayıyoruz!
                $urun->bostaki_stok = DB::table('demirbaslar')
                    ->where('urun_id', $urun->urun_id)
                    ->where('durum', 'Bosta') // Resimde gördüğüm birebir yazım
                    ->count();
            }
        }

        return view('odunc_ver', compact('kullanicilar', 'urun_katalogu'));
    }

    public function iade_al($odunc_id) {
        // 1. İşlem yapılacak ödünç kaydını bul
        $odunc = DB::table('oduncler')->where('odunc_id', $odunc_id)->first();

        if($odunc) {
            // 2. Ödünç tablosunu 'İade Edildi' olarak güncelle ve tarihi at
            DB::table('oduncler')
                ->where('odunc_id', $odunc_id)
                ->update([
                    'durum' => 'İade Edildi',
                    'gerceklesen_iade_tarihi' => \Carbon\Carbon::now(),
                    'alan_yetkili_id' => auth()->user()->kullanici_id ?? 1
                ]);

            // 3. ÇOK KRİTİK: Demirbaş tablosundaki durumu tekrar 'Bosta' yap!
            // Bunu yapmazsak cihaz sonsuza kadar o öğrencide "Zimmette" kalmış gibi görünür.
            DB::table('demirbaslar')
                ->where('demirbas_id', $odunc->demirbas_id)
                ->update(['durum' => 'Bosta']);
        }

        // 4. İşlem bitince kullanıcıyı başka yere atma, GELDİĞİ SAYFAYA (Profile) geri döndür
        return redirect()->back();
    }


    public function gecikenler()
    {
        $gecikenler = \Illuminate\Support\Facades\DB::table('oduncler')
            // Öğrenci bilgilerini getirmek için kullanicilar tablosunu bağlıyoruz
            ->join('kullanicilar', 'oduncler.kullanici_id', '=', 'kullanicilar.kullanici_id')
            ->leftJoin('demirbaslar', 'oduncler.demirbas_id', '=', 'demirbaslar.demirbas_id')
            ->leftJoin('urun_katalogu', 'demirbaslar.urun_id', '=', 'urun_katalogu.urun_id')
            // İade edilmemiş ve tarihi bugünden eski olanlar
            ->whereNull('oduncler.gerceklesen_iade_tarihi')
            ->where('oduncler.planlanan_iade_tarihi', '<', now())
            ->select(
                'oduncler.*',
                'urun_katalogu.urun_adi',
                'demirbaslar.seri_no',
                'kullanicilar.ad',
                'kullanicilar.soyad',
                'kullanicilar.okul_no'
            )
            ->orderBy('oduncler.planlanan_iade_tarihi', 'asc')
            ->get();

        return view('gecikenler', compact('gecikenler'));
    }


}

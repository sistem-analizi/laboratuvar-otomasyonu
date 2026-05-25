<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Veritabanı işlemleri için şart

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Giriş Kontrolü
        if (!session()->has('kullanici_id')) return redirect('/giris');

        // 2. Form için gerekli listeleri çek
        $ana_kategoriler = DB::table('kategoriler')->get();
        $alt_kategoriler = DB::table('alt_kategori')->get();
        $tipler = DB::table('urun_tipleri')->get();

        // 3. Ana Sorguyu Başlat
        $query = DB::table('urun_katalogu as u')
            ->join('alt_kategori as a', 'u.alt_kategori_id', '=', 'a.alt_kategori_id')
            ->join('kategoriler as k', 'a.kategori_id', '=', 'k.kategori_id')
            ->join('urun_tipleri as t', 'u.tip_id', '=', 't.tip_id')
            ->select('u.urun_id', 'u.urun_kodu', 'u.urun_adi', 'u.resim_yolu', 'a.alt_kategori_adi', 't.tip_adi as urun_tipi',
                DB::raw("(SELECT COUNT(*) FROM demirbaslar d WHERE d.urun_id = u.urun_id AND d.durum = 'bosta') as stok_adedi")
            );

        // 4. FİLTRELEME İŞLEMLERİ
        if ($request->filled('arama')) {
            $arama = $request->arama;
            $query->where(function($q) use ($arama) {
                $q->where('u.urun_adi', 'like', '%' . $arama . '%')
                    ->orWhere('u.urun_kodu', 'like', '%' . $arama . '%');
            });
        }

        if ($request->filled('tip')) {
            $query->where('u.tip_id', $request->tip);
        }

        if ($request->filled('ana_kategori')) {
            $query->where('k.kategori_id', $request->ana_kategori);
        }

        // İŞTE ÇÖZÜLEN KISIM BURASI! (alt_kategori -> alt_kategori_id yapıldı)
        if ($request->filled('alt_kategori_id')) {
            $query->where('u.alt_kategori_id', $request->alt_kategori_id);
        }

        // 5. Verileri getir ve sayfaya yolla
        $urunler = $query->orderBy('u.urun_adi', 'asc')->get();

        // ======= YENİ EKLENEN STOK HESAPLAMA KISMI =======
        foreach ($urunler as $urun) {
            // tip_id ana sorguda gelmediği için anlık olarak katalogdan öğreniyoruz
            $gercek_tip_id = DB::table('urun_katalogu')->where('urun_id', $urun->urun_id)->value('tip_id');

            if ($gercek_tip_id == 1) {
                // Demirbaş ise: Boşta olan cihazları say
                $urun->stok_adedi = DB::table('demirbaslar')
                    ->where('urun_id', $urun->urun_id)
                    ->where('durum', 'Bosta')
                    ->count();
            } else if ($gercek_tip_id == 2) {
                // BAŞINA (int) EKLENDİ! Artık 50.00 yerine direkt 50 olarak alacak.
                $urun->stok_adedi = (int) DB::table('sarf_stok_durumu')
                    ->where('urun_id', $urun->urun_id)
                    ->sum('toplam_miktar');
            }
        }
        return view('dashboard', compact('urunler', 'tipler', 'ana_kategoriler', 'alt_kategoriler'));
    }
}

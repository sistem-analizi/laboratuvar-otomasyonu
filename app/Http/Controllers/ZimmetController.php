<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class ZimmetController extends Controller
{
    public function kalici_zimmet_sayfasi() {
        if (session('rol_id') != 2 && session('rol_id') != 3) return redirect('/');

        $bostaki_cihazlar = DB::table('demirbaslar')
            ->join('urun_katalogu', 'demirbaslar.urun_id', '=', 'urun_katalogu.urun_id')
            ->where('demirbaslar.durum', 'Bosta')
            ->select('demirbaslar.demirbas_id', 'demirbaslar.seri_no', 'urun_katalogu.urun_adi', 'urun_katalogu.urun_kodu')
            ->orderBy('urun_katalogu.urun_adi', 'asc')->get();

        // SADECE PERSONEL/HOCA/LAB SORUMLUSU (Örn: rol_id'si 1, 2 veya 3 olanlar)
        // Kendi veritabanındaki personel rol ID'lerini buraya yazmalısın.
        $personeller = DB::table('kullanicilar')
            ->whereIn('rol_id', [2, 3])
            ->orderBy('ad', 'asc')->get();

        return view('kalici_zimmet', compact('bostaki_cihazlar', 'personeller'));
    }


    public function odunc_sayfasi() {
        // 1. Cihazları Çek
        $urun_katalogu = DB::table('urun_katalogu')
            ->join('demirbaslar', 'urun_katalogu.urun_id', '=', 'demirbaslar.urun_id')
            ->where('demirbaslar.durum', 'Bosta')
            ->select('urun_katalogu.urun_id', 'urun_katalogu.urun_adi', DB::raw('COUNT(demirbaslar.demirbas_id) as bostaki_stok'))
            ->groupBy('urun_katalogu.urun_id', 'urun_katalogu.urun_adi')
            ->get();

        // 2. Kullanıcıları ve Rollerini Çek (BURASI DEĞİŞTİ)
        $kullanicilar = DB::table('kullanicilar')
            ->leftJoin('roller', 'kullanicilar.rol_id', '=', 'roller.rol_id')
            ->select('kullanicilar.kullanici_id', 'kullanicilar.ad', 'kullanicilar.soyad', 'kullanicilar.okul_no', 'roller.rol_adi')
            ->orderBy('kullanicilar.ad', 'asc') // İsme göre alfabetik sıraladık
            ->get();

        return view('odunc_ver', compact('urun_katalogu', 'kullanicilar'));
    }

    // 1. KALICI ZİMMET KAYDETME
    public function kalici_zimmet_kaydet(Request $request) {
        $request->validate([
            'demirbas_id' => 'required|integer',
            'kullanici_id' => 'required|integer',
        ]);

        // Transaction başlatıyoruz: Ya hep ya hiç!
        DB::beginTransaction();

        try {
            // A. kalici_zimmetler tablosuna kaydı at
            DB::table('kalici_zimmetler')->insert([
                'demirbas_id' => $request->demirbas_id,
                'kullanici_id' => $request->kullanici_id,
                'veren_yetkili_id' => session('kullanici_id'), // Teslim eden sen (giriş yapan yetkili)
                'verilis_tarihi' => now(),
                'aktif_mi' => 1,
                'aciklama' => $request->aciklama
            ]);



            DB::commit();
            return back()->with('basari', 'Sorumluluk ataması (Kalıcı Zimmet) başarıyla yapıldı. Cihaz hala ödünç verilebilir durumdadır.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors('Sistemsel bir hata oluştu: ' . $e->getMessage());
        }
    }

// 2. ÖDÜNÇ KAYDETME
    public function odunc_kaydet(Request $request) {
        try {
            DB::beginTransaction();

            // 1. FORM'DAN DİREKT SEÇİLEN KULLANICININ ID'Sİ GELİYOR
            $kullanici_id = $request->kullanici_id;

            // 2. STOK KONTROLÜ VE CİHAZLARI BUL (Burası aynı)
            $verilecek_cihazlar = DB::table('demirbaslar')
                ->where('urun_id', $request->urun_id)
                ->where('durum', 'Bosta')
                ->limit($request->miktar)
                ->get();

            if($verilecek_cihazlar->count() < $request->miktar) {
                return back()->with('hata', 'Yeterli stok yok. Lütfen sayfayı yenileyin.');
            }

            // 3. ZİMMETLE
            foreach($verilecek_cihazlar as $cihaz) {
                DB::table('oduncler')->insert([
                    'demirbas_id' => $cihaz->demirbas_id,
                    'kullanici_id' => $kullanici_id, // Direkt formdan geleni yazıyoruz
                    'veren_yetkili_id' => 1,
                    'planlanan_iade_tarihi' => $request->planlanan_iade_tarihi,
                    'durum' => 'Kullanimda'
                ]);

                DB::table('demirbaslar')
                    ->where('demirbas_id', $cihaz->demirbas_id)
                    ->update(['durum' => 'Zimmette']);
            }

            DB::commit();
            return back()->with('basari', $request->miktar . ' adet cihaz başarıyla zimmetlendi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('hata', 'İşlem başarısız: ' . $e->getMessage());
        }
    }    // AJAX İçin Cihaz Bilgisi Döndüren Metot
    public function cihaz_detay($id) {
        $cihaz = DB::table('demirbaslar')
            ->join('urun_katalogu', 'demirbaslar.urun_id', '=', 'urun_katalogu.urun_id')
            ->leftJoin('markalar', 'urun_katalogu.marka_id', '=', 'markalar.marka_id')
            ->where('demirbaslar.demirbas_id', $id)
            ->select(
                'urun_katalogu.urun_adi',
                'urun_katalogu.urun_kodu',
                'urun_katalogu.resim_yolu',
                'demirbaslar.seri_no',
                'markalar.marka_adi'
            )
            ->first();

        return response()->json($cihaz);
    }

}

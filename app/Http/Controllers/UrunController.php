<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Otomatik barkod üretmek için eklendi!

class UrunController extends Controller
{

    //  YENİ CİHAZ (KATALOG) İŞLEMLERİ


    public function urun_tanit_sayfasi() {
        if (session('rol_id') != 2 && session('rol_id') != 3) return redirect('/');

        $ana_kategoriler = DB::table('kategoriler')->get();
        $alt_kategoriler = DB::table('alt_kategori')->get();
        $tipler = DB::table('urun_tipleri')->get();
        $markalar = DB::table('markalar')->orderBy('marka_adi', 'asc')->get(); // YENİ EKLENDİ

        return view('urun_tanit', compact('ana_kategoriler', 'alt_kategoriler', 'tipler', 'markalar'));
    }

    public function urun_ekle(Request $request) {

        if (session('rol_id') != 2 && session('rol_id') != 3) return redirect('/');

        $request->validate([
            'urun_adi' => 'required|min:3|max:150',
            'ana_kategori' => 'required',
            'alt_kategori_id' => 'required|integer',
            'tip_id' => 'required|integer',
            'resim' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'urun_adi.required' => 'Lütfen cihaza bir isim verin.',
            'urun_adi.min' => 'Ürün adı en az 3 karakter olmalıdır.',
            'urun_adi.max' => 'Cihaz adı çok uzun.',
            'ana_kategori.required' => 'Ana kategori seçimi zorunludur.',
            'alt_kategori_id.required' => 'Lütfen bir alt kategori seçin.',
            'tip_id.required' => 'Ürün tipi seçimi zorunludur.',

            'dolap_konumu.max' => 'Konum bilgisi çok uzun.',
            'resim.image' => 'Yüklenen dosya bir resim olmalıdır.',
            'resim.mimes' => 'Sadece JPG ve PNG formatları desteklenir.',
            'resim.max' => 'Resim boyutu en fazla 2MB olabilir.'
        ]);

       //FOTO
        $resim_yolu = null;
        if ($request->hasFile('resim')) {
            $file = $request->file('resim');
            $resim_adi = time() . '-' . uniqid() . '.jpg';
            $hedef_yol = public_path('uploads/urunler');

            if (!file_exists($hedef_yol)) {
                mkdir($hedef_yol, 0777, true);
            }
            try {
                $file->move($hedef_yol, $resim_adi);
                $resim_yolu = 'uploads/urunler/' . $resim_adi;
            } catch (\Exception $e) {
                $resim_yolu = null;
            }
        }

      //MARKA
        $gelen_marka = $request->marka_id; // Blade'deki select adının marka_id olduğundan emin ol
        $nihai_marka_id = null;

        if (!empty($gelen_marka)) {
            // Eğer kullanıcı listede olmayan yeni bir marka ismi yazdıysa (sayı değilse)
            if (!is_numeric($gelen_marka)) {
                // Önce markalar tablosuna ekle ve yeni ID'yi al
                $nihai_marka_id = DB::table('markalar')->insertGetId([
                    'marka_adi' => $gelen_marka
                ]);
            } else {
                // Listeden mevcut bir marka seçildiyse (ID gelmiştir)
                $nihai_marka_id = $gelen_marka;
            }
        }
        $alt_kategori_id = $request->alt_kategori_id;
        $alt_kategori_bilgisi = DB::table('alt_kategori')->where('alt_kategori_id', $alt_kategori_id)->first();


      //DB KAYIT
        $yeni_urun_id = DB::table('urun_katalogu')->insertGetId([
            'urun_adi' => $request->urun_adi,
            'alt_kategori_id' => $alt_kategori_id,
            'tip_id' => $request->tip_id,
            'marka_id' => $nihai_marka_id, // Burası kritik! marka değil marka_id
            'teknik_detay' => $request->teknik_detay,
            'resim_yolu' => $resim_yolu
        ]);

        //AKILLI KOD URETIMI
        $akilli_kod = sprintf("%01d%02d%02d-%03d",
            $request->tip_id,
            $alt_kategori_bilgisi->kategori_id,
            $alt_kategori_id,
            $yeni_urun_id
        );

        DB::table('urun_katalogu')->where('urun_id', $yeni_urun_id)->update(['urun_kodu' => $akilli_kod]);

        return back()->with('basari', "Cihaz başarıyla eklendi! Katalog Kodu: " . $akilli_kod);
    }


    // STOK GİRİŞ İŞLEMLERİ
    public function stok_giris_sayfasi() {
        if (session('rol_id') != 2 && session('rol_id') != 3) return redirect('/');

        // 1. Ürünler ve Konumlar
        $urunler = DB::table('urun_katalogu')->orderBy('urun_adi', 'asc')->get();
        $konumlar = DB::table('konumlar')->orderBy('dolap_adi', 'asc')->get();

        // 2. Personeller
        $personeller = DB::table('kullanicilar')
            ->whereIn('rol_id', [2, 3])
            ->orderBy('ad', 'asc')->get();

        // 3. Faturalar
        $faturalar = DB::table('faturalar')->orderBy('fatura_id', 'desc')->get();

        // 4. İŞTE EKSİK OLAN KISIM: Tedarik Kaynakları (Bütçe/Kurum listesi)
        $tedarik_kaynaklari = DB::table('tedarik_kaynaklari')->orderBy('kaynak_adi', 'asc')->get();

        // Hepsini compact içine ekleyerek sayfaya gönderiyoruz
        return view('stok_giris', compact('urunler', 'konumlar', 'personeller', 'faturalar', 'tedarik_kaynaklari'));
    }

    public function stok_ekle(Request $request) {
        if (session('rol_id') != 2 && session('rol_id') != 3) return redirect('/');

        // 1. DOĞRULAMA (Sarf malzemeleri için adet limitini biraz artırdım, 500 kablo yetmeyebilir)
        $request->validate([
            'urun_id' => 'required|integer',
            'fatura_id' => 'nullable|integer',
            'konum_id' => 'required|integer',
            'adet' => 'required|integer|min:1|max:5000',
            'sorumlu_kullanici_id' => 'nullable|integer'
        ], [
            'urun_id.required' => 'Lütfen stok eklenecek cihazı seçin.',
            'konum_id.required' => 'Lütfen cihazların yerleştirileceği konumu seçin.',
            'adet.required' => 'Lütfen eklenecek miktarı girin.',
        ]);

        $urun_id = $request->urun_id;
        $fatura_id = $request->fatura_id;
        $konum_id = $request->konum_id;
        $eklenecek_adet = $request->adet;
        $sorumlu_id = $request->sorumlu_kullanici_id;

        // Ürünün bilgilerini çekiyoruz (tip_id ayrımı için çok önemli)
        $urun_bilgisi = DB::table('urun_katalogu')->where('urun_id', $urun_id)->first();

        // 3. TRANSACTION BAŞLATIYORUZ
        DB::beginTransaction();

        try {
            // ========================================================
            // SENARYO 1: EĞER GELEN ÜRÜN DEMİRBAŞ İSE (tip_id == 1)
            // ========================================================
            if ($urun_bilgisi->tip_id == 1) {

                $katalog_kodu = $urun_bilgisi->urun_kodu;

                // --- SQLITE VE MYSQL ORTAK UYUMLU KISIM (ÇÖZÜLDÜ) ---
                $mevcut_cihazlar = DB::table('demirbaslar')
                    ->where('urun_id', $urun_id)
                    ->get();

                $baslangic_no = 0;

                foreach ($mevcut_cihazlar as $cihaz) {
                    $parcalar = explode('-', $cihaz->seri_no);
                    $sonKisim = (int) end($parcalar);

                    if ($sonKisim > $baslangic_no) {
                        $baslangic_no = $sonKisim;
                    }
                }
                // ----------------------------------------------------

                for($i = 0; $i < $eklenecek_adet; $i++) {
                    $siradaki_no = $baslangic_no + $i + 1;
                    $barkod = $katalog_kodu . '-' . sprintf("%03d", $siradaki_no); // Senin eklediğin 001, 002 formatı korundu!

                    $yeni_demirbas_id = DB::table('demirbaslar')->insertGetId([
                        'urun_id' => $urun_id,
                        'seri_no' => $barkod,
                        'fatura_id' => $fatura_id,
                        'konum_id' => $konum_id,
                        'durum' => 'Bosta',
                        'gelis_tarihi' => now()
                    ]);

                    if ($sorumlu_id) {
                        DB::table('kalici_zimmetler')->insert([
                            'demirbas_id' => $yeni_demirbas_id,
                            'kullanici_id' => $sorumlu_id,
                            'veren_yetkili_id' => session('kullanici_id') ?? 1,
                            'verilis_tarihi' => now(),
                            'aktif_mi' => 1,
                            'aciklama' => 'Stok girişi sırasında otomatik olarak atanmıştır.'
                        ]);
                    }
                }
            }
            // ========================================================
            // SENARYO 2: EĞER GELEN ÜRÜN SARF MALZEMESİ İSE (tip_id == 2)
            // ========================================================
            else if ($urun_bilgisi->tip_id == 2) {

                // Hem ürünü hem de formdan seçilen konumu kontrol ediyoruz!
                $mevcut_stok = DB::table('sarf_stok_durumu')
                    ->where('urun_id', $urun_id)
                    ->where('konum_id', $konum_id)
                    ->first();

                if ($mevcut_stok) {
                    // Bu konumda (rafta/çekmecede) bu üründen zaten varsa sayısını artır
                    DB::table('sarf_stok_durumu')
                        ->where('urun_id', $urun_id)
                        ->where('konum_id', $konum_id)
                        ->increment('toplam_miktar', $eklenecek_adet);
                } else {
                    // Bu konuma bu ürün ilk defa ekleniyorsa yeni satır oluştur
                    DB::table('sarf_stok_durumu')->insert([
                        'urun_id' => $urun_id,
                        'konum_id' => $konum_id,
                        'toplam_miktar' => $eklenecek_adet
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('basari', $eklenecek_adet . ' adet ürün başarıyla stoklara eklendi!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors('Kayıt sırasında bir hata oluştu: ' . $e->getMessage());
        }
    }
    // 3. DÜZENLEME VE SİLME İŞLEMLERİ

    public function duzenle_sayfasi($id) {
        if (session('rol_id') != 2 && session('rol_id') != 3) return redirect('/');

        $urun = DB::table('urun_katalogu')->where('urun_id', $id)->first();
        if (!$urun) return redirect('/')->with('hata', 'Böyle bir cihaz bulunamadı.');

        $ana_kategoriler = DB::table('kategoriler')->get();
        $alt_kategoriler = DB::table('alt_kategori')->get();
        $tipler = DB::table('urun_tipleri')->get();

        $markalar = DB::table('markalar')->orderBy('marka_adi', 'asc')->get();

        return view('urun_duzenle', compact('urun', 'ana_kategoriler', 'alt_kategoriler', 'tipler', 'markalar'));

    }

    public function urun_guncelle(Request $request, $id) {
        if (session('rol_id') != 2 && session('rol_id') != 3) return redirect('/');

        $request->validate([
            'urun_adi' => 'required|min:3|max:150',
            'alt_kategori_id' => 'required|integer',
            'tip_id' => 'required|integer',
            'resim' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // MARKA AKILLI KONTROL SİSTEMİ
        $gelen_marka = $request->marka_id;
        $final_marka_id = null;

        if ($gelen_marka) {
            if (is_numeric($gelen_marka)) {
                // Kullanıcı açılır listeden var olan bir marka seçtiyse ID'yi direkt al
                $final_marka_id = $gelen_marka;
            } else {
                // Kullanıcı listede olmayan YENİ BİR MARKA yazdıysa, markalar tablosuna ekle ve yeni ID'yi al
                $final_marka_id = DB::table('markalar')->insertGetId([
                    'marka_adi' => $gelen_marka
                ]);
            }
        }

        // ESKİ 'marka' SİLİNDİ, YENİ 'marka_id' EKLENDİ
        $guncellenecek_veriler = [
            'urun_adi' => $request->urun_adi,
            'alt_kategori_id' => $request->alt_kategori_id,
            'tip_id' => $request->tip_id,
            'marka_id' => $final_marka_id, // Artık markayı ID olarak kaydediyoruz
            'teknik_detay' => $request->teknik_detay,
        ];

        if ($request->hasFile('resim')) {
            $file = $request->file('resim');
            $resim_adi = time() . '-' . uniqid() . '.jpg';
            $file->move(public_path('uploads/urunler'), $resim_adi);
            $guncellenecek_veriler['resim_yolu'] = 'uploads/urunler/' . $resim_adi;
        }

        DB::table('urun_katalogu')->where('urun_id', $id)->update($guncellenecek_veriler);

        return redirect('/')->with('basari', 'Cihaz bilgileri başarıyla güncellendi!');
    }

    public function urun_sil($id) {
        if (session('rol_id') != 2 && session('rol_id') != 3) return redirect('/');

        $stok_sayisi = DB::table('demirbaslar')->where('urun_id', $id)->count();

        if ($stok_sayisi > 0) {
            return back()->with('hata', "Bu cihazı silemezsiniz! Sistemde bu cihaza ait {$stok_sayisi} adet fiziksel stok bulunuyor. Önce stokları sıfırlamalısınız.");
        }

        DB::table('urun_katalogu')->where('urun_id', $id)->delete();
        return redirect('/')->with('basari', 'Cihaz katalogdan tamamen silindi.');
    }


    public function urun_detay($id) {
        $urun = DB::table('urun_katalogu')
            ->leftJoin('alt_kategori', 'urun_katalogu.alt_kategori_id', '=', 'alt_kategori.alt_kategori_id')
            ->leftJoin('urun_tipleri', 'urun_katalogu.tip_id', '=', 'urun_tipleri.tip_id')
            ->where('urun_katalogu.urun_id', $id)
            ->first();

        if (!$urun) return redirect('/')->with('hata', 'Böyle bir cihaz sistemde yok.');

        // 1. DURUM: EĞER ÜRÜN BİR DEMİRBAŞ İSE (tip_id = 1)
        if ($urun->tip_id == 1) {
            $fiziksel_stoklar = DB::table('demirbaslar')
                ->leftJoin('konumlar', 'demirbaslar.konum_id', '=', 'konumlar.konum_id')
                ->leftJoin('faturalar', 'demirbaslar.fatura_id', '=', 'faturalar.fatura_id')
                ->leftJoin('tedarik_kaynaklari', 'faturalar.tedarik_id', '=', 'tedarik_kaynaklari.tedarik_id')
                ->where('demirbaslar.urun_id', $id)
                ->orderBy('demirbaslar.gelis_tarihi', 'asc')
                ->get();

            // Demirbaşlarda toplam stok, veritabanındaki satır sayısıdır
            $toplam_stok = DB::table('demirbaslar')
                ->where('urun_id', $id)
                ->where('durum', 'Bosta')
                ->count();
        }
        // 2. DURUM: EĞER ÜRÜN BİR SARF MALZEMESİ İSE (tip_id = 2)
        else if ($urun->tip_id == 2) {
            $fiziksel_stoklar = DB::table('sarf_stok_durumu')
                ->leftJoin('konumlar', 'sarf_stok_durumu.konum_id', '=', 'konumlar.konum_id')
                ->where('sarf_stok_durumu.urun_id', $id)
                ->get();

            // Sarflarda toplam stok, 'toplam_miktar' sütunlarının toplamıdır
            $toplam_stok = (int) DB::table('sarf_stok_durumu')
                ->where('urun_id', $id)
                ->sum('toplam_miktar');
        }

        // Hem ürünü, hem listeyi, hem de toplam stoğu sayfaya yolla
        return view('urun_detay', compact('urun', 'fiziksel_stoklar', 'toplam_stok'));
    }



}

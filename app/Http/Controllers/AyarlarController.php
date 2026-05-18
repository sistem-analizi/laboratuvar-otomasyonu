<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;

    class AyarlarController extends Controller
    {
        public function index() {
            if (session('rol_id') != 2) return redirect('/');

            $kategoriler = DB::table('kategoriler')->get();

            $alt_kategoriler = DB::table('alt_kategori')
                ->join('kategoriler', 'alt_kategori.kategori_id', '=', 'kategoriler.kategori_id')
                ->select('alt_kategori.*', 'kategoriler.kategori_adi')
                ->get();

            $konumlar = DB::table('konumlar')->get();
            $tipler = DB::table('urun_tipleri')->get();

            $tedarik_tipleri = DB::table('tedarik_tipleri')->get();

            $tedarik_kaynaklari = DB::table('tedarik_kaynaklari')
                ->join('tedarik_tipleri', 'tedarik_kaynaklari.tedarik_tip_id', '=', 'tedarik_tipleri.id')
                ->select('tedarik_kaynaklari.*', 'tedarik_tipleri.tip_adi')
                ->get();

            // Faturaları ve bağlı olduğu tedarik kaynağının adını çekiyoruz
            $faturalar = DB::table('faturalar')
                ->leftJoin('tedarik_kaynaklari', 'faturalar.tedarik_id', '=', 'tedarik_kaynaklari.tedarik_id')                ->select('faturalar.*', 'tedarik_kaynaklari.kaynak_adi')
                ->orderBy('faturalar.fatura_id', 'desc')
                ->get();

            $markalar = DB::table('markalar')->orderBy('marka_adi', 'asc')->get();

            // Compact içine 'faturalar'ı da ekle
            return view('ayarlar.index', compact(
                'kategoriler', 'alt_kategoriler', 'konumlar', 'tedarik_tipleri', 'tedarik_kaynaklari', 'tipler', 'faturalar','markalar'
            ));

        }

        public function marka_ekle(Request $request) {
            try {
                DB::table('markalar')->insert(['marka_adi' => $request->marka_adi]);
                return back()->with('basari', 'Yeni marka sisteme eklendi.');
            } catch (\Exception $e) { return back()->with('hata', 'Hata: ' . $e->getMessage()); }
        }

        public function marka_duzenle(Request $request, $id) {
            try {
                DB::table('markalar')->where('marka_id', $id)->update(['marka_adi' => $request->marka_adi]);
                return back()->with('basari', 'Marka adı güncellendi.');
            } catch (\Exception $e) { return back()->with('hata', 'Hata: ' . $e->getMessage()); }
        }

        public function marka_sil($id) {
            try {
                DB::table('markalar')->where('marka_id', $id)->delete();
                return back()->with('basari', 'Marka sistemden tamamen silindi.');
            } catch (\Illuminate\Database\QueryException $e) {
                return back()->with('hata', 'DUR! Sistemde bu markaya ait cihaz/ürün var. Önce onları silmeli veya markasını değiştirmelisiniz.');
            }
        }

        public function fatura_duzenle(Request $request, $id) {
            try {
                DB::table('faturalar')->where('fatura_id', $id)->update([
                    'tedarik_id'    => $request->tedarik_id,
                    'satici_firma'  => $request->satici_firma,
                    'fatura_tarihi' => $request->fatura_tarihi,
                    'toplam_tutar'  => $request->toplam_tutar ?? 0
                ]);
                return back()->with('basari', 'Fatura/Alım bilgileri başarıyla güncellendi.');
            } catch (\Exception $e) {
                return back()->with('hata', 'Güncelleme sırasında bir hata oluştu: ' . $e->getMessage());
            }
        }

        // 1. EKLEME METOTLARI

        public function kategori_ekle(Request $request) {
            if(DB::table('kategoriler')->where('kategori_adi', $request->kategori_adi)->exists()) {
                return back()->with('hata', 'Bu ana kategori zaten sistemde kayıtlı!');
            }
            DB::table('kategoriler')->insert(['kategori_adi' => $request->kategori_adi]);
            return back()->with('basari', 'Yeni Ana Kategori eklendi.');
        }

        public function alt_kategori_ekle(Request $request) {
            if(DB::table('alt_kategori')->where('alt_kategori_adi', $request->alt_kategori_adi)->where('kategori_id', $request->kategori_id)->exists()) {
                return back()->with('hata', 'Bu ana kategori altında bu isimde bir alt kategori zaten var!');
            }
            DB::table('alt_kategori')->insert([
                'kategori_id' => $request->kategori_id,
                'alt_kategori_adi' => $request->alt_kategori_adi
            ]);
            return back()->with('basari', 'Yeni Alt Kategori eklendi.');
        }

        public function konum_ekle(Request $request) {
            if(DB::table('konumlar')->where('dolap_adi', $request->dolap_adi)->where('raf_numarasi', $request->raf_numarasi)->exists()) {
                return back()->with('hata', 'Bu dolap ve raf numarası kombinasyonu zaten kayıtlı!');
            }
            DB::table('konumlar')->insert([
                'dolap_adi' => $request->dolap_adi,
                'raf_numarasi' => $request->raf_numarasi
            ]);
            return back()->with('basari', 'Yeni fiziksel konum tanımlandı.');
        }

        public function tip_ekle(Request $request) {
            if(DB::table('urun_tipleri')->where('tip_adi', $request->tip_adi)->exists()) {
                return back()->with('hata', 'Bu ürün tipi zaten mevcut!');
            }
            DB::table('urun_tipleri')->insert(['tip_adi' => $request->tip_adi]);
            return back()->with('basari', 'Yeni Ürün Tipi eklendi.');
        }

        public function tedarik_tip_ekle(Request $request) {
            if(DB::table('tedarik_tipleri')->where('tip_adi', $request->tip_adi)->exists()) {
                return back()->with('hata', 'Bu tedarik tipi zaten mevcut!');
            }
            DB::table('tedarik_tipleri')->insert(['tip_adi' => $request->tip_adi]);
            return back()->with('basari', 'Yeni tedarik tipi sisteme eklendi.');
        }

        public function tedarikci_ekle(Request $request) {
            if(DB::table('tedarik_kaynaklari')->where('kaynak_adi', $request->kaynak_adi)->exists()) {
                return back()->with('hata', 'Bu isimde bir tedarikçi/kurum zaten kayıtlı!');
            }
            DB::table('tedarik_kaynaklari')->insert([
                'kaynak_adi' => $request->kaynak_adi,
                'tedarik_tip_id' => $request->tedarik_tip_id,
                'iletisim_bilgisi' => $request->iletisim_bilgisi
            ]);
            return back()->with('basari', 'Yeni tedarik kaynağı başarıyla eklendi.');
        }

        public function fatura_ekle(Request $request) {
            DB::table('faturalar')->insert([
                'tedarik_id'    => $request->tedarik_id,
                'satici_firma'  => $request->satici_firma,
                'fatura_tarihi' => $request->fatura_tarihi, // Artık datetime formatında gelecek
                'toplam_tutar'  => $request->toplam_tutar ?? 0
            ]);
            return back()->with('basari', 'Yeni Alım/Fatura bilgisi sisteme eklendi.');
        }


        // 2. SİLME METOTLARI (Güvenlik Korumalı - Try/Catch)


        public function fatura_sil($id) {
            try {
                DB::table('faturalar')->where('fatura_id', $id)->delete();
                return back()->with('basari', 'Fatura başarıyla silindi.');
            } catch (\Illuminate\Database\QueryException $e) {
                return back()->with('hata', 'DUR! Bu faturaya bağlı zimmetli/stokta cihazlar var. Önce onları silmeli veya faturasız yapmalısınız.');
            }
        }

        public function ana_kategori_sil($id) {
            try {
                DB::table('kategoriler')->where('kategori_id', $id)->delete();
                return back()->with('basari', 'Ana kategori başarıyla silindi.');
            } catch (\Illuminate\Database\QueryException $e) {
                return back()->with('hata', 'DUR! Bu kategoriye bağlı alt kategoriler veya cihazlar var. Önce onları silmeli veya taşımalısınız.');
            }
        }

        public function alt_kategori_sil($id) {
            try {
                DB::table('alt_kategori')->where('alt_kategori_id', $id)->delete();
                return back()->with('basari', 'Alt kategori başarıyla silindi.');
            } catch (\Illuminate\Database\QueryException $e) {
                return back()->with('hata', 'DUR! Bu alt kategoriye kayıtlı cihazlar var. Önce cihazları silmelisiniz.');
            }
        }

        public function konum_sil($id) {
            try {
                DB::table('konumlar')->where('konum_id', $id)->delete();
                return back()->with('basari', 'Konum başarıyla silindi.');
            } catch (\Illuminate\Database\QueryException $e) {
                return back()->with('hata', 'DUR! Bu konumda (dolap/rafta) şu an bulunan cihazlar var. Önce cihazların konumunu değiştirin.');
            }
        }

        public function tip_sil($id) {
            {
                try {
                    // Sütun adını veritabanındaki gibi 'tip_id' olarak değiştirdik
                    DB::table('urun_tipleri')->where('tip_id', $id)->delete();

                    return back()->with('basari', 'Ürün tipi başarıyla silindi.');
                } catch (\Illuminate\Database\QueryException $e) {
                    return back()->with('hata', 'DUR! Sistemde bu tipe sahip cihazlar var.');
                }
            }
        }
        public function tedarik_tip_sil($id) {
            try {
                DB::table('tedarik_tipleri')->where('id', $id)->delete();
                return back()->with('basari', 'Kaynak tipi sistemden başarıyla silindi.');
            } catch (\Illuminate\Database\QueryException $e) {
                return back()->with('hata', 'DUR! Bu tipe kayıtlı tedarikçi kurumlar var. Önce o kurumları silmeli veya tiplerini değiştirmelisiniz.');
            }
        }


        public function tedarikci_duzenle(Request $request, $id) {
            try {
                DB::table('tedarik_kaynaklari')->where('tedarik_id', $id)->update([
                    'kaynak_adi' => $request->kaynak_adi,
                    'tedarik_tip_id' => $request->tedarik_tip_id,
                    'iletisim_bilgisi' => $request->iletisim_bilgisi
                ]);
                return back()->with('basari', 'Kurum bilgileri başarıyla güncellendi.');
            } catch (\Exception $e) {
                return back()->with('hata', 'Güncelleme sırasında bir hata oluştu: ' . $e->getMessage());
            }
        }

        public function tedarikci_sil($id) {
            try {
                DB::table('tedarik_kaynaklari')->where('tedarik_id', $id)->delete();
                return back()->with('basari', 'Kurum başarıyla silindi.');
            } catch (\Illuminate\Database\QueryException $e) {
                return back()->with('hata', 'DUR! Bu kuruma ait alımlar/faturalar var. Önce onları silmelisiniz.');
            }
        }

        public function ana_kategori_duzenle(Request $request, $id) {
            try {
                DB::table('kategoriler')->where('kategori_id', $id)->update(['kategori_adi' => $request->kategori_adi]);
                return back()->with('basari', 'Ana kategori güncellendi.');
            } catch (\Exception $e) { return back()->with('hata', 'Hata: ' . $e->getMessage()); }
        }

        public function alt_kategori_duzenle(Request $request, $id) {
            try {
                DB::table('alt_kategoriler')->where('alt_kategori_id', $id)->update([
                    'kategori_id' => $request->kategori_id,
                    'alt_kategori_adi' => $request->alt_kategori_adi
                ]);
                return back()->with('basari', 'Alt kategori güncellendi.');
            } catch (\Exception $e) { return back()->with('hata', 'Hata: ' . $e->getMessage()); }
        }

        public function konum_duzenle(Request $request, $id) {
            try {
                DB::table('konumlar')->where('konum_id', $id)->update([
                    'dolap_adi' => $request->dolap_adi,
                    'raf_numarasi' => $request->raf_numarasi
                ]);
                return back()->with('basari', 'Konum güncellendi.');
            } catch (\Exception $e) { return back()->with('hata', 'Hata: ' . $e->getMessage()); }
        }

        public function tedarik_tip_duzenle(Request $request, $id) {
            try {
                DB::table('tedarik_tipleri')->where('id', $id)->update(['tip_adi' => $request->tip_adi]);
                return back()->with('basari', 'Kaynak tipi güncellendi.');
            } catch (\Exception $e) { return back()->with('hata', 'Hata: ' . $e->getMessage()); }
        }

        public function tip_duzenle(Request $request, $id) {
            try {
                // Önceki hatadan hatırlarsın, buranın ID'si tip_id
                DB::table('urun_tipleri')->where('tip_id', $id)->update(['tip_adi' => $request->tip_adi]);
                return back()->with('basari', 'Ürün tipi güncellendi.');
            } catch (\Exception $e) { return back()->with('hata', 'Hata: ' . $e->getMessage()); }
        }

    }
